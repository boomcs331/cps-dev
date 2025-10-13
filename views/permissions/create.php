<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสิทธิ์ - CPS</title>
    <link rel="stylesheet" href="./lib/compiled/css/app.css">
    <link rel="stylesheet" href="./lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
</head>

<body>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="container-fluid mt-3">
            <div class="page-heading">
                <h3>เพิ่มสิทธิ์ใหม่</h3>
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">ข้อมูลสิทธิ์</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger">เกิดข้อผิดพลาดในการเพิ่มสิทธิ์</div>
                                <?php endif; ?>

                                <form action="<?= BASE_URL ?>?url=permissions/store" method="POST">
                                    <div class="form-group">
                                        <label for="name">ชื่อสิทธิ์ (Permission Name)</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="เช่น user.create" required>
                                        <small class="text-muted">ใช้รูปแบบ module.action เช่น user.create, role.view</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="display_name">ชื่อแสดง</label>
                                        <input type="text" class="form-control" id="display_name" name="display_name"
                                            placeholder="เช่น เพิ่มผู้ใช้" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">คำอธิบาย</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            placeholder="อธิบายรายละเอียดของสิทธิ์นี้"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="module">โมดูล</label>
                                        <select class="form-control" id="module" name="module" required>
                                            <option value="">เลือกโมดูล</option>
                                            <option value="user">User Management</option>
                                            <option value="role">Role Management</option>
                                            <option value="permission">Permission Management</option>
                                            <option value="dashboard">Dashboard</option>
                                            <option value="system">System</option>
                                            <option value="report">Report</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> บันทึก
                                        </button>
                                        <a href="<?= BASE_URL ?>?url=permissions" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> ยกเลิก
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="lib/compiled/js/app.js"></script>
</body>

</html>