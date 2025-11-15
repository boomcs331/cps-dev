<?php
require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'core/SessionManager.php';
require_once 'models/Material.php';
require_once 'controllers/MaterialController.php';

// Mock session
$_SESSION['user_id'] = 1;
$_SESSION['full_name'] = 'Test User';

// Mock AJAX request
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

header('Content-Type: application/json; charset=utf-8');

try {
    $controller = new MaterialController();
    $controller->stockSummary();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>