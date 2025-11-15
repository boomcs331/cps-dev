<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<h2>เพิ่มข้อมูลตัวอย่าง</h2>";
    
    // เพิ่มข้อมูลรับเข้าตัวอย่าง
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
    echo "👉 <a href='?url=materials'>กลับไปที่หน้าวัตถุดิบ</a>";
    
} catch (Exception $e) {
    echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>