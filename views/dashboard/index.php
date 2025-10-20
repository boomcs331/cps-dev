<?php
/**
 * ข้อมูลที่หน้าแดชบอร์ดใช้แสดงผลสามารถส่งมาจาก Controller ได้
 * หากไม่มีการส่งเข้ามา จะใช้ค่าตัวอย่างด้านล่างเพื่อให้หน้าเพจยังสวยงาม
 */

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

$menuItems = [];
if (in_array('user.view', $permissions, true)) {
    $menuItems[] = [
        'title' => 'จัดการผู้ใช้',
        'subtitle' => 'สร้างและอัปเดตบัญชีผู้ใช้งาน',
        'icon' => 'fas fa-users',
        'type' => 'link',
        'url' => BASE_URL . '?url=users',
    ];
}
if (in_array('role.view', $permissions, true)) {
    $menuItems[] = [
        'title' => 'จัดการบทบาท',
        'subtitle' => 'กำหนดบทบาทและสิทธิ์',
        'icon' => 'fas fa-user-tag',
        'type' => 'link',
        'url' => BASE_URL . '?url=roles',
    ];
}
if (in_array('permission.view', $permissions, true)) {
    $menuItems[] = [
        'title' => 'จัดการสิทธิ์',
        'subtitle' => 'สร้างและกำหนดสิทธิ์การเข้าถึง',
        'icon' => 'fas fa-user-shield',
        'type' => 'link',
        'url' => BASE_URL . '?url=permissions',
    ];
}
if (in_array('user.create', $permissions, true)) {
    $menuItems[] = [
        'title' => 'เพิ่มผู้ใช้',
        'subtitle' => 'เพิ่มผู้ใช้งานใหม่เข้าสู่ระบบ',
        'icon' => 'fas fa-user-plus',
        'type' => 'modal',
        'target' => '#addUserModal',
    ];
}
$menuItems[] = [
    'title' => 'รายงาน',
    'subtitle' => 'สรุปข้อมูลและสถิติสำคัญ',
    'icon' => 'fas fa-chart-bar',
    'type' => 'link',
    'url' => BASE_URL . '?url=reports',
];
if (in_array('system.admin', $permissions, true)) {
    $menuItems[] = [
        'title' => 'ตั้งค่าระบบ',
        'subtitle' => 'ปรับแต่งค่าการทำงานของระบบ',
        'icon' => 'fas fa-cogs',
        'type' => 'link',
        'url' => BASE_URL . '?url=settings',
    ];
}
if (in_array('pc', $permissions, true)) {
    $menuItems[] = [
        'title' => 'Part Control',
        'subtitle' => 'ติดตามสถานะ Part Control',
        'icon' => 'fas fa-clipboard-check',
        'type' => 'link',
        'url' => BASE_URL . '?url=pc',
    ];
}

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
    <title>Dashboard - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <style>
        :root {
            color-scheme: light dark;
            --bg: #f4f6fb;
            --surface: #ffffff;
            --text: #1f2933;
            --muted: #6c7a89;
            --accent: #2563eb;
            --accent-soft: rgba(37, 99, 235, 0.12);
            --accent-strong: rgba(37, 99, 235, 0.18);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --border: rgba(15, 23, 42, 0.1);
        }

        body {
            background: var(--bg);
            color: var(--text);
        }

        .dashboard-container {
            padding: 32px clamp(16px, 2vw, 32px);
        }

        .dashboard-hero {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 28px;
        }

        .dashboard-hero h1 {
            font-size: clamp(24px, 2.4vw, 32px);
            font-weight: 600;
            margin: 0;
        }

        .dashboard-hero p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
        }

        .kpi-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            margin-bottom: 32px;
        }

        .menu-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 32px;
        }

        .menu-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .menu-header p {
            margin: 4px 0 0;
            font-size: 14px;
            color: var(--muted);
        }

        .menu-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .menu-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            background: var(--surface);
            border-radius: 16px;
            border: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .menu-card:hover,
        .menu-card:focus-visible {
            transform: translateY(-2px);
            border-color: var(--accent);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.16);
            outline: none;
        }

        .menu-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--accent-strong);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 18px;
            flex-shrink: 0;
        }

        .menu-card-text h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .menu-card-text p {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--muted);
        }

        .menu-card-button {
            border: none;
            background: var(--surface);
            text-align: left;
            width: 100%;
            cursor: pointer;
            font: inherit;
            color: inherit;
        }

        .kpi-card {
            background: var(--surface);
            border-radius: 18px;
            padding: 20px 22px;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 12px;
            box-shadow: 0 8px 28px rgba(15, 23, 42, 0.08);
        }

        .kpi-card h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .kpi-card strong {
            font-size: 28px;
            font-weight: 600;
        }

        .kpi-trend.up {
            color: var(--success);
        }

        .kpi-trend.down {
            color: var(--danger);
        }

        .kpi-trend.neutral {
            color: var(--muted);
        }

        .chart-layout {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            margin-bottom: 32px;
        }

        .card {
            background: var(--surface);
            border-radius: 18px;
            border: 1px solid var(--border);
            padding: 20px 22px;
            box-shadow: 0 8px 28px rgba(15, 23, 42, 0.08);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .card-header span {
            font-size: 13px;
            color: var(--muted);
        }

        .status-chip {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: var(--accent-soft);
            color: var(--accent);
        }

        .weekly-compare-card {
            display: flex;
            gap: 24px;
            align-items: flex-end;
            justify-content: space-between;
        }

        .weekly-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .weekly-bars {
            display: flex;
            gap: 6px;
            align-items: flex-end;
        }

        .weekly-bars .bar {
            width: 28px;
            border-radius: 12px 12px 6px 6px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: #ffffff;
            padding-bottom: 6px;
        }

        .bar.inventory {
            background: linear-gradient(180deg, rgba(37, 99, 235, 0.85), rgba(37, 99, 235, 0.6));
        }

        .bar.orders {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.85), rgba(16, 185, 129, 0.6));
        }

        .weekly-label {
            font-size: 13px;
            color: var(--muted);
        }

        .product-weekly-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            margin-bottom: 32px;
        }

        .product-week-card {
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: var(--surface);
            border-radius: 18px;
            border: 1px solid var(--border);
            padding: 20px 22px;
            box-shadow: 0 8px 28px rgba(15, 23, 42, 0.06);
        }

        .product-header {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .product-code {
            font-size: 15px;
            font-weight: 600;
        }

        .product-meta {
            font-size: 13px;
            color: var(--muted);
        }

        .product-week-chart {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .product-week-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .product-week-bars {
            display: flex;
            gap: 6px;
            align-items: flex-end;
        }

        .product-week-bars .bar {
            width: 18px;
            border-radius: 10px 10px 6px 6px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            color: #ffffff;
            padding-bottom: 4px;
        }

        .product-week-summary {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            color: var(--muted);
            flex-wrap: wrap;
        }

        .dual-column {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            margin-bottom: 32px;
        }

        .list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .list-item {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: var(--surface);
        }

        .list-item h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .list-item span {
            font-size: 13px;
            color: var(--muted);
        }

        .badge {
            align-self: flex-start;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .badge.warning {
            background: rgba(245, 158, 11, 0.14);
            color: var(--warning);
        }

        .badge.critical {
            background: rgba(239, 68, 68, 0.16);
            color: var(--danger);
        }

        .badge.info {
            background: rgba(37, 99, 235, 0.12);
            color: var(--accent);
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .actions-bar .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .button-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .button-link.secondary {
            background: rgba(37, 99, 235, 0.1);
            color: var(--accent);
        }
    </style>
</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <header class="dashboard-hero">
                <h1>แดชบอร์ดการผลิต</h1>
                <p>ภาพรวมกำลังการผลิต สถานะสายการผลิต และแจ้งเตือนสำคัญประจำวัน</p>
                <div class="actions-bar">
                    <div class="actions">
                        <a class="button-link" href="<?= BASE_URL ?>?url=reports/daily">
                            <i class="fas fa-file-alt"></i> รายงานประจำวัน
                        </a>
                        <a class="button-link secondary" href="<?= BASE_URL ?>?url=production/planner">
                            <i class="fas fa-calendar-alt"></i> วางแผนการผลิต
                        </a>
                    </div>
                    <a class="button-link" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user-plus"></i> เพิ่มผู้ใช้งาน
                    </a>
                </div>
            </header>

            <?php if (!empty($menuItems)): ?>
                <section class="menu-section">
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
                </section>
            <?php endif; ?>

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
                            <h2>สินค้าคงคลัง vs ออเดอร์</h2>
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
                            <h2>สัดส่วนสินค้าตามหมวด</h2>
                            <span>รวมทั้งหมด <?= number_format(array_sum(array_column($productBreakdown, 'value'))) ?> รายการ</span>
                        </div>
                        <span class="status-chip">อัปเดตล่าสุด</span>
                    </div>
                    <div class="list">
                        <?php foreach ($productBreakdown as $item): ?>
                            <div class="list-item">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <span><?= number_format($item['value']) ?> SKU · <?= $item['percent'] ?>%</span>
                                <div style="height: 6px; border-radius: 999px; background: rgba(37, 99, 235, 0.12); overflow: hidden;">
                                    <div style="width: <?= $item['percent'] ?>%; height: 100%; background: var(--accent);"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            </section>

            <section class="product-weekly-grid">
                <?php foreach ($productWeekly as $code => $product): ?>
                    <?php
                    $maxProductValue = 0;
                    foreach ($product['weeks'] as $week) {
                        $maxProductValue = max($maxProductValue, $week['inventory'], $week['orders']);
                    }
                    $maxProductValue = $maxProductValue ?: 1;
                    $inventorySum = array_sum(array_column($product['weeks'], 'inventory'));
                    $ordersSum = array_sum(array_column($product['weeks'], 'orders'));
                    ?>
                    <article class="product-week-card">
                        <div class="product-header">
                            <span class="product-code"><?= htmlspecialchars($code) ?></span>
                            <span class="product-meta"><?= htmlspecialchars($product['label']) ?></span>
                        </div>
                        <div class="product-week-chart">
                            <?php foreach ($product['weeks'] as $week): ?>
                                <?php
                                $inventoryHeight = max(12, round(($week['inventory'] / $maxProductValue) * 120));
                                $ordersHeight = max(12, round(($week['orders'] / $maxProductValue) * 120));
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
                            <span>คงคลังเฉลี่ย <?= number_format($inventorySum / count($product['weeks'])) ?> ชิ้น</span>
                            <span>ออเดอร์เฉลี่ย <?= number_format($ordersSum / count($product['weeks'])) ?> ชิ้น</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="dual-column">
                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>สถานะสายการผลิต</h2>
                            <span>ภาพรวมโรงงาน</span>
                        </div>
                    </div>
                    <div class="list">
                        <?php foreach ($lineStatus as $line): ?>
                            <div class="list-item">
                                <h4><?= htmlspecialchars($line['line']) ?></h4>
                                <span><?= htmlspecialchars($line['detail']) ?></span>
                                <span class="badge <?= $line['status'] === 'หยุดฉุกเฉิน' ? 'critical' : ($line['status'] === 'เฝ้าระวัง' ? 'warning' : 'info') ?>">
                                    <?= htmlspecialchars($line['status']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>แจ้งเตือนล่าสุด</h2>
                            <span>อัปเดตแบบเรียลไทม์</span>
                        </div>
                    </div>
                    <div class="list">
                        <?php foreach ($alerts as $alert): ?>
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
                            <h2>งานบำรุงรักษา</h2>
                            <span>ภายใน 24 ชั่วโมง</span>
                        </div>
                    </div>
                    <div class="list">
                        <?php foreach ($maintenanceTasks as $task): ?>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มผู้ใช้งาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addUserForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" name="full_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">บทบาท</label>
                            <?php
                            $userModel = new User();
                            $roles = $userModel->getAllRoles();
                            foreach ($roles as $role):
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>">
                                    <label class="form-check-label" for="role_<?= $role['id'] ?>">
                                        <?= htmlspecialchars($role['display_name']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกผู้ใช้</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
    <script>
        document.getElementById('addUserForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>?url=users/create', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('เพิ่มผู้ใช้งานเรียบร้อยแล้ว');
                        location.reload();
                    } else {
                        alert('ไม่สามารถเพิ่มผู้ใช้ได้: ' + (data.message || 'กรุณาลองใหม่'));
                    }
                })
                .catch(() => {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                });
        });
    </script>
</body>

</html>
