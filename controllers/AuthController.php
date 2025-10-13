<?php

class AuthController extends Controller {
    
    public function login() {
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // ตรวจสอบข้อมูล login (ตัวอย่างง่ายๆ)
            if ($this->authenticate($username, $password)) {
                header('Location: /cps/?url=dashboard');
                exit;
            } else {
                $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                $this->view('auth/login-modern', ['error' => $error]);
            }
        } else {
            // ถ้า login แล้วให้ redirect ไป dashboard
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
                header('Location: /cps/?url=dashboard');
                exit;
            }
            
            $this->view('auth/login-modern');
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: /cps/?url=login');
        exit;
    }
    
    private function authenticate($username, $password) {
        $userModel = $this->model('User');
        $user = $userModel->authenticate($username, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['roles'] = explode(',', $user['roles'] ?? 'user');
            $_SESSION['logged_in'] = true;
            $_SESSION['expires'] = time() + (1 * 60 * 60); // 1 hour
            
            return true;
        }
        
        return false;
    }
}