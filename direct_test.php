<?php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['full_name'] = 'Test User';

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Material.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $materialModel = new Material();
    $materialStockSummary = $materialModel->getMaterialStockSummary();
    
    echo json_encode([
        'success' => true,
        'materialStockSummary' => $materialStockSummary,
        'count' => count($materialStockSummary),
        'debug' => 'Direct model call successful'
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => 'Error in direct model call'
    ], JSON_UNESCAPED_UNICODE);
}
?>