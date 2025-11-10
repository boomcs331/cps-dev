<?php
header('Content-Type: text/html; charset=utf-8');
$materials = $materials ?? [];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการวัตถุดิบ - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/pagination.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/modern-tabs.css">

</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <!-- Tab Navigation -->
            <div class="modern-tabs-container mb-4">
                <div class="modern-tabs">
                    <button class="modern-tab active" id="materials-tab" data-bs-toggle="tab" data-bs-target="#materials" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">รายการวัตถุดิบ</h6>
                            <p class="tab-desc">ข้อมูลและสต็อกวัตถุดิบ</p>
                        </div>
                        <div class="tab-indicator"></div>
                    </button>
                    <button class="modern-tab" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">รายการเคลื่อนไหว</h6>
                            <p class="tab-desc">บันทึกรับเข้า-จ่ายออก</p>
                        </div>
                        <div class="tab-indicator"></div>
                    </button>
                    <button class="modern-tab" id="workorders-tab" data-bs-toggle="tab" data-bs-target="#workorders" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">สั่งใบจัดงานล่วงหน้า</h6>
                            <p class="tab-desc">วางแผนการใช้วัตถุดิบ</p>
                        </div>
                        <div class="tab-indicator"></div>
                    </button>
                    <button class="modern-tab" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">รายงาน</h6>
                            <p class="tab-desc">สรุปและวิเคราะห์ข้อมูล</p>
                        </div>
                        <div class="tab-indicator"></div>
                    </button>
                </div>
            </div>

            <div class="tab-content" id="materialTabContent">
                <!-- Materials Management Tab -->
                <div class="tab-pane fade show active" id="materials" role="tabpanel">
                    <!-- Stats Cards -->
                    <section class="kpi-grid">
                        <article class="kpi-card">
                            <h3>วัตถุดิบทั้งหมด</h3>
                            <strong><?= count($materials) ?></strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                        <article class="kpi-card">
                            <h3>วัตถุดิบที่ใช้งาน</h3>
                            <strong><?= count(array_filter($materials, fn($m) => $m['is_active'] == 1)) ?></strong>
                            <span class="kpi-trend up">พร้อมใช้งาน</span>
                        </article>
                        <article class="kpi-card">
                            <h3>คลังทั้งหมด</h3>
                            <strong><?= count($locations ?? []) ?></strong>
                            <span class="kpi-trend neutral">สถานที่</span>
                        </article>
                        <article class="kpi-card">
                            <h3>หน่วยนับ</h3>
                            <strong><?= count($units ?? []) ?></strong>
                            <span class="kpi-trend neutral">ประเภท</span>
                        </article>
                    </section>

                    <!-- Materials Table -->
                    <section class="card">
                        <div class="table-responsive">
                            <table class="table" id="materialsTable">
                                <thead>
                                    <tr>
                                        <th>รหัส</th>
                                        <th>ชื่อวัตถุดิบ</th>
                                        <th>หน่วย</th>
                                        <th>คลัง</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materials as $material): ?>
                                        <tr class="material-item">
                                            <td><code class="code-badge"><?= htmlspecialchars($material['material_code']) ?></code></td>
                                            <td>
                                                <div>
                                                    <strong><?= htmlspecialchars($material['material_name']) ?></strong><br>
                                                    <?php if (!empty($material['description'])): ?>
                                                        <small class="text-muted"><?= htmlspecialchars($material['description']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($material['unit_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($material['location_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge <?= $material['is_active'] === 'ใช้งาน' ? 'info' : 'warning' ?>">
                                                    <?= htmlspecialchars($material['is_active']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-warning" onclick="editMaterial(<?= $material['id'] ?>)" title="แก้ไข">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="deleteMaterial(<?= $material['id'] ?>)" title="ลบ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php
                        require_once 'helpers/PaginationHelper.php';
                        echo PaginationHelper::render($currentPage, $totalRecords, $perPage);
                        ?>
                    </section>
                </div>

                <!-- Transactions Tab -->
                <div class="tab-pane fade" id="transactions" role="tabpanel">
                    <section class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">รายการรับเข้าจ่ายออก</h5>
                            <p class="text-muted">กำลังพัฒนาฟีเจอร์นี้</p>
                        </div>
                    </section>
                </div>

                <!-- Work Orders Tab -->
                <div class="tab-pane fade" id="workorders" role="tabpanel">
                    <section class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">สั่งใบจัดงานล่วงหน้า</h5>
                            <p class="text-muted">วางแผนการใช้วัตถุดิบล่วงหน้า</p>
                        </div>
                    </section>
                </div>

                <!-- Reports Tab -->
                <div class="tab-pane fade" id="reports" role="tabpanel">
                    <section class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">รายงาน</h5>
                            <p class="text-muted">สรุปและวิเคราะห์ข้อมูลวัตถุดิบ</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มวัตถุดิบใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addMaterialForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสวัตถุดิบ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_code" placeholder="เช่น MAT-001" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อวัตถุดิบ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_name" placeholder="เช่น เหล็กแผ่น" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">หน่วยนับ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="default_unit" required>
                                        <option value="">เลือกหน่วยนับ</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">คลังจัดเก็บ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="location_id" required>
                                        <option value="">เลือกคลัง</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location['location_id'] ?>"><?= htmlspecialchars($location['location_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="รายละเอียดเพิ่มเติมเกี่ยวกับวัตถุดิบ (ถ้ามี)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Material Modal -->
    <div class="modal fade" id="editMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขวัตถุดิบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editMaterialForm">
                    <input type="hidden" name="material_id" id="edit_material_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสวัตถุดิบ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_code" id="edit_material_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อวัตถุดิบ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_name" id="edit_material_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">หน่วยนับ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="default_unit" id="edit_default_unit" required>
                                        <option value="">เลือกหน่วยนับ</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">คลังจัดเก็บ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="location_id" id="edit_location_id" required>
                                        <option value="">เลือกคลัง</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location['location_id'] ?>"><?= htmlspecialchars($location['location_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label class="form-check-label">ใช้งาน</label>
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

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('addMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>?url=materials/store', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'เพิ่มวัตถุดิบเรียบร้อยแล้ว',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message || 'กรุณาลองใหม่'
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ'
                }));
        });

        function editMaterial(id) {
            fetch('<?= BASE_URL ?>?url=materials/get&id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const material = data.material;
                        document.getElementById('edit_material_id').value = material.material_id;
                        document.getElementById('edit_material_code').value = material.material_code;
                        document.getElementById('edit_material_name').value = material.material_name || '';
                        document.getElementById('edit_default_unit').value = material.default_unit;
                        document.getElementById('edit_location_id').value = material.location_id;
                        document.getElementById('edit_description').value = material.description || '';
                        document.getElementById('edit_is_active').checked = material.is_active == 1;

                        new bootstrap.Modal(document.getElementById('editMaterialModal')).show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่พบข้อมูล',
                            text: data.message || 'กรุณาลองใหม่'
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ'
                }));
        }

        document.getElementById('editMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>?url=materials/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'แก้ไขวัตถุดิบเรียบร้อยแล้ว',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message || 'กรุณาลองใหม่'
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ'
                }));
        });

        function deleteMaterial(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบวัตถุดิบนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('material_id', id);

                    fetch('<?= BASE_URL ?>?url=materials/delete', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบเรียบร้อย!',
                                    text: 'ลบวัตถุดิบเรียบร้อยแล้ว',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: data.message || 'กรุณาลองใหม่'
                                });
                            }
                        })
                        .catch(() => Swal.fire({
                            icon: 'error',
                            title: 'ข้อผิดพลาด',
                            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ'
                        }));
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

        // Tab switching functionality
        document.querySelectorAll('.modern-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.modern-tab').forEach(t => t.classList.remove('active'));

                // Add active class to clicked tab
                this.classList.add('active');

                // Handle Bootstrap tab functionality
                const targetId = this.getAttribute('data-bs-target');
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });

                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });

        // Tab switching functionality
        document.querySelectorAll('.modern-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.modern-tab').forEach(t => t.classList.remove('active'));

                // Add active class to clicked tab
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>