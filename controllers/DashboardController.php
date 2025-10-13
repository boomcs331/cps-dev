<?php

class DashboardController extends Controller {
    
    public function index() {
        // ตรวจสอบ session
        SessionManager::checkSession();
        SessionManager::extendSession();
        
        // ตรวจสอบสิทธิ์ dashboard
        $userModel = $this->model('User');
        // if (!$userModel->hasPermission($_SESSION['user_id'], 'dashboard.view')) {
        //     header('Location: /cps/?url=login');
        //     exit;
        // }
        
        // ดึงสิทธิ์ของผู้ใช้
        $permissions = $userModel->getUserPermissions($_SESSION['user_id']);
        $userPermissions = array_column($permissions, 'name');
        
        $data = [
            'username' => $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้',
            'roles' => $_SESSION['roles'] ?? ['user'],
            'permissions' => $userPermissions,
            'user_id' => $_SESSION['user_id']
        ];
        
        $this->view('dashboard/index', $data);
    }
        public function test() {
        
        $this->view('dashboard/test');
    }
}