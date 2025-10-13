<?php
/**
 * Base Controller Class
 * คลาสหลักที่ controller อื่นๆ จะ inherit จาก
 */
class Controller
{
    protected $data = [];

    /**
     * โหลด model
     */
    protected function model($model)
    {
        $model_file = MODELS_PATH . $model . '.php';
        
        if (file_exists($model_file)) {
            require_once $model_file;
            return new $model();
        } else {
            throw new Exception("Model {$model} not found");
        }
    }

    /**
     * โหลด view
     */
    protected function view($view, $data = [])
    {
        $view_file = VIEWS_PATH . $view . '.php';
        
        if (file_exists($view_file)) {
            // เพิ่มข้อมูล user ปัจจุบันเข้าไปใน data
            $data['current_user'] = $this->getCurrentUser();
            
            // แปลง data array เป็นตัวแปร
            extract($data);
            
            // เริ่มต้น output buffering
            ob_start();
            
            // include view file
            require_once $view_file;
            
            // ดึง content และล้าง buffer
            $content = ob_get_clean();
            
            // แสดงผล
            echo $content;
        } else {
            throw new Exception("View {$view} not found");
        }
    }

    /**
     * redirect ไปยัง URL อื่น
     */
    protected function redirect($url)
    {
        header("Location: " . BASE_URL . $url);
        exit();
    }

    /**
     * ส่ง JSON response
     */
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * ดึง POST data
     */
    protected function getPost($key = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     * ดึง GET data
     */
    protected function getGet($key = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    /**
     * ตรวจสอบว่าเป็น POST request หรือไม่
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * ตรวจสอบว่าเป็น GET request หรือไม่
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * ตรวจสอบว่า user login แล้วหรือไม่
     */
    protected function requireAuth()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || 
            !isset($_SESSION['expires']) || $_SESSION['expires'] <= time()) {
            $this->redirect('/login');
        }
    }

    /**
     * ดึงข้อมูล user ที่ login
     */
    protected function getCurrentUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            return [
                'user_id' => $_SESSION['user_id'],
                'user_name' => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : $_SESSION['user_id'],
                'user_email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '',
                'id' => isset($_SESSION['user_db_id']) ? $_SESSION['user_db_id'] : null, // Database ID for foreign keys
                'role' => isset($_SESSION['role']) ? $_SESSION['role'] : 'user',
                'roles' => isset($_SESSION['roles']) ? $_SESSION['roles'] : ['user'],
                'login_time' => isset($_SESSION['login_time']) ? $_SESSION['login_time'] : null
            ];
        }
        
        return null;
    }

    /**
     * ตรวจสอบว่า user login แล้วหรือไม่ (return boolean)
     */
    protected function isAuthenticated()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && 
               isset($_SESSION['expires']) && $_SESSION['expires'] > time();
    }
    
    /**
     * ตรวจสอบสิทธิ์ของผู้ใช้
     */
    protected function requirePermission($allowed_roles = [])
    {
        // ตรวจสอบ authentication ก่อน
        $this->requireAuth();
        
        // ตรวจสอบสิทธิ์
        $current_user = $this->getCurrentUser();
        if (!$current_user) {
            $this->view('error/access_denied');
            exit();
        }
        
        // Check if user has any of the allowed roles
        $user_roles = $current_user['roles'];
        $has_permission = false;
        
        foreach ($allowed_roles as $allowed_role) {
            if (in_array($allowed_role, $user_roles)) {
                $has_permission = true;
                break;
            }
        }
        
        if (!$has_permission) {
            // ถ้าไม่มีสิทธิ์ ให้แสดงหน้า error
            $this->view('error/access_denied');
            exit();
        }
    }
}
?> 