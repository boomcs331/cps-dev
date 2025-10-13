<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - CPS</title>
    <link href="<?= BASE_URL ?>lib/css/login-modern.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-container">
        <div class="login-header">
            <h1 class="login-title">ยินดีต้อนรับ</h1>
            <p class="login-subtitle">เข้าสู่ระบบจัดการ CPS</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>login" id="loginForm" autocomplete="off">
            <div class="form-group">
                <input type="text" class="form-input" id="username" name="username"
                    placeholder=" " required autocomplete="off">
                <label class="form-label">ชื่อผู้ใช้</label>
            </div>

            <div class="form-group">
                <input type="password" class="form-input" id="password" name="password"
                    placeholder=" " required autocomplete="new-password">
                <label class="form-label">รหัสผ่าน</label>
                <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
            </div>

            <button type="submit" class="login-btn">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="test-accounts">
            <h6>บัญชีทดสอบ</h6>
            <button class="account-btn" onclick="fillLogin('admin', 'password')">
                <strong>admin</strong> / password
                <span class="badge badge-admin">Admin</span>
            </button>
            <button class="account-btn" onclick="fillLogin('manager', 'password')">
                <strong>manager</strong> / password
                <span class="badge badge-manager">Manager</span>
            </button>
            <button class="account-btn" onclick="fillLogin('user1', 'password')">
                <strong>user1</strong> / password
                <span class="badge badge-user">User</span>
            </button>
        </div>
    </div>
    </div>

    <script>
        function fillLogin(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = this.querySelector('.login-btn');
            const originalText = button.innerHTML;

            button.innerHTML = 'กำลังเข้าสู่ระบบ...';
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    </script>
</body>

</html>