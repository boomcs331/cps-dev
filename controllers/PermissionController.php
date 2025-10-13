<?php

class PermissionController extends Controller {
    
    public function index() {
        SessionManager::checkSession();
        SessionManager::extendSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.view')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }

        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 5);
        $search = $_GET['search'] ?? '';
        
        $permissions = $permissionModel->getAllPermissions($page, $perPage, $search);
        $totalRecords = $permissionModel->getTotalPermissions($search);
        $modules = $permissionModel->getDistinctModules();
        
        $data = [
            'permissions' => $permissions,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords,
            'modules' => $modules,
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้'
        ];
        
        $this->view('permissions/index', $data);
    }

    public function create() {
        SessionManager::checkSession();
        SessionManager::extendSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.manage')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }

        $data = [
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้'
        ];
        
        $this->view('permissions/create', $data);
    }

    public function store() {
        SessionManager::checkSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.manage')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $display_name = $_POST['display_name'];
            $description = $_POST['description'];
            $module = $_POST['module'];

            $result = $permissionModel->createPermission($name, $display_name, $description, $module);

            if ($result) {
                header('Location: ' . BASE_URL . '?url=permissions&success=1');
            } else {
                header('Location: ' . BASE_URL . '?url=permissions/create&error=1');
            }
            exit;
        }
    }

    public function edit() {
        SessionManager::checkSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.manage')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $permission = $permissionModel->getPermissionById($id);

        if (!$permission) {
            header('Location: ' . BASE_URL . '?url=permissions');
            exit;
        }

        $data = [
            'permission' => $permission,
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้'
        ];
        
        $this->view('permissions/edit', $data);
    }

    public function update() {
        SessionManager::checkSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.manage')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $display_name = $_POST['display_name'];
            $description = $_POST['description'];
            $module = $_POST['module'];

            $result = $permissionModel->updatePermission($id, $name, $display_name, $description, $module);

            if ($result) {
                header('Location: ' . BASE_URL . '?url=permissions&updated=1');
            } else {
                header('Location: ' . BASE_URL . '?url=permissions/edit&id=' . $id . '&error=1');
            }
            exit;
        }
    }

    public function get() {
        SessionManager::checkSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.view')) {
            http_response_code(403);
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $permission = $permissionModel->getPermissionById($id);
        
        header('Content-Type: application/json');
        echo json_encode($permission);
        exit;
    }

    public function delete() {
        SessionManager::checkSession();
        
        $permissionModel = $this->model('Permission');
        if (!$permissionModel->hasPermission($_SESSION['user_id'], 'permission.manage')) {
            header('Location: ' . BASE_URL . '?url=dashboard');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $result = $permissionModel->deletePermission($id);

        if ($result) {
            header('Location: ' . BASE_URL . '?url=permissions&deleted=1');
        } else {
            header('Location: ' . BASE_URL . '?url=permissions&error=1');
        }
        exit;
    }
}
