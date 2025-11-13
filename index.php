<?php
// Entry point ของแอปพลิเคชัน
require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/SessionManager.php';
require_once 'helpers/ServerSideTable.php';

// เริ่มต้น session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// เริ่มต้น router
$router = new Router();

// กำหนด routes
$router->addRoute('/', 'AuthController', 'login');
$router->addRoute('/login', 'AuthController', 'login');
$router->addRoute('/logout', 'AuthController', 'logout');
$router->addRoute('/dashboard', 'DashboardController', 'index');
$router->addRoute('/test', 'DashboardController', 'test');
$router->addRoute('/pc', 'PartControlController', 'index');
$router->addRoute('/permissions', 'PermissionController', 'index');
$router->addRoute('/permissions/create', 'PermissionController', 'create');
$router->addRoute('/permissions/store', 'PermissionController', 'store');
$router->addRoute('/permissions/get', 'PermissionController', 'get');
$router->addRoute('/permissions/edit', 'PermissionController', 'edit');
$router->addRoute('/permissions/update', 'PermissionController', 'update');
$router->addRoute('/permissions/delete', 'PermissionController', 'delete');
$router->addRoute('/roles', 'RoleController', 'index');
$router->addRoute('/roles/store', 'RoleController', 'store');
$router->addRoute('/roles/get', 'RoleController', 'get');
$router->addRoute('/roles/update', 'RoleController', 'update');
$router->addRoute('/roles/delete', 'RoleController', 'delete');
$router->addRoute('/roles/permissions', 'RoleController', 'permissions');
$router->addRoute('/roles/updatePermissions', 'RoleController', 'updatePermissions');
$router->addRoute('/users', 'UserController', 'index');
$router->addRoute('/users/create', 'UserController', 'create');
$router->addRoute('/users/store', 'UserController', 'store');
$router->addRoute('/users/get', 'UserController', 'get');
$router->addRoute('/users/update', 'UserController', 'update');
$router->addRoute('/users/delete', 'UserController', 'delete');
$router->addRoute('/materials', 'MaterialController', 'index');
$router->addRoute('/materials/index', 'MaterialController', 'index');
$router->addRoute('/materials/page/{page}', 'MaterialController', 'index');
$router->addRoute('/materials/table', 'MaterialController', 'table');
$router->addRoute('/materials/add', 'MaterialController', 'add');
$router->addRoute('/materials/store', 'MaterialController', 'store');
$router->addRoute('/materials/get', 'MaterialController', 'get');
$router->addRoute('/materials/edit/{id}', 'MaterialController', 'edit');
$router->addRoute('/materials/update', 'MaterialController', 'update');
$router->addRoute('/materials/delete', 'MaterialController', 'delete');
$router->addRoute('/materials/receipt', 'MaterialController', 'receipt');
$router->addRoute('/materials/storeReceipt', 'MaterialController', 'storeReceipt');
$router->addRoute('/materials/deleteReceipt', 'MaterialController', 'deleteReceipt');
$router->addRoute('/materials/receiptDetail/{id}', 'MaterialController', 'receiptDetail');
$router->addRoute('/materials/issue', 'MaterialController', 'issue');



// Fallback for direct access (when .htaccess doesn't work)
if (isset($_GET['url'])) {
    $_SERVER['REQUEST_URI'] = '/' . $_GET['url'];
}

// เริ่มต้นแอปพลิเคชัน
$router->dispatch();
?> 
