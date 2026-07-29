<?php
/** @var array $payload */
/** @var array $profile */
$payload = $payload ?? [];
$profile = $profile ?? [];

$name    = $profile['name']  ?? ($payload['email'] ?? 'Người dùng');
$email   = $profile['email'] ?? ($payload['email'] ?? '');
$initial = strtoupper(substr($name, 0, 1));

// Giới tính nhãn hiển thị
$genderMap = ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'];
$genderLb  = $genderMap[$profile['gender'] ?? ''] ?? '—';

// Hạng thành viên badge
$tierMap = [
    'dong'   => ['label' => '🥉 Đồng',   'color' => '#cd7f32', 'bg' => 'rgba(205,127,50,.12)',  'bd' => 'rgba(205,127,50,.3)'],
    'bac'    => ['label' => '🥈 Bạc',    'color' => '#9ca3af', 'bg' => 'rgba(156,163,175,.12)', 'bd' => 'rgba(156,163,175,.3)'],
    'vang'   => ['label' => '🥇 Vàng',   'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.12)',  'bd' => 'rgba(245,158,11,.3)'],
    'kim_cuong' => ['label' => '💎 Kim cương', 'color' => '#60a5fa', 'bg' => 'rgba(96,165,250,.12)', 'bd' => 'rgba(96,165,250,.3)'],
];
$tier    = $profile['member_tier'] ?? 'dong';
$tierCfg = $tierMap[$tier] ?? $tierMap['dong'];
$tierBadgeHtml = '<span class="tier-badge" style="color:' . $tierCfg['color'] . ';background:' . $tierCfg['bg'] . ';border-color:' . $tierCfg['bd'] . '">'
               . htmlspecialchars($tierCfg['label'], ENT_QUOTES, 'UTF-8') . '</span>';

// 8 trường thông tin (hiển thị dạng grid 2 cột)
$infoFields = [
    ['Họ và tên',       htmlspecialchars($name, ENT_QUOTES, 'UTF-8')],
    ['Email',           htmlspecialchars($email, ENT_QUOTES, 'UTF-8')],
    ['Điện thoại',      !empty($profile['phone'])   ? htmlspecialchars($profile['phone'],   ENT_QUOTES, 'UTF-8') : '—'],
    ['Địa chỉ',         !empty($profile['address']) ? htmlspecialchars($profile['address'], ENT_QUOTES, 'UTF-8') : '—'],
    ['Ngày sinh',       !empty($profile['dob'])     ? date('d/m/Y', strtotime($profile['dob'])) : '—'],
    ['Giới tính',       $genderLb],
    ['Điểm thành viên', '⭐ ' . ($profile['member_points'] ?? 0)],
    ['Hạng thành viên', $tierBadgeHtml],
];
?>

<div class="row g-4">

    <!-- ── Banner ── -->
    <div class="col-12">
        <div class="card p-4" style="border-color:var(--crm-border)">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                     style="width:52px;height:52px;background:var(--crm-accent);font-size:1.3rem;color:#fff">
                    <?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div>
                    <div class="fw-bold fs-5" style="color:var(--crm-text)">
                        Xin chào, <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>!
                    </div>
                    <div style="font-size:.875rem;color:var(--crm-muted)">
                        <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Thông tin tài khoản (grid 2 cột × 4 hàng) ── -->
    <div class="col-12">
        <div class="card p-4" style="border-color:var(--crm-border)">
            <h2 class="fw-semibold mb-3"
                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;color:var(--crm-muted)">
                Thông tin tài khoản
            </h2>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 2rem">
                <?php foreach ($infoFields as [$label, $value]): ?>
                <div style="border-bottom:1px solid var(--crm-border);padding:.6rem 0;display:flex;align-items:center;gap:.75rem;min-width:0">
                    <span style="font-size:.8125rem;color:var(--crm-muted);flex:0 0 140px;font-weight:500">
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                    <span style="font-size:.8125rem;color:var(--crm-text);word-break:break-word;flex:1">
                        <?= $value ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Lịch sử đặt phòng (full width, load bằng AJAX) ── -->
    <div class="col-12">
        <div class="card p-4" style="border-color:var(--crm-border)">
            <h2 class="fw-semibold mb-3"
                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;color:var(--crm-muted)">
                Lịch sử đặt phòng
            </h2>
            <div id="myBookings">
                <div class="text-center py-3" style="color:var(--crm-muted)">
                    <div class="spinner-border spinner-border-sm me-1" role="status"></div>
                    Đang tải...
                </div>
            </div>
        </div>
    </div>

</div>