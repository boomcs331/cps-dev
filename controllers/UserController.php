<?php

class UserController extends Controller {
    
    public function index() {
        SessionManager::checkSession();
        
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        
        $userModel = $this->model('User');
        $users = $userModel->getAllUsersWithPagination($search, $page, $perPage);
        $totalRecords = $userModel->getTotalUsers($search);
        $roles = $userModel->getAllRoles();
        
        $data = [
            'users' => $users,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords,
            'search' => $search,
            'roles' => $roles
        ];
        
        $this->view('users/index', $data);
    }
    
    public function create() {
        // ตรวจสอบสิทธิ์
        if (!$this->checkPermission('user.create')) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์ในการเพิ่มผู้ใช้']);
                exit;
            }
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $roles = $_POST['roles'] ?? [];
            
            $userModel = $this->model('User');
            
            // ตรวจสอบข้อมูล
            $errors = [];
            if (empty($username)) $errors[] = 'กรุณากรอกชื่อผู้ใช้';
            if (empty($email)) $errors[] = 'กรุณากรอกอีเมล';
            if (empty($password)) $errors[] = 'กรุณากรอกรหัสผ่าน';
            if (empty($full_name)) $errors[] = 'กรุณากรอกชื่อเต็ม';
            
            if (!empty($errors)) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
                exit;
            }
            
            $result = $userModel->createUser($username, $email, $password, $full_name, $roles);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'เพิ่มผู้ใช้เรียบร้อยแล้ว']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้']);
            }
            exit;
        } else {
            $userModel = $this->model('User');
            $roles = $userModel->getAllRoles();
            $this->view('users/create', ['roles' => $roles]);
        }
    }
    
    public function edit($id) {
        // ตรวจสอบสิทธิ์
        if (!$this->checkPermission('user.edit')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }
        
        $userModel = $this->model('User');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $roles = $_POST['roles'] ?? [];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $errors = [];
            if (empty($username)) $errors[] = 'กรุณากรอกชื่อผู้ใช้';
            if (empty($email)) $errors[] = 'กรุณากรอกอีเมล';
            if (empty($full_name)) $errors[] = 'กรุณากรอกชื่อเต็ม';
            
            if (empty($errors)) {
                $result = $userModel->updateUser($id, $username, $email, $full_name, $roles, $is_active);
                if ($result) {
                    $_SESSION['success'] = 'แก้ไขข้อมูลผู้ใช้เรียบร้อยแล้ว';
                    header('Location: ' . BASE_URL . '?url=users');
                    exit;
                } else {
                    $errors[] = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
                }
            }
            
            $user = $userModel->getUserById($id);
            $roles_list = $userModel->getAllRoles();
            $user_roles = $userModel->getUserRoles($id);
            $this->view('users/edit', [
                'user' => $user, 
                'roles' => $roles_list, 
                'user_roles' => $user_roles,
                'errors' => $errors
            ]);
        } else {
            $user = $userModel->getUserById($id);
            if (!$user) {
                header('Location: ' . BASE_URL . '?url=users');
                exit;
            }
            
            $roles = $userModel->getAllRoles();
            $user_roles = $userModel->getUserRoles($id);
            $this->view('users/edit', [
                'user' => $user, 
                'roles' => $roles, 
                'user_roles' => $user_roles
            ]);
        }
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $roles = $_POST['roles'] ?? [];
            
            $userModel = $this->model('User');
            
            if ($userModel->createUser($username, $email, $password, $full_name, $roles)) {
                header('Location: ' . BASE_URL . '?url=users&success=1');
            } else {
                header('Location: ' . BASE_URL . '?url=users&error=1');
            }
            exit;
        }
    }
    
    public function get() {
        if (isset($_GET['id'])) {
            $userModel = $this->model('User');
            $user = $userModel->getUserById($_GET['id']);
            $userRoles = $userModel->getUserRoles($_GET['id']);
            
            $user['roles'] = $userRoles;
            
            header('Content-Type: application/json');
            echo json_encode($user);
            exit;
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $roles = $_POST['roles'] ?? [];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $userModel = $this->model('User');
            
            if ($userModel->updateUser($id, $username, $email, $full_name, $roles, $is_active)) {
                header('Location: ' . BASE_URL . '?url=users&updated=1');
            } else {
                header('Location: ' . BASE_URL . '?url=users&error=1');
            }
            exit;
        }
    }
    
    public function delete() {
        if (isset($_GET['id'])) {
            $userModel = $this->model('User');
            
            if ($userModel->deleteUser($_GET['id'])) {
                header('Location: ' . BASE_URL . '?url=users&deleted=1');
            } else {
                header('Location: ' . BASE_URL . '?url=users&error=1');
            }
            exit;
        }
    }
    
    private function checkPermission($permission) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $userModel = $this->model('User');
        return $userModel->hasPermission($_SESSION['user_id'], $permission);
    }
}