<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - CPS</title>
    <style>
        :root {
            color-scheme: light dark;
            --bg: #f4f6fb;
            --card-bg: #ffffff;
            --accent: #2563eb;
            --accent-weak: rgba(37, 99, 235, 0.15);
            --text: #1f2933;
            --muted: #6c7a89;
            --danger: #b91c1c;
            --danger-weak: rgba(220, 38, 38, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(37, 99, 235, 0.08), transparent 50%),
                radial-gradient(circle at 80% 0%, rgba(37, 99, 235, 0.12), transparent 55%);
            padding: 32px 16px;
        }

        .login-card {
            width: min(420px, 92vw);
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(31, 41, 51, 0.12);
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .card-header {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .brand {
            font-size: 14px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 600;
        }

        .headline {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
        }

        .subtext {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .input-wrapper {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        input {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(31, 41, 51, 0.15);
            font-size: 15px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-weak);
        }

        button {
            padding: 14px 18px;
            border-radius: 12px;
            border: none;
            background-color: var(--accent);
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
        }

        .error-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 12px;
            background: var(--danger-weak);
            color: var(--danger);
            font-size: 14px;
        }

        .test-accounts {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 16px;
            border-radius: 14px;
            background: rgba(37, 99, 235, 0.06);
        }

        .test-accounts h6 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .account-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid rgba(31, 41, 51, 0.08);
            cursor: pointer;
            font-size: 14px;
            transition: border-color 0.2s ease, transform 0.1s ease;
        }

        .account-item:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .account-item span {
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
            background: rgba(37, 99, 235, 0.12);
            padding: 4px 10px;
            border-radius: 999px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 24px;
            }

            .headline {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="card-header">
            <span class="brand">CPS Admin</span>
            <h1 class="headline">เข้าสู่ระบบเพื่อจัดการโรงงาน</h1>
            <p class="subtext">เข้าสู่แผงควบคุมเพื่อจัดการข้อมูล ผู้ใช้ และสิทธิ์การใช้งาน</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-banner">
                <strong>ไม่สามารถเข้าสู่ระบบได้</strong>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo BASE_URL ?>/login" id="loginForm">
            <div class="input-wrapper">
                <label for="username">ชื่อผู้ใช้</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="ชื่อผู้ใช้ของคุณ"
                    required
                    autocomplete="username">
            </div>

            <div class="input-wrapper">
                <label for="password">รหัสผ่าน</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password">
            </div>

            <button type="submit">เข้าสู่ระบบ</button>
        </form>

        <div class="test-accounts">
            <h6>บัญชีทดสอบ</h6>
            <div class="account-item" onclick="fillLogin('admin', 'password')">
                <span>Super Admin</span>
                <strong>admin / password</strong>
            </div>
            <div class="account-item" onclick="fillLogin('manager', 'password')">
                <span>Manager</span>
                <strong>manager / password</strong>
            </div>
            <div class="account-item" onclick="fillLogin('user1', 'password')">
                <span>User</span>
                <strong>user1 / password</strong>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('password').focus();
        }

        document.getElementById('loginForm').addEventListener('submit', function () {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.textContent = 'กำลังตรวจสอบ...';
        });
    </script>
</body>

</html>