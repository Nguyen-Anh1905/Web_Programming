<?php
/** @var array $payload */
$payload = $payload ?? [];
?>

<style>
/* ── CRUD page styles ──────────────────────────────────────── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 12px;
}

.page-header h2 {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.4px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    border: none;
    transition: all 0.15s;
}

.btn-primary { background: #4f8ef7; color: #fff; }
.btn-primary:hover { background: #3a6fd4; box-shadow: 0 0 16px rgba(79,142,247,0.4); }
.btn-danger  { background: rgba(248,81,73,0.15); color: #f85149; border: 1px solid rgba(248,81,73,0.3); }
.btn-danger:hover  { background: rgba(248,81,73,0.25); }
.btn-edit    { background: rgba(63,185,80,0.1); color: #3fb950; border: 1px solid rgba(63,185,80,0.3); padding: 5px 12px; font-size: 12px; }
.btn-edit:hover    { background: rgba(63,185,80,0.2); }
.btn-del     { background: rgba(248,81,73,0.1); color: #f85149; border: 1px solid rgba(248,81,73,0.3); padding: 5px 12px; font-size: 12px; }
.btn-del:hover     { background: rgba(248,81,73,0.2); }

/* Search bar */
.search-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.search-input {
    flex: 1;
    max-width: 320px;
    padding: 8px 14px;
    background: var(--bg-3);
    border: 1px solid var(--border);
    border-radius: 7px;
    color: var(--text-pri);
    font-size: 13px;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
}
.search-input:focus { border-color: var(--accent); }
.search-input::placeholder { color: var(--text-muted); }

/* Table */
.table-wrap {
    background: var(--bg-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    text-align: left;
    padding: 12px 16px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-sec);
    background: var(--bg-3);
    border-bottom: 1px solid var(--border);
}

tbody tr {
    border-bottom: 1px solid rgba(48,54,61,0.5);
    transition: background 0.12s;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: rgba(255,255,255,0.02); }

tbody td {
    padding: 12px 16px;
    font-size: 13.5px;
    vertical-align: middle;
}

.td-name    { font-weight: 500; }
.td-email   { color: var(--text-sec); }
.td-date    { color: var(--text-muted); font-size: 12px; }
.td-actions { white-space: nowrap; display: flex; gap: 6px; }

.badge-customer {
    display: inline-block;
    padding: 2px 8px;
    background: rgba(63,185,80,0.12);
    border: 1px solid rgba(63,185,80,0.3);
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
    color: #3fb950;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-muted);
}
.empty-state .icon { font-size: 36px; margin-bottom: 10px; }
.empty-state p { font-size: 14px; }

/* Toast */
#toast {
    position: fixed;
    bottom: 24px; right: 24px;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    z-index: 9999;
    opacity: 0;
    transform: translateY(8px);
    transition: opacity 0.25s, transform 0.25s;
    pointer-events: none;
    max-width: 320px;
}
#toast.show { opacity: 1; transform: none; }
#toast.success { background: #1c3a24; border: 1px solid rgba(63,185,80,0.4); color: #3fb950; }
#toast.error   { background: #3a1515; border: 1px solid rgba(248,81,73,0.4); color: #f85149; }

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(4px);
    z-index: 100;
    align-items: center;
    justify-content: center;
}
.modal-overlay.open { display: flex; }

.modal {
    background: var(--bg-2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 28px 28px 24px;
    width: 100%;
    max-width: 440px;
    animation: fadeUp 0.25s cubic-bezier(0.16,1,0.3,1);
    box-shadow: 0 24px 64px rgba(0,0,0,0.5);
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: none; }
}

.modal h3 { font-size: 16px; font-weight: 700; margin-bottom: 20px; }

.form-group { margin-bottom: 14px; }
.form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-sec); margin-bottom: 5px; }
.form-input {
    width: 100%;
    padding: 9px 13px;
    background: var(--bg-3);
    border: 1px solid var(--border);
    border-radius: 7px;
    color: var(--text-pri);
    font-size: 13.5px;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
}
.form-input:focus { border-color: var(--accent); }
.form-hint { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.btn-ghost {
    background: none;
    border: 1px solid var(--border);
    color: var(--text-sec);
    padding: 8px 16px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 500;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-ghost:hover { background: var(--bg-3); color: var(--text-pri); }

/* Confirm modal */
.confirm-icon { font-size: 32px; text-align: center; margin-bottom: 14px; }
.confirm-msg  { text-align: center; color: var(--text-sec); font-size: 13.5px; line-height: 1.6; margin-bottom: 20px; }
</style>

<!-- ── Page header ── -->
<div class="page-header">
    <h2>👥 Quản lý Khách hàng</h2>
    <button class="btn btn-primary" onclick="openCreateModal()">＋ Thêm khách hàng</button>
</div>

<!-- ── Search ── -->
<div class="search-row">
    <input id="searchInput" class="search-input" type="search" placeholder="🔍  Tìm kiếm tên hoặc email..." oninput="filterTable()">
    <span id="countBadge" style="font-size:12px;color:var(--text-muted)"></span>
</div>

<!-- ── Table ── -->
<div class="table-wrap">
    <table id="customerTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Role</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="6"><div class="empty-state"><div class="icon">⏳</div><p>Đang tải...</p></div></td></tr>
        </tbody>
    </table>
</div>

<!-- ── Toast ── -->
<div id="toast"></div>

<!-- ── Create / Edit Modal ── -->
<div id="formModal" class="modal-overlay">
    <div class="modal">
        <h3 id="modalTitle">Thêm khách hàng</h3>
        <form id="customerForm" novalidate>
            <input type="hidden" id="customerId">
            <div class="form-group">
                <label class="form-label" for="fieldName">Họ và tên *</label>
                <input id="fieldName" class="form-input" type="text" placeholder="Nguyễn Văn A" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="fieldEmail">Email *</label>
                <input id="fieldEmail" class="form-input" type="email" placeholder="khachhang@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="fieldPassword">Mật khẩu <span id="passHint" style="font-weight:400;color:var(--text-muted)">*</span></label>
                <input id="fieldPassword" class="form-input" type="password" placeholder="••••••••">
                <div class="form-hint" id="passHintText">Tối thiểu 8 ký tự.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeFormModal()">Hủy</button>
                <button type="submit" class="btn btn-primary" id="formSubmitBtn">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- ── Confirm Delete Modal ── -->
<div id="confirmModal" class="modal-overlay">
    <div class="modal" style="max-width:360px">
        <div class="confirm-icon">🗑️</div>
        <div class="confirm-msg">
            Bạn có chắc muốn xóa khách hàng<br>
            <strong id="confirmName" style="color:var(--text-pri)"></strong>?<br>
            Hành động này không thể hoàn tác.
        </div>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn-ghost" onclick="closeConfirmModal()">Hủy</button>
            <button class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
        </div>
    </div>
</div>

<script>
/* ── State ── */
let allCustomers = [];
let deletingId   = null;

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', loadCustomers);

/* ── API helpers ── */
async function api(method, url, body = null) {
    const opts = {
        method,
        headers: body ? { 'Content-Type': 'application/x-www-form-urlencoded' } : {},
        body: body ? new URLSearchParams(body) : undefined,
    };
    const res  = await fetch(url, opts);
    return res.json();
}

/* ── Load customers ── */
async function loadCustomers() {
    try {
        const data = await api('GET', '/admin/customers');
        if (!data.success) { toast(data.message, 'error'); return; }
        allCustomers = data.customers || [];
        renderTable(allCustomers);
    } catch {
        toast('Không thể tải danh sách khách hàng.', 'error');
    }
}

/* ── Render table ── */
function renderTable(list) {
    const tbody = document.getElementById('tableBody');
    document.getElementById('countBadge').textContent = `${list.length} khách hàng`;

    if (list.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6"><div class="empty-state"><div class="icon">👤</div><p>Chưa có khách hàng nào.</p></div></td></tr>`;
        return;
    }

    tbody.innerHTML = list.map((c, i) => `
        <tr data-id="${c.id}" data-name="${esc(c.name)}" data-email="${esc(c.email)}">
            <td style="color:var(--text-muted);font-size:12px">${i + 1}</td>
            <td class="td-name">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#4f8ef7,#8a63d2);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;flex-shrink:0">
                        ${esc(c.name.charAt(0).toUpperCase())}
                    </div>
                    ${esc(c.name)}
                </div>
            </td>
            <td class="td-email">${esc(c.email)}</td>
            <td><span class="badge-customer">customer</span></td>
            <td class="td-date">${formatDate(c.created_at)}</td>
            <td>
                <div class="td-actions">
                    <button class="btn btn-edit" onclick="openEditModal(${c.id}, '${esc(c.name)}', '${esc(c.email)}')">✏️ Sửa</button>
                    <button class="btn btn-del"  onclick="openConfirmModal(${c.id}, '${esc(c.name)}')">🗑️ Xóa</button>
                </div>
            </td>
        </tr>
    `).join('');
}

/* ── Filter ── */
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const filtered = allCustomers.filter(c =>
        c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q)
    );
    renderTable(filtered);
}

/* ── Create Modal ── */
function openCreateModal() {
    document.getElementById('modalTitle').textContent     = '➕ Thêm khách hàng';
    document.getElementById('customerId').value           = '';
    document.getElementById('fieldName').value            = '';
    document.getElementById('fieldEmail').value           = '';
    document.getElementById('fieldPassword').value        = '';
    document.getElementById('fieldPassword').required     = true;
    document.getElementById('passHint').textContent       = '*';
    document.getElementById('passHintText').textContent   = 'Tối thiểu 8 ký tự.';
    document.getElementById('formSubmitBtn').textContent  = 'Tạo khách hàng';
    document.getElementById('formModal').classList.add('open');
    document.getElementById('fieldName').focus();
}

/* ── Edit Modal ── */
function openEditModal(id, name, email) {
    document.getElementById('modalTitle').textContent     = '✏️ Sửa khách hàng';
    document.getElementById('customerId').value           = id;
    document.getElementById('fieldName').value            = name;
    document.getElementById('fieldEmail').value           = email;
    document.getElementById('fieldPassword').value        = '';
    document.getElementById('fieldPassword').required     = false;
    document.getElementById('passHint').textContent       = '(tuỳ chọn)';
    document.getElementById('passHintText').textContent   = 'Để trống nếu không đổi mật khẩu.';
    document.getElementById('formSubmitBtn').textContent  = 'Lưu thay đổi';
    document.getElementById('formModal').classList.add('open');
    document.getElementById('fieldName').focus();
}

function closeFormModal() {
    document.getElementById('formModal').classList.remove('open');
}

/* ── Form submit (create or update) ── */
document.getElementById('customerForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn  = document.getElementById('formSubmitBtn');
    const id   = document.getElementById('customerId').value;
    const body = {
        name:  document.getElementById('fieldName').value.trim(),
        email: document.getElementById('fieldEmail').value.trim(),
        password: document.getElementById('fieldPassword').value,
    };

    btn.textContent = '⏳ Đang lưu...';
    btn.disabled    = true;

    try {
        const data = id
            ? await api('PUT',  `/admin/customers/${id}`, body)
            : await api('POST', '/admin/customers',        body);

        if (data.success) {
            closeFormModal();
            toast(data.message, 'success');
            await loadCustomers();
        } else {
            toast(data.message, 'error');
        }
    } catch {
        toast('Lỗi kết nối.', 'error');
    } finally {
        btn.textContent = id ? 'Lưu thay đổi' : 'Tạo khách hàng';
        btn.disabled    = false;
    }
});

/* ── Confirm Delete Modal ── */
function openConfirmModal(id, name) {
    deletingId = id;
    document.getElementById('confirmName').textContent = name;
    document.getElementById('confirmModal').classList.add('open');
}

function closeConfirmModal() {
    deletingId = null;
    document.getElementById('confirmModal').classList.remove('open');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    if (!deletingId) return;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.textContent = '⏳ Đang xóa...';
    btn.disabled    = true;

    try {
        const data = await api('DELETE', `/admin/customers/${deletingId}`);
        if (data.success) {
            closeConfirmModal();
            toast(data.message, 'success');
            await loadCustomers();
        } else {
            toast(data.message, 'error');
        }
    } catch {
        toast('Lỗi kết nối.', 'error');
    } finally {
        btn.textContent = 'Xóa';
        btn.disabled    = false;
    }
});

/* ── Close modal on overlay click ── */
document.getElementById('formModal').addEventListener('click', e => {
    if (e.target === document.getElementById('formModal')) closeFormModal();
});
document.getElementById('confirmModal').addEventListener('click', e => {
    if (e.target === document.getElementById('confirmModal')) closeConfirmModal();
});

/* ── Toast ── */
let toastTimer;
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className   = `show ${type}`;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { el.classList.remove('show'); }, 3000);
}

/* ── Helpers ── */
function esc(str) {
    return String(str).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

function formatDate(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}
</script>
