<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar-mazer.php';
?>


<div class="page-content">
    <div class="dashboard-container">
        <!-- Header Section -->
        <section class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">รายละเอียดการรับเข้าวัตถุดิบ</h5>
                    <p class="mb-0 text-muted">เลขที่ใบรับ: <?= htmlspecialchars($receipt['receipt_no']) ?></p>
                </div>
                <div>
                    <button class="btn btn-outline-secondary me-2" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>พิมพ์
                    </button>
                    <a href="<?= BASE_URL ?>?url=materials" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>กลับ
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="receipt-info-grid">
                    <div class="info-item">
                        <div class="info-label">วันที่รับเข้า</div>
                        <div class="info-value"><?= date('d/m/Y', strtotime($receipt['receipt_date'])) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ผู้จำหน่าย</div>
                        <div class="info-value"><?= htmlspecialchars($receipt['supplier_name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">รหัสวัตถุดิบ</div>
                        <div class="info-value"><code class="code-badge"><?= htmlspecialchars($receipt['material_code']) ?></code></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ชื่อวัตถุดิบ</div>
                        <div class="info-value"><?= htmlspecialchars($receipt['material_name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">คลังจัดเก็บ</div>
                        <div class="info-value"><?= htmlspecialchars($receipt['location_name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">หน่วยนับ</div>
                        <div class="info-value"><?= htmlspecialchars($receipt['unit_name']) ?></div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Summary Cards -->
        <section class="kpi-grid mb-4">
            <article class="kpi-card">
                <h3>จำนวนรับเข้า</h3>
                <strong><?= number_format($receipt['received_qty']) ?></strong>
                <span class="kpi-trend neutral"><?= htmlspecialchars($receipt['unit_name']) ?></span>
            </article>
            <article class="kpi-card">
                <h3>จำนวนต่อกล่อง</h3>
                <strong><?= number_format($receipt['packing_qty']) ?></strong>
                <span class="kpi-trend neutral">ชิ้น/กล่อง</span>
            </article>
            <article class="kpi-card">
                <h3>กล่องเต็ม</h3>
                <strong><?= number_format($receipt['full_box_count']) ?></strong>
                <span class="kpi-trend up">กล่อง</span>
            </article>
            <article class="kpi-card">
                <h3>ชิ้นในกล่องสุดท้าย</h3>
                <strong><?= number_format($receipt['partial_box_qty']) ?></strong>
                <span class="kpi-trend neutral">ชิ้น</span>
            </article>
        </section>

        <!-- QR Codes Section -->
        <section class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-qrcode me-2"></i>
                    QR Code สำหรับแต่ละกล่อง (รวม <?= count($receipt['qr_codes'] ?? []) ?> กล่อง)
                </h5>
            </div>
            <div class="card-body">
                <div class="qr-grid" id="qr-codes-container">
                    <?php if (!empty($receipt['qr_codes'])): ?>
                        <?php foreach ($receipt['qr_codes'] as $qr): ?>
                        <div class="qr-card">
                            <div class="qr-code-container" data-qr="<?= htmlspecialchars($qr['qr_code']) ?>">
                                <div class="qr-loading">
                                    <i class="fas fa-spinner"></i>
                                    กำลังสร้าง QR Code...
                                </div>
                            </div>
                            <div class="qr-info">
                                <div class="qr-box-number">กล่องที่ <?= $qr['pack_no'] ?></div>
                                <div class="qr-quantity"><?= $qr['pack_size'] ?> ชิ้น</div>
                                <div class="qr-code-text"><?= htmlspecialchars($qr['qr_code']) ?></div>
                                <span class="badge <?= strtolower($qr['status']) === 'available' ? 'bg-info' : (strtolower($qr['status']) === 'used' ? 'bg-danger' : 'bg-warning') ?>">
                                    <?= $qr['status'] ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">ไม่มีข้อมูล QR Code</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrContainers = document.querySelectorAll('.qr-code-container');
    
    qrContainers.forEach((container, index) => {
        const qrData = container.getAttribute('data-qr');
        
        setTimeout(() => {
            try {
                container.innerHTML = '';
                
                // Create QR Code using qrcode-generator library
                const qr = qrcode(0, 'M');
                qr.addData(qrData);
                qr.make();
                
                // Create canvas
                const canvas = document.createElement('canvas');
                const size = 120;
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                
                // Draw QR code
                const moduleCount = qr.getModuleCount();
                const cellSize = size / moduleCount;
                
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, size, size);
                
                ctx.fillStyle = '#000000';
                for (let row = 0; row < moduleCount; row++) {
                    for (let col = 0; col < moduleCount; col++) {
                        if (qr.isDark(row, col)) {
                            ctx.fillRect(col * cellSize, row * cellSize, cellSize, cellSize);
                        }
                    }
                }
                
                container.appendChild(canvas);
                
                // Add hover effects
                container.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                    this.style.transition = 'transform 0.2s ease';
                });
                
                container.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
                
            } catch (error) {
                console.error('Error generating QR code for', qrData, ':', error);
                container.innerHTML = `
                    <div class="text-danger text-center p-3">
                        <i class="fas fa-exclamation-triangle mb-2"></i><br>
                        <small>ไม่สามารถสร้าง QR Code ได้</small><br>
                        <small class="text-muted">ข้อมูล: ${qrData}</small>
                    </div>
                `;
            }
        }, index * 50);
    });
});

// ฟังก์ชันสำหรับพิมพ์
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});

// เพิ่มฟังก์ชัน copy QR code text
function copyQRCode(qrText) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(qrText).then(() => {
            showToast('คัดลอกรหัส QR Code แล้ว', 'success');
        }).catch(err => {
            console.error('ไม่สามารถคัดลอกได้:', err);
            showToast('ไม่สามารถคัดลอกได้', 'error');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = qrText;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('คัดลอกรหัส QR Code แล้ว', 'success');
        } catch (err) {
            showToast('ไม่สามารถคัดลอกได้', 'error');
        }
        document.body.removeChild(textArea);
    }
}

// ฟังก์ชันแสดง toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px; opacity: 0.95;';
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// เพิ่ม click event ให้ QR code text
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('qr-code-text')) {
        copyQRCode(e.target.textContent);
    }
});
</script>



<?php require_once 'views/layouts/footer.php'; ?>