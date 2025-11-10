<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้ - CPS</title>
    <link rel="stylesheet" href="./lib/compiled/css/app.css">
    <link rel="stylesheet" href="./lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./lib/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="./lib/css/dashboard-shared.css">
</head>

<body>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <!-- Header Section -->
            <header class="dashboard-hero">
                <h1><i class="fas fa-users"></i> จัดการผู้ใช้</h1>
                <p>จัดการข้อมูลผู้ใช้และสิทธิ์การเข้าถึงระบบ</p>
                <div class="actions-bar">
                    <div class="actions">
                        <input type="text" class="form-control" placeholder="ค้นหาผู้ใช้..." id="searchInput" style="width: 300px; border-radius: 12px;">
                        <select class="form-select" id="statusFilter" style="width: 150px; border-radius: 12px;">
                            <option value="">สถานะทั้งหมด</option>
                            <option value="1">เปิดใช้งาน</option>
                            <option value="0">ปิดใช้งาน</option>
                        </select>
                    </div>
                    <button type="button" class="button-link" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> เพิ่มผู้ใช้ใหม่
                    </button>
                </div>
            </header>

            <!-- Stats Cards -->
            <section class="kpi-grid">
                <article class="kpi-card">
                    <h3>ผู้ใช้ทั้งหมด</h3>
                    <strong><?= $userStats['total'] ?></strong>
                    <span class="kpi-trend neutral">รวมทุกสถานะ</span>
                </article>
                <article class="kpi-card">
                    <h3>ผู้ใช้ที่เปิดใช้งาน</h3>
                    <strong><?= $userStats['active'] ?></strong>
                    <span class="kpi-trend up">พร้อมใช้งาน</span>
                </article>
                <article class="kpi-card">
                    <h3>ผู้ใช้ที่ปิดใช้งาน</h3>
                    <strong><?= $userStats['inactive'] ?></strong>
                    <span class="kpi-trend down">ไม่ได้ใช้งาน</span>
                </article>
                <article class="kpi-card">
                    <h3>บทบาททั้งหมด</h3>
                    <strong><?= count($roles) ?></strong>
                    <span class="kpi-trend neutral">ระดับสิทธิ์</span>
                </article>
            </section>

            <!-- Alerts -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>เพิ่มผู้ใช้สำเร็จ
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>แก้ไขผู้ใช้สำเร็จ
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>ลบผู้ใช้สำเร็จ
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>เกิดข้อผิดพลาด
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Users Table -->
            <section class="card">
                <div class="table-responsive">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th>ผู้ใช้</th>
                                <th>อีเมล</th>
                                <th>บทบาท</th>
                                <th>สถานะ</th>
                                <th>วันที่สมัคร</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="user-item"
                                    data-username="<?= strtolower($user['username']) ?>"
                                    data-fullname="<?= strtolower($user['full_name']) ?>"
                                    data-email="<?= strtolower($user['email']) ?>"
                                    data-status="<?= $user['is_active'] ?>">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <!-- <div class="summary-icon icon-orange" style="width: 40px; height: 40px; font-size: 16px;">
                                                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                            </div> -->
                                            <div>
                                                <strong><?= htmlspecialchars($user['full_name']) ?></strong><br>
                                                <small class="text-muted">@<?= htmlspecialchars($user['username']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php
                                        $roles_list = explode(', ', $user['role_names']);
                                        foreach ($roles_list as $role):
                                        ?>
                                            <span class="badge info" style="margin: 1px; font-size: 11px;"><?= htmlspecialchars($role) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $user['is_active'] ? 'info' : 'warning' ?>">
                                            <?= $user['is_active'] ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div style="display: flex; gap: 4px;">
                                            <button class="btn btn-warning btn-sm" onclick="editUser(<?= $user['id'] ?>)" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id'] ?>)" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php 
            require_once 'helpers/PaginationHelper.php';
            echo PaginationHelper::render($currentPage, $totalRecords, $perPage);
            ?>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มผู้ใช้ใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ชื่อเต็ม</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">บทบาท</label>
                            <div class="row">
                                <?php foreach ($roles as $role): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>">
                                            <label class="form-check-label" for="role_<?= $role['id'] ?>">
                                                <?= htmlspecialchars($role['display_name']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขผู้ใช้</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ชื่อเต็ม</label>
                            <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">บทบาท</label>
                            <div class="row" id="edit_roles">
                                <?php foreach ($roles as $role): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="edit_role_<?= $role['id'] ?>">
                                            <label class="form-check-label" for="edit_role_<?= $role['id'] ?>">
                                                <?= htmlspecialchars($role['display_name']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./lib/compiled/js/app.js"></script>
    <script src="./lib/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = document.querySelectorAll('.user-item');

            userItems.forEach(item => {
                const username = item.dataset.username;
                const fullname = item.dataset.fullname;
                const email = item.dataset.email;

                if (username.includes(searchTerm) || fullname.includes(searchTerm) || email.includes(searchTerm)) {
                    item.style.display = 'table-row';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const userItems = document.querySelectorAll('.user-item');

            userItems.forEach(item => {
                if (status === '' || item.dataset.status === status) {
                    item.style.display = 'table-row';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Create user form
        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('?url=users/create', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'เพิ่มผู้ใช้สำเร็จ',
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: data.message || 'ไม่สามารถเพิ่มผู้ใช้ได้'
                            });
                        }
                    } catch (error) {
                        console.error('Response:', text);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                    });
                });
        });

        // Edit user function
        function editUser(id) {
            fetch(`?url=users/get&id=${id}`)
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            const user = data.user;
                            document.getElementById('edit_id').value = user.id;
                            document.getElementById('edit_username').value = user.username;
                            document.getElementById('edit_full_name').value = user.full_name;
                            document.getElementById('edit_email').value = user.email;
                            document.getElementById('edit_is_active').checked = user.is_active == 1;

                            // Set roles
                            const roleCheckboxes = document.querySelectorAll('#editModal input[name="roles[]"]');
                            roleCheckboxes.forEach(checkbox => {
                                checkbox.checked = user.roles.includes(parseInt(checkbox.value));
                            });

                            new bootstrap.Modal(document.getElementById('editModal')).show();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถโหลดข้อมูลผู้ใช้ได้'
                            });
                        }
                    } catch (error) {
                        console.error('Response:', text);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                    });
                });
        }

        // Edit form submit
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('?url=users/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'แก้ไขผู้ใช้สำเร็จ',
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: data.message || 'ไม่สามารถแก้ไขผู้ใช้ได้'
                            });
                        }
                    } catch (error) {
                        console.error('Response:', text);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                    });
                });
        });

        // Delete user function
        function deleteUser(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'การลบผู้ใช้นี้ไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`?url=users/delete&id=${id}`, {
                            method: 'POST'
                        })
                        .then(response => response.text())
                        .then(text => {
                            try {
                                const data = JSON.parse(text);
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลบสำเร็จ!',
                                        text: 'ลบผู้ใช้สำเร็จแล้ว',
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด!',
                                        text: data.message || 'ไม่สามารถลบผู้ใช้ได้'
                                    });
                                }
                            } catch (error) {
                                console.error('Response:', text);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด!',
                                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                            });
                        });
                }
            });
        }
        
        // Change per page function
        function changePerPage(perPage) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    </script>
</body>

</html>