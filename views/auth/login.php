<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - CPS</title>
    <link href="<?= BASE_URL ?>lib/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>lib/css/login-new.css" rel="stylesheet">
</head>

<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-card">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>ระบบจัดการ CPS</h2>
            <p class="mb-0 opacity-75">ยินดีต้อนรับเข้าสู่ระบบ</p>
        </div>

        <div class="login-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login" id="loginForm">
                <div class="form-floating">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder=" " required autocomplete="username">
                    <div class="input-wave"></div>
                    <label class="form-label">ชื่อผู้ใช้</label>
                </div>

                <div class="form-floating">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder=" " required autocomplete="current-password">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                    <div class="input-wave"></div>
                    <label class="form-label">รหัสผ่าน</label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    เข้าสู่ระบบ
                </button>
            </form>

            <div class="test-accounts">
                <h6><i class="fas fa-users me-2"></i>บัญชีทดสอบ</h6>
                <div class="account-item" onclick="fillLogin('admin', 'password')">
                    <strong>admin</strong> / password <span class="badge bg-danger ms-2">Super Admin</span>
                </div>
                <div class="account-item" onclick="fillLogin('manager', 'password')">
                    <strong>manager</strong> / password <span class="badge bg-warning ms-2">Manager</span>
                </div>
                <div class="account-item" onclick="fillLogin('user1', 'password')">
                    <strong>user1</strong> / password <span class="badge bg-info ms-2">User</span>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>lib/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillLogin(username, password) {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');

            // Typing animation
            typeAnimation(usernameInput, username, () => {
                typeAnimation(passwordInput, password, () => {
                    // Glow effect after filling
                    document.querySelectorAll('.form-floating').forEach(el => {
                        el.querySelector('.form-control').classList.add('glow');
                        setTimeout(() => {
                            el.querySelector('.form-control').classList.remove('glow');
                        }, 800);
                    });
                });
            });
        }

        function typeAnimation(input, text, callback) {
            input.value = '';
            input.focus();
            let i = 0;
            const timer = setInterval(() => {
                input.value += text[i];
                i++;
                if (i >= text.length) {
                    clearInterval(timer);
                    if (callback) setTimeout(callback, 200);
                }
            }, 80);
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');

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

        // Add input validation effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('invalid', function() {
                this.parentElement.classList.add('shake');
                setTimeout(() => {
                    this.parentElement.classList.remove('shake');
                }, 500);
            });

            // Handle autofill detection
            input.addEventListener('animationstart', function(e) {
                if (e.animationName === 'onAutoFillStart') {
                    this.classList.add('autofilled');
                }
            });

            input.addEventListener('input', function() {
                if (this.value !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังเข้าสู่ระบบ...';
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    </script>
</body>

</html>