<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?> — CRM System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<?php
$payload = $payload ?? [];
$email   = $payload['email'] ?? 'user@example.com';
$name    = $payload['name']  ?? $email;
$role    = $payload['role']  ?? 'customer';
$initial = strtoupper(substr($name, 0, 1));
?>

<!-- ── Sidebar ── -->
<aside class="crm-sidebar">

    <!-- Logo -->
    <div class="d-flex align-items-center gap-2 px-3 py-3" style="border-bottom:1px solid var(--crm-border)">
        <div class="auth-logo-icon flex-shrink-0">📋</div>
        <span class="fw-bold" style="font-size:.9rem;color:var(--crm-text)">CRM System</span>
    </div>

    <!-- Navigation — only real routes -->
    <nav class="flex-grow-1 p-2 pt-3">
        <?php if ($role === 'admin'): ?>
        <a class="nav-link active" href="/admin/dashboard">
            <span class="nav-icon">👥</span> Quản lý Khách hàng
        </a>
        <?php else: ?>
        <a class="nav-link active" href="/customer/dashboard">
            <span class="nav-icon">🏠</span> Trang của tôi
        </a>
        <?php endif; ?>
    </nav>

    <!-- User card -->
    <div class="p-2" style="border-top:1px solid var(--crm-border)">
        <div class="d-flex align-items-center gap-2 rounded-2 p-2" style="background:var(--crm-surface-2)">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-semibold"
                 style="width:32px;height:32px;background:var(--crm-accent);font-size:.8rem;color:#fff">
                <?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-semibold text-truncate" style="font-size:.8rem;color:var(--crm-text)">
                    <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div style="font-size:.7rem;color:var(--crm-muted)">
                    <?= $role === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>
                </div>
            </div>
            <button id="logoutBtn" class="btn btn-sm p-1 border-0" title="Đăng xuất"
                    style="color:var(--crm-muted);background:none;line-height:1"
                    onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='var(--crm-muted)'">
                <!-- logout icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h7a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 13.5 2h-7A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
            </button>
        </div>
    </div>

</aside>

<!-- ── Main content ── -->
<div class="crm-main">

    <!-- Topbar -->
    <div class="crm-topbar">
        <span class="fw-semibold" style="font-size:.9375rem;color:var(--crm-text)">
            <?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?>
        </span>
        <span class="badge rounded-pill fw-semibold"
              style="font-size:.65rem;
                     <?= $role === 'admin'
                         ? 'background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3)'
                         : 'background:rgba(34,197,94,.15);color:#4ade80;border:1px solid rgba(34,197,94,.3)' ?>">
            <?= $role === 'admin' ? 'ADMIN' : 'CUSTOMER' ?>
        </span>
    </div>

    <!-- Page content -->
    <div class="p-4 flex-grow-1">
        <?= $content ?? '' ?>
    </div>

</div><!-- /crm-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('logoutBtn').addEventListener('click', async () => {
    try {
        const res  = await fetch('/auth/logout', { method: 'POST' });
        const data = await res.json();
        if (data.success) window.location.href = '/auth/login';
    } catch {
        window.location.href = '/auth/login';
    }
});
</script>
<?php if (!empty($pageScript)): ?>
<script src="<?= htmlspecialchars($pageScript, ENT_QUOTES, 'UTF-8') ?>"></script>
<?php endif; ?>

</body>
</html>
