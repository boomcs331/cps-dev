<?php

$permissions = $permissions ?? ($_SESSION['permissions'] ?? []);
if (!is_array($permissions)) {
    $permissions = [];
}

$dashboardMetrics = $dashboardMetrics ?? [];

$kpiCards = [
    [
        'title' => 'สินค้าทั้งหมด',
        'value' => number_format($dashboardMetrics['total_products'] ?? 48320),
        'trend' => $dashboardMetrics['products_trend'] ?? '+8.4% เทียบสัปดาห์ก่อน',
        'trendType' => $dashboardMetrics['products_trend_type'] ?? 'up',
    ],
    [
        'title' => 'ลูกค้าที่ใช้งาน',
        'value' => number_format($dashboardMetrics['active_customers'] ?? 312),
        'trend' => $dashboardMetrics['customers_trend'] ?? '+6 ลูกค้าองค์กร',
        'trendType' => $dashboardMetrics['customers_trend_type'] ?? 'up',
    ],
    [
        'title' => 'สาขา/โรงงาน',
        'value' => number_format($dashboardMetrics['branches'] ?? 8),
        'trend' => $dashboardMetrics['branches_meta'] ?? 'โรงงานหลัก 7 · HQ 1',
        'trendType' => $dashboardMetrics['branches_trend_type'] ?? 'neutral',
    ],
    [
        'title' => 'ผลิตเฉลี่ยต่อวัน',
        'value' => number_format($dashboardMetrics['avg_daily_output'] ?? 7920) . ' ชิ้น',
        'trend' => $dashboardMetrics['daily_output_trend'] ?? '103% ของเป้าหมาย',
        'trendType' => $dashboardMetrics['daily_output_trend_type'] ?? 'up',
    ],
];

$productBreakdown = $productBreakdown ?? [
    ['name' => 'ระบบเครื่องยนต์', 'value' => 480, 'percent' => 39],
    ['name' => 'โครงรถ & กันสะเทือน', 'value' => 360, 'percent' => 29],
    ['name' => 'ระบบเบรก', 'value' => 220, 'percent' => 18],
    ['name' => 'ชุดประกอบอื่น', 'value' => 180, 'percent' => 14],
];

$weeklyComparison = $weeklyComparison ?? [
    ['week' => 'สัปดาห์ 39', 'inventory' => 18000, 'orders' => 15600],
    ['week' => 'สัปดาห์ 40', 'inventory' => 17000, 'orders' => 16000],
    ['week' => 'สัปดาห์ 41', 'inventory' => 16500, 'orders' => 18200],
    ['week' => 'สัปดาห์ 42', 'inventory' => 15200, 'orders' => 16800],
];

$productWeekly = $productWeekly ?? [
    '24651-KRM-840' => [
        'label' => 'SPG.SHIFT RETURN',
        'weeks' => [
            ['week' => '39', 'inventory' => 18000, 'orders' => 15600],
            ['week' => '40', 'inventory' => 16800, 'orders' => 14400],
            ['week' => '41', 'inventory' => 15600, 'orders' => 17600],
            ['week' => '42', 'inventory' => 14800, 'orders' => 16800],
        ],
    ],
    '53220-GN5-850' => [
        'label' => 'THREAD COMP STRG TOP',
        'weeks' => [
            ['week' => '39', 'inventory' => 16000, 'orders' => 14800],
            ['week' => '40', 'inventory' => 16800, 'orders' => 15200],
            ['week' => '41', 'inventory' => 15800, 'orders' => 17000],
            ['week' => '42', 'inventory' => 15000, 'orders' => 16000],
        ],
    ],
    '90306-K87-A000' => [
        'label' => 'NUT STRG STEM',
        'weeks' => [
            ['week' => '39', 'inventory' => 14000, 'orders' => 15200],
            ['week' => '40', 'inventory' => 14800, 'orders' => 16800],
            ['week' => '41', 'inventory' => 15600, 'orders' => 16000],
            ['week' => '42', 'inventory' => 16400, 'orders' => 15000],
        ],
    ],
    '95015-32001' => [
        'label' => 'JOINT B BRAKE ARM',
        'weeks' => [
            ['week' => '39', 'inventory' => 13200, 'orders' => 14000],
            ['week' => '40', 'inventory' => 14400, 'orders' => 15600],
            ['week' => '41', 'inventory' => 15200, 'orders' => 14800],
            ['week' => '42', 'inventory' => 13800, 'orders' => 16000],
        ],
    ],
    '90501-K26-9500' => [
        'label' => 'WASHER PLAIN 6 MM',
        'weeks' => [
            ['week' => '39', 'inventory' => 15000, 'orders' => 13800],
            ['week' => '40', 'inventory' => 16000, 'orders' => 15000],
            ['week' => '41', 'inventory' => 16800, 'orders' => 18000],
            ['week' => '42', 'inventory' => 15800, 'orders' => 17000],
        ],
    ],
];

$lineStatus = $lineStatus ?? [
    ['line' => 'สาย A3 · โครงเกียร์', 'detail' => 'OEE 96.1% · 3,720 ชิ้น/กะ', 'status' => 'กำลังผลิต'],
    ['line' => 'สาย B1 · ปีกนก', 'detail' => 'เพิ่มรอบตรวจ QC +12 นาที/ล็อต', 'status' => 'เฝ้าระวัง'],
    ['line' => 'สาย C2 · ก้ามเบรก', 'detail' => 'คิวเปลี่ยนแม่พิมพ์ใน 25 นาที', 'status' => 'กำลังผลิต'],
    ['line' => 'สาย D4 · ระบบพวงมาลัย', 'detail' => 'ซ่อมวาล์วไฮดรอลิก 45 นาที', 'status' => 'หยุดฉุกเฉิน'],
];

$alerts = $alerts ?? [
    ['title' => 'เหล็กเกรด 42CrMo4 ส่งล่าช้า', 'meta' => 'ซัพพลายเออร์หลัก · 6 ชม. · กระทบสาย C2', 'type' => 'critical'],
    ['title' => 'กะกลางคืนต่ำกว่าเป้า 5%', 'meta' => 'เครื่องปรับบ่อย · รอกำหนดกะสำรอง', 'type' => 'warning'],
    ['title' => 'QC ขอเพิ่มตัวอย่าง X-ray', 'meta' => 'โครงเกียร์ล็อต 2211-07', 'type' => 'info'],
];

$maintenanceTasks = $maintenanceTasks ?? [
    ['title' => 'เปลี่ยนซีลไฮดรอลิก · สาย D4', 'meta' => 'เครื่องกด #2 · 10:30 น.', 'time' => 'อีก 45 นาที'],
    ['title' => 'ปรับเทียบแขนกล · สาย B1', 'meta' => 'หลังจบกะกลางวัน · ทีมวิศวกรรม', 'time' => 'วันนี้ 16:00 น.'],
    ['title' => 'ตรวจระบบหล่อเย็น · สาย A3', 'meta' => 'แจ้งเตือน IoT · อุณหภูมิสูง', 'time' => 'ภายใน 6 ชม.'],
];

require_once 'helpers/MenuHelper.php';
$menuItems = MenuHelper::getAccessibleMenuItems();


$maxWeeklyValue = 0;
foreach ($weeklyComparison as $week) {
    $maxWeeklyValue = max($maxWeeklyValue, $week['inventory'], $week['orders']);
}
$maxWeeklyValue = $maxWeeklyValue ?: 1;
?>


<?php include 'views/layouts/navbar-mazer.php'; ?>
<?php include 'views/layouts/header.php'; ?>
<div class="dashboard-container">
    <?php if (!empty($menuItems)): ?>
        <section class="menu-section">
             <article class="kpi-card">
            <div class="menu-header">
                <h2>เมนูที่สามารถเข้าถึงได้</h2>
                <p>เลือกจัดการโมดูลที่คุณมีสิทธิ์เข้าถึงได้อย่างรวดเร็ว</p>
            </div>
            <div class="menu-grid">
                <?php foreach ($menuItems as $item): ?>
                    <?php if ($item['type'] === 'link'): ?>
                        <a class="menu-card" href="<?= htmlspecialchars($item['url']) ?>">
                            <div class="menu-card-icon">
                                <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                            </div>
                            <div class="menu-card-text">
                                <h3><?= htmlspecialchars($item['title']) ?></h3>
                                <p><?= htmlspecialchars($item['subtitle']) ?></p>
                            </div>
                        </a>
                    <?php elseif ($item['type'] === 'modal'): ?>
                        <button type="button" class="menu-card menu-card-button" data-bs-toggle="modal" data-bs-target="<?= htmlspecialchars($item['target']) ?>">
                            <div class="menu-card-icon">
                                <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                            </div>
                            <div class="menu-card-text">
                                <h3><?= htmlspecialchars($item['title']) ?></h3>
                                <p><?= htmlspecialchars($item['subtitle']) ?></p>
                            </div>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            </article>
        </section>
        <section class="kpi-grid">
            <article class="kpi-card">
                <div class="menu-header">
                    <h2>เมนูที่สามารถเข้าถึงได้</h2>
                    <p>เลือกจัดการโมดูลที่คุณมีสิทธิ์เข้าถึงได้อย่างรวดเร็ว</p>
                </div>
            </article>
        </section>
    <?php endif; ?>
</div>
<?php include 'views/layouts/footer.php'; ?>