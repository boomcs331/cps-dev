<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการบทบาท - CPS</title>
    <link rel="stylesheet" href="./lib/compiled/css/app.css">
    <link rel="stylesheet" href="./lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./lib/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="container-fluid mt-3">
            <div class="page-heading">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>จัดการบทบาท</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> เพิ่มบทบาทใหม่
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="card">
                    <div class="card-body">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">เพิ่มบทบาทสำเร็จ</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['updated'])): ?>
                            <div class="alert alert-success">แก้ไขบทบาทสำเร็จ</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['deleted'])): ?>
                            <div class="alert alert-success">ลบบทบาทสำเร็จ</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">เกิดข้อผิดพลาด</div>
                        <?php endif; ?>

                        <?php
                        $table = ServerSideTable::create(
                            $roles, 
                            $currentPage, 
                            $perPage, 
                            $totalRecords
                        )
                            ->addColumn('id', 'ID')
                            ->addColumn('name', 'ชื่อบทบาท')
                            ->addColumn('display_name', 'ชื่อแสดง')
                            ->addColumn('description', 'คำอธิบาย')
                            ->addColumn('is_active', 'สถานะ', function($value) {
                                return $value ? '<span class="badge bg-success">เปิดใช้งาน</span>' : '<span class="badge bg-danger">ปิดใช้งาน</span>';
                            })
                            ->addAction('จัดการสิทธิ์', 'javascript:managePermissions({id})', 'btn-info', 'fas fa-key')
                            ->addAction('แก้ไข', 'javascript:editRole({id})', 'btn-warning', 'fas fa-edit')
                            ->addAction('ลบ', 'javascript:deleteRole({id})', 'btn-danger', 'fas fa-trash');
                        
                        echo $table->render();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มบทบาทใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อบทบาท</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ชื่อแสดง</label>
                            <input type="text" class="form-control" name="display_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
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
                    <h5 class="modal-title">แก้ไขบทบาท</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อบทบาท</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ชื่อแสดง</label>
                            <input type="text" class="form-control" name="display_name" id="edit_display_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">เปิดใช้งาน</label>
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

    <!-- Permissions Modal -->
    <div class="modal fade" id="permissionsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">จัดการสิทธิ์บทบาท</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="permissionsForm">
                    <input type="hidden" name="role_id" id="permissions_role_id">
                    <div class="modal-body">
                        <div id="permissions-list"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="lib/compiled/js/app.js"></script>
    <script src="./lib/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script>
    // Create Role
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'ยืนยันการบันทึก',
            text: 'ต้องการบันทึกข้อมูลหรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(this);
                
                fetch('/cps/?url=roles/store', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire('สำเร็จ!', 'บันทึกข้อมูลเรียบร้อย', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาด', 'error');
                    }
                });
            }
        });
    });

    // Edit Role
    function editRole(id) {
        fetch('/cps/?url=roles/get&id=' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_display_name').value = data.display_name;
            document.getElementById('edit_description').value = data.description;
            document.getElementById('edit_is_active').checked = data.is_active == 1;
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'ยืนยันการแก้ไข',
            text: 'ต้องการบันทึกการแก้ไขหรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(this);
                
                fetch('/cps/?url=roles/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire('สำเร็จ!', 'แก้ไขข้อมูลเรียบร้อย', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาด', 'error');
                    }
                });
            }
        });
    });

    // Delete Role
    function deleteRole(id) {
        Swal.fire({
            title: 'ยืนยันการลบ',
            text: 'ต้องการลบบทบาทนี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/cps/?url=roles/delete&id=' + id;
            }
        });
    }

    // Manage Permissions
    function managePermissions(id) {
        Promise.all([
            fetch('/cps/?url=roles/get&id=' + id).then(r => r.json()),
            fetch('/cps/?url=permissions').then(r => r.text())
        ]).then(([roleData, permissionsHtml]) => {
            // ดึงข้อมูล permissions จาก API
            fetch('/cps/?url=roles/permissions&id=' + id)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const permissionsList = doc.querySelector('#permissions-content');
                
                if (permissionsList) {
                    document.getElementById('permissions_role_id').value = id;
                    document.getElementById('permissions-list').innerHTML = permissionsList.innerHTML;
                    document.querySelector('#permissionsModal .modal-title').textContent = 'จัดการสิทธิ์บทบาท: ' + roleData.display_name;
                    
                    new bootstrap.Modal(document.getElementById('permissionsModal')).show();
                }
            });
        });
    }

    // Save Permissions
    document.getElementById('permissionsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'ยืนยันการบันทึก',
            text: 'ต้องการบันทึกสิทธิ์หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(this);
                
                fetch('/cps/?url=roles/updatePermissions', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire('สำเร็จ!', 'บันทึกสิทธิ์เรียบร้อย', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาด', 'error');
                    }
                });
            }
        });
    });
    
    // Global functions for permission management
    window.selectAllPermissions = function() {
        document.querySelectorAll('#permissions-list input[type="checkbox"]').forEach(cb => cb.checked = true);
    }
    
    window.deselectAllPermissions = function() {
        document.querySelectorAll('#permissions-list input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
    </script>
</body>

</html>