-- ข้อมูลตัวอย่างสำหรับทดสอบระบบ

-- เพิ่มข้อมูล units
INSERT IGNORE INTO units (unit_code, unit_name) VALUES 
('PCS', 'ชิ้น'),
('KG', 'กิโลกรัม'),
('M', 'เมตร'),
('BOX', 'กล่อง');

-- เพิ่มข้อมูล locations
INSERT IGNORE INTO locations (location_code, location_name) VALUES 
('RM-A', 'คลังวัตถุดิบ A'),
('RM-B', 'คลังวัตถุดิบ B'),
('FG-A', 'คลังสินค้าสำเร็จรูป A');

-- เพิ่มข้อมูล materials
INSERT IGNORE INTO materials (material_code, default_unit, location_id, description, packing_qty) VALUES 
('MAT-001', 1, 1, 'เหล็กแผ่น 2mm', 50),
('MAT-002', 1, 1, 'สกรู M6x20', 100),
('MAT-003', 2, 2, 'สีน้ำมัน', 1);

-- เพิ่มข้อมูล material_names
INSERT IGNORE INTO material_names (material_id, language_code, name, is_primary) VALUES 
(1, 'th', 'เหล็กแผ่น 2mm', 1),
(2, 'th', 'สกรู M6x20', 1),
(3, 'th', 'สีน้ำมัน', 1);

-- เพิ่มข้อมูล material_receipts ตัวอย่าง
INSERT IGNORE INTO material_receipts (receipt_no, receipt_date, supplier_name, location_id, created_by) VALUES 
('RCP-20250110-001', '2025-01-10', 'บริษัท เหล็กดี จำกัด', 1, 1),
('RCP-20250110-002', '2025-01-10', 'บริษัท สกรูแสง จำกัด', 1, 1);

-- เพิ่มข้อมูล material_receipt_items ตัวอย่าง
INSERT IGNORE INTO material_receipt_items (receipt_id, material_id, received_qty, packing_qty, full_box_count, partial_box_qty, total_box_count, lot_no, location_id) VALUES 
(1, 1, 150, 50, 3, 0, 3, 'LOT-20250110-01', 1),
(2, 2, 250, 100, 2, 50, 3, 'LOT-20250110-02', 1);

-- เพิ่มข้อมูล material_stock_lots ตัวอย่าง
INSERT IGNORE INTO material_stock_lots (receipt_item_id, material_id, location_id, lot_no, pack_no, pack_size, qr_code, status) VALUES 
-- สำหรับ receipt_item_id = 1 (เหล็กแผ่น)
(1, 1, 1, 'LOT-20250110-01', 1, 50, 'LOT-20250110-01-BOX-001', 'AVAILABLE'),
(1, 1, 1, 'LOT-20250110-01', 2, 50, 'LOT-20250110-01-BOX-002', 'AVAILABLE'),
(1, 1, 1, 'LOT-20250110-01', 3, 50, 'LOT-20250110-01-BOX-003', 'AVAILABLE'),
-- สำหรับ receipt_item_id = 2 (สกรู)
(2, 2, 1, 'LOT-20250110-02', 1, 100, 'LOT-20250110-02-BOX-001', 'AVAILABLE'),
(2, 2, 1, 'LOT-20250110-02', 2, 100, 'LOT-20250110-02-BOX-002', 'AVAILABLE'),
(2, 2, 1, 'LOT-20250110-02', 3, 50, 'LOT-20250110-02-BOX-003', 'AVAILABLE');