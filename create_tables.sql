-- สร้างฐานข้อมูลและตารางที่จำเป็น
CREATE DATABASE IF NOT EXISTS cps_cci CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cps_cci;

-- ตาราง units (หน่วยนับ)
CREATE TABLE IF NOT EXISTS units (
    unit_id INT AUTO_INCREMENT PRIMARY KEY,
    unit_code VARCHAR(10) NOT NULL UNIQUE,
    unit_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ตาราง locations (คลัง/ตำแหน่งจัดเก็บ)
CREATE TABLE IF NOT EXISTS locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    location_code VARCHAR(20) NOT NULL UNIQUE,
    location_name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ตาราง materials (วัตถุดิบ)
CREATE TABLE IF NOT EXISTS materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    material_code VARCHAR(50) NOT NULL UNIQUE,
    default_unit INT,
    location_id INT,
    description TEXT,
    packing_qty INT DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (default_unit) REFERENCES units(unit_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id)
);

-- ตาราง material_names (ชื่อวัตถุดิบหลายภาษา)
CREATE TABLE IF NOT EXISTS material_names (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    language_code VARCHAR(5) NOT NULL DEFAULT 'th',
    name VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(material_id) ON DELETE CASCADE,
    UNIQUE KEY unique_primary_name (material_id, language_code, is_primary)
);

-- ตาราง material_receipts (ใบรับวัตถุดิบ)
CREATE TABLE IF NOT EXISTS material_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_no VARCHAR(50) NOT NULL UNIQUE,
    receipt_date DATE NOT NULL,
    supplier_name VARCHAR(255) NOT NULL,
    location_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (location_id) REFERENCES locations(location_id)
);

-- ตาราง material_receipt_items (รายการในใบรับวัตถุดิบ)
CREATE TABLE IF NOT EXISTS material_receipt_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_id INT NOT NULL,
    material_id INT NOT NULL,
    received_qty INT NOT NULL,
    packing_qty INT DEFAULT 1,
    full_box_count INT DEFAULT 0,
    partial_box_qty INT DEFAULT 0,
    total_box_count INT DEFAULT 0,
    lot_no VARCHAR(50),
    location_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (receipt_id) REFERENCES material_receipts(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(material_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id)
);

-- ตาราง material_stock_lots (สต็อกแต่ละกล่อง/ล็อต)
CREATE TABLE IF NOT EXISTS material_stock_lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_item_id INT,
    material_id INT NOT NULL,
    location_id INT NOT NULL,
    lot_no VARCHAR(50) NOT NULL,
    pack_no INT NOT NULL,
    pack_size INT NOT NULL,
    qr_code VARCHAR(100) UNIQUE,
    status ENUM('AVAILABLE', 'RESERVED', 'USED') DEFAULT 'AVAILABLE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (receipt_item_id) REFERENCES material_receipt_items(id) ON DELETE SET NULL,
    FOREIGN KEY (material_id) REFERENCES materials(material_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id),
    INDEX idx_material_status (material_id, status),
    INDEX idx_qr_code (qr_code)
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT IGNORE INTO units (unit_code, unit_name) VALUES 
('PCS', 'ชิ้น'),
('BOX', 'กล่อง'),
('KG', 'กิโลกรัม'),
('M', 'เมตร');

INSERT IGNORE INTO locations (location_code, location_name, description) VALUES 
('RM-A', 'คลังวัตถุดิบ A', 'คลังเก็บวัตถุดิบหลัก'),
('RM-B', 'คลังวัตถุดิบ B', 'คลังเก็บวัตถุดิบสำรอง'),
('WIP-1', 'คลัง WIP 1', 'คลังงานระหว่างผลิต 1');

-- เพิ่มวัตถุดิบตัวอย่าง
INSERT IGNORE INTO materials (material_code, default_unit, location_id, description, packing_qty) VALUES 
('MAT001', 1, 1, 'วัตถุดิบ A', 100),
('MAT002', 1, 1, 'วัตถุดิบ B', 50),
('MAT003', 2, 2, 'วัตถุดิบ C', 25);

-- เพิ่มชื่อวัตถุดิบ
INSERT IGNORE INTO material_names (material_id, language_code, name, is_primary) VALUES 
(1, 'th', 'วัตถุดิบ A', 1),
(2, 'th', 'วัตถุดิบ B', 1),
(3, 'th', 'วัตถุดิบ C', 1);