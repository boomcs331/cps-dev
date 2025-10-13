<?php

class Role extends Model {
    
    public function getAllRoles($search = '', $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE name LIKE ? OR display_name LIKE ? OR description LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }
        
        $sql = "SELECT * FROM roles $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalRoles($search = '') {
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE name LIKE ? OR display_name LIKE ? OR description LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }
        
        $sql = "SELECT COUNT(*) as total FROM roles $whereClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getRoleById($id) {
        $sql = "SELECT * FROM roles WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createRole($name, $display_name, $description = '') {
        $sql = "INSERT INTO roles (name, display_name, description) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$name, $display_name, $description]);
    }
    
    public function updateRole($id, $name, $display_name, $description = '', $is_active = 1) {
        $sql = "UPDATE roles SET name = ?, display_name = ?, description = ?, is_active = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$name, $display_name, $description, $is_active, $id]);
    }
    
    public function deleteRole($id) {
        try {
            $this->db->beginTransaction();
            
            // ลบ role_permissions ก่อน
            $sql = "DELETE FROM role_permissions WHERE role_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            // ลบ user_roles
            $sql = "DELETE FROM user_roles WHERE role_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            // ลบ role
            $sql = "DELETE FROM roles WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function getRolePermissions($roleId) {
        $sql = "SELECT permission_id FROM role_permissions WHERE role_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleId]);
        
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'permission_id');
    }
    
    public function updateRolePermissions($roleId, $permissions = []) {
        try {
            $this->db->beginTransaction();
            
            // ลบ permissions เดิม
            $sql = "DELETE FROM role_permissions WHERE role_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roleId]);
            
            // เพิ่ม permissions ใหม่
            if (!empty($permissions)) {
                $sql = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                
                foreach ($permissions as $permissionId) {
                    $stmt->execute([$roleId, $permissionId]);
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function getAllPermissions() {
        $sql = "SELECT * FROM permissions ORDER BY module, display_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}