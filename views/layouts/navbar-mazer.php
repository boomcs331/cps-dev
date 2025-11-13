<?php
$navCurrent = $_GET['url'] ?? '';
if ($navCurrent === '' && isset($_SERVER['REQUEST_URI'])) {
    $requestPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $basePath = trim(BASE_URL, '/');
    if ($basePath !== '' && strpos($requestPath, $basePath) === 0) {
        $requestPath = ltrim(substr($requestPath, strlen($basePath)), '/');
    }
    $navCurrent = $requestPath;
}
$navCurrent = trim($navCurrent);
if ($navCurrent === '') {
    $navCurrent = 'dashboard';
}
$navCurrentSegment = explode('/', $navCurrent)[0];
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #435ebe 0%, #5a6acf 100%);">
    <div class="container-fluid">
    <a class="navbar-brand" href="<?= BASE_URL ?>?url=dashboard">
            <i class="fas fa-code me-2" style="font-size: 24px;"></i>
            CPS System
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $navCurrentSegment === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>?url=dashboard">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $navCurrentSegment === 'pc' ? 'active' : '' ?>" href="<?= BASE_URL ?>?url=pc">
                        <i class="fas fa-clipboard-check me-1"></i>Part Control
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>จัดการผู้ใช้
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=users">รายการผู้ใช้</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=users/create">เพิ่มผู้ใช้</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=users/groups">กลุ่มผู้ใช้</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-1"></i>สิทธิ์
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=roles">จัดการบทบาท</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=permissions">จัดการสิทธิ์</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-chart-bar me-1"></i>รายงาน
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=reports/users">รายงานผู้ใช้</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=reports/activity">รายงานกิจกรรม</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=reports/login">รายงานการเข้าใช้</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cogs me-1"></i>ระบบ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=system/settings">ตั้งค่า</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=system/logs">ล็อกระบบ</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=system/backup">สำรองข้อมูล</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-tools me-1"></i>เครื่องมือ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=tools/file-manager">จัดการไฟล์</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=tools/database">จัดการฐานข้อมูล</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=tools/cache">จัดการแคช</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-question-circle me-1"></i>ช่วยเหลือ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=help/docs">เอกสาร</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=help/support">สนับสนุน</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=help/about">เกี่ยวกับ</a></li>
                    </ul>
                </li>
            </ul>
            
            <div class="navbar-nav">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=profile">Profile</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=settings">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?url=logout">Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>?url=login">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    เข้าสู่ระบบ
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
