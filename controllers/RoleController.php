<?php

class RoleController extends Controller {
    
    public function index() {
        SessionManager::checkSession();
        
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        
        $roleModel = $this->model('Role');
        $roles = $roleModel->getAllRoles($search, $page, $perPage);
        $totalRecords = $roleModel->getTotalRoles($search);
        
        $data = [
            'roles' => $roles,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords,
            'search' => $search
        ];
        
        $this->view('roles/index', $data);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $display_name = $_POST['display_name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            $roleModel = $this->model('Role');
            
            if ($roleModel->createRole($name, $display_name, $description)) {
                header('Location: ' . BASE_URL . '?url=roles&success=1');
            } else {
                header('Location: ' . BASE_URL . '?url=roles&error=1');
            }
            exit;
        }
    }
    
    public function get() {
        if (isset($_GET['id'])) {
            $roleModel = $this->model('Role');
            $role = $roleModel->getRoleById($_GET['id']);
            
            header('Content-Type: application/json');
            echo json_encode($role);
            exit;
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $display_name = $_POST['display_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $permissions = $_POST['permissions'] ?? [];
            
            $roleModel = $this->model('Role');
            
            if ($roleModel->updateRole($id, $name, $display_name, $description, $is_active)) {
                $roleModel->updateRolePermissions($id, $permissions);
                header('Location: ' . BASE_URL . '?url=roles&updated=1');
            } else {
                header('Location: ' . BASE_URL . '?url=roles&error=1');
            }
            exit;
        }
    }
    
    public function permissions() {
        if (isset($_GET['id'])) {
            $roleModel = $this->model('Role');
            $role = $roleModel->getRoleById($_GET['id']);
            $permissions = $roleModel->getAllPermissions();
            $rolePermissions = $roleModel->getRolePermissions($_GET['id']);
            
            $data = [
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $rolePermissions
            ];
            
            $this->view('roles/permissions', $data);
        }
    }
    
    public function updatePermissions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roleId = $_POST['role_id'] ?? '';
            $permissions = $_POST['permissions'] ?? [];
            
            $roleModel = $this->model('Role');
            
            if ($roleModel->updateRolePermissions($roleId, $permissions)) {
                header('Location: ' . BASE_URL . '?url=roles&updated=1');
            } else {
                header('Location: ' . BASE_URL . '?url=roles&error=1');
            }
            exit;
        }
    }
    
    public function delete() {
        if (isset($_GET['id'])) {
            $roleModel = $this->model('Role');
            
            if ($roleModel->deleteRole($_GET['id'])) {
                header('Location: ' . BASE_URL . '?url=roles&deleted=1');
            } else {
                header('Location: ' . BASE_URL . '?url=roles&error=1');
            }
            exit;
        }
    }
}