<?php

class User extends Model {
    
    public function authenticate($username, $password) {
        $sql = "SELECT u.*, GROUP_CONCAT(r.name) as roles 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.username = ? AND u.is_active = 1 
                GROUP BY u.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function getUserPermissions($userId) {
        $sql = "SELECT DISTINCT p.name, p.display_name, p.module 
                FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                JOIN user_roles ur ON rp.role_id = ur.role_id 
                WHERE ur.user_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function hasPermission($userId, $permission) {
        $sql = "SELECT COUNT(*) as count 
                FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                JOIN user_roles ur ON rp.role_id = ur.role_id 
                WHERE ur.user_id = ? AND p.name = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $permission]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] > 0;
    }
    
    public function createSession($userId, $token, $ipAddress, $userAgent, $expiresAt) {
        $sql = "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $token, $ipAddress, $userAgent, $expiresAt]);
    }
    
    public function validateSession($token) {
        $sql = "SELECT us.*, u.username, u.full_name, u.email 
                FROM user_sessions us 
                JOIN users u ON us.user_id = u.id 
                WHERE us.session_token = ? AND us.expires_at > NOW() AND u.is_active = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deleteSession($token) {
        $sql = "DELETE FROM user_sessions WHERE session_token = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token]);
    }
    
    public function getAllUsers() {
        $sql = "SELECT u.*, GROUP_CONCAT(r.display_name SEPARATOR ', ') as role_names 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                GROUP BY u.id 
                ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllRoles() {
        $sql = "SELECT * FROM roles WHERE is_active = 1 ORDER BY display_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserRoles($userId) {
        $sql = "SELECT role_id FROM user_roles WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'role_id');
    }
    
    public function createUser($username, $email, $password, $full_name, $roles = []) {
        try {
            $this->db->beginTransaction();
            
            // เพิ่มผู้ใช้
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username, $email, $hashedPassword, $full_name]);
            
            $userId = $this->db->lastInsertId();
            
            // เพิ่ม roles
            if (!empty($roles)) {
                $sql = "INSERT INTO user_roles (user_id, role_id, assigned_by) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                
                foreach ($roles as $roleId) {
                    $stmt->execute([$userId, $roleId, $_SESSION['user_id']]);
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function updateUser($id, $username, $email, $full_name, $roles = [], $is_active = 1) {
        try {
            $this->db->beginTransaction();
            
            // อัพเดทข้อมูลผู้ใช้
            $sql = "UPDATE users SET username = ?, email = ?, full_name = ?, is_active = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username, $email, $full_name, $is_active, $id]);
            
            // ลบ roles เดิม
            $sql = "DELETE FROM user_roles WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            // เพิ่ม roles ใหม่
            if (!empty($roles)) {
                $sql = "INSERT INTO user_roles (user_id, role_id, assigned_by) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                
                foreach ($roles as $roleId) {
                    $stmt->execute([$id, $roleId, $_SESSION['user_id']]);
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function getAllUsersWithPagination($search = '', $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE u.username LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }
        
        $sql = "SELECT u.*, GROUP_CONCAT(r.display_name SEPARATOR ', ') as role_names 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                $whereClause
                GROUP BY u.id 
                ORDER BY u.created_at DESC 
                LIMIT $perPage OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalUsers($search = '') {
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE username LIKE ? OR full_name LIKE ? OR email LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }
        
        $sql = "SELECT COUNT(*) as total FROM users $whereClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function deleteUser($id) {
        try {
            $this->db->beginTransaction();
            
            // ลบ user_roles ก่อน
            $sql = "DELETE FROM user_roles WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            // ลบ user
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}