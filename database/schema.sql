-- ฐานข้อมูลระบบ CPS
CREATE DATABASE IF NOT EXISTS cps_cci CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cps_cci;

-- ตาราง users (ข้อมูลผู้ใช้)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ตาราง roles (บทบาท/ตำแหน่ง)
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง permissions (สิทธิ์การใช้งาน)
CREATE TABLE permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    module VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง user_roles (ความสัมพันธ์ระหว่าง user และ role - Many to Many)
CREATE TABLE user_roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    assigned_by INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_role (user_id, role_id)
);

-- ตาราง role_permissions (ความสัมพันธ์ระหว่าง role และ permission - Many to Many)
CREATE TABLE role_permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_permission (role_id, permission_id)
);

-- ตาราง user_sessions (จัดการ session)
CREATE TABLE user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ข้อมูลเริ่มต้น

-- เพิ่ม permissions พื้นฐาน
INSERT INTO permissions (name, display_name, description, module) VALUES
('user.view', 'ดูข้อมูลผู้ใช้', 'สามารถดูรายการและข้อมูลผู้ใช้', 'user'),
('user.create', 'เพิ่มผู้ใช้', 'สามารถเพิ่มผู้ใช้ใหม่', 'user'),
('user.edit', 'แก้ไขผู้ใช้', 'สามารถแก้ไขข้อมูลผู้ใช้', 'user'),
('user.delete', 'ลบผู้ใช้', 'สามารถลบผู้ใช้', 'user'),
('role.view', 'ดูข้อมูล Role', 'สามารถดูรายการ Role', 'role'),
('role.manage', 'จัดการ Role', 'สามารถเพิ่ม แก้ไข ลบ Role', 'role'),
('permission.view', 'ดูสิทธิ์', 'สามารถดูรายการสิทธิ์', 'permission'),
('permission.manage', 'จัดการสิทธิ์', 'สามารถจัดการสิทธิ์', 'permission'),
('dashboard.view', 'เข้าถึง Dashboard', 'สามารถเข้าถึงหน้า Dashboard', 'dashboard'),
('system.admin', 'ผู้ดูแลระบบ', 'สิทธิ์ผู้ดูแลระบบเต็มรูปแบบ', 'system');

-- เพิ่ม roles พื้นฐาน
INSERT INTO roles (name, display_name, description) VALUES
('super_admin', 'ผู้ดูแลระบบสูงสุด', 'มีสิทธิ์เต็มในระบบ'),
('admin', 'ผู้ดูแลระบบ', 'ดูแลระบบและจัดการผู้ใช้'),
('manager', 'ผู้จัดการ', 'จัดการข้อมูลและรายงาน'),
('user', 'ผู้ใช้ทั่วไป', 'ใช้งานระบบพื้นฐาน');

-- กำหนดสิทธิ์ให้ role
-- Super Admin มีสิทธิ์ทั้งหมด
INSERT INTO role_permissions (role_id, permission_id) 
SELECT r.id, p.id FROM roles r, permissions p WHERE r.name = 'super_admin';

-- Admin มีสิทธิ์จัดการผู้ใช้และ role
INSERT INTO role_permissions (role_id, permission_id) 
SELECT r.id, p.id FROM roles r, permissions p 
WHERE r.name = 'admin' AND p.name IN ('user.view', 'user.create', 'user.edit', 'role.view', 'dashboard.view');

-- Manager มีสิทธิ์ดูข้อมูลและ dashboard
INSERT INTO role_permissions (role_id, permission_id) 
SELECT r.id, p.id FROM roles r, permissions p 
WHERE r.name = 'manager' AND p.name IN ('user.view', 'dashboard.view');

-- User มีสิทธิ์พื้นฐาน
INSERT INTO role_permissions (role_id, permission_id) 
SELECT r.id, p.id FROM roles r, permissions p 
WHERE r.name = 'user' AND p.name IN ('dashboard.view');

-- เพิ่มผู้ใช้ทดสอบ (password = 'password' -> hashed)
INSERT INTO users (username, email, password, full_name) VALUES
('admin', 'admin@cps.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ผู้ดูแลระบบ'),
('manager', 'manager@cps.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ผู้จัดการ'),
('user1', 'user1@cps.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ผู้ใช้ 1');

-- Username + Password สำหรับทดสอบ:
-- admin / password
-- manager / password  
-- user1 / password

-- กำหนด role ให้ผู้ใช้
INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), -- admin มี role super_admin
(1, 2), -- admin มี role admin ด้วย
(2, 3), -- manager มี role manager
(2, 4), -- manager มี role user ด้วย
(3, 4); -- user1 มี role user

-- Index สำหรับ performance
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_user_roles_user_id ON user_roles(user_id);
CREATE INDEX idx_user_roles_role_id ON user_roles(role_id);
CREATE INDEX idx_role_permissions_role_id ON role_permissions(role_id);
CREATE INDEX idx_user_sessions_token ON user_sessions(session_token);
CREATE INDEX idx_user_sessions_expires ON user_sessions(expires_at);