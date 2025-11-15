<?php require_once 'views/layouts/header.php'; ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>รายละเอียดวัตถุดิบ</h3>
                <p class="text-subtitle text-muted">ข้อมูลรายละเอียดของวัตถุดิบ</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">หน้าหลัก</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?url=materials">วัตถุดิบ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">รายละเอียด</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">ข้อมูลวัตถุดิบ</h4>
                    <div>
                        <a href="<?= BASE_URL ?>?url=materials/edit/<?= $material['material_id'] ?>" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>แก้ไข
                        </a>
                        <a href="<?= BASE_URL ?>?url=materials" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="150">รหัสวัตถุดิบ:</td>
                                    <td><code class="code-badge"><?= htmlspecialchars($material['material_code']) ?></code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">ชื่อวัตถุดิบ:</td>
                                    <td><?= htmlspecialchars($material['material_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">หน่วยนับ:</td>
                                    <td><?= htmlspecialchars($material['unit_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">คลังจัดเก็บ:</td>
                                    <td><?= htmlspecialchars($material['location_name'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="150">สถานะ:</td>
                                    <td>
                                        <span class="badge <?= $material['is_active'] == 1 ? 'bg-success' : 'bg-warning' ?>">
                                            <?= $material['is_active'] == 1 ? 'ใช้งาน' : 'ปิดใช้งาน' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">วันที่สร้าง:</td>
                                    <td><?= isset($material['created_at']) ? date('d/m/Y H:i', strtotime($material['created_at'])) : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">วันที่แก้ไข:</td>
                                    <td><?= isset($material['updated_at']) ? date('d/m/Y H:i', strtotime($material['updated_at'])) : 'N/A' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($material['description'])): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="fw-bold">รายละเอียด:</h6>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($material['description'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stock Information -->
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">ข้อมูลสต็อก</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-primary mb-1"><?= $stockInfo['total_stock'] ?? 0 ?></h4>
                                <small class="text-muted">สต็อกทั้งหมด</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-success mb-1"><?= $stockInfo['available_stock'] ?? 0 ?></h4>
                                <small class="text-muted">สต็อกพร้อมใช้</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-warning mb-1"><?= $stockInfo['reserved_stock'] ?? 0 ?></h4>
                                <small class="text-muted">สต็อกจอง</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-info mb-1"><?= $stockInfo['total_lots'] ?? 0 ?></h4>
                                <small class="text-muted">จำนวน Lot</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Transactions -->
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">ประวัติการเคลื่อนไหวล่าสุด</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>ประเภท</th>
                                    <th>จำนวน</th>
                                    <th>หมายเหตุ</th>
                                    <th>ผู้ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTransactions as $transaction): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                                    <td>
                                        <span class="badge <?= $transaction['type'] == 'IN' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $transaction['type'] == 'IN' ? 'รับเข้า' : 'เบิกออก' ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($transaction['quantity']) ?></td>
                                    <td><?= htmlspecialchars($transaction['reference_no'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($transaction['created_by_name'] ?? 'N/A') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">ยังไม่มีประวัติการเคลื่อนไหว</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.code-badge {
    background-color: #f8f9fa;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>