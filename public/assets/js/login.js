/* ── login.js ─────────────────────────────────────────────────────────
   Handles the login form submit: POST /auth/login, redirect on success.
   ──────────────────────────────────────────────────────────────────── */

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn  = document.getElementById('submitBtn');
    const txt  = btn.querySelector('.btn-text');
    const spin = btn.querySelector('.spinner-border');

    setLoading(btn, txt, spin, true);
    hideAlert();

    try {
        const body = new URLSearchParams({
            email:    this.email.value.trim(),
            password: this.password.value,
        });

        const res  = await fetch('/auth/login', { method: 'POST', body });
        const data = await res.json();

        if (data.success) {
            showAlert('Đăng nhập thành công! Đang chuyển hướng...', 'success');
            setTimeout(() => { window.location.href = data.redirect_url || '/'; }, 900);
        } else {
            showAlert(data.message || 'Đăng nhập thất bại.', 'danger');
        }
    } catch {
        showAlert('Lỗi kết nối. Vui lòng thử lại.', 'danger');
    } finally {
        setLoading(btn, txt, spin, false);
    }
});

function setLoading(btn, txt, spin, on) {
    btn.disabled = on;
    txt.classList.toggle('d-none', on);
    spin.classList.toggle('d-none', !on);
}

function showAlert(msg, type) {
    const el = document.getElementById('alert');
    el.textContent = msg;
    el.className   = `alert alert-${type}`;
}

function hideAlert() {
    document.getElementById('alert').className = 'alert d-none';
}
