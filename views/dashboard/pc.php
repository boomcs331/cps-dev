<?php
require_once 'helpers/MenuHelper.php';

$permissions = $permissions ?? ($_SESSION['permissions'] ?? []);
if (!is_array($permissions)) {
    $permissions = [];
}

$dashboardMetrics = $dashboardMetrics ?? [];

$kpiCards = [
    [
        'title' => 'วัตถุดิบทั้งหมด',
        'value' => number_format($dashboardMetrics['total_materials'] ?? 2840),
        'trend' => $dashboardMetrics['materials_trend'] ?? '+12 รายการใหม่',
        'trendType' => $dashboardMetrics['materials_trend_type'] ?? 'up',
    ],
    [
        'title' => 'คงคลังต่ำ',
        'value' => number_format($dashboardMetrics['low_stock'] ?? 47),
        'trend' => $dashboardMetrics['low_stock_trend'] ?? 'ต้องเติมสต็อก',
        'trendType' => $dashboardMetrics['low_stock_trend_type'] ?? 'warning',
    ],
    [
        'title' => 'ซัพพลายเออร์',
        'value' => number_format($dashboardMetrics['suppliers'] ?? 156),
        'trend' => $dashboardMetrics['suppliers_trend'] ?? 'ใช้งานปกติ',
        'trendType' => $dashboardMetrics['suppliers_trend_type'] ?? 'neutral',
    ],
    [
        'title' => 'มูลค่าคงคลัง',
        'value' => '฿' . number_format($dashboardMetrics['inventory_value'] ?? 18500000),
        'trend' => $dashboardMetrics['inventory_trend'] ?? '+2.8% จากเดือนก่อน',
        'trendType' => $dashboardMetrics['inventory_trend_type'] ?? 'up',
    ],
];

$materialBreakdown = $materialBreakdown ?? [
    ['name' => 'วัตถุดิบหลัก', 'value' => 1280, 'percent' => 45],
    ['name' => 'วัตถุดิบรอง', 'value' => 860, 'percent' => 30],
    ['name' => 'วัสดุสิ้นเปลือง', 'value' => 480, 'percent' => 17],
    ['name' => 'อะไหล่', 'value' => 220, 'percent' => 8],
];

$weeklyComparison = $weeklyComparison ?? [
    ['week' => 'สัปดาห์ 39', 'inventory' => 12000, 'orders' => 8600],
    ['week' => 'สัปดาห์ 40', 'inventory' => 11500, 'orders' => 9200],
    ['week' => 'สัปดาห์ 41', 'inventory' => 13200, 'orders' => 10800],
    ['week' => 'สัปดาห์ 42', 'inventory' => 12800, 'orders' => 9400],
];

$materialWeekly = $materialWeekly ?? [
    'ST-304-16MM' => [
        'label' => 'STAINLESS STEEL 304',
        'weeks' => [
            ['week' => '39', 'inventory' => 2400, 'orders' => 1800],
            ['week' => '40', 'inventory' => 2200, 'orders' => 2100],
            ['week' => '41', 'inventory' => 2600, 'orders' => 1900],
            ['week' => '42', 'inventory' => 2300, 'orders' => 2000],
        ],
    ],
    'AL-6061-T6' => [
        'label' => 'ALUMINUM ALLOY 6061',
        'weeks' => [
            ['week' => '39', 'inventory' => 1800, 'orders' => 1600],
            ['week' => '40', 'inventory' => 1900, 'orders' => 1700],
            ['week' => '41', 'inventory' => 2100, 'orders' => 1800],
            ['week' => '42', 'inventory' => 1950, 'orders' => 1650],
        ],
    ],
    'CR-STEEL-S45C' => [
        'label' => 'CARBON STEEL S45C',
        'weeks' => [
            ['week' => '39', 'inventory' => 1600, 'orders' => 1400],
            ['week' => '40', 'inventory' => 1750, 'orders' => 1550],
            ['week' => '41', 'inventory' => 1680, 'orders' => 1620],
            ['week' => '42', 'inventory' => 1720, 'orders' => 1480],
        ],
    ],
];

$supplierStatus = $supplierStatus ?? [
    ['supplier' => 'บริษัท เหล็กไทย จำกัด', 'detail' => 'ส่งมอบตรงเวลา 98.5% · คุณภาพดี', 'status' => 'ปกติ'],
    ['supplier' => 'อลูมิเนียม อินดัสทรี่', 'detail' => 'ล่าช้า 2 วัน · รอการยืนยัน', 'status' => 'เฝ้าระวัง'],
    ['supplier' => 'สแตนเลส ซัพพลาย', 'detail' => 'คุณภาพไม่ผ่าน QC · ส่งคืน', 'status' => 'หยุดฉุกเฉิน'],
];

$materialAlerts = $materialAlerts ?? [
    ['title' => 'เหล็กเกรด S45C คงคลังต่ำ', 'meta' => 'เหลือ 180 ชิ้น · ต่ำกว่า Safety Stock', 'type' => 'critical'],
    ['title' => 'อลูมิเนียม 6061 ล่าช้า', 'meta' => 'ซัพพลายเออร์แจ้งล่าช้า 3 วัน', 'type' => 'warning'],
    ['title' => 'ตรวจสอบคุณภาพ Lot ST304-2411', 'meta' => 'QC ขอตรวจเพิ่มเติม', 'type' => 'info'],
];

$inspectionTasks = $inspectionTasks ?? [
    ['title' => 'ตรวจรับ Lot เหล็ก S45C', 'meta' => 'จาก บริษัท เหล็กไทย · 500 ชิ้น', 'time' => 'วันนี้ 14:00 น.'],
    ['title' => 'ตรวจคุณภาพอลูมิเนียม', 'meta' => 'Lot AL-2411-B · ตรวจสี่เหลี่ยม', 'time' => 'พรุ่งนี้ 09:30 น.'],
    ['title' => 'อัปเดตสถานะคลัง WH-A', 'meta' => 'นับสต็อกประจำสัปดาห์', 'time' => 'ศุกร์ 16:00 น.'],
];

$allMenuItems = MenuHelper::getAccessibleMenuItems();
$menuItems = array_filter($allMenuItems, function($item) {
    $pcRelated = ['materials', 'part', 'inventory', 'stock', 'supplier'];
    $url = strtolower($item['url'] ?? '');
    $title = strtolower($item['title'] ?? '');
    foreach ($pcRelated as $keyword) {
        if (strpos($url, $keyword) !== false || strpos($title, $keyword) !== false) {
            return true;
        }
    }
    return false;
});

$maxWeeklyValue = 0;
foreach ($weeklyComparison as $week) {
    $maxWeeklyValue = max($maxWeeklyValue, $week['inventory'], $week['orders']);
}
$maxWeeklyValue = $maxWeeklyValue ?: 1;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Part Control Dashboard - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <header class="dashboard-hero">
                <h1>แดชบอร์ด Part Control</h1>
                <p>ภาพรวมการจัดการวัตถุดิบ สถานะคงคลัง และแจ้งเตือนสำคัญ</p>
                                <div class="actions-bar">
                    <div class="actions">
                        <a class="button-link" href="<?= BASE_URL ?>?url=materials">
                            <i class="fas fa-boxes"></i> จัดการวัตถุดิบ
                        </a>
                        <a class="button-link secondary" href="<?= BASE_URL ?>?url=materials/reports">
                            <i class="fas fa-chart-bar"></i> รายงานคงคลัง
                        </a>
                    </div>
                    <a class="button-link" href="<?= BASE_URL ?>?url=materials/add">
                        <i class="fas fa-plus"></i> เพิ่มวัตถุดิบ
                    </a>
                </div>
            </header>



            <section class="kpi-grid">
                <?php foreach ($kpiCards as $card): ?>
                    <article class="kpi-card">
                        <h3><?= htmlspecialchars($card['title']) ?></h3>
                        <strong><?= htmlspecialchars($card['value']) ?></strong>
                        <span class="kpi-trend <?= htmlspecialchars($card['trendType']) ?>">
                            <?= htmlspecialchars($card['trend']) ?>
                        </span>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="chart-layout">
                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>วัตถุดิบคงคลัง vs การสั่งซื้อ</h2>
                            <span>เปรียบเทียบสี่สัปดาห์ล่าสุด</span>
                        </div>
                        <span class="status-chip">อัปเดต 09:45 น.</span>
                    </div>
                    <div class="weekly-compare-card">
                        <?php foreach ($weeklyComparison as $week): ?>
                            <?php
                            $inventoryHeight = max(14, round(($week['inventory'] / $maxWeeklyValue) * 170));
                            $ordersHeight = max(14, round(($week['orders'] / $maxWeeklyValue) * 170));
                            ?>
                            <div class="weekly-column">
                                <div class="weekly-bars">
                                    <div class="bar inventory" style="height: <?= $inventoryHeight ?>px;">
                                        <?= round($week['inventory'] / 1000, 1) ?>k
                                    </div>
                                    <div class="bar orders" style="height: <?= $ordersHeight ?>px;">
                                        <?= round($week['orders'] / 1000, 1) ?>k
                                    </div>
                                </div>
                                <span class="weekly-label"><?= htmlspecialchars($week['week']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>สัดส่วนวัตถุดิบตามประเภท</h2>
                            <span>รวมทั้งหมด <?= number_format(array_sum(array_column($materialBreakdown, 'value'))) ?> รายการ</span>
                        </div>
                        <span class="status-chip">อัปเดตล่าสุด</span>
                    </div>
                    <div class="list">
                        <?php foreach ($materialBreakdown as $item): ?>
                            <div class="list-item">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <span><?= number_format($item['value']) ?> รายการ · <?= $item['percent'] ?>%</span>
                                <div style="height: 6px; border-radius: 999px; background: rgba(37, 99, 235, 0.12); overflow: hidden;">
                                    <div style="width: <?= $item['percent'] ?>%; height: 100%; background: var(--accent);"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            </section>

            <section class="product-weekly-grid">
                <?php foreach ($materialWeekly as $code => $material): ?>
                    <?php
                    $maxMaterialValue = 0;
                    foreach ($material['weeks'] as $week) {
                        $maxMaterialValue = max($maxMaterialValue, $week['inventory'], $week['orders']);
                    }
                    $maxMaterialValue = $maxMaterialValue ?: 1;
                    $inventorySum = array_sum(array_column($material['weeks'], 'inventory'));
                    $ordersSum = array_sum(array_column($material['weeks'], 'orders'));
                    ?>
                    <article class="product-week-card">
                        <div class="product-header">
                            <span class="product-code"><?= htmlspecialchars($code) ?></span>
                            <span class="product-meta"><?= htmlspecialchars($material['label']) ?></span>
                        </div>
                        <div class="product-week-chart">
                            <?php foreach ($material['weeks'] as $week): ?>
                                <?php
                                $inventoryHeight = max(12, round(($week['inventory'] / $maxMaterialValue) * 120));
                                $ordersHeight = max(12, round(($week['orders'] / $maxMaterialValue) * 120));
                                ?>
                                <div class="product-week-column">
                                    <div class="product-week-bars">
                                        <div class="bar inventory" style="height: <?= $inventoryHeight ?>px;"></div>
                                        <div class="bar orders" style="height: <?= $ordersHeight ?>px;"></div>
                                    </div>
                                    <span class="weekly-label">wk <?= htmlspecialchars($week['week']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="product-week-summary">
                            <span>คงคลังเฉลี่ย <?= number_format($inventorySum / count($material['weeks'])) ?> ชิ้น</span>
                            <span>สั่งซื้อเฉลี่ย <?= number_format($ordersSum / count($material['weeks'])) ?> ชิ้น</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="dual-column">
                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>สถานะซัพพลายเออร์</h2>
                            <span>ภาพรวมคู่ค้า</span>
                        </div>
                    </div>
                    <div class="list">
                        <?php foreach ($supplierStatus as $supplier): ?>
                            <div class="list-item">
                                <h4><?= htmlspecialchars($supplier['supplier']) ?></h4>
                                <span><?= htmlspecialchars($supplier['detail']) ?></span>
                                <span class="badge <?= $supplier['status'] === 'หยุดฉุกเฉิน' ? 'critical' : ($supplier['status'] === 'เฝ้าระวัง' ? 'warning' : 'info') ?>">
                                    <?= htmlspecialchars($supplier['status']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>แจ้งเตือนวัตถุดิบ</h2>
                            <span>อัปเดตแบบเรียลไทม์</span>
                        </div>
                    </div>
                    <div class="list">
                        <?php foreach ($materialAlerts as $alert): ?>
                            <div class="list-item">
                                <h4><?= htmlspecialchars($alert['title']) ?></h4>
                                <span><?= htmlspecialchars($alert['meta']) ?></span>
                                <span class="badge <?= htmlspecialchars($alert['type']) ?>">
                                    <?= strtoupper($alert['type']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>งานตรวจสอบ</h2>
                            <span>ภายใน 24 ชั่วโมง</span>
                        </div>
                    </div>
                    <div class="list">
                        <?php foreach ($inspectionTasks as $task): ?>
                            <div class="list-item">
                                <h4><?= htmlspecialchars($task['title']) ?></h4>
                                <span><?= htmlspecialchars($task['meta']) ?></span>
                                <span class="badge info"><?= htmlspecialchars($task['time']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            </section>
        </div>
    </div>

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
</body>

</html>



