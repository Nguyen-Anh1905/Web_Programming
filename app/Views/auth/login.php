<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">🔐</div>
            <span class="auth-logo-text">CRM System</span>
        </div>

        <h1 class="auth-title">Chào mừng trở lại</h1>
        <p class="auth-subtitle">Đăng nhập vào tài khoản của bạn để tiếp tục</p>

        <div id="alert" class="alert" role="alert"></div>

        <form id="loginForm" novalidate>
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
                <label class="form-label" for="password">Mật khẩu</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button id="submitBtn" type="submit" class="btn-primary">
                <span class="btn-text">Đăng nhập</span>
                <span class="spinner"></span>
            </button>
        </form>

        <div class="auth-footer">
            Chưa có tài khoản? <a href="/auth/register">Đăng ký ngay</a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn   = document.getElementById('submitBtn');
    const alert = document.getElementById('alert');
    const form  = e.target;

    // Show loading
    btn.classList.add('loading');
    btn.disabled = true;
    alert.style.display = 'none';

    try {
        const body = new URLSearchParams({
            email:    form.email.value.trim(),
            password: form.password.value,
        });

        const res  = await fetch('/auth/login', { method: 'POST', body });
        const data = await res.json();

        if (data.success) {
            showAlert('Đăng nhập thành công! Đang chuyển hướng...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url || '/';
            }, 900);
        } else {
            showAlert(data.message || 'Đăng nhập thất bại.', 'error');
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
    el.textContent    = msg;
    el.className      = 'alert alert-' + type;
    el.style.display  = 'block';
}
</script>
