<?php 
require_once 'views/layouts/header.php'; 

// ตรวจสอบข้อมูลที่จำเป็น
$materials = $materials ?? [];
$locations = $locations ?? [];
$stockLots = $stockLots ?? [];
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>รายละเอียดสต็อกวัตถุดิบ</h3>
                <p class="text-subtitle text-muted">ติดตามสต็อกแต่ละกล่อง/ล็อต</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">หน้าหลัก</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?url=materials">วัตถุดิบ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">สต็อก</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <!-- Filter Card -->
    <section class="card mb-3">
        <div class="card-header">
            <h6 class="card-title mb-0"><i class="fas fa-filter me-2"></i>กรองข้อมูล</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">วัตถุดิบ</label>
                    <select class="form-select" id="filterMaterial">
                        <option value="">วัตถุดิบทั้งหมด</option>
                        <?php if (!empty($materials)): ?>
                            <?php foreach ($materials as $material): ?>
                                <option value="<?= $material['id'] ?>"><?= htmlspecialchars($material['material_code'] . ' - ' . ($material['material_name'] ?? 'N/A')) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">สถานะ</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="AVAILABLE">พร้อมใช้งาน</option>
                        <option value="RESERVED">จองแล้ว</option>
                        <option value="USED">ใช้แล้ว</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">คลัง</label>
                    <select class="form-select" id="filterLocation">
                        <option value="">คลังทั้งหมด</option>
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?= $location['location_id'] ?>"><?= htmlspecialchars($location['location_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-secondary w-100 d-block" onclick="clearFilters()">
                        <i class="fas fa-times me-1"></i>ล้าง
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Material Summary Table -->
    <section class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">รายการวัตถุดิบทั้งหมด</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="materialSummaryTable">
                <thead>
                    <tr>
                        <th>รหัสวัตถุดิบ</th>
                        <th>ชื่อวัตถุดิบ</th>
                        <th>จำนวนกล่อง</th>
                        <th>จำนวนชิ้นรวม</th>
                        <th>คลัง</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $materialSummary = [];
                    if (!empty($materials) && !empty($stockLots)) {
                        foreach ($materials as $material) {
                            $totalBoxes = 0;
                            $totalStock = 0;
                            foreach ($stockLots as $lot) {
                                if ($lot['material_code'] === $material['material_code']) {
                                    $totalBoxes++;
                                    $totalStock += (int)$lot['pack_size'];
                                }
                            }
                            if ($totalBoxes > 0) {
                                $materialSummary[] = [
                                    'material_code' => $material['material_code'],
                                    'material_name' => $material['material_name'] ?? 'N/A',
                                    'total_boxes' => $totalBoxes,
                                    'total_stock' => $totalStock,
                                    'location_name' => $material['location_name'] ?? 'N/A'
                                ];
                            }
                        }
                    }
                    ?>
                    <?php if (!empty($materialSummary)): ?>
                        <?php foreach ($materialSummary as $summary): ?>
                            <tr>
                                <td><code class="code-badge"><?= htmlspecialchars($summary['material_code']) ?></code></td>
                                <td><strong><?= htmlspecialchars($summary['material_name']) ?></strong></td>
                                <td><span class="badge bg-secondary"><?= number_format($summary['total_boxes']) ?></span></td>
                                <td><span class="badge bg-primary"><?= number_format($summary['total_stock']) ?></span></td>
                                <td><?= htmlspecialchars($summary['location_name']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showMaterialDetail('<?= $summary['material_code'] ?>')">
                                        <i class="fas fa-eye"></i> ดูรายละเอียด
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <?php if (isset($error)): ?>
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i><?= htmlspecialchars($error) ?>
                                <?php else: ?>
                                    <i class="fas fa-info-circle me-2"></i>ไม่พบข้อมูลสต็อกวัตถุดิบ
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php
        require_once 'helpers/PaginationHelper.php';
        echo PaginationHelper::render($currentPage, $totalRecords, $perPage);
        ?>
    </section>

    <!-- Material Summary Card -->
    <section class="card mb-3" id="materialSummaryCard" style="display: none;">
        <div class="card-header">
            <h5 class="card-title mb-0">สรุปสต็อก - <span id="summaryMaterialName"></span></h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="hideStockDetail()">
                <i class="fas fa-arrow-left"></i> กลับ
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-primary mb-1" id="totalBoxes">0</h4>
                        <small class="text-muted">จำนวนกล่อง</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-success mb-1" id="totalStock">0</h4>
                        <small class="text-muted">จำนวนชิ้นรวม</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-info mb-1" id="availableStock">0</h4>
                        <small class="text-muted">พร้อมใช้งาน</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-warning mb-1" id="reservedStock">0</h4>
                        <small class="text-muted">จองแล้ว</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stock Detail Table -->
    <section class="card" id="stockDetailSection" style="display: none;">
        <div class="card-header">
            <h5 class="card-title mb-0">รายละเอียดสต็อกแต่ละกล่อง - <span id="selectedMaterial"></span></h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="stockDetailTable">
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
                <tbody id="stockDetailBody">
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

<script>
// Filter functionality
document.getElementById('filterMaterial').addEventListener('change', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);
document.getElementById('filterLocation').addEventListener('change', filterTable);

function filterTable() {
    const materialFilter = document.getElementById('filterMaterial').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const locationFilter = document.getElementById('filterLocation').value;
    const rows = document.querySelectorAll('#stockTable tbody tr');
    
    rows.forEach(row => {
        let showRow = true;
        
        // Material filter
        if (materialFilter) {
            const materialCode = row.cells[1].querySelector('strong').textContent;
            const materialSelect = document.getElementById('filterMaterial');
            const selectedText = materialSelect.options[materialSelect.selectedIndex].text;
            if (!selectedText.includes(materialCode)) {
                showRow = false;
            }
        }
        
        // Status filter
        if (statusFilter) {
            const statusBadge = row.cells[6].querySelector('.badge');
            const statusValue = statusBadge.textContent.trim();
            const expectedStatus = statusFilter === 'AVAILABLE' ? 'พร้อมใช้งาน' : 
                                 statusFilter === 'RESERVED' ? 'จองแล้ว' : 
                                 statusFilter === 'USED' ? 'ใช้แล้ว' : statusFilter;
            if (statusValue !== expectedStatus) {
                showRow = false;
            }
        }
        
        // Location filter
        if (locationFilter) {
            const locationText = row.cells[5].textContent;
            const locationSelect = document.getElementById('filterLocation');
            const selectedLocationText = locationSelect.options[locationSelect.selectedIndex].text;
            if (!selectedLocationText.includes(locationText)) {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('filterMaterial').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterLocation').value = '';
    filterTable();
}

function showMaterialDetail(materialCode) {
    // Use AJAX to fetch material-specific stock data
    fetch(`${window.location.origin}${window.location.pathname}?url=materials/stockByMaterial&material_code=${encodeURIComponent(materialCode)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
        }
        
        const filteredLots = data.stockLots || [];
        const materials = <?= json_encode($materials ?? []) ?>;
        
        displayMaterialDetail(materialCode, filteredLots, materials);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการโหลดข้อมูล: ' + error.message);
    });
}

let currentDetailPage = 1;
const detailPerPage = 10;
let allDetailLots = [];

function displayMaterialDetail(materialCode, filteredLots, materials) {
    allDetailLots = filteredLots;
    currentDetailPage = 1;
    
    // Find material name
    const material = materials.find(m => m.material_code === materialCode);
    const materialName = material ? material.material_name : materialCode;
    
    // Calculate summary
    let totalBoxes = filteredLots.length;
    let totalStock = filteredLots.reduce((sum, lot) => sum + parseInt(lot.pack_size || 0), 0);
    let availableStock = filteredLots.filter(lot => lot.status === 'AVAILABLE').reduce((sum, lot) => sum + parseInt(lot.pack_size || 0), 0);
    let reservedStock = filteredLots.filter(lot => lot.status === 'RESERVED').reduce((sum, lot) => sum + parseInt(lot.pack_size || 0), 0);
    
    // Update summary card
    document.getElementById('summaryMaterialName').textContent = materialName;
    document.getElementById('totalBoxes').textContent = totalBoxes.toLocaleString();
    document.getElementById('totalStock').textContent = totalStock.toLocaleString();
    document.getElementById('availableStock').textContent = availableStock.toLocaleString();
    document.getElementById('reservedStock').textContent = reservedStock.toLocaleString();
    
    renderDetailPage(1);
    
    // Show sections
    document.getElementById('selectedMaterial').textContent = materialCode;
    document.getElementById('materialSummaryCard').style.display = 'block';
    document.getElementById('stockDetailSection').style.display = 'block';
    
    // Scroll to summary card
    document.getElementById('materialSummaryCard').scrollIntoView({ behavior: 'smooth' });
}

function renderDetailPage(page) {
    const tbody = document.getElementById('stockDetailBody');
    const startIndex = (page - 1) * detailPerPage;
    const endIndex = startIndex + detailPerPage;
    const pageData = allDetailLots.slice(startIndex, endIndex);
    
    tbody.innerHTML = '';
    
    if (pageData.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td colspan="6" class="text-center text-muted py-4">
                <i class="fas fa-info-circle me-2"></i>ไม่พบข้อมูลสต็อกของวัตถุดิบนี้
            </td>
        `;
        tbody.appendChild(row);
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
        // Previous button
        paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changeDetailPage(${currentPage - 1})">ก่อนหน้า</a>
        </li>`;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage || i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changeDetailPage(${i})">${i}</a>
                </li>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        // Next button
        paginationHtml += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changeDetailPage(${currentPage + 1})">ถัดไป</a>
        </li>`;
    }

    pagination.innerHTML = paginationHtml;
}

function changeDetailPage(page) {
    if (page < 1 || page > Math.ceil(allDetailLots.length / detailPerPage)) return;
    currentDetailPage = page;
    renderDetailPage(page);
}

function hideStockDetail() {
    document.getElementById('materialSummaryCard').style.display = 'none';
    document.getElementById('stockDetailSection').style.display = 'none';
}

</script>

<?php require_once 'views/layouts/footer.php'; ?>