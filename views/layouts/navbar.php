<nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container">
        <a class="navbar-brand" href="/cps/?url=dashboard">
            <i class="fas fa-shield-alt me-2"></i>
            ระบบจัดการ CPS
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/cps/?url=dashboard">
                        <i class="fas fa-home me-1"></i>หน้าหลัก
                    </a>
                </li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php 
                    $userModel = new User();
                    $userPermissions = array_column($userModel->getUserPermissions($_SESSION['user_id']), 'name');
                    ?>
                    
                    <?php if (in_array('user.view', $userPermissions)): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/cps/?url=users">
                            <i class="fas fa-users me-1"></i>จัดการผู้ใช้
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array('role.view', $userPermissions)): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/cps/?url=roles">
                            <i class="fas fa-user-tag me-1"></i>จัดการบทบาท
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array('system.admin', $userPermissions)): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/cps/?url=settings">
                            <i class="fas fa-cog me-1"></i>ตั้งค่าระบบ
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            
            <div class="navbar-nav">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="navbar-text me-3">
                        <i class="fas fa-user me-1"></i>
                        สวัสดี, <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้') ?>
                    </span>
                    <a class="btn btn-outline-light btn-sm" href="/cps/?url=logout">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        ออกจากระบบ
                    </a>
                <?php else: ?>
                    <a class="btn btn-outline-light btn-sm" href="/cps/?url=login">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        เข้าสู่ระบบ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>