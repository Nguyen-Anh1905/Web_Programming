<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'CRM System', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-dark:     #0d1117;
            --bg-card:     rgba(22, 27, 34, 0.85);
            --border:      rgba(48, 54, 61, 0.8);
            --accent:      #4f8ef7;
            --accent-glow: rgba(79, 142, 247, 0.35);
            --accent-dark: #3a6fd4;
            --text-pri:    #e6edf3;
            --text-sec:    #8b949e;
            --error:       #f85149;
            --success:     #3fb950;
            --radius:      14px;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(79,142,247,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(138,99,210,0.10) 0%, transparent 55%);
            pointer-events: none;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 16px;
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px 40px 36px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.04) inset,
                0 24px 64px rgba(0,0,0,0.5);
            animation: slideUp 0.4s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .auth-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), #8a63d2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .auth-logo-text {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-pri);
            letter-spacing: -0.2px;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-pri);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 13.5px;
            color: var(--text-sec);
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-sec);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            background: rgba(13,17,23,0.7);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-pri);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input::placeholder { color: rgba(139,148,158,0.5); }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .btn-primary {
            width: 100%;
            padding: 11px 20px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 0 20px var(--accent-glow);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover  { background: var(--accent-dark); box-shadow: 0 0 28px rgba(79,142,247,0.5); }
        .btn-primary:active { transform: scale(0.98); }

        .btn-primary .spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-primary.loading .btn-text { display: none; }
        .btn-primary.loading .spinner  { display: block; }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-sec);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover { text-decoration: underline; }

        .alert {
            display: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }

        .alert-error   { background: rgba(248,81,73,0.15);  border: 1px solid rgba(248,81,73,0.35);  color: #f85149; }
        .alert-success { background: rgba(63,185,80,0.15);  border: 1px solid rgba(63,185,80,0.35);  color: #3fb950; }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
            color: var(--text-sec);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }

        @media (max-width: 480px) {
            .auth-card { padding: 28px 24px; }
        }
    </style>
</head>
<body>
    <?= $content ?? '' ?>
</body>
</html>
