/* ═══════════════════════════════════════════════════════════════════════
   admin-customers.js  —  Server-side Pagination + Sorting + Search
   All heavy lifting (sort / filter / count) is done by MySQL.
   JS only: renders the page slice it receives, drives pagination UI.
   ═══════════════════════════════════════════════════════════════════════ */

/* ── State ── */
let currentPage = 1;
let totalPages = 1;
let totalItems = 0;
const PAGE_SIZE = 10;
let sortCol = 'created_at';
let sortDir = 'desc';
let searchQuery = '';
let debounceTimer = null;
let deletingId = null;

/* ── Bootstrap instances ── */
let formModal = null;
let confirmModal = null;
let bsToast = null;

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
    formModal = new bootstrap.Modal(document.getElementById('formModal'));
    confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    bsToast = new bootstrap.Toast(document.getElementById('toast'), { delay: 3500 });

    document.getElementById('openCreateBtn').addEventListener('click', moPopupThemMoi);
    document.getElementById('customerForm').addEventListener('submit', xuLyGuiForm);
    document.getElementById('confirmDeleteBtn').addEventListener('click', xuLyXoa);
    document.addEventListener('click', e => {
        if (!e.target.closest('.search-wrap')) anDanhSachGoiY();
    });

    taiDanhSachKhachHang();
});

/* ══════════════════════════════════════════════════════════════════════
   CORE: fetch one page from the server
   ══════════════════════════════════════════════════════════════════════ */

async function taiDanhSachKhachHang() {
    const params = new URLSearchParams({
        page: currentPage,
        limit: PAGE_SIZE,
        sort: sortCol,
        order: sortDir,
    });
    if (searchQuery) params.set('q', searchQuery);

    try {
        const res = await fetch('/admin/customers?' + params.toString());
        const data = await res.json();

        if (!data.success) { hienThongBao(data.message || 'Lỗi tải dữ liệu.', 'danger'); return; }

        const pg = data.pagination || {};
        currentPage = pg.current_page || 1;
        totalPages = pg.total_pages || 1;
        totalItems = pg.total_items || 0;

        veBangKhachHang(data.customers || []);
        vePhanTrang();
        capNhatTieuDeSapXep();
        document.getElementById('countBadge').textContent =
            `${totalItems} khách hàng`;

    } catch {
        hienThongBao('Không thể kết nối đến máy chủ.', 'danger');
    }
}

/* ══════════════════════════════════════════════════════════════════════
   API helper (for CRUD)
   ══════════════════════════════════════════════════════════════════════ */

async function goiApi(method, url, body = null) {
    const res = await fetch(url, {
        method,
        headers: body ? { 'Content-Type': 'application/x-www-form-urlencoded' } : {},
        body: body ? new URLSearchParams(body) : undefined,
    });
    return res.json();
}

/* ══════════════════════════════════════════════════════════════════════
   SORT  —  sends new request to server with sort params
   ══════════════════════════════════════════════════════════════════════ */

function sapXepCot(col) {
    sortDir = (sortCol === col && sortDir === 'asc') ? 'desc' : 'asc';
    sortCol = col;
    currentPage = 1;   // Reset to page 1 on new sort
    taiDanhSachKhachHang();
}

function capNhatTieuDeSapXep() {
    document.querySelectorAll('thead th.sortable').forEach(th => {
        const key = th.dataset.sortKey;
        const icon = th.querySelector('.sort-icon');
        th.classList.remove('sort-asc', 'sort-desc');
        if (key === sortCol) {
            th.classList.add(sortDir === 'asc' ? 'sort-asc' : 'sort-desc');
            if (icon) icon.textContent = sortDir === 'asc' ? '↑' : '↓';
        } else {
            if (icon) icon.textContent = '⇅';
        }
    });
}

/* ══════════════════════════════════════════════════════════════════════
   PAGINATION  —  driven by server metadata
   ══════════════════════════════════════════════════════════════════════ */

function chuyenTrang(p) {
    currentPage = Math.max(1, Math.min(p, totalPages));
    taiDanhSachKhachHang();
}

function vePhanTrang() {
    const bar = document.getElementById('paginationBar');
    const info = document.getElementById('paginationInfo');
    const btns = document.getElementById('paginationBtns');

    if (totalItems === 0) { bar.classList.add('d-none'); return; }
    bar.classList.remove('d-none');

    const start = (currentPage - 1) * PAGE_SIZE + 1;
    const end = Math.min(currentPage * PAGE_SIZE, totalItems);
    info.textContent = `Hiển thị ${start}–${end} / ${totalItems} khách hàng`;

    const li = (label, page, disabled, active) =>
        `<li class="page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}">
            <button class="page-link" ${disabled ? 'tabindex="-1"' : `onclick="chuyenTrang(${page})"`}>${label}</button>
         </li>`;

    const delta = 2;
    const lo = Math.max(1, currentPage - delta);
    const hi = Math.min(totalPages, currentPage + delta);
    let pages = '';
    if (lo > 1) {
        pages += li(1, 1, false, false);
        if (lo > 2) pages += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
    }
    for (let p = lo; p <= hi; p++) pages += li(p, p, false, p === currentPage);
    if (hi < totalPages) {
        if (hi < totalPages - 1) pages += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
        pages += li(totalPages, totalPages, false, false);
    }

    btns.innerHTML =
        li('«', 1, currentPage === 1, false) +
        li('‹', currentPage - 1, currentPage === 1, false) +
        pages +
        li('›', currentPage + 1, currentPage === totalPages, false) +
        li('»', totalPages, currentPage === totalPages, false);
}

/* ══════════════════════════════════════════════════════════════════════
   RENDER TABLE
   ══════════════════════════════════════════════════════════════════════ */

const GENDER_LABEL = { male: 'Nam', female: 'Nữ', other: 'Khác' };

const TIER_CONFIG = {
    dong: { label: 'Đồng', icon: '🥉', cls: 'tier-dong' },
    bac: { label: 'Bạc', icon: '🥈', cls: 'tier-bac' },
    vang: { label: 'Vàng', icon: '🥇', cls: 'tier-vang' },
    vip: { label: 'VIP', icon: '💎', cls: 'tier-vip' },
};

function taoHuyHieuHang(tier) {
    const t = TIER_CONFIG[tier] || TIER_CONFIG.dong;
    return `<span class="tier-badge ${t.cls}">${t.icon} ${t.label}</span>`;
}

function veBangKhachHang(customers) {
    const tbody = document.getElementById('tableBody');

    if (customers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-5" style="color:var(--crm-muted)">
            <div style="font-size:2rem;margin-bottom:.5rem">👤</div>
            <div style="font-size:.875rem">
                ${searchQuery ? 'Không tìm thấy khách hàng phù hợp.' : 'Chưa có khách hàng nào.'}
            </div>
        </td></tr>`;
        return;
    }

    const startIdx = (currentPage - 1) * PAGE_SIZE;

    tbody.innerHTML = customers.map((c, i) => `
        <tr data-id="${c.id}"
            data-name="${chongXss(c.name)}"
            data-email="${chongXss(c.email)}"
            data-phone="${chongXss(c.phone || '')}"
            data-address="${chongXss(c.address || '')}"
            data-dob="${chongXss(c.dob || '')}"
            data-gender="${chongXss(c.gender || '')}"
            data-points="${c.member_points || 0}"
            data-tier="${chongXss(c.member_tier || 'dong')}"
            style="cursor:pointer"
            ondblclick="moPopupChiTiet(${c.id}, '${chongXss(c.name)}')">
            <td class="text-secondary" style="font-size:.75rem">${startIdx + i + 1}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-semibold"
                         style="width:30px;height:30px;background:var(--crm-accent);font-size:.75rem;color:#fff">
                        ${chongXss(c.name.charAt(0).toUpperCase())}
                    </div>
                    <span class="fw-medium" style="font-size:.875rem">${chongXss(c.name)}</span>
                </div>
            </td>
            <td style="font-size:.8125rem;color:var(--crm-muted)">${chongXss(c.email)}</td>
            <td style="font-size:.8125rem">
                ${c.phone ? chongXss(c.phone) : '<span style="color:var(--crm-muted)">—</span>'}
            </td>
            <td style="font-size:.75rem;color:var(--crm-muted)">
                ${c.dob ? dinhDangNgay(c.dob) : '—'}
            </td>
            <td style="font-size:.8125rem">
                ${c.gender ? (GENDER_LABEL[c.gender] || chongXss(c.gender)) : '<span style="color:var(--crm-muted)">—</span>'}
            </td>
            <td>
                <span class="fw-semibold" style="color:#f59e0b;font-size:.8125rem">⭐ ${c.member_points || 0}</span>
            </td>
            <td>${taoHuyHieuHang(c.member_tier || 'dong')}</td>
            <td style="font-size:.75rem;color:var(--crm-muted)">${dinhDangNgay(c.created_at)}</td>
            <td>
                <div class="d-flex gap-1">
                    <button class="btn btn-outline-success btn-sm py-0 px-2"
                            style="font-size:.75rem"
                            onclick="moPopupSua(this.closest('tr'))">✏️ Sửa</button>
                    <button class="btn btn-outline-danger btn-sm py-0 px-2"
                            style="font-size:.75rem"
                            onclick="moPopupXacNhan(${c.id}, '${chongXss(c.name)}')">🗑️ Xóa</button>
                </div>
            </td>
        </tr>
    `).join('');
}

/* ══════════════════════════════════════════════════════════════════════
   SEARCH — Tách biệt 2 luồng:
   1. Gõ phím → chỉ hiện Dropdown Gợi ý (fetch nhẹ limit=5, bảng giữ nguyên)
   2. Click vào gợi ý / Enter → mới cập nhật bảng chính
   ══════════════════════════════════════════════════════════════════════ */

function khiNhapTimKiem() {
    const q = document.getElementById('searchInput').value.trim();

    // Xóa hết chữ → ẩn dropdown và reset bảng về toàn bộ danh sách
    if (!q) {
        anDanhSachGoiY();
        clearTimeout(debounceTimer);
        if (searchQuery !== '') {
            searchQuery = '';
            currentPage = 1;
            taiDanhSachKhachHang();
        }
        return;
    }

    // Debounce 300ms chỉ để gọi gợi ý (nhẹ) — KHÔNG cập nhật bảng
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => taiGoiY(q), 300);
}

/**
 * Gọi server lấy tối đa 5 gợi ý để hiển thị dropdown.
 * Bảng chính KHÔNG bị ảnh hưởng.
 */
async function taiGoiY(q) {
    try {
        const params = new URLSearchParams({ q, page: 1, limit: 5, sort: 'name', order: 'asc' });
        const res = await fetch('/admin/customers?' + params.toString());
        const data = await res.json();
        if (data.success) {
            veDanhSachGoiY(data.customers || [], q);
        }
    } catch {
        // Thất bại âm thầm — dropdown chỉ là tính năng phụ
    }
}

function khiNhanPhimTimKiem(e) {
    const box = document.getElementById('suggestions');
    const items = box.querySelectorAll('.sug-item');
    let idx = parseInt(box.dataset.activeIdx ?? '-1');

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        idx = Math.min(idx + 1, items.length - 1);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        idx = Math.max(idx - 1, -1);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (idx >= 0 && items[idx]) {
            // Có gợi ý đang được chọn → chọn nó
            items[idx].dispatchEvent(new MouseEvent('mousedown'));
        } else {
            // Không có gợi ý nào được chọn → tìm kiếm theo chữ đang gõ
            const q = document.getElementById('searchInput').value.trim();
            anDanhSachGoiY();
            searchQuery = q;
            currentPage = 1;
            taiDanhSachKhachHang();
        }
        return;
    } else if (e.key === 'Escape') {
        anDanhSachGoiY(); return;
    } else { return; }

    box.dataset.activeIdx = idx;
    items.forEach((el, i) => el.classList.toggle('sug-active', i === idx));
    if (idx >= 0) items[idx].scrollIntoView({ block: 'nearest' });
}

/* ── Vẽ dropdown từ mảng customers trả về từ server ── */
function veDanhSachGoiY(customers, q) {
    const box = document.getElementById('suggestions');

    if (!customers.length) {
        box.className = 'open';
        box.innerHTML = `<div class="sug-empty">Không tìm thấy kết quả nào.</div>`;
        return;
    }

    box.className = 'open';
    box.dataset.activeIdx = '-1';
    box.innerHTML = customers.map(c => {
        const sub = [c.email, c.phone].filter(Boolean).join(' · ');
        return `<div class="sug-item" onmousedown="chonGoiY('${chongXss(c.name)}')">
            <div class="sug-avatar">${chongXss(c.name.charAt(0).toUpperCase())}</div>
            <div class="overflow-hidden">
                <div class="sug-name">${toDamTuKhoa(c.name, q)}</div>
                <div class="sug-sub">${toDamTuKhoa(sub, q)}</div>
            </div>
        </div>`;
    }).join('');
}

function anDanhSachGoiY() {
    const box = document.getElementById('suggestions');
    box.className = ''; box.innerHTML = '';
}

/**
 * Người dùng click vào 1 gợi ý → điền tên vào ô → cập nhật bảng chính.
 */
function chonGoiY(name) {
    document.getElementById('searchInput').value = name;
    anDanhSachGoiY();
    searchQuery = name;
    currentPage = 1;
    taiDanhSachKhachHang();   // ← CHỈ ĐÂY mới re-render bảng
}


/* ══════════════════════════════════════════════════════════════════════
   CREATE / EDIT MODAL
   ══════════════════════════════════════════════════════════════════════ */

function moPopupThemMoi() {
    document.getElementById('modalTitle').textContent = '➕ Thêm khách hàng';
    document.getElementById('customerId').value = '';
    document.getElementById('fieldName').value = '';
    document.getElementById('fieldEmail').value = '';
    document.getElementById('fieldPassword').value = '';
    document.getElementById('fieldPassword').required = true;
    document.getElementById('passHint').textContent = '*';
    document.getElementById('passHintText').textContent = 'Tối thiểu 8 ký tự.';
    document.getElementById('fieldPhone').value = '';
    document.getElementById('fieldAddress').value = '';
    document.getElementById('fieldDob').value = '';
    document.getElementById('fieldGender').value = '';
    document.getElementById('fieldPoints').value = '0';
    document.getElementById('formSubmitBtn').textContent = 'Tạo khách hàng';
    formModal.show();
    setTimeout(() => document.getElementById('fieldName').focus(), 250);
}

function moPopupSua(row) {
    const d = row.dataset;
    document.getElementById('modalTitle').textContent = '✏️ Sửa khách hàng';
    document.getElementById('customerId').value = d.id;
    document.getElementById('fieldName').value = d.name;
    document.getElementById('fieldEmail').value = d.email;
    document.getElementById('fieldPassword').value = '';
    document.getElementById('fieldPassword').required = false;
    document.getElementById('passHint').textContent = '(tuỳ chọn)';
    document.getElementById('passHintText').textContent = 'Để trống nếu không đổi mật khẩu.';
    document.getElementById('fieldPhone').value = d.phone || '';
    document.getElementById('fieldAddress').value = d.address || '';
    document.getElementById('fieldDob').value = d.dob || '';
    document.getElementById('fieldGender').value = d.gender || '';
    document.getElementById('fieldPoints').value = d.points || '0';
    document.getElementById('formSubmitBtn').textContent = 'Lưu thay đổi';
    formModal.show();
}

async function xuLyGuiForm(e) {
    e.preventDefault();
    const btn = document.getElementById('formSubmitBtn');
    const origText = btn.textContent;
    const id = document.getElementById('customerId').value;
    const body = {
        name: document.getElementById('fieldName').value.trim(),
        email: document.getElementById('fieldEmail').value.trim(),
        password: document.getElementById('fieldPassword').value,
        phone: document.getElementById('fieldPhone').value.trim(),
        address: document.getElementById('fieldAddress').value.trim(),
        dob: document.getElementById('fieldDob').value,
        gender: document.getElementById('fieldGender').value,
        member_points: document.getElementById('fieldPoints').value,
    };

    btn.textContent = '⏳ Đang lưu...'; btn.disabled = true;

    try {
        const data = id
            ? await goiApi('PUT', `/admin/customers/${id}`, body)
            : await goiApi('POST', '/admin/customers', body);

        if (data.success) {
            formModal.hide();
            hienThongBao(data.message, 'success');
            await taiDanhSachKhachHang();
        } else {
            hienThongBao(data.message, 'danger');
        }
    } catch {
        hienThongBao('Lỗi kết nối.', 'danger');
    } finally {
        btn.textContent = origText; btn.disabled = false;
    }
}

/* ══════════════════════════════════════════════════════════════════════
   DELETE CONFIRM MODAL
   ══════════════════════════════════════════════════════════════════════ */

function moPopupXacNhan(id, name) {
    deletingId = id;
    document.getElementById('confirmName').textContent = name;
    confirmModal.show();
}

async function xuLyXoa() {
    if (!deletingId) return;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.textContent = '⏳ Đang xóa...'; btn.disabled = true;

    try {
        const data = await goiApi('DELETE', `/admin/customers/${deletingId}`);
        if (data.success) {
            confirmModal.hide();
            hienThongBao(data.message, 'success');
            // If we deleted the last item on current page, step back one page
            if (currentPage > 1 && (totalItems - 1) <= (currentPage - 1) * PAGE_SIZE) {
                currentPage--;
            }
            await taiDanhSachKhachHang();
        } else {
            hienThongBao(data.message, 'danger');
        }
    } catch {
        hienThongBao('Lỗi kết nối.', 'danger');
    } finally {
        btn.textContent = 'Xóa'; btn.disabled = false; deletingId = null;
    }
}

/* ══════════════════════════════════════════════════════════════════════
   TOAST
   ══════════════════════════════════════════════════════════════════════ */

function hienThongBao(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.className = `toast align-items-center text-bg-${type} border-0`;
    document.getElementById('toastBody').textContent = msg;
    bsToast.show();
}

/* ══════════════════════════════════════════════════════════════════════
   HELPERS
   ══════════════════════════════════════════════════════════════════════ */

function chongXss(str) {
    return String(str ?? '').replace(/[&<>"']/g, c =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])
    );
}

function toDamTuKhoa(str, q) {
    if (!str || !q) return chongXss(str || '');
    const safe = chongXss(str);
    const re = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return safe.replace(re, '<mark>$1</mark>');
}

function dinhDangNgay(str) {
    if (!str) return '—';
    return new Date(str).toLocaleDateString('vi-VN', {
        day: '2-digit', month: '2-digit', year: 'numeric',
    });
}

/* ══════════════════════════════════════════════════════════════════════
   CUSTOMER DETAIL MODAL — double-click tr → Modal XL 
   ══════════════════════════════════════════════════════════════════════ */

let _detailModal = null;

async function moPopupChiTiet(id, name) {
    if (!_detailModal) {
        _detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    }

    document.getElementById('detailModalLabel').textContent = name;
    document.getElementById('detailModalBody').innerHTML = `
        <div class="text-center py-5" style="color:var(--crm-muted)">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            Đang tải hồ sơ khách hàng...
        </div>`;

    _detailModal.show();

    try {
        const res = await fetch(`/admin/customers/${id}/bookings`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Lỗi tải dữ liệu');

        const tr = document.querySelector(`#tableBody tr[data-id="${id}"]`);
        const p = tr ? {
            name: tr.dataset.name,
            email: tr.dataset.email,
            phone: tr.dataset.phone,
            address: tr.dataset.address,
            dob: tr.dataset.dob,
            gender: tr.dataset.gender,
            points: tr.dataset.points,
            tier: tr.dataset.tier,
        } : { name, email: '' };

        document.getElementById('detailModalBody').innerHTML =
            veChiTietKhachHang(p, data.bookings || []);
    } catch (err) {
        document.getElementById('detailModalBody').innerHTML =
            `<p class="text-danger">${chongXss(err.message)}</p>`;
    }
}

/* Layout 2 hàng dọc: thông tin tài khoản → lịch sử đặt phòng */
function veChiTietKhachHang(p, bookings) {
    const initial = chongXss((p.name || '?').charAt(0).toUpperCase());
    const genderLb = GENDER_LABEL[p.gender] || p.gender || '—';
    const dobFmt = p.dob ? dinhDangNgayNgan(p.dob) : '—';

    /* 8 trường xếp thành 2 cột × 4 hàng dùng CSS grid */
    const fields = [
        ['Họ và tên', chongXss(p.name)],
        ['Email', chongXss(p.email)],
        ['Điện thoại', p.phone ? chongXss(p.phone) : '—'],
        ['Địa chỉ', p.address ? chongXss(p.address) : '—'],
        ['Ngày sinh', dobFmt],
        ['Giới tính', genderLb],
        ['Điểm thành viên', `⭐ ${p.points || 0}`],
        ['Hạng thành viên', taoHuyHieuHang(p.tier || 'dong')],
    ];

    const infoGrid = fields.map(([k, v]) => `
        <div style="border-bottom:1px solid var(--crm-border);padding:.6rem 0;display:flex;align-items:center;gap:.75rem;min-width:0">
            <span style="font-size:.8125rem;color:var(--crm-muted);flex:0 0 140px;font-weight:500">${k}</span>
            <span style="font-size:.8125rem;color:var(--crm-text);word-break:break-word;flex:1">${v}</span>
        </div>`).join('');

    return `
    <div class="row g-4">
        <!-- Banner -->
        <div class="col-12">
            <div class="card p-4" style="border-color:var(--crm-border)">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:52px;height:52px;background:var(--crm-accent);font-size:1.3rem;color:#fff">
                        ${initial}
                    </div>
                    <div>
                        <div class="fw-bold fs-5" style="color:var(--crm-text)">${chongXss(p.name)}</div>
                        <div style="font-size:.875rem;color:var(--crm-muted)">${chongXss(p.email)}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Thông tin tài khoản (2 cột × 4 hàng) -->
        <div class="col-12">
            <div class="card p-4" style="border-color:var(--crm-border)">
                <h2 class="fw-semibold mb-3"
                    style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;color:var(--crm-muted)">
                    Thông tin tài khoản
                </h2>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 2rem">
                    ${infoGrid}
                </div>
            </div>
        </div>
        <!-- Lịch sử đặt phòng (full width) -->
        <div class="col-12">
            ${veLichSuDatPhong(bookings)}
        </div>
    </div>`;
}


function veLichSuDatPhong(bookings) {
    const fmt = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
    const inner = !bookings.length
        ? `<p style="font-size:.875rem;color:var(--crm-muted);margin:0">Chưa có đơn đặt phòng nào.</p>`
        : `<div class="table-responsive">
               <table class="table table-sm mb-0" style="font-size:.875rem">
                   <thead><tr>
                       <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Phòng</th>
                       <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Check-in</th>
                       <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Check-out</th>
                       <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Tổng tiền</th>
                       <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Trạng thái</th>
                   </tr></thead>
                   <tbody>${bookings.map(b => `
                   <tr>
                       <td style="font-size:.8125rem;color:var(--crm-text)">${chongXss(b.room_name)}</td>
                       <td style="font-size:.8125rem;color:var(--crm-muted);white-space:nowrap">${dinhDangNgayNgan(b.check_in)}</td>
                       <td style="font-size:.8125rem;color:var(--crm-muted);white-space:nowrap">${dinhDangNgayNgan(b.check_out)}</td>
                       <td style="font-size:.8125rem;white-space:nowrap">${fmt.format(b.total_price)}</td>
                       <td>${taoHuyHieuTrangThai(b.status)}</td>
                   </tr>`).join('')}
                   </tbody>
               </table>
           </div>`;

    return `<div class="card p-4" style="border-color:var(--crm-border)">
        <h2 class="fw-semibold mb-3"
            style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;color:var(--crm-muted)">
            Lịch sử đặt phòng
        </h2>
        ${inner}
    </div>`;
}

function taoHuyHieuTrangThai(status) {
    const map = {
        completed: ['#4ade80', 'rgba(34,197,94,.12)', 'rgba(34,197,94,.3)', '✅ Hoàn thành'],
        confirmed: ['#fbbf24', 'rgba(251,191,36,.12)', 'rgba(251,191,36,.3)', '🕐 Đã xác nhận'],
        cancelled: ['#f87171', 'rgba(239,68,68,.12)', 'rgba(239,68,68,.3)', '✖ Đã hủy'],
    };
    const [c, bg, bd, lbl] = map[status] || map.completed;
    return `<span class="tier-badge" style="color:${c};background:${bg};border-color:${bd};font-size:.7rem">${lbl}</span>`;
}

function dinhDangNgayNgan(str) {
    if (!str) return '—';
    const [y, m, d] = str.split('-');
    return `${d}/${m}/${y}`;
}


