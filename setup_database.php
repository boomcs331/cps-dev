<?php
require_once 'config/config.php';

try {
    echo "<h2>🔧 ตั้งค่าฐานข้อมูล</h2>";
    
    // เชื่อมต่อฐานข้อมูลโดยไม่ระบุชื่อฐานข้อมูล
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "✅ เชื่อมต่อ MySQL สำเร็จ<br>";
    
    // สร้างฐานข้อมูล
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ สร้างฐานข้อมูล " . DB_NAME . " สำเร็จ<br>";
    
    // เลือกใช้ฐานข้อมูล
    $pdo->exec("USE " . DB_NAME);
    
    // อ่านและรันไฟล์ SQL
    $sql = file_get_contents('create_tables.sql');
    
    // แยก SQL statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^--/', $stmt);
        }
    );
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // ข้ามข้อผิดพลาดที่ไม่สำคัญ เช่น ตารางมีอยู่แล้ว
                if (strpos($e->getMessage(), 'already exists') === false && 
                    strpos($e->getMessage(), 'Duplicate entry') === false) {
                    throw $e;
                }
            }
        }
    }
    
    echo "✅ สร้างตารางทั้งหมดสำเร็จ<br>";
    
    // ตรวจสอบตาราง
    $tables = ['units', 'locations', 'materials', 'material_names', 'material_receipts', 'material_receipt_items', 'material_stock_lots'];
    
    echo "<h3>📊 ตรวจสอบตาราง</h3>";
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $table");
        $stmt->execute();
        $result = $stmt->fetch();
        echo "📋 ตาราง <strong>$table</strong>: " . $result['count'] . " รายการ<br>";
    }
    
    echo "<br>🎉 <strong>การตั้งค่าเสร็จสิ้น!</strong><br>";
    echo "👉 <a href='?url=materials/stock'>ไปที่หน้าสต็อกวัตถุดิบ</a>";
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #ffe6e6; padding: 10px; border-radius: 5px;'>";
    echo "<h3>❌ เกิดข้อผิดพลาด</h3>";
    echo "<p><strong>ข้อความ:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>ไฟล์:</strong> " . $e->getFile() . " บรรทัด " . $e->getLine() . "</p>";
    echo "</div>";
}
?>