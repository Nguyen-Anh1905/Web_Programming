/* ═══════════════════════════════════════════════════════════════════════
   customer-dashboard.js  —  Load booking history via AJAX on page load
   ═══════════════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => loadMyBookings());

async function loadMyBookings() {
    const box = document.getElementById('myBookings');
    try {
        const res  = await fetch('/customer/bookings');
        const data = await res.json();

        if (!data.success || !data.bookings || !data.bookings.length) {
            box.innerHTML = '<p style="color:var(--crm-muted);font-size:.875rem;margin:0">Chưa có đơn đặt phòng nào.</p>';
            return;
        }
        box.innerHTML = buildBookingTable(data.bookings);
    } catch {
        box.innerHTML = '<p style="color:#f87171;font-size:.875rem;margin:0">Không thể tải dữ liệu.</p>';
    }
}

/* Full-width table — giống hệt admin detail modal */
function buildBookingTable(bookings) {
    const fmt  = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
    const rows = bookings.map(b => `
        <tr>
            <td style="font-size:.8125rem;color:var(--crm-text)">${escHtml(b.room_name)}</td>
            <td style="font-size:.8125rem;color:var(--crm-muted);white-space:nowrap">${fmtDate(b.check_in)}</td>
            <td style="font-size:.8125rem;color:var(--crm-muted);white-space:nowrap">${fmtDate(b.check_out)}</td>
            <td style="font-size:.8125rem;white-space:nowrap">${fmt.format(b.total_price)}</td>
            <td>${statusBadge(b.status)}</td>
        </tr>`).join('');

    return `
        <div class="table-responsive">
            <table class="table table-sm mb-0" style="font-size:.875rem">
                <thead><tr>
                    <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Phòng</th>
                    <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Check-in</th>
                    <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Check-out</th>
                    <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Tổng tiền</th>
                    <th style="color:var(--crm-muted);font-weight:500;font-size:.75rem">Trạng thái</th>
                </tr></thead>
                <tbody>${rows}</tbody>
            </table>
        </div>`;
}

function statusBadge(status) {
    const map = {
        completed: ['#4ade80', 'rgba(34,197,94,.12)',  'rgba(34,197,94,.3)',  '✅ Hoàn thành'],
        confirmed: ['#fbbf24', 'rgba(251,191,36,.12)', 'rgba(251,191,36,.3)', '🕐 Đã xác nhận'],
        cancelled: ['#f87171', 'rgba(239,68,68,.12)',  'rgba(239,68,68,.3)',  '✖ Đã hủy'],
    };
    const [c, bg, bd, lbl] = map[status] || map.completed;
    return `<span class="tier-badge" style="color:${c};background:${bg};border-color:${bd};font-size:.7rem">${lbl}</span>`;
}

function fmtDate(str) {
    if (!str) return '—';
    const [y, m, d] = str.split('-');
    return `${d}/${m}/${y}`;
}

function escHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, c =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])
    );
}
