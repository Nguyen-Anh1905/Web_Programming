<!-- auth/login.php — Bootstrap 5 · no inline JS (logic in /assets/js/login.js) -->

<div class="auth-wrapper">
    <div class="auth-card card border-0 shadow-lg p-4 p-md-5">

        <!-- Logo -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="auth-logo-icon">📋</div>
            <span class="fw-bold" style="font-size:.9375rem;color:var(--crm-text)">CRM System</span>
        </div>

        <h1 class="h4 fw-bold mb-1" style="color:var(--crm-text)">Chào mừng trở lại</h1>
        <p class="mb-4" style="font-size:.875rem;color:var(--crm-muted)">
            Đăng nhập vào tài khoản của bạn để tiếp tục
        </p>

        <div id="alert" class="alert d-none" role="alert"></div>

        <form id="loginForm" novalidate>
            <div class="mb-3">
                <label class="form-label fw-medium" for="email" style="font-size:.8125rem">
                    Địa chỉ Email
                </label>
                <input id="email" name="email" type="email" class="form-control"
                       placeholder="you@example.com" autocomplete="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium" for="password" style="font-size:.8125rem">
                    Mật khẩu
                </label>
                <input id="password" name="password" type="password" class="form-control"
                       placeholder="••••••••" autocomplete="current-password" required>
            </div>

            <button id="submitBtn" type="submit" class="btn btn-primary w-100 fw-semibold mt-1">
                <span class="btn-text">Đăng nhập</span>
                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
            </button>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:.8125rem;color:var(--crm-muted)">
            Chưa có tài khoản?
            <a href="/auth/register" class="text-decoration-none fw-medium" style="color:var(--crm-accent)">
                Đăng ký ngay
            </a>
        </p>

    </div>
</div>

<script src="/assets/js/login.js"></script>
