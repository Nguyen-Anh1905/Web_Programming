<?php
/** @var array $payload */
$payload = $payload ?? [];
?>

<div class="stats-grid">
    <div class="card">
        <div class="card-title">Đơn hàng của tôi</div>
        <div class="card-value" style="color:#4f8ef7">12</div>
        <div class="card-sub">3 đang xử lý</div>
    </div>
    <div class="card">
        <div class="card-title">Điểm thưởng</div>
        <div class="card-value" style="color:#3fb950">2,450</div>
        <div class="card-sub">Đủ điều kiện đổi quà</div>
    </div>
    <div class="card">
        <div class="card-title">Ưu đãi hiện có</div>
        <div class="card-value" style="color:#d2a8ff">5</div>
        <div class="card-sub">Hết hạn trong 7 ngày</div>
    </div>
    <div class="card">
        <div class="card-title">Hạng thành viên</div>
        <div class="card-value" style="color:#f0c000; font-size:22px">⭐ Silver</div>
        <div class="card-sub">550 điểm nữa lên Gold</div>
    </div>
</div>

<div class="section-title">🎉 Ưu đãi dành cho bạn</div>
<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(240px,1fr)); gap:14px; margin-bottom:24px">
    <?php
    $offers = [
        ['icon'=>'🛒','title'=>'Giảm 20% đơn hàng tiếp theo','desc'=>'Áp dụng đến 31/07/2026','color'=>'#4f8ef7'],
        ['icon'=>'🎁','title'=>'Quà tặng sinh nhật','desc'=>'Nhận ngay tại cửa hàng gần bạn','color'=>'#3fb950'],
        ['icon'=>'🚚','title'=>'Miễn phí vận chuyển','desc'=>'Đơn hàng từ ₫200K trở lên','color'=>'#d2a8ff'],
    ];
    foreach ($offers as $offer): ?>
    <div class="card" style="border-color: <?= $offer['color'] ?>33">
        <div style="font-size:24px; margin-bottom:10px"><?= $offer['icon'] ?></div>
        <div style="font-weight:600; margin-bottom:4px"><?= htmlspecialchars($offer['title']) ?></div>
        <div style="font-size:12px; color:var(--text-sec)"><?= htmlspecialchars($offer['desc']) ?></div>
    </div>
    <?php endforeach; ?>
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
