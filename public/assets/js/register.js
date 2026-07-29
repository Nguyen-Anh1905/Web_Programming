/* ── register.js ───────────────────────────────────────────────────────
   Handles the register form submit: POST /auth/register.
   ──────────────────────────────────────────────────────────────────── */

document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn  = document.getElementById('submitBtn');
    const txt  = btn.querySelector('.btn-text');
    const spin = btn.querySelector('.spinner-border');

    // Client-side validation
    if (this.password.value !== this.password_confirm.value) {
        showAlert('Mật khẩu xác nhận không khớp.', 'danger');
        return;
    }
    if (this.password.value.length < 8) {
        showAlert('Mật khẩu phải có ít nhất 8 ký tự.', 'danger');
        return;
    }

    setLoading(btn, txt, spin, true);
    hideAlert();

    try {
        const body = new URLSearchParams({
            name:     this.name.value.trim(),
            email:    this.email.value.trim(),
            password: this.password.value,
        });

        const res  = await fetch('/auth/register', { method: 'POST', body });
        const data = await res.json();

        if (data.success) {
            showAlert('Đăng ký thành công! Đang chuyển đến trang đăng nhập...', 'success');
            setTimeout(() => { window.location.href = '/auth/login'; }, 1200);
        } else {
            showAlert(data.message || 'Đăng ký thất bại.', 'danger');
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
