<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสิทธิ์ - CPS</title>
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
                    <h3>จัดการสิทธิ์</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> เพิ่มสิทธิ์ใหม่
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="card">
                    <div class="card-body">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">เพิ่มสิทธิ์สำเร็จ</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['updated'])): ?>
                            <div class="alert alert-success">แก้ไขสิทธิ์สำเร็จ</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['deleted'])): ?>
                            <div class="alert alert-success">ลบสิทธิ์สำเร็จ</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">เกิดข้อผิดพลาด</div>
                        <?php endif; ?>

                        <?php
                        $table = ServerSideTable::create(
                            $permissions, 
                            $currentPage, 
                            $perPage, 
                            $totalRecords
                        )
                            ->addColumn('id', 'ID')
                            ->addColumn('name', 'ชื่อสิทธิ์')
                            ->addColumn('display_name', 'ชื่อแสดง')
                            ->addColumn('description', 'คำอธิบาย')
                            ->addColumn('module', 'โมดูล')
                            ->addAction('แก้ไข', 'javascript:editPermission({id})', 'btn-warning', 'fas fa-edit')
                            ->addAction('ลบ', 'javascript:deletePermission({id})', 'btn-danger', 'fas fa-trash');
                        
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
                    <h5 class="modal-title">เพิ่มสิทธิ์ใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อสิทธิ์</label>
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
                        <div class="mb-3">
                            <label class="form-label">โมดูล</label>
                            <select class="form-control" name="module" id="module_select" onchange="toggleCustomModule()" required>
                                <option value="">เลือกโมดูล</option>
                                <?php foreach ($modules as $module): ?>
                                    <option value="<?= htmlspecialchars($module) ?>"><?= ucfirst($module) ?></option>
                                <?php endforeach; ?>
                                <option value="custom">อื่นๆ (ระบุเอง)</option>
                            </select>
                            <input type="text" class="form-control mt-2" name="custom_module" id="custom_module" placeholder="ระบุชื่อโมดูล" style="display:none;">
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
                    <h5 class="modal-title">แก้ไขสิทธิ์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อสิทธิ์</label>
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
                            <label class="form-label">โมดูล</label>
                            <select class="form-control" name="module" id="edit_module" onchange="toggleEditCustomModule()" required>
                                <?php foreach ($modules as $module): ?>
                                    <option value="<?= htmlspecialchars($module) ?>"><?= ucfirst($module) ?></option>
                                <?php endforeach; ?>
                                <option value="custom">อื่นๆ (ระบุเอง)</option>
                            </select>
                            <input type="text" class="form-control mt-2" name="custom_module" id="edit_custom_module" placeholder="ระบุชื่อโมดูล" style="display:none;">
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

    <script src="lib/compiled/js/app.js"></script>
    <script src="./lib/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script>
    // Create Permission
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
                
                // Handle custom module
                const moduleSelect = document.getElementById('module_select');
                const customModule = document.getElementById('custom_module');
                if (moduleSelect.value === 'custom' && customModule.value) {
                    formData.set('module', customModule.value);
                }
                
                fetch('<?= BASE_URL ?>?url=permissions/store', {
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

    // Edit Permission
    function editPermission(id) {
    fetch('<?= BASE_URL ?>?url=permissions/get&id=' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_display_name').value = data.display_name;
            document.getElementById('edit_description').value = data.description;
            document.getElementById('edit_module').value = data.module;
            
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
                
                // Handle custom module
                const moduleSelect = document.getElementById('edit_module');
                const customModule = document.getElementById('edit_custom_module');
                if (moduleSelect.value === 'custom' && customModule.value) {
                    formData.set('module', customModule.value);
                }
                
                fetch('<?= BASE_URL ?>?url=permissions/update', {
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

    // Delete Permission
    function deletePermission(id) {
        Swal.fire({
            title: 'ยืนยันการลบ',
            text: 'ต้องการลบสิทธิ์นี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= BASE_URL ?>?url=permissions/delete&id=' + id;
            }
        });
    }
    
    // Module selection functions
    function toggleCustomModule() {
        const select = document.getElementById('module_select');
        const customInput = document.getElementById('custom_module');
        
        if (select.value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
        }
    }
    
    function toggleEditCustomModule() {
        const select = document.getElementById('edit_module');
        const customInput = document.getElementById('edit_custom_module');
        
        if (select.value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
        }
    }
    </script>
</body>

</html>