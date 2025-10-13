<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #435ebe 0%, #5a6acf 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="/cps/?url=dashboard">
            <i class="fas fa-code me-2" style="font-size: 24px;"></i>
            CPS System
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/cps/?url=dashboard">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>จัดการผู้ใช้
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/cps/?url=users">รายการผู้ใช้</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=users/create">เพิ่มผู้ใช้</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=users/groups">กลุ่มผู้ใช้</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-1"></i>สิทธิ์
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/cps/?url=roles">จัดการบทบาท</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=permissions">จัดการสิทธิ์</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-chart-bar me-1"></i>รายงาน
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/cps/?url=reports/users">รายงานผู้ใช้</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=reports/activity">รายงานกิจกรรม</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=reports/login">รายงานการเข้าใช้</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cogs me-1"></i>ระบบ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/cps/?url=system/settings">ตั้งค่า</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=system/logs">ล็อกระบบ</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=system/backup">สำรองข้อมูล</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-tools me-1"></i>เครื่องมือ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/cps/?url=tools/file-manager">จัดการไฟล์</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=tools/database">จัดการฐานข้อมูล</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=tools/cache">จัดการแคช</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-question-circle me-1"></i>ช่วยเหลือ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/cps/?url=help/docs">เอกสาร</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=help/support">สนับสนุน</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=help/about">เกี่ยวกับ</a></li>
                    </ul>
                </li>
            </ul>
            
            <div class="navbar-nav">
                <div class="d-flex align-items-center me-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggle-dark">
                        <label class="form-check-label text-white" for="toggle-dark">🌙</label>
                    </div>
                </div>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/cps/?url=profile">Profile</a></li>
                        <li><a class="dropdown-item" href="/cps/?url=settings">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/cps/?url=logout">Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a class="btn btn-outline-light btn-sm" href="/cps/?url=login">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    เข้าสู่ระบบ
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>