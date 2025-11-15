<?php require_once 'views/layouts/header.php'; ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>แก้ไขวัตถุดิบ</h3>
                <p class="text-subtitle text-muted">แก้ไขข้อมูลวัตถุดิบในระบบ</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">หน้าหลัก</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?url=materials">วัตถุดิบ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไข</li>
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
                <div class="card-header">
                    <h4 class="card-title">แก้ไขข้อมูลวัตถุดิบ</h4>
                </div>
                <div class="card-body">
                    <form id="editMaterialForm" method="POST">
                        <input type="hidden" name="material_id" value="<?= htmlspecialchars($material['material_id']) ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_code" class="form-label">รหัสวัตถุดิบ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="material_code" name="material_code" 
                                           value="<?= htmlspecialchars($material['material_code']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_name" class="form-label">ชื่อวัตถุดิบ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="material_name" name="material_name" 
                                           value="<?= htmlspecialchars($material['material_name'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_unit" class="form-label">หน่วยนับ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="default_unit" name="default_unit" required>
                                        <option value="">เลือกหน่วยนับ</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['unit_id'] ?>" 
                                                    <?= $unit['unit_id'] == $material['default_unit'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($unit['unit_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location_id" class="form-label">คลังจัดเก็บ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="location_id" name="location_id" required>
                                        <option value="">เลือกคลังจัดเก็บ</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location['location_id'] ?>" 
                                                    <?= $location['location_id'] == $material['location_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($location['location_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active" class="form-label">สถานะ</label>
                                    <select class="form-select" id="is_active" name="is_active">
                                        <option value="1" <?= $material['is_active'] == 1 ? 'selected' : '' ?>>ใช้งาน</option>
                                        <option value="0" <?= $material['is_active'] == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">รายละเอียด</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="รายละเอียดเพิ่มเติม"><?= htmlspecialchars($material['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-1"></i>บันทึกการแก้ไข
                            </button>
                            <a href="<?= BASE_URL ?>?url=materials" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>ยกเลิก
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.getElementById('editMaterialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังบันทึก...';
    
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
                text: 'แก้ไขข้อมูลวัตถุดิบเรียบร้อยแล้ว',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                window.location.href = '<?= BASE_URL ?>?url=materials';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: data.message || 'ไม่สามารถแก้ไขข้อมูลได้',
                confirmButtonText: 'ตกลง'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
            confirmButtonText: 'ตกลง'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>