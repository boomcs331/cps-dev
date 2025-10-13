<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขผู้ใช้ - CPS</title>
    <link rel="stylesheet" href="./lib/compiled/css/app.css">
    <link rel="stylesheet" href="./lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./lib/compiled/css/iconly.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
</head>
<body>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>
        
        <div class="container-fluid mt-3">
            <div class="page-heading">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>แก้ไขผู้ใช้: <?= htmlspecialchars($user['username']) ?></h3>
                    <a href="<?= BASE_URL ?>?url=users" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>
            
            <div class="page-content">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ข้อมูลผู้ใช้</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?= htmlspecialchars($_POST['username'] ?? $user['username']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">ชื่อเต็ม <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?= htmlspecialchars($_POST['full_name'] ?? $user['full_name']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   <?= (isset($_POST['is_active']) ? $_POST['is_active'] : $user['is_active']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_active">
                                                เปิดใช้งาน
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">บทบาท</label>
                                <div class="row">
                                    <?php if (!empty($roles)): ?>
                                        <?php foreach ($roles as $role): ?>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="role_<?= $role['id'] ?>" name="roles[]" 
                                                           value="<?= $role['id'] ?>"
                                                           <?php 
                                                           $selected_roles = $_POST['roles'] ?? $user_roles;
                                                           echo in_array($role['id'], $selected_roles) ? 'checked' : '';
                                                           ?>>
                                                    <label class="form-check-label" for="role_<?= $role['id'] ?>">
                                                        <?= htmlspecialchars($role['display_name']) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> บันทึกการแก้ไข
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="./lib/compiled/js/app.js"></script>
</body>
</html>