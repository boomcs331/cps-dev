<?php
header('Content-Type: text/html; charset=utf-8');
$receipts = $receipts ?? [];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รับเข้าวัตถุดิบ - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/pagination.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/receipt.css">
</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">รับเข้าวัตถุดิบ</h2>
                    <p class="text-muted">จัดการการรับเข้าวัตถุดิบและติดตามสต็อก</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
                        <i class="fas fa-plus me-2"></i>บันทึกรับเข้า
                    </button>
                    <a href="<?= BASE_URL ?>?url=materials" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <section class="kpi-grid mb-4">
                <article class="kpi-card">
                    <h3>รายการรับเข้าวันนี้</h3>
                    <strong><?= count(array_filter($receipts, fn($r) => date('Y-m-d', strtotime($r['receipt_date'])) == date('Y-m-d'))) ?></strong>
                    <span class="kpi-trend neutral">รายการ</span>
                </article>
                <article class="kpi-card">
                    <h3>มูลค่ารวมวันนี้</h3>
                    <strong><?= number_format(array_sum(array_map(fn($r) => date('Y-m-d', strtotime($r['receipt_date'])) == date('Y-m-d') ? $r['total_amount'] : 0, $receipts)), 2) ?></strong>
                    <span class="kpi-trend up">บาท</span>
                </article>
                <article class="kpi-card">
                    <h3>รายการทั้งหมด</h3>
                    <strong><?= $totalRecords ?></strong>
                    <span class="kpi-trend neutral">รายการ</span>
                </article>
                <article class="kpi-card">
                    <h3>ผู้จำหน่ายที่ใช้</h3>
                    <strong><?= count(array_unique(array_column($receipts, 'supplier_name'))) ?></strong>
                    <span class="kpi-trend neutral">ราย</span>
                </article>
            </section>

            <!-- Receipts Table -->
            <section class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">รายการรับเข้าวัตถุดิบ</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>วันที่รับ</th>
                                <th>รหัสวัตถุดิบ</th>
                                <th>ชื่อวัตถุดิบ</th>
                                <th>จำนวน</th>
                                <th>ราคาต่อหน่วย</th>
                                <th>มูลค่ารวม</th>
                                <th>ผู้จำหน่าย</th>
                                <th>เลขที่อ้างอิง</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($receipts)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">ยังไม่มีข้อมูลการรับเข้าวัตถุดิบ</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($receipts as $receipt): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <strong><?= date('d/m/Y', strtotime($receipt['receipt_date'])) ?></strong><br>
                                                <small class="text-muted"><?= date('H:i', strtotime($receipt['created_at'])) ?></small>
                                            </div>
                                        </td>
                                        <td><code class="code-badge"><?= htmlspecialchars($receipt['material_code']) ?></code></td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($receipt['material_name']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($receipt['location_name'] ?? 'N/A') ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= number_format($receipt['quantity'], 2) ?> <?= htmlspecialchars($receipt['unit_name'] ?? '') ?></span>
                                        </td>
                                        <td><?= number_format($receipt['unit_price'], 2) ?> บาท</td>
                                        <td><strong><?= number_format($receipt['total_amount'], 2) ?> บาท</strong></td>
                                        <td><?= htmlspecialchars($receipt['supplier_name']) ?></td>
                                        <td>
                                            <?php if (!empty($receipt['reference_no'])): ?>
                                                <code><?= htmlspecialchars($receipt['reference_no']) ?></code>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewReceipt(<?= $receipt['receipt_id'] ?>)" title="ดูรายละเอียด">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="editReceipt(<?= $receipt['receipt_id'] ?>)" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteReceipt(<?= $receipt['receipt_id'] ?>)" title="ลบ">
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

                <!-- Pagination -->
                <?php
                require_once 'helpers/PaginationHelper.php';
                echo PaginationHelper::render($currentPage, $totalRecords, $perPage);
                ?>
            </section>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>


        // Form submission
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

        function editReceipt(id) {
            Swal.fire({
                title: 'แก้ไขการรับเข้า',
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