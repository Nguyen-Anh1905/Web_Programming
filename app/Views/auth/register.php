<!-- auth/register.php — Bootstrap 5 · no inline JS (logic in /assets/js/register.js) -->

<div class="auth-wrapper">
    <div class="auth-card card border-0 shadow-lg p-4 p-md-5">

        <!-- Logo -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="auth-logo-icon">📋</div>
            <span class="fw-bold" style="font-size:.9375rem;color:var(--crm-text)">CRM System</span>
        </div>

        <h1 class="h4 fw-bold mb-1" style="color:var(--crm-text)">Tạo tài khoản mới</h1>
        <p class="mb-4" style="font-size:.875rem;color:var(--crm-muted)">
            Điền thông tin để bắt đầu sử dụng hệ thống
        </p>

        <div id="alert" class="alert d-none" role="alert"></div>

        <form id="registerForm" novalidate>
            <div class="mb-3">
                <label class="form-label fw-medium" for="name" style="font-size:.8125rem">
                    Họ và tên
                </label>
                <input id="name" name="name" type="text" class="form-control"
                       placeholder="Nguyễn Văn A" autocomplete="name" required>
            </div>

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
                    <span class="fw-normal" style="color:var(--crm-muted)">(tối thiểu 8 ký tự)</span>
                </label>
                <input id="password" name="password" type="password" class="form-control"
                       placeholder="••••••••" autocomplete="new-password" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium" for="password_confirm" style="font-size:.8125rem">
                    Xác nhận mật khẩu
                </label>
                <input id="password_confirm" name="password_confirm" type="password" class="form-control"
                       placeholder="••••••••" autocomplete="new-password" required>
            </div>

            <button id="submitBtn" type="submit" class="btn btn-primary w-100 fw-semibold mt-1">
                <span class="btn-text">Tạo tài khoản</span>
                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
            </button>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:.8125rem;color:var(--crm-muted)">
            Đã có tài khoản?
            <a href="/auth/login" class="text-decoration-none fw-medium" style="color:var(--crm-accent)">
                Đăng nhập
            </a>
        </p>

    </div>
</div>

<script src="/assets/js/register.js"></script>
