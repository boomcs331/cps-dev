<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    echo "กำลังทดสอบการเชื่อมต่อฐานข้อมูล...\n";
    
    $db = Database::getInstance()->getConnection();
    echo "✓ เชื่อมต่อฐานข้อมูลสำเร็จ\n";
    
    // ทดสอบตาราง materials
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM materials");
    $stmt->execute();
    $result = $stmt->fetch();
    echo "✓ ตาราง materials มี " . $result['count'] . " รายการ\n";
    
    // ทดสอบตาราง locations
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM locations");
    $stmt->execute();
    $result = $stmt->fetch();
    echo "✓ ตาราง locations มี " . $result['count'] . " รายการ\n";
    
    // ทดสอบตาราง material_stock_lots
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM material_stock_lots");
    $stmt->execute();
    $result = $stmt->fetch();
    echo "✓ ตาราง material_stock_lots มี " . $result['count'] . " รายการ\n";
    
    echo "\n✅ การทดสอบเสร็จสิ้น - ไม่พบปัญหา\n";
    
} catch (Exception $e) {
    echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>