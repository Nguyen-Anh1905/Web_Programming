<?php
/** @var array $payload */
$payload = $payload ?? [];
?>

<div class="stats-grid">
    <div class="card">
        <div class="card-title">Người dùng</div>
        <div class="card-value" style="color:#4f8ef7">1,284</div>
        <div class="card-sub">↑ 12% so với tháng trước</div>
    </div>
    <div class="card">
        <div class="card-title">Doanh thu</div>
        <div class="card-value" style="color:#3fb950">₫48.2M</div>
        <div class="card-sub">↑ 8.3% so với tháng trước</div>
    </div>
    <div class="card">
        <div class="card-title">Đơn hàng</div>
        <div class="card-value" style="color:#f78166">342</div>
        <div class="card-sub">↓ 3% so với tháng trước</div>
    </div>
    <div class="card">
        <div class="card-title">Đang hoạt động</div>
        <div class="card-value" style="color:#d2a8ff">97</div>
        <div class="card-sub">Online ngay bây giờ</div>
    </div>
</div>

<div class="section-title">🔐 JWT Payload (debug)</div>
<div class="token-label">Access Token Claims</div>
<div class="token-box">
<?php foreach ($payload as $key => $value): ?>
    <strong style="color:#79c0ff"><?= htmlspecialchars((string)$key) ?></strong>:
    <?php if ($key === 'exp' || $key === 'iat'): ?>
        <?= htmlspecialchars((string)$value) ?> <span style="color:#484f58">(<?= date('d/m/Y H:i:s', (int)$value) ?>)</span>
    <?php else: ?>
        <?= htmlspecialchars((string)$value) ?>
    <?php endif; ?>
    <br>
<?php endforeach; ?>
</div>

<br>
<div class="card" style="border-color: rgba(247,129,102,0.3); background: rgba(247,129,102,0.05);">
    <div style="font-size:13px; color:#f78166; font-weight:600; margin-bottom:8px;">⚠️ Khu vực Admin Only</div>
    <div style="color:var(--text-sec); font-size:13px; line-height:1.6">
        Trang này chỉ dành cho tài khoản có role <strong style="color:#f78166">admin</strong>.<br>
        Customer cố truy cập sẽ nhận lỗi <code>403 FORBIDDEN</code>.
    </div>
</div>
