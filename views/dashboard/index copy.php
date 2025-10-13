<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด - CPS</title>
    <link href="/cps/lib/css/bootstrap.min.css" rel="stylesheet">
    <link href="/cps/lib/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="/cps/lib/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include 'views/layouts/navbar.php'; ?>

    <div class="container mt-4">
        <!-- ข้อมูลผู้ใช้ -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-blue">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">ยินดีต้อนรับ, <?= htmlspecialchars($username) ?>!</h4>
                                <p class="mb-2">บทบาท: 
                                    <?php foreach($roles as $role): ?>
                                        <span class="role-badge role-<?= $role ?>"><?= ucfirst($role) ?></span>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="stats-icon icon-blue">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- เมนูหลักตามสิทธิ์ -->
        <div class="row">
            <!-- จัดการผู้ใช้ -->
            <?php if (in_array('user.view', $permissions)): ?>
            <div class="col-md-4 mb-4">
                <div class="card menu-card">
                    <div class="card-body text-center stats-card">
                        <div class="stats-icon icon-blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">จัดการผู้ใช้</h5>
                        <p class="card-text">จัดการข้อมูลผู้ใช้ในระบบ</p>
                        <a href="#" class="btn btn-blue">เข้าสู่หน้า</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- จัดการ Role -->
            <?php if (in_array('role.view', $permissions)): ?>
            <div class="col-md-4 mb-4">
                <div class="card menu-card">
                    <div class="card-body text-center stats-card">
                        <div class="stats-icon icon-success">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <h5 class="card-title">จัดการบทบาท</h5>
                        <p class="card-text">จัดการ Role และสิทธิ์ต่างๆ</p>
                        <a href="#" class="btn btn-success">เข้าสู่หน้า</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- รายงาน -->
            <div class="col-md-4 mb-4">
                <div class="card menu-card">
                    <div class="card-body text-center stats-card">
                        <div class="stats-icon icon-info">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">รายงาน</h5>
                        <p class="card-text">ดูรายงานและสถิติต่างๆ</p>
                        <a href="#" class="btn btn-info">เข้าสู่หน้า</a>
                    </div>
                </div>
            </div>

            <!-- ตั้งค่าระบบ (เฉพาะ Admin ขึ้นไป) -->
            <?php if (in_array('system.admin', $permissions)): ?>
            <div class="col-md-4 mb-4">
                <div class="card menu-card">
                    <div class="card-body text-center stats-card">
                        <div class="stats-icon icon-warning">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="card-title">ตั้งค่าระบบ</h5>
                        <p class="card-text">กำหนดค่าต่างๆ ของระบบ</p>
                        <a href="#" class="btn btn-warning">เข้าสู่หน้า</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- จัดการสิทธิ์ -->
            <?php if (in_array('permission.view', $permissions)): ?>
            <div class="col-md-4 mb-4">
                <div class="card menu-card">
                    <div class="card-body text-center stats-card">
                        <div class="stats-icon icon-success">
                            <i class="fas fa-key"></i>
                        </div>
                        <h5 class="card-title">จัดการสิทธิ์</h5>
                        <p class="card-text">จัดการสิทธิ์การใช้งาน</p>
                        <a href="#" class="btn btn-success">เข้าสู่หน้า</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- สิทธิ์ของผู้ใช้ -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>สิทธิ์ของคุณ</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach($permissions as $permission): ?>
                            <span class="permission-badge"><?= $permission ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/cps/lib/js/bootstrap.bundle.min.js"></script>
</body>
</html>