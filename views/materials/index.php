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
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/stock-detail.css">

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
                    <button class="modern-tab" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">สต็อกวัตถุดิบ</h6>
                            <p class="tab-desc">รายละเอียดสต็อกแต่ละกล่อง</p>
                        </div>
                        <div class="tab-indicator"></div>
                    </button>
                    <button class="modern-tab" id="product-stock-tab" data-bs-toggle="tab" data-bs-target="#product-stock" type="button" role="tab">
                        <div class="tab-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="tab-content">
                            <h6 class="tab-title">สต็อกสินค้า</h6>
                            <p class="tab-desc">คงคลังสินค้าสำเร็จรูป</p>
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



                    <!-- Search and Filter -->
                    <section class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="fas fa-filter me-2"></i>กรองและค้นหาข้อมูล</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">ค้นหา</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="searchMaterial" placeholder="ค้นหารหัสหรือชื่อวัตถุดิบ...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">สถานะ</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">สถานะทั้งหมด</option>
                                        <option value="1">ใช้งาน</option>
                                        <option value="0">ปิดใช้งาน</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">คลัง</label>
                                    <select class="form-select" id="filterLocation">
                                        <option value="">คลังทั้งหมด</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location['location_id'] ?>"><?= htmlspecialchars($location['location_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-secondary w-100 d-block" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>ล้างตัวกรอง
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Materials Table -->
                    <section class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">รายการวัตถุดิบ</h5>
                            <button class="btn btn-primary rounded-0" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                                <i class="fas fa-plus me-2"></i>เพิ่มวัตถุดิบ
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="materialsTable">
                                <thead>
                                    <tr>
                                        <th>รหัส</th>
                                        <th>ชื่อวัตถุดิบ</th>
                                        <th>หน่วย</th>
                                        <th>คลัง</th>
                                        <th>สต็อกคงเหลือ</th>
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
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:viewMaterialStock('<?= $material['material_code'] ?>')" class="text-decoration-none">
                                                        <span class="badge bg-primary me-2"><?= number_format($material['total_stock'] ?? 0) ?></span>
                                                        <small class="text-muted">ชิ้น</small>
                                                        <?php if (($material['total_boxes'] ?? 0) > 0): ?>
                                                            <span class="ms-2 text-info">(<i class="fas fa-box me-1"></i><?= $material['total_boxes'] ?> กล่อง)</span>
                                                        <?php endif; ?>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge <?= $material['is_active'] === 'ใช้งาน' ? 'info' : 'warning' ?>">
                                                    <?= htmlspecialchars($material['is_active']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                require_once 'helpers/ActionHelper.php';
                                                $actions = [
                                                    ['type' => 'link', 'label' => 'ดูรายละเอียด', 'url' => 'javascript:viewMaterial({id})', 'icon' => 'fas fa-eye'],
                                                    ['type' => 'link', 'label' => 'แก้ไข', 'url' => 'javascript:editMaterial({id})', 'icon' => 'fas fa-edit', 'class' => 'text-warning'],
                                                    ['type' => 'divider'],
                                                    ['type' => 'link', 'label' => 'ลบ', 'url' => 'javascript:deleteMaterial({id})', 'icon' => 'fas fa-trash', 'class' => 'text-danger'],
                                                ];
                                                echo ActionHelper::renderDropdown($actions, $material['id']);
                                                ?>
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

                <!-- Stock Tab -->
                <div class="tab-pane fade" id="stock" role="tabpanel">
                    <!-- Stats Cards -->
                    <section class="kpi-grid mb-4">
                        <article class="kpi-card">
                            <h3>สต็อกทั้งหมด</h3>
                            <strong id="total-stock">0</strong>
                            <span class="kpi-trend neutral">ชิ้น</span>
                        </article>
                        <article class="kpi-card">
                            <h3>กล่องทั้งหมด</h3>
                            <strong id="total-boxes">0</strong>
                            <span class="kpi-trend up">กล่อง</span>
                        </article>
                        <article class="kpi-card">
                            <h3>สต็อกพร้อมใช้</h3>
                            <strong id="available-stock">0</strong>
                            <span class="kpi-trend up">ชิ้น</span>
                        </article>
                        <article class="kpi-card">
                            <h3>วัตถุดิบที่มีสต็อก</h3>
                            <strong id="materials-with-stock">0</strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                    </section>

                    <!-- Material Stock Summary -->
                    <section class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">สรุปสต็อกตามวัตถุดิบ</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="materialStockSummaryTable">
                                <thead>
                                    <tr>
                                        <th>รหัสวัตถุดิบ</th>
                                        <th>ชื่อวัตถุดิบ</th>
                                        <th>จำนวนกล่อง</th>
                                        <th>จำนวนชิ้น</th>
                                        <th>คลัง</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="material-stock-summary-body">
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted mb-0">กำลังโหลดข้อมูล...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="text-muted">แสดง <span id="summary-start">0</span>-<span id="summary-end">0</span> จาก <span id="summary-total">0</span> รายการ</span>
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0" id="summary-pagination">
                                        <!-- Pagination will be generated by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </section>

                    <!-- Material Detail Card -->
                    <section class="card mb-3" id="materialDetailCard" style="display: none;">
                        <div class="card-header">
                            <h5 class="card-title mb-0">สรุปสต็อก - <span id="detailMaterialName"></span></h5>
                            <button class="btn btn-sm btn-outline-secondary" onclick="hideMaterialDetail()">
                                <i class="fas fa-arrow-left"></i> กลับ
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-primary mb-1" id="detailTotalBoxes">0</h4>
                                        <small class="text-muted">จำนวนกล่อง</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-success mb-1" id="detailTotalStock">0</h4>
                                        <small class="text-muted">จำนวนชิ้นรวม</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-info mb-1" id="detailAvailableStock">0</h4>
                                        <small class="text-muted">พร้อมใช้งาน</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-warning mb-1" id="detailReservedStock">0</h4>
                                        <small class="text-muted">จองแล้ว</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Stock Detail Table -->
                    <section class="card" id="stockDetailCard" style="display: none;">
                        <div class="card-header">
                            <h5 class="card-title mb-0">รายละเอียดสต็อกแต่ละกล่อง - <span id="stockDetailMaterialCode"></span></h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="materialStockDetailTable">
                                <thead>
                                    <tr>
                                        <th>QR Code</th>
                                        <th>Lot No.</th>
                                        <th>กล่องที่</th>
                                        <th>จำนวนชิ้น</th>
                                        <th>สถานะ</th>
                                        <th>วันที่รับเข้า</th>
                                    </tr>
                                </thead>
                                <tbody id="material-stock-detail-body">
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Detail Pagination -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="text-muted">แสดง <span id="detail-start">0</span>-<span id="detail-end">0</span> จาก <span id="detail-total">0</span> รายการ</span>
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0" id="detail-pagination">
                                        <!-- Pagination will be generated by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </section>


                </div>

                <!-- Product Stock Tab -->
                <div class="tab-pane fade" id="product-stock" role="tabpanel">
                    <!-- Stats Cards -->
                    <section class="kpi-grid mb-4">
                        <article class="kpi-card">
                            <h3>สินค้าทั้งหมด</h3>
                            <strong id="total-products">0</strong>
                            <span class="kpi-trend neutral">รายการ</span>
                        </article>
                        <article class="kpi-card">
                            <h3>สินค้าคงคลัง</h3>
                            <strong id="total-product-stock">0</strong>
                            <span class="kpi-trend up">ชิ้น</span>
                        </article>
                        <article class="kpi-card">
                            <h3>มูลค่าคงคลัง</h3>
                            <strong id="total-stock-value">0</strong>
                            <span class="kpi-trend neutral">บาท</span>
                        </article>
                        <article class="kpi-card">
                            <h3>สินค้าใกล้หมด</h3>
                            <strong id="low-stock-products">0</strong>
                            <span class="kpi-trend down">รายการ</span>
                        </article>
                    </section>

                    <!-- Product Stock Table -->
                    <section class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">คงคลังสินค้าสำเร็จรูป</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="productStockTable">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>หมวดหมู่</th>
                                        <th>คงคลัง</th>
                                        <th>ราคาต่อหน่วย</th>
                                        <th>มูลค่ารวม</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="product-stock-table-body">
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted mb-0">กำลังโหลดข้อมูลสต็อกสินค้า...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                            <button class="btn btn-gradient-success btn-add-receipt" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
                                <i class="fas fa-arrow-down me-2"></i>
                                <span>บันทึกรับเข้า</span>
                                <i class="fas fa-plus ms-2"></i>
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
                                                    <div class="btn-group">
                                                        <a href="<?= BASE_URL ?>?url=materials/receiptDetail/<?= $receipt['id'] ?>" class="btn btn-sm btn-outline-primary" title="ดูรายละเอียดและ QR Code">
                                                            <i class="fas fa-qrcode me-1"></i>QR Code
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteReceipt(<?= $receipt['id'] ?>)" title="ลบรายการ">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
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
                            <button class="btn btn-gradient-warning btn-add-issue" data-bs-toggle="modal" data-bs-target="#addIssueModal">
                                <i class="fas fa-arrow-up me-2"></i>
                                <span>บันทึกจ่ายออก</span>
                                <i class="fas fa-minus ms-2"></i>
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

        function viewMaterial(id) {
            fetch('<?= BASE_URL ?>?url=materials/get&id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const material = data.material;

                        // หาข้อมูล unit และ location จาก dropdown
                        const unitSelect = document.querySelector('#edit_default_unit');
                        const locationSelect = document.querySelector('#edit_location_id');

                        let unitName = 'N/A';
                        let locationName = 'N/A';

                        if (unitSelect && material.default_unit) {
                            const unitOption = unitSelect.querySelector(`option[value="${material.default_unit}"]`);
                            if (unitOption) unitName = unitOption.textContent;
                        }

                        if (locationSelect && material.location_id) {
                            const locationOption = locationSelect.querySelector(`option[value="${material.location_id}"]`);
                            if (locationOption) locationName = locationOption.textContent;
                        }

                        Swal.fire({
                            title: 'รายละเอียดวัตถุดิบ',
                            html: `
                                <div class="text-start">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td class="fw-bold" width="120">รหัสวัตถุดิบ:</td>
                                                    <td><code class="bg-light px-2 py-1 rounded">${material.material_code}</code></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">ชื่อวัตถุดิบ:</td>
                                                    <td><strong class="text-primary">${material.material_name || 'N/A'}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">หน่วยนับ:</td>
                                                    <td><span class="badge bg-info">${unitName}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">คลังจัดเก็บ:</td>
                                                    <td><span class="badge bg-secondary">${locationName}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">สถานะ:</td>
                                                    <td><span class="badge ${material.is_active == 1 ? 'bg-success' : 'bg-warning'}">${material.is_active == 1 ? 'ใช้งาน' : 'ปิดใช้งาน'}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">ID:</td>
                                                    <td><small class="text-muted">#${material.material_id}</small></td>
                                                </tr>
                                                ${material.description ? `
                                                <tr>
                                                    <td class="fw-bold align-top">รายละเอียด:</td>
                                                    <td><div class="bg-light p-2 rounded"><small>${material.description}</small></div></td>
                                                </tr>
                                                ` : ''}
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <small class="text-muted">คลิก "แก้ไข" เพื่อแก้ไขข้อมูลวัตถุดิบนี้</small>
                                    </div>
                                </div>
                            `,
                            width: '600px',
                            showCancelButton: true,
                            confirmButtonText: '<i class="fas fa-edit me-1"></i>แก้ไข',
                            cancelButtonText: '<i class="fas fa-times me-1"></i>ปิด',
                            confirmButtonColor: '#ffc107',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                editMaterial(id);
                            }
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'ไม่สามารถโหลดข้อมูลได้'
                }));
        }

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

                    // Load stock content when stock tab is clicked
                    if (targetId === '#stock') {
                        loadMaterialStockSummary();
                    }
                    // Load product stock content when product stock tab is clicked
                    if (targetId === '#product-stock') {
                        loadProductStockContent();
                    }
                }
            });
        });

        // Load stock content function
        function loadStockContent() {
            const stockTableBody = document.getElementById('stock-table-body');
            if (!stockTableBody) return; // Element doesn't exist
            if (stockTableBody.dataset.loaded) return; // Already loaded

            // Try to fetch from server, fallback to mock data
            fetch('<?= BASE_URL ?>?url=materials/stock', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.stockLots) {
                        updateStockStats(data.stats);
                        renderStockTable(data.stockLots);
                    } else {
                        throw new Error('Invalid data format');
                    }
                    stockTableBody.dataset.loaded = 'true';
                })
                .catch(error => {
                    console.warn('Failed to load from server, using mock data:', error);
                    // Use mock data as fallback
                    updateStockStats(mockStockData.stats);
                    renderStockTable(mockStockData.stockLots);
                    stockTableBody.dataset.loaded = 'true';
                });
        }

        function updateStockStats(stats) {
            document.getElementById('total-stock').textContent = stats.totalStock || 0;
            document.getElementById('total-boxes').textContent = stats.totalBoxes || 0;
            document.getElementById('available-stock').textContent = stats.availableStock || 0;
            document.getElementById('materials-with-stock').textContent = stats.materialsWithStock || 0;
        }

        function renderStockTable(stockLots) {
            const tbody = document.getElementById('stock-table-body');
            if (!tbody) return; // Element doesn't exist

            if (stockLots.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><p class="text-muted mb-0">ยังไม่มีข้อมูลสต็อก</p></td></tr>';
                return;
            }

            let html = '';
            stockLots.forEach(lot => {
                const statusClass = lot.status === 'AVAILABLE' ? 'bg-success' :
                    lot.status === 'RESERVED' ? 'bg-warning' : 'bg-secondary';
                const statusText = lot.status === 'AVAILABLE' ? 'พร้อมใช้งาน' :
                    lot.status === 'RESERVED' ? 'จองแล้ว' : 'ใช้แล้ว';

                html += `
                    <tr>
                        <td><code class="code-badge clickable" onclick="viewStockDetail('${lot.qr_code}')">${lot.qr_code}</code></td>
                        <td>
                            <div>
                                <strong>${lot.material_code}</strong><br>
                                <small class="text-muted">${lot.material_name || 'N/A'}</small>
                            </div>
                        </td>
                        <td><span class="badge bg-info">${lot.lot_no}</span></td>
                        <td><span class="badge bg-secondary">${lot.pack_no}</span></td>
                        <td>
                            <strong class="text-primary">${parseInt(lot.pack_size).toLocaleString()}</strong>
                            <small class="text-muted d-block">ชิ้น/กล่อง</small>
                        </td>
                        <td>${lot.location_name || 'N/A'}</td>
                        <td><span class="badge ${statusClass}">${statusText}</span></td>
                        <td><small>${new Date(lot.created_at).toLocaleDateString('th-TH')}</small></td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // View stock detail function
        function viewStockDetail(qrCode) {
            fetch('<?= BASE_URL ?>?url=materials/stockDetail&qr_code=' + encodeURIComponent(qrCode), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.stockDetail) {
                        const stockDetail = data.stockDetail;

                        const statusClass = stockDetail.status === 'AVAILABLE' ? 'success' :
                            stockDetail.status === 'RESERVED' ? 'warning' : 'secondary';
                        const statusText = stockDetail.status === 'AVAILABLE' ? 'พร้อมใช้งาน' :
                            stockDetail.status === 'RESERVED' ? 'จองแล้ว' : 'ใช้แล้ว';

                        Swal.fire({
                            title: `รายละเอียดกล่อง`,
                            html: `
                    <div class="text-start">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-qrcode me-2"></i>${stockDetail.qr_code}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold" width="120">วัตถุดิบ:</td>
                                                <td>
                                                    <code class="bg-light px-2 py-1 rounded">${stockDetail.material_code}</code><br>
                                                    <small class="text-muted">${stockDetail.material_name}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Lot No.:</td>
                                                <td><span class="badge bg-info">${stockDetail.lot_no}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">กล่องที่:</td>
                                                <td><span class="badge bg-secondary">${stockDetail.pack_no}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">จำนวน:</td>
                                                <td><strong class="text-primary">${stockDetail.pack_size.toLocaleString()} ชิ้น</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">สถานะ:</td>
                                                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold" width="120">คลัง:</td>
                                                <td>${stockDetail.location_name}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">ผู้จำหน่าย:</td>
                                                <td>${stockDetail.supplier_name}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">วันที่รับ:</td>
                                                <td><small>${new Date(stockDetail.receipt_date).toLocaleDateString('th-TH')}</small></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">วันหมดอายุ:</td>
                                                <td><small class="text-warning">${new Date(stockDetail.expiry_date).toLocaleDateString('th-TH')}</small></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Batch:</td>
                                                <td><code class="bg-light px-2 py-1 rounded">${stockDetail.batch_info}</code></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                            width: '700px',
                            showCancelButton: true,
                            confirmButtonText: '<i class="fas fa-edit me-1"></i>ปรับสถานะ',
                            cancelButtonText: '<i class="fas fa-times me-1"></i>ปิด',
                            confirmButtonColor: '#ffc107',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                updateStockStatus(qrCode);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่พบข้อมูล',
                            text: 'ไม่พบข้อมูลสต็อกที่ต้องการ'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching stock detail:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถโหลดข้อมูลได้'
                    });
                });
        }

        function updateStockStatus(qrCode) {
            Swal.fire({
                title: 'ปรับสถานะสต็อก',
                html: `
                    <div class="text-start">
                        <p class="mb-3">เลือกสถานะใหม่สำหรับ QR Code: <code>${qrCode}</code></p>
                        <select class="form-select" id="newStatus">
                            <option value="AVAILABLE">พร้อมใช้งาน</option>
                            <option value="RESERVED">จองแล้ว</option>
                            <option value="USED">ใช้แล้ว</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                preConfirm: () => {
                    return document.getElementById('newStatus').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ปรับสถานะสำเร็จ!',
                        text: `อัปเดตสถานะของ ${qrCode} เรียบร้อยแล้ว`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Load product stock content function
        function loadProductStockContent() {
            const productStockTableBody = document.getElementById('product-stock-table-body');
            if (productStockTableBody.dataset.loaded) return; // Already loaded

            // Mock data for demonstration
            const mockProductStock = [{
                    product_code: 'PRD-001',
                    product_name: 'สินค้า A',
                    category: 'หมวดหมู่ 1',
                    stock_qty: 150,
                    unit_price: 250.00,
                    total_value: 37500.00,
                    status: 'IN_STOCK'
                },
                {
                    product_code: 'PRD-002',
                    product_name: 'สินค้า B',
                    category: 'หมวดหมู่ 2',
                    stock_qty: 25,
                    unit_price: 180.00,
                    total_value: 4500.00,
                    status: 'LOW_STOCK'
                },
                {
                    product_code: 'PRD-003',
                    product_name: 'สินค้า C',
                    category: 'หมวดหมู่ 1',
                    stock_qty: 0,
                    unit_price: 320.00,
                    total_value: 0.00,
                    status: 'OUT_OF_STOCK'
                }
            ];

            const mockStats = {
                totalProducts: 3,
                totalProductStock: 175,
                totalStockValue: 42000.00,
                lowStockProducts: 2
            };

            updateProductStockStats(mockStats);
            renderProductStockTable(mockProductStock);
            productStockTableBody.dataset.loaded = 'true';
        }

        function updateProductStockStats(stats) {
            document.getElementById('total-products').textContent = stats.totalProducts || 0;
            document.getElementById('total-product-stock').textContent = (stats.totalProductStock || 0).toLocaleString();
            document.getElementById('total-stock-value').textContent = (stats.totalStockValue || 0).toLocaleString();
            document.getElementById('low-stock-products').textContent = stats.lowStockProducts || 0;
        }

        function renderProductStockTable(products) {
            const tbody = document.getElementById('product-stock-table-body');

            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><p class="text-muted mb-0">ยังไม่มีข้อมูลสินค้า</p></td></tr>';
                return;
            }

            let html = '';
            products.forEach(product => {
                const statusClass = product.status === 'IN_STOCK' ? 'bg-success' :
                    product.status === 'LOW_STOCK' ? 'bg-warning' : 'bg-danger';
                const statusText = product.status === 'IN_STOCK' ? 'คงคลังปกติ' :
                    product.status === 'LOW_STOCK' ? 'คงคลังต่ำ' : 'หมดสต็อก';

                html += `
                    <tr>
                        <td><code class="code-badge">${product.product_code}</code></td>
                        <td><strong>${product.product_name}</strong></td>
                        <td><span class="badge bg-info">${product.category}</span></td>
                        <td>
                            <strong class="text-primary">${product.stock_qty.toLocaleString()}</strong>
                            <small class="text-muted">ชิ้น</small>
                        </td>
                        <td>฿${product.unit_price.toLocaleString()}</td>
                        <td>
                            <strong class="text-success">฿${product.total_value.toLocaleString()}</strong>
                        </td>
                        <td><span class="badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewProductStock('${product.product_code}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="adjustStock('${product.product_code}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function viewProductStock(productCode) {
            Swal.fire({
                icon: 'info',
                title: 'รายละเอียดสินค้า',
                text: `ดูรายละเอียดสินค้า ${productCode}`,
                confirmButtonText: 'ตกลง'
            });
        }

        function adjustStock(productCode) {
            Swal.fire({
                icon: 'info',
                title: 'ปรับสต็อก',
                text: `ปรับสต็อกสินค้า ${productCode}`,
                confirmButtonText: 'ตกลง'
            });
        }

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



        // Search and Filter functionality
        let searchTimeout;

        document.getElementById('searchMaterial').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterTable, 300);
        });

        document.getElementById('filterStatus').addEventListener('change', filterTable);
        document.getElementById('filterLocation').addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchMaterial').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const locationFilter = document.getElementById('filterLocation').value;
            const rows = document.querySelectorAll('#materialsTable tbody tr');

            rows.forEach(row => {
                const code = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                const location = row.cells[3].textContent;
                const statusBadge = row.cells[4].querySelector('.badge');
                const isActive = statusBadge && statusBadge.textContent.includes('ใช้งาน') ? '1' : '0';

                let showRow = true;

                // Search filter
                if (searchTerm && !code.includes(searchTerm) && !name.includes(searchTerm)) {
                    showRow = false;
                }

                // Status filter
                if (statusFilter && statusFilter !== isActive) {
                    showRow = false;
                }

                // Location filter
                if (locationFilter) {
                    const locationSelect = document.getElementById('filterLocation');
                    const selectedLocationText = locationSelect.options[locationSelect.selectedIndex].text;
                    if (!location.includes(selectedLocationText)) {
                        showRow = false;
                    }
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('searchMaterial').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterLocation').value = '';
            filterTable();
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

        function loadMaterialStockSummary(page = 1) {
            const materialStockSummaryBody = document.getElementById('material-stock-summary-body');

            fetch(`<?= BASE_URL ?>direct_test.php?page=${page}&per_page=${summaryPerPage}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data);
                    if (data.success) {
                        renderMaterialStockSummary(data.materialStockSummary || []);
                    } else {
                        console.error('API returned error:', data);
                        materialStockSummaryBody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i><p class="text-muted mb-0">API ส่งคืนข้อผิดพลาด</p></td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Failed to load material stock summary:', error);
                    materialStockSummaryBody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i><p class="text-muted mb-0">เกิดข้อผิดพลาดในการโหลดข้อมูล</p></td></tr>';
                });
        }

        let currentSummaryPage = 1;
        const summaryPerPage = 10;
        let allMaterialSummary = [];

        function renderMaterialStockSummary(materialStockSummary) {
            allMaterialSummary = materialStockSummary;
            renderSummaryPage(currentSummaryPage);
        }

        function renderSummaryPage(page) {
            const tbody = document.getElementById('material-stock-summary-body');
            const startIndex = (page - 1) * summaryPerPage;
            const endIndex = startIndex + summaryPerPage;
            const pageData = allMaterialSummary.slice(startIndex, endIndex);

            if (pageData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><p class="text-muted mb-0">ยังไม่มีข้อมูลสต็อก</p></td></tr>';
                updateSummaryPagination(0, page);
                return;
            }

            let html = '';
            pageData.forEach(material => {
                html += `
                    <tr>
                        <td><code class="code-badge">${material.material_code || 'N/A'}</code></td>
                        <td>
                            <div>
                                <strong>${material.material_name || 'N/A'}</strong><br>
                                <small class="text-muted">${material.location_name || 'N/A'}</small>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">${(material.total_boxes || 0).toLocaleString()}</span></td>
                        <td><span class="badge bg-primary">${(material.total_stock || 0).toLocaleString()}</span></td>
                        <td><span class="badge bg-info">${material.location_name || 'N/A'}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewMaterialStockDetail('${material.material_code}')" title="ดูรายละเอียด">
                                <i class="fas fa-eye"></i> ดูรายละเอียด
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            updateSummaryPagination(allMaterialSummary.length, page);
        }

        function updateSummaryPagination(total, currentPage) {
            const totalPages = Math.ceil(total / summaryPerPage);
            const startItem = total === 0 ? 0 : (currentPage - 1) * summaryPerPage + 1;
            const endItem = Math.min(currentPage * summaryPerPage, total);

            document.getElementById('summary-start').textContent = startItem;
            document.getElementById('summary-end').textContent = endItem;
            document.getElementById('summary-total').textContent = total;

            const pagination = document.getElementById('summary-pagination');
            let paginationHtml = '';

            if (totalPages > 1) {
                // Previous button
                paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changeSummaryPage(${currentPage - 1})">ก่อนหน้า</a>
                </li>`;

                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    if (i === currentPage || i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                        paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="changeSummaryPage(${i})">${i}</a>
                        </li>`;
                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                        paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }

                // Next button
                paginationHtml += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changeSummaryPage(${currentPage + 1})">ถัดไป</a>
                </li>`;
            }

            pagination.innerHTML = paginationHtml;
        }

        function changeSummaryPage(page) {
            if (page < 1 || page > Math.ceil(allMaterialSummary.length / summaryPerPage)) return;
            currentSummaryPage = page;
            loadMaterialStockSummary(page);
        }

        function viewMaterialStockDetail(materialCode) {
            loadStockContentByMaterial(materialCode);
        }

        function loadStockContentByMaterial(materialCode) {
            // Fetch stock data for specific material
            fetch(`<?= BASE_URL ?>?url=materials/stockByMaterial&material_code=${encodeURIComponent(materialCode)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.stockLots) {
                        const filteredLots = data.stockLots;

                        // Find material name
                        const material = filteredLots[0];
                        const materialName = material ? material.material_name : materialCode;

                        // Calculate summary
                        let totalBoxes = filteredLots.length;
                        let totalStock = filteredLots.reduce((sum, lot) => sum + parseInt(lot.pack_size || 0), 0);
                        let availableStock = filteredLots.filter(lot => lot.status === 'AVAILABLE').reduce((sum, lot) => sum + parseInt(lot.pack_size || 0), 0);
                        let reservedStock = filteredLots.filter(lot => lot.status === 'RESERVED').reduce((sum, lot) => sum + parseInt(lot.pack_size || 0), 0);

                        // Update summary card
                        document.getElementById('detailMaterialName').textContent = materialName;
                        document.getElementById('detailTotalBoxes').textContent = totalBoxes.toLocaleString();
                        document.getElementById('detailTotalStock').textContent = totalStock.toLocaleString();
                        document.getElementById('detailAvailableStock').textContent = availableStock.toLocaleString();
                        document.getElementById('detailReservedStock').textContent = reservedStock.toLocaleString();

                        // Store data for pagination
                        allDetailLots = filteredLots;
                        currentDetailPage = 1;
                        renderDetailPage(1);

                        // Show sections
                        document.getElementById('stockDetailMaterialCode').textContent = materialCode;
                        document.getElementById('materialDetailCard').style.display = 'block';
                        document.getElementById('stockDetailCard').style.display = 'block';

                        // Scroll to detail card
                        document.getElementById('materialDetailCard').scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        // Show empty state with helpful message
                        document.getElementById('detailMaterialName').textContent = materialCode;
                        document.getElementById('detailTotalBoxes').textContent = '0';
                        document.getElementById('detailTotalStock').textContent = '0';
                        document.getElementById('detailAvailableStock').textContent = '0';
                        document.getElementById('detailReservedStock').textContent = '0';
                        
                        const tbody = document.getElementById('material-stock-detail-body');
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-info-circle me-2"></i>ไม่พบข้อมูลสต็อกของวัตถุดิบนี้ กรุณาตรวจสอบว่ามีการรับเข้าแล้วหรือไม่</td></tr>';
                        
                        document.getElementById('stockDetailMaterialCode').textContent = materialCode;
                        document.getElementById('materialDetailCard').style.display = 'block';
                        document.getElementById('stockDetailCard').style.display = 'block';
                        document.getElementById('materialDetailCard').scrollIntoView({ behavior: 'smooth' });
                    }
                })
                .catch(error => {
                    console.error('Error loading stock data:', error);
                    
                    // Show error state but still display the cards
                    document.getElementById('detailMaterialName').textContent = materialCode;
                    document.getElementById('detailTotalBoxes').textContent = '0';
                    document.getElementById('detailTotalStock').textContent = '0';
                    document.getElementById('detailAvailableStock').textContent = '0';
                    document.getElementById('detailReservedStock').textContent = '0';
                    
                    const tbody = document.getElementById('material-stock-detail-body');
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-exclamation-triangle text-warning me-2"></i>เกิดข้อผิดพลาดในการโหลดข้อมูล กรุณาลองใหม่อีกครั้ง</td></tr>';
                    
                    document.getElementById('stockDetailMaterialCode').textContent = materialCode;
                    document.getElementById('materialDetailCard').style.display = 'block';
                    document.getElementById('stockDetailCard').style.display = 'block';
                    document.getElementById('materialDetailCard').scrollIntoView({ behavior: 'smooth' });
                });
        }

        function hideMaterialDetail() {
            document.getElementById('materialDetailCard').style.display = 'none';
            document.getElementById('stockDetailCard').style.display = 'none';
        }
        
        let currentDetailPage = 1;
        const detailPerPage = 10;
        let allDetailLots = [];

        function renderDetailPage(page) {
            const tbody = document.getElementById('material-stock-detail-body');
            const startIndex = (page - 1) * detailPerPage;
            const endIndex = startIndex + detailPerPage;
            const pageData = allDetailLots.slice(startIndex, endIndex);
            
            tbody.innerHTML = '';
            
            if (pageData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-info-circle me-2"></i>ไม่พบข้อมูลสต็อกของวัตถุดิบนี้</td></tr>';
            } else {
                pageData.forEach(lot => {
                    const row = document.createElement('tr');
                    const statusClass = lot.status === 'AVAILABLE' ? 'success' :
                                       lot.status === 'RESERVED' ? 'warning' : 'secondary';
                    const statusText = lot.status === 'AVAILABLE' ? 'พร้อมใช้งาน' :
                                      lot.status === 'RESERVED' ? 'จองแล้ว' : 'ใช้แล้ว';
                    
                    row.innerHTML = `
                        <td><code class="code-badge">${lot.qr_code || 'N/A'}</code></td>
                        <td><span class="badge bg-info">${lot.lot_no || 'N/A'}</span></td>
                        <td><span class="badge bg-secondary">${lot.pack_no || 'N/A'}</span></td>
                        <td><strong class="text-primary">${parseInt(lot.pack_size || 0).toLocaleString()}</strong> <small class="text-muted">ชิ้น</small></td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td><small>${lot.created_at ? new Date(lot.created_at).toLocaleDateString('th-TH') : 'N/A'}</small></td>
                    `;
                    tbody.appendChild(row);
                });
            }
            
            updateDetailPagination(allDetailLots.length, page);
        }

        function updateDetailPagination(total, currentPage) {
            const totalPages = Math.ceil(total / detailPerPage);
            const startItem = total === 0 ? 0 : (currentPage - 1) * detailPerPage + 1;
            const endItem = Math.min(currentPage * detailPerPage, total);

            document.getElementById('detail-start').textContent = startItem;
            document.getElementById('detail-end').textContent = endItem;
            document.getElementById('detail-total').textContent = total;

            const pagination = document.getElementById('detail-pagination');
            let paginationHtml = '';

            if (totalPages > 1) {
                paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changeDetailPage(${currentPage - 1})">ก่อนหน้า</a>
                </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    if (i === currentPage || i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                        paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0)" onclick="changeDetailPage(${i})">${i}</a>
                        </li>`;
                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                        paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }

                paginationHtml += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changeDetailPage(${currentPage + 1})">ถัดไป</a>
                </li>`;
            }

            pagination.innerHTML = paginationHtml;
        }

        function changeDetailPage(page) {
            event.preventDefault();
            if (page < 1 || page > Math.ceil(allDetailLots.length / detailPerPage)) return;
            currentDetailPage = page;
            renderDetailPage(page);
        }

        function viewMaterialStock(materialCode) {
            // Switch to stock tab first
            const stockTab = document.getElementById('stock-tab');
            if (stockTab) {
                stockTab.click();
                // Wait for tab to load then show material detail
                setTimeout(() => {
                    loadStockContentByMaterial(materialCode);
                }, 100);
            } else {
                // If tab switching fails, try direct load
                loadStockContentByMaterial(materialCode);
            }
        }
    </script>
</body>

</html>