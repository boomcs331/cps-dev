<?php

class MenuHelper
{
    public static function getMenuItems()
    {
        return [
            [
                'title' => 'หน้าหลัก',
                'url' => BASE_URL . 'dashboard',
                'icon' => 'fas fa-home'
            ],
            [
                'title' => 'Part Control',
                'url' => BASE_URL . 'pc',
                'icon' => 'fas fa-industry'
            ],
            [
                'title' => 'จัดการวัสดุ',
                'url' => BASE_URL . 'materials',
                'icon' => 'fas fa-boxes'
            ],
            [
                'title' => 'จัดการผู้ใช้',
                'url' => BASE_URL . 'users',
                'icon' => 'fas fa-users'
            ],
            [
                'title' => 'จัดการบทบาท',
                'url' => BASE_URL . 'roles',
                'icon' => 'fas fa-user-tag'
            ],
            [
                'title' => 'จัดการสิทธิ์',
                'url' => BASE_URL . 'permissions',
                'icon' => 'fas fa-key'
            ]
        ];
    }

    public static function renderMenu()
    {
        $menuItems = self::getMenuItems();
        $html = '';
        
        foreach ($menuItems as $item) {
            $html .= '<li class="nav-item">';
            $html .= '<a class="nav-link" href="' . $item['url'] . '">';
            $html .= '<i class="' . $item['icon'] . ' me-1"></i>' . $item['title'];
            $html .= '</a>';
            $html .= '</li>';
        }
        
        return $html;
    }

    public static function getAccessibleMenuItems($userRoles = [])
    {
        // Get roles from session if not provided
        if (empty($userRoles) && isset($_SESSION['roles'])) {
            $userRoles = $_SESSION['roles'];
        }
        
        $allMenus = [
            [
                'title' => 'Users Management',
                'subtitle' => 'บริหารจัดการผู้ใช้งาน',
                'icon' => 'fas fa-user-tag',
                'type' => 'link',
                'url' => BASE_URL . 'users',
                'required_roles' => ['admin']
            ],
            [
                'title' => 'Part Control',
                'subtitle' => 'ติดตามสถานะ Part Control',
                'icon' => 'fas fa-industry',
                'type' => 'link',
                'url' => BASE_URL . 'pc',
                'required_roles' => ['pc', 'admin']
            ],
            [
                'title' => 'จัดการบทบาท',
                'subtitle' => 'กำหนดบทบาทและสิทธิ์',
                'icon' => 'fas fa-user-tag',
                'type' => 'link',
                'url' => BASE_URL . 'roles',
                'required_roles' => ['admin']
            ],
            [
                'title' => 'จัดการสิทธิ์',
                'subtitle' => 'สร้างและกำหนดสิทธิ์การเข้าถึง',
                'icon' => 'fas fa-key',
                'type' => 'link',
                'url' => BASE_URL . 'permissions',
                'required_roles' => ['admin']
            ],
            [
                'title' => 'รายงาน',
                'subtitle' => 'สรุปข้อมูลและสถิติสำคัญ',
                'icon' => 'fas fa-chart-bar',
                'type' => 'link',
                'url' => BASE_URL . 'reports',
                'required_roles' => ['admin', 'manager']
            ]
        ];

        return array_filter($allMenus, function($menu) use ($userRoles) {
            return !empty(array_intersect($userRoles, $menu['required_roles']));
        });
    }

    public static function hasAccess($requiredRoles, $userRoles = [])
    {
        if (empty($userRoles) && isset($_SESSION['roles'])) {
            $userRoles = $_SESSION['roles'];
        }
        return !empty(array_intersect($userRoles, $requiredRoles));
    }
}