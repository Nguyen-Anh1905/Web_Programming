<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:          #0d1117;
            --bg-2:        #161b22;
            --bg-3:        #21262d;
            --border:      rgba(48, 54, 61, 0.9);
            --text-pri:    #e6edf3;
            --text-sec:    #8b949e;
            --text-muted:  #484f58;
            --accent:      #4f8ef7;
            --accent-glow: rgba(79,142,247,0.25);
            --admin:       #f78166;
            --admin-glow:  rgba(247,129,102,0.25);
            --customer:    #3fb950;
            --customer-glow: rgba(63,185,80,0.25);
            --sidebar-w:   240px;
            --radius:      10px;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-pri);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 10;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 18px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .sidebar-logo-icon.admin    { background: linear-gradient(135deg,#f78166,#c9450d); box-shadow: 0 0 16px var(--admin-glow); }
        .sidebar-logo-icon.customer { background: linear-gradient(135deg,#3fb950,#1a7f37); box-shadow: 0 0 16px var(--customer-glow); }

        .sidebar-logo-text { font-weight: 700; font-size: 14px; letter-spacing: -0.2px; }

        .sidebar-nav { flex: 1; padding: 12px 10px; display: flex; flex-direction: column; gap: 2px; }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px;
            border-radius: 6px;
            color: var(--text-sec);
            text-decoration: none;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            cursor: pointer;
        }

        .nav-item:hover { background: var(--bg-3); color: var(--text-pri); }
        .nav-item.active { background: var(--bg-3); color: var(--text-pri); }
        .nav-item .icon { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 14px 10px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px;
            border-radius: 6px;
            background: var(--bg-3);
        }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #8a63d2);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600;
            flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name  { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role  { font-size: 11px; color: var(--text-sec); margin-top: 1px; }

        .logout-btn {
            background: none; border: none;
            color: var(--text-muted);
            cursor: pointer; font-size: 16px;
            padding: 4px; border-radius: 4px;
            transition: color 0.15s;
            flex-shrink: 0;
        }
        .logout-btn:hover { color: #f85149; }

        /* ── Main ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 56px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 28px;
            gap: 12px;
            background: var(--bg-2);
        }

        .topbar-title { font-size: 15px; font-weight: 600; }

        .role-badge {
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .role-badge.admin    { background: rgba(247,129,102,0.15); color: var(--admin);    border: 1px solid rgba(247,129,102,0.3); }
        .role-badge.customer { background: rgba(63,185,80,0.15);   color: var(--customer); border: 1px solid rgba(63,185,80,0.3); }

        .page-content { flex: 1; padding: 28px; }

        /* ── Cards ── */
        .card {
            background: var(--bg-2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 22px 24px;
        }

        .card-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-sec);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
        }

        .card-value {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.8px;
        }

        .card-sub {
            font-size: 12px;
            color: var(--text-sec);
            margin-top: 4px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .token-box {
            background: var(--bg-3);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px 16px;
            font-family: monospace;
            font-size: 11.5px;
            color: var(--text-sec);
            word-break: break-all;
            line-height: 1.6;
        }

        .token-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            font-family: 'Inter', sans-serif;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        @media (max-width: 640px) {
            .sidebar { transform: translateX(-100%); }
            .main    { margin-left: 0; }
        }
    </style>
</head>
<body>

<?php
$payload = $payload ?? [];
$email   = $payload['email'] ?? 'user@example.com';
$role    = $payload['role']  ?? 'customer';
$initial = strtoupper(substr($email, 0, 1));
?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon <?= htmlspecialchars($role) ?>">
            <?= $role === 'admin' ? '⚙️' : '👤' ?>
        </div>
        <span class="sidebar-logo-text">CRM System</span>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-item active" href="#">
            <span class="icon">🏠</span> Dashboard
        </a>
        <?php if ($role === 'admin'): ?>
        <a class="nav-item" href="#">
            <span class="icon">👥</span> Người dùng
        </a>
        <a class="nav-item" href="#">
            <span class="icon">📊</span> Báo cáo
        </a>
        <a class="nav-item" href="#">
            <span class="icon">🔧</span> Cài đặt
        </a>
        <?php else: ?>
        <a class="nav-item" href="#">
            <span class="icon">📦</span> Đơn hàng
        </a>
        <a class="nav-item" href="#">
            <span class="icon">💬</span> Hỗ trợ
        </a>
        <a class="nav-item" href="#">
            <span class="icon">👤</span> Hồ sơ
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= htmlspecialchars($initial) ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($email) ?></div>
                <div class="user-role"><?= $role === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?></div>
            </div>
            <button class="logout-btn" id="logoutBtn" title="Đăng xuất">↩</button>
        </div>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <span class="topbar-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></span>
        <span class="role-badge <?= htmlspecialchars($role) ?>">
            <?= $role === 'admin' ? 'ADMIN' : 'CUSTOMER' ?>
        </span>
    </div>

    <div class="page-content">
        <?= $content ?? '' ?>
    </div>
</div>

<script>
document.getElementById('logoutBtn').addEventListener('click', async () => {
    const res  = await fetch('/auth/logout', { method: 'POST' });
    const data = await res.json();
    if (data.success) window.location.href = '/auth/login';
});
</script>

</body>
</html>
