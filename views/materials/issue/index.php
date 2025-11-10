<?php
header('Content-Type: text/html; charset=utf-8');
$issues = $issues ?? [];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จ่ายออกวัตถุดิบ - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/pagination.css">
</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">จ่ายออกวัตถุดิบ</h2>
                    <p class="text-muted">จัดการการจ่ายออกวัตถุดิบเพื่อการผลิต</p>
                </div>
                <div>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addIssueModal">
                        <i class="fas fa-minus me-2"></i>บันทึกจ่ายออก
                    </button>
                    <a href="<?= BASE_URL ?>?url=materials" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                </div>
            </div>

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
                    <strong><?= $totalRecords ?? 0 ?></strong>
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
                <div class="card-header">
                    <h5 class="card-title mb-0">รายการจ่ายออกวัตถุดิบ</h5>
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
                                        <?php foreach ($materials ?? [] as $material): ?>
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

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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