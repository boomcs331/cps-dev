<nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>?url=dashboard">
            <i class="fas fa-shield-alt me-2"></i>
            ระบบจัดการ CPS
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php echo MenuHelper::renderMenu(); ?>
            </ul>
            
            <div class="navbar-nav">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="navbar-text me-3">
                        <i class="fas fa-user me-1"></i>
                        สวัสดี, <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้') ?>
                    </span>
                    <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>?url=logout">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        ออกจากระบบ
                    </a>
                <?php else: ?>
                    <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>?url=login">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        เข้าสู่ระบบ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>