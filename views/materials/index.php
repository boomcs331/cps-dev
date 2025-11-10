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
                    <button class="modern-tab" id="receipt-tab" data-bs-toggle="tab" data-bs-target="#receipt" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">รับเข้าวัตถุดิบ</h6>
                            <p class="tab-desc">บันทึกการรับเข้า</p>
                        </div>
                        <div class="tab-indicator"></div>
                    </button>
                    <button class="modern-tab" id="issue-tab" data-bs-toggle="tab" data-bs-target="#issue" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">จ่ายออกวัตถุดิบ</h6>
                            <p class="tab-desc">บันทึกการจ่ายออก</p>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">รายการวัตถุดิบ</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                                <i class="fas fa-plus me-2"></i>เพิ่มวัตถุดิบ
                            </button>
                        </div>
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

                <!-- Receipt Tab -->
                <div class="tab-pane fade" id="receipt" role="tabpanel">
                    <!-- Stats Cards -->
                    <section class="kpi-grid mb-4">
                        <article class="kpi-card">
                            <h3>รายการรับเข้าวันนี้</h3>
                            <strong><?= count(array_filter($receipts ?? [], fn($r) => date('Y-m-d', strtotime($r['receipt_date'])) == date('Y-m-d'))) ?></strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                        <article class="kpi-card">
                            <h3>กล่องทั้งหมดวันนี้</h3>
                            <strong><?= array_sum(array_map(fn($r) => date('Y-m-d', strtotime($r['receipt_date'])) == date('Y-m-d') ? ($r['total_box_count'] ?? 0) : 0, $receipts ?? [])) ?></strong>
                            <span class="kpi-trend up">กล่อง</span>
                        </article>
                        <article class="kpi-card">
                            <h3>รายการทั้งหมด</h3>
                            <strong><?= $totalReceiptRecords ?? 0 ?></strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                        <article class="kpi-card">
                            <h3>ผู้จำหน่ายที่ใช้</h3>
                            <strong><?= count(array_unique(array_column($receipts ?? [], 'supplier_name'))) ?></strong>
                            <span class="kpi-trend neutral">ราย</span>
                        </article>
                    </section>

                    <!-- Receipts Table -->
                    <section class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">รายการรับเข้าวัตถุดิบ</h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
                                <i class="fas fa-plus me-2"></i>บันทึกรับเข้า
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>วันที่รับ</th>
                                        <th>รหัสวัตถุดิบ</th>
                                        <th>ชื่อวัตถุดิบ</th>
                                        <th>จำนวน</th>
                                        <th>ผู้จำหน่าย</th>
                                        <th>เลขที่อ้างอิง</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($receipts ?? [])): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">ยังไม่มีข้อมูลการรับเข้าวัตถุดิบ</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($receipts as $receipt): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($receipt['receipt_date'])) ?></td>
                                                <td><code><?= htmlspecialchars($receipt['material_code'] ?? 'N/A') ?></code></td>
                                                <td><?= htmlspecialchars($receipt['material_name'] ?? 'N/A') ?></td>
                                                <td><span class="badge bg-info"><?= number_format($receipt['received_qty'] ?? 0) ?> ชิ้น</span></td>
                                                <td><?= htmlspecialchars($receipt['supplier_name']) ?></td>
                                                <td><code><?= htmlspecialchars($receipt['receipt_no']) ?></code></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" onclick="viewReceipt(<?= $receipt['id'] ?>)" title="ดูรายละเอียด">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteReceipt(<?= $receipt['id'] ?>)" title="ลบ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination for Receipts -->
                        <?php
                        require_once 'helpers/PaginationHelper.php';
                        echo PaginationHelper::renderWithPrefix($receiptCurrentPage ?? 1, $totalReceiptRecords ?? 0, $receiptPerPage ?? 10, 'receipt_');
                        ?>
                    </section>
                </div>

                <!-- Issue Tab -->
                <div class="tab-pane fade" id="issue" role="tabpanel">
                    <!-- Stats Cards -->
                    <section class="kpi-grid mb-4">
                        <article class="kpi-card">
                            <h3>รายการจ่ายออกวันนี้</h3>
                            <strong>0</strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                        <article class="kpi-card">
                            <h3>มูลค่าจ่ายออกวันนี้</h3>
                            <strong>0.00</strong>
                            <span class="kpi-trend down">บาท</span>
                        </article>
                        <article class="kpi-card">
                            <h3>รายการทั้งหมด</h3>
                            <strong>0</strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                        <article class="kpi-card">
                            <h3>แผนกที่ใช้</h3>
                            <strong>0</strong>
                            <span class="kpi-trend neutral">แผนก</span>
                        </article>
                    </section>

                    <!-- Issues Table -->
                    <section class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">รายการจ่ายออกวัตถุดิบ</h5>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addIssueModal">
                                <i class="fas fa-minus me-2"></i>บันทึกจ่ายออก
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>วันที่จ่าย</th>
                                        <th>รหัสวัตถุดิบ</th>
                                        <th>ชื่อวัตถุดิบ</th>
                                        <th>จำนวน</th>
                                        <th>แผนก/งาน</th>
                                        <th>เลขที่อ้างอิง</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">ยังไม่มีข้อมูลการจ่ายออกวัตถุดิบ</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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

    <!-- Add Receipt Modal -->
    <div class="modal fade" id="addReceiptModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">บันทึกรับเข้าวัตถุดิบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addReceiptForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">วันที่รับเข้า <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="receipt_date" value="<?= date('Y-m-d') ?>" required readonly>
                            <div class="form-text">ตั้งค่าเป็นวันที่ปัจจุบันอัตโนมัติ</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เลขที่อ้างอิง</label>
                            <input type="text" class="form-control" name="reference_no" placeholder="ปล่อยว่างได้ ระบบจะ generate อัตโนมัติ">
                            <div class="form-text">หากปล่อยว่าง ระบบจะสร้างเลขอ้างอิงอัตโนมัติ</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">วัตถุดิบ <span class="text-danger">*</span></label>
                            <select class="form-select" name="material_id" required>
                                <option value="">เลือกวัตถุดิบ</option>
                                <?php foreach ($materials as $material): ?>
                                    <option value="<?= $material['id'] ?>"><?= htmlspecialchars($material['material_code'] . ' - ' . $material['material_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">จำนวน (ชิ้น) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantity" min="1" step="1" required placeholder="จำนวนชิ้นทั้งหมด">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ผู้จำหน่าย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="supplier_name" placeholder="ชื่อบริษัทหรือผู้จำหน่าย" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Issue Modal -->
    <div class="modal fade" id="addIssueModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">บันทึกจ่ายออกวัตถุดิบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addIssueForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">วันที่จ่ายออก <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="issue_date" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">เลขที่อ้างอิง</label>
                                    <input type="text" class="form-control" name="reference_no" placeholder="เช่น WO-001, JOB-001">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">วัตถุดิบ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="material_id" required>
                                        <option value="">เลือกวัตถุดิบ</option>
                                        <?php foreach ($materials as $material): ?>
                                            <option value="<?= $material['id'] ?>"><?= htmlspecialchars($material['material_code'] . ' - ' . $material['material_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">จำนวน <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">แผนก/งาน <span class="text-danger">*</span></label>
                                    <select class="form-select" name="department" required>
                                        <option value="">เลือกแผนก</option>
                                        <option value="ผลิต">แผนกผลิต</option>
                                        <option value="ประกอบ">แผนกประกอบ</option>
                                        <option value="บรรจุ">แผนกบรรจุ</option>
                                        <option value="QC">แผนก QC</option>
                                        <option value="R&D">แผนก R&D</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="รายละเอียดเพิ่มเติม (ถ้ามี)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-warning">บันทึกข้อมูล</button>
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
        
        function changePerPageWithPrefix(perPage, prefix) {
            const url = new URL(window.location);
            url.searchParams.set(prefix + 'per_page', perPage);
            url.searchParams.set(prefix + 'page', 1);
            window.location.href = url.toString();
        }

        // Tab switching functionality with localStorage
        document.querySelectorAll('.modern-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const targetId = this.getAttribute('data-bs-target');
                localStorage.setItem('activeTab', targetId);
                
                document.querySelectorAll('.modern-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
        
        // Restore active tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                const tab = document.querySelector(`[data-bs-target="${activeTab}"]`);
                if (tab) {
                    tab.click();
                }
            }
        });

        // Receipt form functionality
        document.getElementById('addReceiptForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>?url=materials/storeReceipt', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showQRCodeSummary(data.data);
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

        function showQRCodeSummary(data) {
            let qrCodesHtml = '';
            data.qr_codes.forEach(qr => {
                qrCodesHtml += `
                    <div class="qr-item mb-2 p-2 border rounded">
                        <strong>QR Code:</strong> ${qr.qr_code}<br>
                        <small>กล่องที่ ${qr.pack_no} - จำนวน ${qr.pack_size} ชิ้น</small>
                    </div>
                `;
            });

            Swal.fire({
                title: 'บันทึกสำเร็จ!',
                html: `
                    <div class="text-start">
                        <p><strong>เลขที่รับเข้า:</strong> ${data.receipt_no}</p>
                        <hr>
                        <h6>QR Codes ที่สร้าง:</h6>
                        ${qrCodesHtml}
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'ตกลง',
                width: '600px'
            }).then(() => {
                bootstrap.Modal.getInstance(document.getElementById('addReceiptModal')).hide();
                location.reload();
            });
        }

        function viewReceipt(id) {
            Swal.fire({
                title: 'รายละเอียดการรับเข้า',
                text: 'ฟีเจอร์นี้กำลังพัฒนา',
                icon: 'info'
            });
        }

        function deleteReceipt(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบรายการรับเข้านี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('receipt_id', id);

                    fetch('<?= BASE_URL ?>?url=materials/deleteReceipt', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบเรียบร้อย!',
                                    text: 'ลบรายการรับเข้าเรียบร้อยแล้ว',
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



        // Issue form functionality
        document.getElementById('addIssueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'กำลังพัฒนา',
                text: 'ฟีเจอร์นี้กำลังพัฒนา'
            });
        });


    </script>
</body>

</html>