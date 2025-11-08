<?php
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
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <header class="dashboard-hero">
                <h1>จัดการวัตถุดิบ</h1>
                <p>จัดการและติดตามข้อมูลวัตถุดิบทั้งหมด</p>
                <div class="actions-bar">
                    <div class="actions">
                        <a class="button-link secondary" href="<?= BASE_URL ?>?url=dashboard/pc">
                            <i class="fas fa-arrow-left"></i> กลับแดชบอร์ด
                        </a>
                    </div>
                    <button class="button-link" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                        <i class="fas fa-plus"></i> เพิ่มวัตถุดิบ
                    </button>
                </div>
            </header>

            <section class="card">
                <div class="card-header">
                    <h2>รายการวัตถุดิบทั้งหมด</h2>
                </div>
                <div class="card-body">
                    <?php
                    require_once 'helpers/ServerSideTable.php';
                    
                    $table = ServerSideTable::create(
                        $materials,
                        $_GET['page'] ?? 1,
                        $_GET['per_page'] ?? 10,
                        $totalRecords ?? count($materials)
                    )
                    ->addColumn('material_code', 'รหัส')
                    ->addColumn('material_name', 'ชื่อวัตถุดิบ')
                    ->addColumn('unit', 'หน่วย')
                    ->addColumn('stock_quantity', 'คงคลัง')
                    ->addColumn('unit_price', 'ราคา/หน่วย')
                    ->addColumn('supplier_name', 'ซัพพลายเออร์')
                    ->addColumn('status', 'สถานะ')
                    ->addAction('แก้ไข', BASE_URL . '?url=materials/edit/{id}', 'btn-primary', 'fas fa-edit')
                    ->addAction('ลบ', 'javascript:deleteMaterial({id})', 'btn-danger', 'fas fa-trash');
                    
                    echo $table->render();
                    ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon me-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-0">เพิ่มวัตถุดิบใหม่</h4>
                            <small class="opacity-75">กรอกข้อมูลวัตถุดิบที่ต้องการเพิ่มเข้าระบบ</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addMaterialForm">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-barcode text-primary me-2"></i>
                                        รหัสวัตถุดิบ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-2" name="material_code" placeholder="เช่น MAT-001" required>
                                    <div class="form-text">รหัสเฉพาะสำหรับระบุวัตถุดิบ</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-tag text-success me-2"></i>
                                        ชื่อวัตถุดิบ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-2" name="material_name" placeholder="เช่น เหล็กแผ่น" required>
                                    <div class="form-text">ชื่อเรียกของวัตถุดิบ</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-ruler text-info me-2"></i>
                                        หน่วยนับ <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg border-2" name="unit" required>
                                        <option value="">เลือกหน่วยนับ</option>
                                        <option value="ชิ้น">ชิ้น</option>
                                        <option value="กิโลกรัม">กิโลกรัม</option>
                                        <option value="เมตร">เมตร</option>
                                        <option value="ลิตร">ลิตร</option>
                                        <option value="แผ่น">แผ่น</option>
                                        <option value="ม้วน">ม้วน</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-boxes text-warning me-2"></i>
                                        จำนวนคงคลัง
                                    </label>
                                    <input type="number" class="form-control form-control-lg border-2" name="stock_quantity" value="0" min="0">
                                    <div class="form-text">จำนวนที่มีอยู่ในคลัง</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                        สต็อกขั้นต่ำ
                                    </label>
                                    <input type="number" class="form-control form-control-lg border-2" name="min_stock" value="0" min="0">
                                    <div class="form-text">จำนวนขั้นต่ำที่ต้องมีในคลัง</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-money-bill text-success me-2"></i>
                                        ราคาต่อหน่วย (บาท)
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-2">฿</span>
                                        <input type="number" step="0.01" class="form-control border-2" name="unit_price" value="0" min="0">
                                    </div>
                                    <div class="form-text">ราคาต้นทุนต่อหน่วย</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-truck text-primary me-2"></i>
                                        ซัพพลายเออร์
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-2" name="supplier_name" placeholder="ชื่อบริษัทผู้จำหน่าย">
                                    <div class="form-text">บริษัทหรือผู้จำหน่ายหลัก</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-sticky-note text-secondary me-2"></i>
                                หมายเหตุ
                            </label>
                            <textarea class="form-control border-2" name="description" rows="3" placeholder="รายละเอียดเพิ่มเติมเกี่ยวกับวัตถุดิบ (ถ้ามี)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-4">
                        <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
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
                    alert('เพิ่มวัตถุดิบเรียบร้อยแล้ว');
                    location.reload();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (data.message || 'กรุณาลองใหม่'));
                }
            })
            .catch(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'));
        });

        function editMaterial(id) {
            window.location.href = '<?= BASE_URL ?>?url=materials/edit/' + id;
        }

        function deleteMaterial(id) {
            if (confirm('ต้องการลบวัตถุดิบนี้หรือไม่?')) {
                fetch('<?= BASE_URL ?>?url=materials/delete/' + id, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('ลบวัตถุดิบเรียบร้อยแล้ว');
                        location.reload();
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + (data.message || 'กรุณาลองใหม่'));
                    }
                })
                .catch(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'));
            }
        }
    </script>
</body>
</html>