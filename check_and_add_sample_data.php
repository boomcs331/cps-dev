<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<h2>ตรวจสอบและเพิ่มข้อมูลตัวอย่าง</h2>";
    
    // ตรวจสอบข้อมูลที่มีอยู่
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM materials");
    $stmt->execute();
    $materialCount = $stmt->fetch()['count'];
    echo "📊 จำนวนวัตถุดิบ: $materialCount<br>";
    
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM material_stock_lots");
    $stmt->execute();
    $stockCount = $stmt->fetch()['count'];
    echo "📦 จำนวนสต็อกล็อต: $stockCount<br>";
    
    if ($stockCount == 0) {
        echo "<br>🔄 เริ่มเพิ่มข้อมูลตัวอย่าง...<br>";
        
        // ตรวจสอบว่ามี location หรือไม่
        $stmt = $db->prepare("SELECT location_id FROM locations LIMIT 1");
        $stmt->execute();
        $location = $stmt->fetch();
        
        if (!$location) {
            // เพิ่ม location
            $db->exec("INSERT INTO locations (location_code, location_name) VALUES ('RM-A', 'คลังวัตถุดิบ A')");
            $db->exec("INSERT INTO locations (location_code, location_name) VALUES ('RM-B', 'คลังวัตถุดิบ B')");
            echo "✓ เพิ่ม locations<br>";
        }
        
        // ตรวจสอบว่ามี material หรือไม่
        if ($materialCount == 0) {
            // เพิ่ม units ก่อน
            $db->exec("INSERT IGNORE INTO units (unit_code, unit_name) VALUES ('PCS', 'ชิ้น')");
            $db->exec("INSERT IGNORE INTO units (unit_code, unit_name) VALUES ('KG', 'กิโลกรัม')");
            
            // เพิ่ม materials
            $db->exec("INSERT INTO materials (material_code, default_unit, location_id, description, is_active) VALUES ('MAT-001', 1, 1, 'วัตถุดิบ A', 1)");
            $db->exec("INSERT INTO materials (material_code, default_unit, location_id, description, is_active) VALUES ('MAT-002', 1, 2, 'วัตถุดิบ B', 1)");
            
            // เพิ่ม material names
            $db->exec("INSERT INTO material_names (material_id, language_code, name, is_primary) VALUES (1, 'th', 'เหล็กแผ่น', 1)");
            $db->exec("INSERT INTO material_names (material_id, language_code, name, is_primary) VALUES (2, 'th', 'อลูมิเนียม', 1)");
            
            echo "✓ เพิ่ม materials และ material names<br>";
        }
        
        // เพิ่มข้อมูลรับเข้า
        $receiptSql = "INSERT INTO material_receipts (receipt_no, receipt_date, supplier_name, location_id) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($receiptSql);
        $stmt->execute(['RCP-001', '2024-12-01', 'ผู้จำหน่าย A', 1]);
        $receiptId = $db->lastInsertId();
        echo "✓ เพิ่มใบรับเข้า ID: $receiptId<br>";
        
        // เพิ่มรายการรับเข้า
        $itemSql = "INSERT INTO material_receipt_items (receipt_id, material_id, received_qty, packing_qty, full_box_count, partial_box_qty, total_box_count, lot_no, location_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($itemSql);
        $stmt->execute([$receiptId, 1, 200, 100, 2, 0, 2, 'LOT-001', 1]);
        $itemId = $db->lastInsertId();
        echo "✓ เพิ่มรายการรับเข้า ID: $itemId<br>";
        
        // เพิ่มสต็อกล็อต
        $stockSql = "INSERT INTO material_stock_lots (receipt_item_id, material_id, location_id, lot_no, pack_no, pack_size, qr_code, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($stockSql);
        
        // กล่องที่ 1
        $stmt->execute([$itemId, 1, 1, 'LOT-001', 1, 100, 'LOT-001-BOX-001', 'AVAILABLE']);
        echo "✓ เพิ่มกล่องที่ 1<br>";
        
        // กล่องที่ 2
        $stmt->execute([$itemId, 1, 1, 'LOT-001', 2, 100, 'LOT-001-BOX-002', 'AVAILABLE']);
        echo "✓ เพิ่มกล่องที่ 2<br>";
        
        // เพิ่มข้อมูลวัตถุดิบที่ 2
        $stmt = $db->prepare($receiptSql);
        $stmt->execute(['RCP-002', '2024-12-01', 'ผู้จำหน่าย B', 2]);
        $receiptId2 = $db->lastInsertId();
        
        $stmt = $db->prepare($itemSql);
        $stmt->execute([$receiptId2, 2, 150, 50, 3, 0, 3, 'LOT-002', 2]);
        $itemId2 = $db->lastInsertId();
        
        $stmt = $db->prepare($stockSql);
        $stmt->execute([$itemId2, 2, 2, 'LOT-002', 1, 50, 'LOT-002-BOX-001', 'AVAILABLE']);
        $stmt->execute([$itemId2, 2, 2, 'LOT-002', 2, 50, 'LOT-002-BOX-002', 'RESERVED']);
        $stmt->execute([$itemId2, 2, 2, 'LOT-002', 3, 50, 'LOT-002-BOX-003', 'USED']);
        
        echo "✓ เพิ่มข้อมูลวัตถุดิบที่ 2 เสร็จสิ้น<br>";
        
        echo "<br>🎉 <strong>เพิ่มข้อมูลตัวอย่างเสร็จสิ้น!</strong><br>";
    } else {
        echo "<br>ℹ️ มีข้อมูลสต็อกอยู่แล้ว ไม่ต้องเพิ่มข้อมูลตัวอย่าง<br>";
    }
    
    // แสดงข้อมูลสรุป
    echo "<br><h3>📋 สรุปข้อมูลในระบบ</h3>";
    
    $stmt = $db->prepare("
        SELECT m.material_code, mn.name as material_name, l.location_name,
               COUNT(msl.id) as total_boxes,
               SUM(msl.pack_size) as total_stock
        FROM materials m
        LEFT JOIN material_names mn ON m.material_id = mn.material_id 
            AND mn.language_code = 'th' AND mn.is_primary = 1
        LEFT JOIN locations l ON m.location_id = l.location_id
        LEFT JOIN material_stock_lots msl ON m.material_id = msl.material_id
        WHERE msl.id IS NOT NULL
        GROUP BY m.material_id, m.material_code, mn.name, l.location_name
        ORDER BY m.material_code
    ");
    $stmt->execute();
    $summary = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($summary) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>รหัส</th><th>ชื่อ</th><th>คลัง</th><th>กล่อง</th><th>ชิ้น</th></tr>";
        foreach ($summary as $row) {
            echo "<tr>";
            echo "<td>{$row['material_code']}</td>";
            echo "<td>{$row['material_name']}</td>";
            echo "<td>{$row['location_name']}</td>";
            echo "<td>{$row['total_boxes']}</td>";
            echo "<td>{$row['total_stock']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ ไม่พบข้อมูลสรุปสต็อก";
    }
    
    echo "<br><br>👉 <a href='?url=materials'>กลับไปที่หน้าวัตถุดิบ</a>";
    
} catch (Exception $e) {
    echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>