<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด - CPS</title>
    <link href="<?= BASE_URL ?>lib/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>lib/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include 'views/layouts/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="mb-3">ยินดีต้อนรับ, <?= htmlspecialchars($username) ?>!</h3>
                <p class="text-muted">ภาพรวมของระบบจัดการ CPS</p>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-gradient rounded-3 p-3">
                                    <i class="fas fa-users text-white fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small text-muted">ผู้ใช้ทั้งหมด</div>
                                <div class="h5 mb-0">1,234</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-gradient rounded-3 p-3">
                                    <i class="fas fa-user-check text-white fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small text-muted">ผู้ใช้ออนไลน์</div>
                                <div class="h5 mb-0">856</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-gradient rounded-3 p-3">
                                    <i class="fas fa-chart-line text-white fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small text-muted">การเข้าใช้วันนี้</div>
                                <div class="h5 mb-0">142</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-gradient rounded-3 p-3">
                                    <i class="fas fa-exclamation-triangle text-white fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small text-muted">แจ้งเตือน</div>
                                <div class="h5 mb-0">5</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- เมนูที่สามารถเข้าถึงได้ -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3">เมนูที่สามารถเข้าถึงได้</h5>
            </div>
            <?php if (in_array('user.view', $permissions)): ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                        <h6>จัดการผู้ใช้</h6>
                        <a href="<?= BASE_URL ?>?url=users" class="btn btn-primary btn-sm">เข้าใช้</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (in_array('role.view', $permissions)): ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-shield fa-2x text-success mb-2"></i>
                        <h6>จัดการสิทธิ์</h6>
                        <a href="<?= BASE_URL ?>?url=roles" class="btn btn-success btn-sm">เข้าใช้</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                        <h6>รายงาน</h6>
                        <a href="<?= BASE_URL ?>?url=reports" class="btn btn-info btn-sm">เข้าใช้</a>
                    </div>
                </div>
            </div>
            <?php if (in_array('system.admin', $permissions)): ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-cogs fa-2x text-warning mb-2"></i>
                        <h6>ตั้งค่าระบบ</h6>
                        <a href="<?= BASE_URL ?>?url=settings" class="btn btn-warning btn-sm">เข้าใช้</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (in_array('permission.view', $permissions)): ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-key fa-2x text-secondary mb-2"></i>
                        <h6>จัดการสิทธิ์</h6>
                        <a href="<?= BASE_URL ?>?url=permissions" class="btn btn-secondary btn-sm">เข้าใช้</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (in_array('system.admin', $permissions)): ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-database fa-2x text-success mb-2"></i>
                        <h6>สำรองข้อมูล</h6>
                        <a href="<?= BASE_URL ?>?url=backup" class="btn btn-success btn-sm">เข้าใช้</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (in_array('system.admin', $permissions)): ?>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-2x text-dark mb-2"></i>
                        <h6>ล็อกระบบ</h6>
                        <a href="<?= BASE_URL ?>?url=logs" class="btn btn-dark btn-sm">เข้าใช้</a>
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

    <script src="<?= BASE_URL ?>lib/js/bootstrap.bundle.min.js"></script>
</body>
</html>