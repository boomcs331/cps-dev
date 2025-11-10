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
 
 Date: 10/11/2025 21:20:58
 */
SET
  NAMES utf8mb4;

SET
  FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for locations
-- ----------------------------
DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `location_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคลัง/ที่เก็บ (PK)',
  `location_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสคลัง เช่น RM-A, FG',
  `location_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อคลัง เช่น คลังวัตถุดิบ A',
  PRIMARY KEY (`location_id`) USING BTREE,
  UNIQUE INDEX `uq_location_code`(`location_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ข้อมูลตำแหน่งจัดเก็บวัตถุดิบ/สินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of locations
-- ----------------------------
INSERT INTO
  `locations`
VALUES
  (1, 'RM-A', 'คลังวัตถุดิบ A');

INSERT INTO
  `locations`
VALUES
  (2, 'RM-B', 'คลังวัตถุดิบ B');

INSERT INTO
  `locations`
VALUES
  (3, 'FG', 'คลังสินค้าสำเร็จรูป');

INSERT INTO
  `locations`
VALUES
  (4, 'WIP', 'คลังงานระหว่างผลิต');

INSERT INTO
  `locations`
VALUES
  (5, 'TOOL', 'คลังเครื่องมือ');

-- ----------------------------
-- Table structure for material_names
-- ----------------------------
DROP TABLE IF EXISTS `material_names`;

CREATE TABLE `material_names` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `material_id` int NOT NULL COMMENT 'FK -> materials.material_id',
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสภาษา เช่น th, en',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อวัตถุดิบตามภาษา',
  `lr` enum('-', 'RH', 'LH') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-' COMMENT 'ซ้าย/ขวา (ถ้ามี)',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=ชื่อหลักในภาษานั้น',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_material_lang_name`(
    `material_id` ASC,
    `language_code` ASC,
    `name` ASC
  ) USING BTREE,
  INDEX `idx_material_lang`(`language_code` ASC) USING BTREE,
  CONSTRAINT `fk_material_names_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ชื่อวัตถุดิบหลายภาษา' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_names
-- ----------------------------
-- ----------------------------
-- Table structure for materials
-- ----------------------------
DROP TABLE IF EXISTS `materials`;

CREATE TABLE `materials` (
  `material_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสวัตถุดิบ (PK)',
  `material_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสวัตถุดิบ เช่น MAT-0001 (ต้องไม่ซ้ำ)',
  `default_unit` int NOT NULL COMMENT 'หน่วยนับหลักของวัตถุดิบ (FK -> units.unit_id)',
  `location_id` int NOT NULL COMMENT 'คลังจัดเก็บ (FK -> locations.location_id)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`material_id`) USING BTREE,
  UNIQUE INDEX `uq_material_code`(`material_code` ASC) USING BTREE,
  INDEX `fk_materials_unit`(`default_unit` ASC) USING BTREE,
  INDEX `fk_materials_location`(`location_id` ASC) USING BTREE,
  CONSTRAINT `fk_materials_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_materials_unit` FOREIGN KEY (`default_unit`) REFERENCES `units` (`unit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ทะเบียนวัตถุดิบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of materials
-- ----------------------------
-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
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
-- Records of permissions
-- ----------------------------
INSERT INTO
  `permissions`
VALUES
  (
    1,
    'user.view',
    'ดูข้อมูลผู้ใช้',
    'สามารถดูรายการและข้อมูลผู้ใช้',
    'user',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    2,
    'user.create',
    'เพิ่มผู้ใช้',
    'สามารถเพิ่มผู้ใช้ใหม่',
    'user',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    3,
    'user.edit',
    'แก้ไขผู้ใช้',
    'สามารถแก้ไขข้อมูลผู้ใช้',
    'user',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    4,
    'user.delete',
    'ลบผู้ใช้',
    'สามารถลบผู้ใช้',
    'user',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    5,
    'role.view',
    'ดูข้อมูล Role',
    'สามารถดูรายการ Role',
    'role',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    6,
    'role.manage',
    'จัดการ Role',
    'สามารถเพิ่ม แก้ไข ลบ Role',
    'role',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    7,
    'permission.view',
    'ดูสิทธิ์',
    'สามารถดูรายการสิทธิ์',
    'permission',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    8,
    'permission.manage',
    'จัดการสิทธิ์',
    'สามารถจัดการสิทธิ์',
    'permission',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    9,
    'dashboard.view',
    'เข้าถึง Dashboard',
    'สามารถเข้าถึงหน้า Dashboard',
    'dashboard',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    10,
    'system.admin',
    'ผู้ดูแลระบบ',
    'สิทธิ์ผู้ดูแลระบบเต็มรูปแบบ',
    'system',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `permissions`
VALUES
  (
    11,
    'pc',
    'เจ้าหน้าที่ pc',
    'จัดการเมนูทั้งหมดของ PC',
    'pc',
    '2025-09-18 08:44:45'
  );

INSERT INTO
  `permissions`
VALUES
  (
    12,
    'ps',
    'เจ้าหน้าที่ - PS',
    'จัดการเมนูทั้งหมดของ - PS',
    'ps',
    '2025-10-09 06:55:30'
  );

INSERT INTO
  `permissions`
VALUES
  (
    13,
    'we',
    'ผู้ใช้ทั่วไป - WE',
    'จัดการเมนูทั้งหมดของ WE',
    'We',
    '2025-10-09 07:06:09'
  );

INSERT INTO
  `permissions`
VALUES
  (
    14,
    'pc.view',
    'เข้าถึง Part Control',
    'สามารถเข้าถึงหน้า Part Control Dashboard',
    'part_control',
    '2025-10-23 12:02:10'
  );

INSERT INTO
  `permissions`
VALUES
  (
    15,
    'pc.manage',
    'จัดการ Part Control',
    'สามารถจัดการข้อมูล Part Control',
    'part_control',
    '2025-10-23 12:02:10'
  );

INSERT INTO
  `permissions`
VALUES
  (
    16,
    'material.view',
    'ดูข้อมูล Materials',
    'สามารถดูรายการ Materials',
    'material',
    '2025-10-23 12:08:13'
  );

INSERT INTO
  `permissions`
VALUES
  (
    17,
    'material.manage',
    'จัดการ Materials',
    'สามารถเพิ่ม แก้ไข ลบ Materials',
    'material',
    '2025-10-23 12:08:13'
  );

-- ----------------------------
-- Table structure for processes
-- ----------------------------
DROP TABLE IF EXISTS `processes`;

CREATE TABLE `processes` (
  `process_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสกระบวนการ (PK)',
  `process_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสย่อขั้นตอน เช่น CUT, WELD',
  `process_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อขั้นตอน เช่น ตัดเหล็ก, เชื่อม',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`process_id`) USING BTREE,
  UNIQUE INDEX `uq_process_code`(`process_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'รายการประเภทงาน/ขั้นตอนในโรงงาน' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of processes
-- ----------------------------
-- ----------------------------
-- Table structure for product_bom
-- ----------------------------
DROP TABLE IF EXISTS `product_bom`;

CREATE TABLE `product_bom` (
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
-- Records of product_bom
-- ----------------------------
-- ----------------------------
-- Table structure for product_names
-- ----------------------------
DROP TABLE IF EXISTS `product_names`;

CREATE TABLE `product_names` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `product_id` int NOT NULL COMMENT 'FK -> products.product_id',
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสภาษา เช่น th, en',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อสินค้าในภาษานั้น',
  `lr` enum('-', 'RH', 'LH') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-' COMMENT 'ซ้าย/ขวา (ถ้ามี)',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=ชื่อหลักในภาษานั้น',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_product_lang_name`(
    `product_id` ASC,
    `language_code` ASC,
    `name` ASC
  ) USING BTREE,
  INDEX `idx_product_lang`(`language_code` ASC) USING BTREE,
  CONSTRAINT `fk_product_names_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ชื่อสินค้าหลายภาษา' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_names
-- ----------------------------
-- ----------------------------
-- Table structure for product_routing
-- ----------------------------
DROP TABLE IF EXISTS `product_routing`;

CREATE TABLE `product_routing` (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ผังลำดับขั้นตอนผลิตของสินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_routing
-- ----------------------------
-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'ทะเบียนสินค้า' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;

CREATE TABLE `role_permissions` (
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
-- Records of role_permissions
-- ----------------------------
INSERT INTO
  `role_permissions`
VALUES
  (17, 2, 5, '2025-09-18 06:19:17');

INSERT INTO
  `role_permissions`
VALUES
  (18, 2, 2, '2025-09-18 06:19:17');

INSERT INTO
  `role_permissions`
VALUES
  (19, 2, 3, '2025-09-18 06:19:17');

INSERT INTO
  `role_permissions`
VALUES
  (20, 2, 1, '2025-09-18 06:19:17');

INSERT INTO
  `role_permissions`
VALUES
  (24, 3, 1, '2025-09-18 06:19:17');

INSERT INTO
  `role_permissions`
VALUES
  (43, 1, 9, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (44, 1, 11, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (45, 1, 8, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (46, 1, 7, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (47, 1, 12, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (48, 1, 6, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (49, 1, 5, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (50, 1, 10, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (51, 1, 1, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (52, 1, 4, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (53, 1, 2, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (54, 1, 3, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (55, 1, 13, '2025-10-13 02:55:23');

INSERT INTO
  `role_permissions`
VALUES
  (56, 2, 15, '2025-10-23 12:02:10');

INSERT INTO
  `role_permissions`
VALUES
  (57, 1, 15, '2025-10-23 12:02:10');

INSERT INTO
  `role_permissions`
VALUES
  (58, 2, 14, '2025-10-23 12:02:10');

INSERT INTO
  `role_permissions`
VALUES
  (59, 1, 14, '2025-10-23 12:02:10');

INSERT INTO
  `role_permissions`
VALUES
  (63, 3, 14, '2025-10-23 12:02:10');

INSERT INTO
  `role_permissions`
VALUES
  (67, 2, 17, '2025-10-23 12:08:13');

INSERT INTO
  `role_permissions`
VALUES
  (68, 1, 17, '2025-10-23 12:08:13');

INSERT INTO
  `role_permissions`
VALUES
  (69, 2, 16, '2025-10-23 12:08:13');

INSERT INTO
  `role_permissions`
VALUES
  (70, 1, 16, '2025-10-23 12:08:13');

INSERT INTO
  `role_permissions`
VALUES
  (74, 3, 16, '2025-10-23 12:08:13');

INSERT INTO
  `role_permissions`
VALUES
  (75, 6, 14, '2025-11-09 13:41:05');

INSERT INTO
  `role_permissions`
VALUES
  (76, 5, 15, '2025-11-09 14:01:43');

INSERT INTO
  `role_permissions`
VALUES
  (77, 5, 14, '2025-11-09 14:01:43');

INSERT INTO
  `role_permissions`
VALUES
  (78, 5, 11, '2025-11-09 14:01:43');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
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
-- Records of roles
-- ----------------------------
INSERT INTO
  `roles`
VALUES
  (
    1,
    'super_admin',
    'ผู้ดูแลระบบสูงสุด',
    'มีสิทธิ์เต็มในระบบ',
    1,
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `roles`
VALUES
  (
    2,
    'admin',
    'ผู้ดูแลระบบ',
    'ดูแลระบบและจัดการผู้ใช้',
    1,
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `roles`
VALUES
  (
    3,
    'manager',
    'ผู้จัดการ',
    'จัดการข้อมูลและรายงาน',
    1,
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `roles`
VALUES
  (
    4,
    'user',
    'ผู้ใช้ทั่วไป',
    'ใช้งานระบบพื้นฐาน',
    1,
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `roles`
VALUES
  (
    5,
    'pc',
    'ผู้ใช้ทั่วไป - PC',
    'เจ้าหน้าที่ แผนก Part Control',
    1,
    '2025-09-18 08:43:17'
  );

INSERT INTO
  `roles`
VALUES
  (
    6,
    'ps',
    'ผู้ใช้ทั่วไป - PS',
    'เจ้าหน้าที่ แผนก PS',
    1,
    '2025-10-09 06:14:39'
  );

INSERT INTO
  `roles`
VALUES
  (
    7,
    'we',
    'ผู้ใช้ทั่วไป - WE',
    'เจ้าหน้าที่ แผนก WE',
    1,
    '2025-10-09 07:07:10'
  );

-- ----------------------------
-- Table structure for units
-- ----------------------------
DROP TABLE IF EXISTS `units`;

CREATE TABLE `units` (
  `unit_id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสหน่วย (PK)',
  `unit_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'รหัสหน่วยย่อ เช่น PCS, KG',
  `unit_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ชื่อหน่วยเต็ม เช่น ชิ้น, กิโลกรัม',
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`unit_id`) USING BTREE,
  UNIQUE INDEX `uq_unit_code`(`unit_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'หน่วยนับมาตรฐาน' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of units
-- ----------------------------
INSERT INTO
  `units`
VALUES
  (
    1,
    'PCS',
    'ชิ้น',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    2,
    'KG',
    'กิโลกรัม',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    3,
    'M',
    'เมตร',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    4,
    'L',
    'ลิตร',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    5,
    'SET',
    'ชุด',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    6,
    'BOX',
    'กล่อง',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    7,
    'ROLL',
    'ม้วน',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

INSERT INTO
  `units`
VALUES
  (
    8,
    'SHEET',
    'แผ่น',
    '2025-10-23 14:36:37',
    '2025-10-23 14:36:37'
  );

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;

CREATE TABLE `user_roles` (
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
  CONSTRAINT `user_roles_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE
  SET
    NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_roles
-- ----------------------------
INSERT INTO
  `user_roles`
VALUES
  (1, 1, 1, '2025-09-18 06:19:17', NULL);

INSERT INTO
  `user_roles`
VALUES
  (2, 1, 2, '2025-09-18 06:19:17', NULL);

INSERT INTO
  `user_roles`
VALUES
  (3, 2, 3, '2025-09-18 06:19:17', NULL);

INSERT INTO
  `user_roles`
VALUES
  (4, 2, 4, '2025-09-18 06:19:17', NULL);

INSERT INTO
  `user_roles`
VALUES
  (5, 3, 4, '2025-09-18 06:19:17', NULL);

INSERT INTO
  `user_roles`
VALUES
  (7, 5, 6, '2025-10-09 06:41:15', 1);

INSERT INTO
  `user_roles`
VALUES
  (8, 7, 6, '2025-10-14 03:28:11', 1);

INSERT INTO
  `user_roles`
VALUES
  (9, 8, 3, '2025-11-03 23:18:14', 1);

INSERT INTO
  `user_roles`
VALUES
  (10, 13, 6, '2025-11-03 23:19:02', 1);

INSERT INTO
  `user_roles`
VALUES
  (11, 15, 3, '2025-11-03 23:19:20', 1);

INSERT INTO
  `user_roles`
VALUES
  (12, 16, 3, '2025-11-03 23:19:43', 1);

INSERT INTO
  `user_roles`
VALUES
  (13, 16, 1, '2025-11-03 23:19:43', 1);

INSERT INTO
  `user_roles`
VALUES
  (15, 17, 3, '2025-11-03 23:22:23', 1);

INSERT INTO
  `user_roles`
VALUES
  (16, 17, 2, '2025-11-03 23:22:23', 1);

INSERT INTO
  `user_roles`
VALUES
  (17, 17, 4, '2025-11-03 23:22:23', 1);

INSERT INTO
  `user_roles`
VALUES
  (20, 19, 3, '2025-11-06 22:43:25', 4);

INSERT INTO
  `user_roles`
VALUES
  (23, 4, 5, '2025-11-09 14:01:11', 4);

-- ----------------------------
-- Table structure for user_sessions
-- ----------------------------
DROP TABLE IF EXISTS `user_sessions`;

CREATE TABLE `user_sessions` (
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
-- Records of user_sessions
-- ----------------------------
-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
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
-- Records of users
-- ----------------------------
INSERT INTO
  `users`
VALUES
  (
    1,
    'admin',
    'admin@cps.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'ผู้ดูแลระบบ',
    1,
    '2025-09-18 06:19:17',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `users`
VALUES
  (
    2,
    'manager',
    'manager@cps.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'ผู้จัดการ',
    1,
    '2025-09-18 06:19:17',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `users`
VALUES
  (
    3,
    'user1',
    'user1@cps.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'ผู้ใช้ 1',
    1,
    '2025-09-18 06:19:17',
    '2025-09-18 06:19:17'
  );

INSERT INTO
  `users`
VALUES
  (
    4,
    'pc01',
    'pc01@cps.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'ธนพงษ์ ( PC )',
    1,
    '2025-09-18 08:40:14',
    '2025-10-05 22:56:31'
  );

INSERT INTO
  `users`
VALUES
  (
    5,
    'boom',
    'tanapong.wa@tfac.or.th',
    '$2y$10$9BQ8O7ieQhUVN/4m5hVPp.aQ1LFuH/I26g4xKjmHgErqh6F2H.dSS',
    'บูม',
    1,
    '2025-10-09 06:41:15',
    '2025-10-09 06:41:15'
  );

INSERT INTO
  `users`
VALUES
  (
    7,
    'ps001',
    'ps@cci.co.th',
    '$2y$10$v/daaUvR6c0EoeOyJ8C6f.903rj/OI0ANfZUAHAiw.DcFKEXWa1SW',
    'TEST-PS',
    1,
    '2025-10-14 03:28:11',
    '2025-10-14 03:28:11'
  );

INSERT INTO
  `users`
VALUES
  (
    8,
    'pc01wsed1',
    'tanapongwannapira@gmail.com',
    '$2y$10$Te4Wj6qFQwgQwuPRuIak7.K0rGpDt5lm/2mGX7tdaEEy/fx7mKydO',
    '2323',
    1,
    '2025-11-03 23:18:14',
    '2025-11-03 23:18:14'
  );

INSERT INTO
  `users`
VALUES
  (
    13,
    '123ase',
    'tanapongweannapira@gmail.com',
    '$2y$10$I49ezIfMdLnPeCeZk0hid.nbn9v2pTe1A6knEGpGA/6rYHY9mXTXa',
    'qweq',
    1,
    '2025-11-03 23:18:51',
    '2025-11-03 23:18:51'
  );

INSERT INTO
  `users`
VALUES
  (
    15,
    '123aseasd',
    'tanapongwaneqn123123apira@gmail.com',
    '$2y$10$tfQCaCiWhAfLPlQil37QMu1GJlETqCpUUAXq0DQr8GUtBnF.Jejwy',
    'qweasd',
    1,
    '2025-11-03 23:19:20',
    '2025-11-03 23:19:20'
  );

INSERT INTO
  `users`
VALUES
  (
    16,
    '123aseqweqwe',
    'admiqweqwen@cps.com',
    '$2y$10$rmL.5pAP8ygLKfJ3C0lvJ..MCdZ99omlJd6LfKU./cU24zU0YCria',
    'qwe',
    1,
    '2025-11-03 23:19:43',
    '2025-11-03 23:19:43'
  );

INSERT INTO
  `users`
VALUES
  (
    17,
    '123ase123asd',
    'tanapongboom307@hotmail.com',
    '$2y$10$fl0t8CNsWBu74n0awz9O2ulTXmgl5DIUfgQUkvs4RhrusL/n4A4QC',
    'qwe',
    1,
    '2025-11-03 23:19:52',
    '2025-11-03 23:19:52'
  );

INSERT INTO
  `users`
VALUES
  (
    19,
    '123aseq',
    'qwd@wdf.fw',
    '$2y$10$g0TgLSY.v.TlitRjaLjI8OXel2PGHTn/mNx89NMtMIY6g/1VshhLq',
    'dq',
    1,
    '2025-11-03 23:48:49',
    '2025-11-06 22:43:25'
  );

-- ----------------------------
-- View structure for v_item_names
-- ----------------------------
DROP VIEW IF EXISTS `v_item_names`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_item_names` AS
SELECT
  'MATERIAL' AS item_type,
  mn.material_id AS item_id,
  mn.language_code,
  mn.name,
  mn.lr,
  mn.is_primary,
  mn.created_at,
  mn.updated_at
FROM
  material_names mn
UNION
ALL
SELECT
  'PRODUCT' AS item_type,
  pn.product_id AS item_id,
  pn.language_code,
  pn.name,
  pn.lr,
  pn.is_primary,
  pn.created_at,
  pn.updated_at
FROM
  product_names pn;

-- ----------------------------
-- Triggers structure for table material_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_material_names_primary_bi`;

delimiter;

;

CREATE TRIGGER `trg_material_names_primary_bi` BEFORE
INSERT
  ON `material_names` FOR EACH ROW BEGIN IF NEW.is_primary = 1 THEN IF (
    SELECT
      COUNT(*)
    FROM
      material_names
    WHERE
      material_id = NEW.material_id
      AND language_code = NEW.language_code
      AND is_primary = 1
  ) > 0 THEN SIGNAL SQLSTATE '45000'
SET
  MESSAGE_TEXT = 'material_names: only one primary per material/language';

END IF;

END IF;

END;

;

delimiter;

-- ----------------------------
-- Triggers structure for table material_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_material_names_primary_bu`;

delimiter;

;

CREATE TRIGGER `trg_material_names_primary_bu` BEFORE
UPDATE
  ON `material_names` FOR EACH ROW BEGIN IF NEW.is_primary = 1
  AND (
    OLD.is_primary <> 1
    OR OLD.language_code <> NEW.language_code
    OR OLD.material_id <> NEW.material_id
  ) THEN IF (
    SELECT
      COUNT(*)
    FROM
      material_names
    WHERE
      material_id = NEW.material_id
      AND language_code = NEW.language_code
      AND is_primary = 1
      AND id <> OLD.id
  ) > 0 THEN SIGNAL SQLSTATE '45000'
SET
  MESSAGE_TEXT = 'material_names: only one primary per material/language';

END IF;

END IF;

END;

;

delimiter;

-- ----------------------------
-- Triggers structure for table product_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_product_names_primary_bi`;

delimiter;

;

CREATE TRIGGER `trg_product_names_primary_bi` BEFORE
INSERT
  ON `product_names` FOR EACH ROW BEGIN IF NEW.is_primary = 1 THEN IF (
    SELECT
      COUNT(*)
    FROM
      product_names
    WHERE
      product_id = NEW.product_id
      AND language_code = NEW.language_code
      AND is_primary = 1
  ) > 0 THEN SIGNAL SQLSTATE '45000'
SET
  MESSAGE_TEXT = 'product_names: only one primary per product/language';

END IF;

END IF;

END;

;

delimiter;

-- ----------------------------
-- Triggers structure for table product_names
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_product_names_primary_bu`;

delimiter;

;

CREATE TRIGGER `trg_product_names_primary_bu` BEFORE
UPDATE
  ON `product_names` FOR EACH ROW BEGIN IF NEW.is_primary = 1
  AND (
    OLD.is_primary <> 1
    OR OLD.language_code <> NEW.language_code
    OR OLD.product_id <> NEW.product_id
  ) THEN IF (
    SELECT
      COUNT(*)
    FROM
      product_names
    WHERE
      product_id = NEW.product_id
      AND language_code = NEW.language_code
      AND is_primary = 1
      AND id <> OLD.id
  ) > 0 THEN SIGNAL SQLSTATE '45000'
SET
  MESSAGE_TEXT = 'product_names: only one primary per product/language';

END IF;

END IF;

END;

;

delimiter;

SET
  FOREIGN_KEY_CHECKS = 1;