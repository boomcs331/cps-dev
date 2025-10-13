<?php
// ไฟล์สำหรับติดตั้งฐานข้อมูล
require_once '../config/config.php';

try {
    // เชื่อมต่อ MySQL โดยไม่ระบุฐานข้อมูล
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // อ่านไฟล์ SQL
    $sql = file_get_contents('schema.sql');
    
    // แยก SQL statements
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "ติดตั้งฐานข้อมูลสำเร็จ!<br>";
    echo "ข้อมูลทดสอบ:<br>";
    echo "- Username: admin, Password: password (Super Admin + Admin)<br>";
    echo "- Username: manager, Password: password (Manager + User)<br>";
    echo "- Username: user1, Password: password (User)<br>";
    
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>