/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : cps_cci

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 10/11/2025 22:30:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for locations
-- ----------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations`  (
  `location_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคลัง/ที่เก็บ (PK)',
  `location_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสคลัง เช่น RM-A, FG',
  `location_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อคลัง เช่น คลังวัตถุดิบ A',
  PRIMARY KEY (`location_id`) USING BTREE,
  UNIQUE INDEX `uq_location_code`(`location_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ข้อมูลตำแหน่งจัดเก็บวัตถุดิบ/สินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_names
-- ----------------------------
DROP TABLE IF EXISTS `material_names`;
CREATE TABLE `material_names`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `material_id` int NOT NULL COMMENT 'FK -> materials.material_id',
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสภาษา เช่น th, en',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อวัตถุดิบตามภาษา',
  `lr` enum('-','RH','LH') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-' COMMENT 'ซ้าย/ขวา (ถ้ามี)',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=ชื่อหลักในภาษานั้น',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_material_lang_name`(`material_id` ASC, `language_code` ASC, `name` ASC) USING BTREE,
  INDEX `idx_material_lang`(`language_code` ASC) USING BTREE,
  CONSTRAINT `fk_material_names_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ชื่อวัตถุดิบหลายภาษา' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_packings
-- ----------------------------
DROP TABLE IF EXISTS `material_packings`;
CREATE TABLE `material_packings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `material_id` int NOT NULL,
  `pack_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'เช่น BOX, BAG, PALLET',
  `pack_size` int NOT NULL COMMENT 'จำนวนต่อ 1 หน่วยบรรจุ',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_pack_material`(`material_id` ASC) USING BTREE,
  CONSTRAINT `fk_pack_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_receipt_items
-- ----------------------------
DROP TABLE IF EXISTS `material_receipt_items`;
CREATE TABLE `material_receipt_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `receipt_id` int NOT NULL,
  `material_id` int NOT NULL,
  `received_qty` int NOT NULL COMMENT 'จำนวนชิ้นที่รับเข้า (รวมทั้งหมด)',
  `packing_qty` int NOT NULL COMMENT 'จำนวนต่อกล่อง ณ วันที่รับ (snapshot)',
  `full_box_count` int NOT NULL COMMENT 'จำนวนกล่องเต็ม',
  `partial_box_qty` int NOT NULL COMMENT 'จำนวนชิ้นในกล่องสุดท้าย (ถ้าไม่เต็ม, 0=ไม่มี)',
  `total_box_count` int NOT NULL COMMENT 'รวมกล่องเต็ม + กล่องไม่เต็ม (ถ้ามี)',
  `lot_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expire_date` date NULL DEFAULT NULL,
  `location_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_mr_item_receipt`(`receipt_id` ASC) USING BTREE,
  INDEX `fk_mr_item_material`(`material_id` ASC) USING BTREE,
  INDEX `fk_mr_item_location`(`location_id` ASC) USING BTREE,
  CONSTRAINT `fk_mr_item_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_mr_item_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_mr_item_receipt` FOREIGN KEY (`receipt_id`) REFERENCES `material_receipts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_receipts
-- ----------------------------
DROP TABLE IF EXISTS `material_receipts`;
CREATE TABLE `material_receipts`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `receipt_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `supplier_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `location_id` int NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_receipt_location`(`location_id` ASC) USING BTREE,
  CONSTRAINT `fk_receipt_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_stock
-- ----------------------------
DROP TABLE IF EXISTS `material_stock`;
CREATE TABLE `material_stock`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `material_id` int NOT NULL,
  `location_id` int NOT NULL,
  `qty_on_hand` int NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_material_location`(`material_id` ASC, `location_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_stock_lots
-- ----------------------------
DROP TABLE IF EXISTS `material_stock_lots`;
CREATE TABLE `material_stock_lots`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `receipt_item_id` int NOT NULL,
  `material_id` int NOT NULL,
  `location_id` int NOT NULL,
  `lot_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pack_no` int NOT NULL COMMENT 'ลำดับกล่องใน Lot',
  `pack_size` int NOT NULL COMMENT 'จำนวนชิ้นในกล่องนี้',
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสสำหรับ Generate/Scan QR',
  `status` enum('AVAILABLE','RESERVED','USED','ADJUSTED','SCRAP') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'AVAILABLE',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_lot_receipt_item`(`receipt_item_id` ASC) USING BTREE,
  INDEX `fk_lot_material`(`material_id` ASC) USING BTREE,
  INDEX `fk_lot_location`(`location_id` ASC) USING BTREE,
  CONSTRAINT `fk_lot_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_lot_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_lot_receipt_item` FOREIGN KEY (`receipt_item_id`) REFERENCES `material_receipt_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for materials
-- ----------------------------
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials`  (
  `material_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสวัตถุดิบ (PK)',
  `material_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสวัตถุดิบ เช่น MAT-0001 (ต้องไม่ซ้ำ)',
  `default_unit` int NOT NULL COMMENT 'หน่วยนับหลักของวัตถุดิบ (FK -> units.unit_id)',
  `location_id` int NOT NULL COMMENT 'คลังจัดเก็บ (FK -> locations.location_id)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `packing_qty` int NOT NULL DEFAULT 1 COMMENT 'จำนวนต่อ 1 กล่อง/แพ็คมาตรฐาน',
  PRIMARY KEY (`material_id`) USING BTREE,
  UNIQUE INDEX `uq_material_code`(`material_code` ASC) USING BTREE,
  INDEX `fk_materials_unit`(`default_unit` ASC) USING BTREE,
  INDEX `fk_materials_location`(`location_id` ASC) USING BTREE,
  CONSTRAINT `fk_materials_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_materials_unit` FOREIGN KEY (`default_unit`) REFERENCES `units` (`unit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ทะเบียนวัตถุดิบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for processes
-- ----------------------------
DROP TABLE IF EXISTS `processes`;
CREATE TABLE `processes`  (
  `process_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสกระบวนการ (PK)',
  `process_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสย่อขั้นตอน เช่น CUT, WELD',
  `process_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อขั้นตอน เช่น ตัดเหล็ก, เชื่อม',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`process_id`) USING BTREE,
  UNIQUE INDEX `uq_process_code`(`process_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'รายการประเภทงาน/ขั้นตอนในโรงงาน' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_bom
-- ----------------------------
DROP TABLE IF EXISTS `product_bom`;
CREATE TABLE `product_bom`  (
  `product_id` int NOT NULL COMMENT 'สินค้า (FK -> products.product_id)',
  `material_id` int NOT NULL COMMENT 'วัตถุดิบ (FK -> materials.material_id)',
  `qty` int NOT NULL COMMENT 'จำนวนวัตถุดิบที่ใช้ต่อสินค้า 1 ชิ้น',
  `unit_id` int NULL DEFAULT NULL COMMENT 'หน่วยของจำนวน (FK -> units.unit_id)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`product_id`, `material_id`) USING BTREE,
  INDEX `fk_bom_material`(`material_id` ASC) USING BTREE,
  INDEX `fk_bom_unit`(`unit_id` ASC) USING BTREE,
  CONSTRAINT `fk_bom_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_bom_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_bom_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'BOM: ระบุวัตถุดิบที่ใช้ผลิตสินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_names
-- ----------------------------
DROP TABLE IF EXISTS `product_names`;
CREATE TABLE `product_names`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `product_id` int NOT NULL COMMENT 'FK -> products.product_id',
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสภาษา เช่น th, en',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อสินค้าในภาษานั้น',
  `lr` enum('-','RH','LH') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-' COMMENT 'ซ้าย/ขวา (ถ้ามี)',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=ชื่อหลักในภาษานั้น',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_product_lang_name`(`product_id` ASC, `language_code` ASC, `name` ASC) USING BTREE,
  INDEX `idx_product_lang`(`language_code` ASC) USING BTREE,
  CONSTRAINT `fk_product_names_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ชื่อสินค้าหลายภาษา' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_routing
-- ----------------------------
DROP TABLE IF EXISTS `product_routing`;
CREATE TABLE `product_routing`  (
  `product_routing_id` int NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `product_id` int NOT NULL COMMENT 'สินค้า (FK -> products.product_id)',
  `operation_no` int NOT NULL COMMENT 'ลำดับขั้น เช่น 10,20,30',
  `process_id` int NOT NULL COMMENT 'ขั้นตอน (FK -> processes.process_id)',
  `workstation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `std_time_min` decimal(10, 2) NULL DEFAULT NULL,
  `lead_time_day` decimal(10, 2) NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`product_routing_id`) USING BTREE,
  UNIQUE INDEX `uq_product_operation`(`product_id` ASC, `operation_no` ASC) USING BTREE,
  INDEX `fk_routing_process`(`process_id` ASC) USING BTREE,
  CONSTRAINT `fk_routing_process` FOREIGN KEY (`process_id`) REFERENCES `processes` (`process_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_routing_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ผังลำดับขั้นตอนผลิตของสินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `product_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสินค้า (PK)',
  `product_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสสินค้า เช่น PROD-0001 (ต้องไม่ซ้ำ)',
  `default_unit` int NOT NULL COMMENT 'หน่วยนับหลักของสินค้า (FK -> units.unit_id)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`) USING BTREE,
  UNIQUE INDEX `uq_product_code`(`product_code` ASC) USING BTREE,
  INDEX `fk_products_unit`(`default_unit` ASC) USING BTREE,
  CONSTRAINT `fk_products_unit` FOREIGN KEY (`default_unit`) REFERENCES `units` (`unit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ทะเบียนสินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_role_permission`(`role_id` ASC, `permission_id` ASC) USING BTREE,
  INDEX `permission_id`(`permission_id` ASC) USING BTREE,
  INDEX `idx_role_permissions_role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for units
-- ----------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units`  (
  `unit_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสหน่วย (PK)',
  `unit_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสหน่วยย่อ เช่น PCS, KG',
  `unit_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อหน่วยเต็ม เช่น ชิ้น, กิโลกรัม',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`unit_id`) USING BTREE,
  UNIQUE INDEX `uq_unit_code`(`unit_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'หน่วยนับมาตรฐาน' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp,
  `assigned_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_user_role`(`user_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `assigned_by`(`assigned_by` ASC) USING BTREE,
  INDEX `idx_user_roles_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_user_roles_role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_roles_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_sessions
-- ----------------------------
DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `session_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `session_token`(`session_token` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_user_sessions_token`(`session_token` ASC) USING BTREE,
  INDEX `idx_user_sessions_expires`(`expires_at` ASC) USING BTREE,
  CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  INDEX `idx_users_username`(`username` ASC) USING BTREE,
  INDEX `idx_users_email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- View structure for v_item_names
-- ----------------------------
DROP VIEW IF EXISTS `v_item_names`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_item_names` AS SELECT 'MATERIAL' AS item_type, mn.material_id AS item_id,
       mn.language_code, mn.name, mn.lr, mn.is_primary,
       mn.created_at, mn.updated_at
FROM material_names mn
UNION ALL
SELECT 'PRODUCT' AS item_type, pn.product_id AS item_id,
       pn.language_code, pn.name, pn.lr, pn.is_primary,
       pn.created_at, pn.updated_at
FROM product_names pn ;

-- ----------------------------
-- Triggers structure for table material_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_material_names_primary_bi`;
delimiter ;;
CREATE TRIGGER `trg_material_names_primary_bi` BEFORE INSERT ON `material_names` FOR EACH ROW BEGIN
  IF NEW.is_primary = 1 THEN
    IF (SELECT COUNT(*) FROM material_names
        WHERE material_id = NEW.material_id
          AND language_code = NEW.language_code
          AND is_primary = 1) > 0 THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'material_names: only one primary per material/language';
    END IF;
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table material_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_material_names_primary_bu`;
delimiter ;;
CREATE TRIGGER `trg_material_names_primary_bu` BEFORE UPDATE ON `material_names` FOR EACH ROW BEGIN
  IF NEW.is_primary = 1 AND (OLD.is_primary <> 1
      OR OLD.language_code <> NEW.language_code
      OR OLD.material_id <> NEW.material_id) THEN
    IF (SELECT COUNT(*) FROM material_names
        WHERE material_id = NEW.material_id
          AND language_code = NEW.language_code
          AND is_primary = 1
          AND id <> OLD.id) > 0 THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'material_names: only one primary per material/language';
    END IF;
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table product_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_product_names_primary_bi`;
delimiter ;;
CREATE TRIGGER `trg_product_names_primary_bi` BEFORE INSERT ON `product_names` FOR EACH ROW BEGIN
  IF NEW.is_primary = 1 THEN
    IF (SELECT COUNT(*) FROM product_names
        WHERE product_id = NEW.product_id
          AND language_code = NEW.language_code
          AND is_primary = 1) > 0 THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'product_names: only one primary per product/language';
    END IF;
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table product_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_product_names_primary_bu`;
delimiter ;;
CREATE TRIGGER `trg_product_names_primary_bu` BEFORE UPDATE ON `product_names` FOR EACH ROW BEGIN
  IF NEW.is_primary = 1 AND (OLD.is_primary <> 1
      OR OLD.language_code <> NEW.language_code
      OR OLD.product_id <> NEW.product_id) THEN
    IF (SELECT COUNT(*) FROM product_names
        WHERE product_id = NEW.product_id
          AND language_code = NEW.language_code
          AND is_primary = 1
          AND id <> OLD.id) > 0 THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'product_names: only one primary per product/language';
    END IF;
  END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
