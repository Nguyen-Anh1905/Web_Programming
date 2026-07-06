<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">🔐</div>
            <span class="auth-logo-text">CRM System</span>
        </div>

        <h1 class="auth-title">Tạo tài khoản mới</h1>
        <p class="auth-subtitle">Điền thông tin để bắt đầu sử dụng hệ thống</p>

        <div id="alert" class="alert" role="alert"></div>

        <form id="registerForm" novalidate>
            <div class="form-group">
                <label class="form-label" for="name">Họ và tên</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-input"
                    placeholder="Nguyễn Văn A"
                    autocomplete="name"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Địa chỉ Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-input"
                    placeholder="you@example.com"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Mật khẩu <span style="color:var(--text-sec);font-weight:400">(tối thiểu 8 ký tự)</span></label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirm">Xác nhận mật khẩu</label>
                <input
                    id="password_confirm"
                    name="password_confirm"
                    type="password"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button id="submitBtn" type="submit" class="btn-primary">
                <span class="btn-text">Tạo tài khoản</span>
                <span class="spinner"></span>
            </button>
        </form>

        <div class="auth-footer">
            Đã có tài khoản? <a href="/auth/login">Đăng nhập</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn  = document.getElementById('submitBtn');
    const form = e.target;

    // Client-side validation
    if (form.password.value !== form.password_confirm.value) {
        showAlert('Mật khẩu xác nhận không khớp.', 'error');
        return;
    }

    if (form.password.value.length < 8) {
        showAlert('Mật khẩu phải có ít nhất 8 ký tự.', 'error');
        return;
    }

    btn.classList.add('loading');
    btn.disabled = true;
    document.getElementById('alert').style.display = 'none';

    try {
        const body = new URLSearchParams({
            name:     form.name.value.trim(),
            email:    form.email.value.trim(),
            password: form.password.value,
        });

        const res  = await fetch('/auth/register', { method: 'POST', body });
        const data = await res.json();

        if (data.success) {
            showAlert('Đăng ký thành công! Đang chuyển đến trang đăng nhập...', 'success');
            setTimeout(() => { window.location.href = '/auth/login'; }, 1200);
        } else {
            showAlert(data.message || 'Đăng ký thất bại.', 'error');
        }
    } catch {
        showAlert('Lỗi kết nối. Vui lòng thử lại.', 'error');
    } finally {
        btn.classList.remove('loading');
        btn.disabled = false;
    }
});

function showAlert(msg, type) {
    const el = document.getElementById('alert');
    el.textContent   = msg;
    el.className     = 'alert alert-' + type;
    el.style.display = 'block';
}
</script>
