<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสิทธิ์ - CPS</title>
    <link rel="stylesheet" href="./lib/compiled/css/app.css">
    <link rel="stylesheet" href="./lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
</head>
<body>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="container-fluid mt-3">
            <div class="page-heading">
                <h3>แก้ไขสิทธิ์</h3>
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">แก้ไขข้อมูลสิทธิ์</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger">เกิดข้อผิดพลาดในการแก้ไขสิทธิ์</div>
                                <?php endif; ?>

                                <form action="/cps/?url=permissions/update" method="POST">
                                    <input type="hidden" name="id" value="<?= $permission['id'] ?>">
                                    
                                    <div class="form-group">
                                        <label for="name">ชื่อสิทธิ์ (Permission Name)</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($permission['name']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="display_name">ชื่อแสดง</label>
                                        <input type="text" class="form-control" id="display_name" name="display_name" 
                                               value="<?= htmlspecialchars($permission['display_name']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">คำอธิบาย</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($permission['description']) ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="module">โมดูล</label>
                                        <select class="form-control" id="module" name="module" required>
                                            <option value="user" <?= $permission['module'] == 'user' ? 'selected' : '' ?>>User Management</option>
                                            <option value="role" <?= $permission['module'] == 'role' ? 'selected' : '' ?>>Role Management</option>
                                            <option value="permission" <?= $permission['module'] == 'permission' ? 'selected' : '' ?>>Permission Management</option>
                                            <option value="dashboard" <?= $permission['module'] == 'dashboard' ? 'selected' : '' ?>>Dashboard</option>
                                            <option value="system" <?= $permission['module'] == 'system' ? 'selected' : '' ?>>System</option>
                                            <option value="report" <?= $permission['module'] == 'report' ? 'selected' : '' ?>>Report</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> บันทึก
                                        </button>
                                        <a href="/cps/?url=permissions" class="btn btn-secondary">
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