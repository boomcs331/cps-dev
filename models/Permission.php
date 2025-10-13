<?php

class Permission extends Model {
    
    public function getAllPermissions($page = 1, $perPage = 10, $search = '') {
        $page = (int)$page;
        $perPage = (int)$perPage;
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE name LIKE ? OR display_name LIKE ? OR description LIKE ? OR module LIKE ?";
            $searchParam = '%' . $search . '%';
            $params = [$searchParam, $searchParam, $searchParam, $searchParam];
        }
        
        $sql = "SELECT * FROM permissions $whereClause ORDER BY module, display_name LIMIT $perPage OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalPermissions($search = '') {
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE name LIKE ? OR display_name LIKE ? OR description LIKE ? OR module LIKE ?";
            $searchParam = '%' . $search . '%';
            $params = [$searchParam, $searchParam, $searchParam, $searchParam];
        }
        
        $sql = "SELECT COUNT(*) as total FROM permissions $whereClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getPermissionById($id) {
        $sql = "SELECT * FROM permissions WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createPermission($name, $displayName, $description, $module) {
        $sql = "INSERT INTO permissions (name, display_name, description, module) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $displayName, $description, $module]);
    }
    
    public function updatePermission($id, $name, $displayName, $description, $module) {
        $sql = "UPDATE permissions SET name = ?, display_name = ?, description = ?, module = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $displayName, $description, $module, $id]);
    }
    
    public function deletePermission($id) {
        $sql = "DELETE FROM permissions WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
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
    
    public function getDistinctModules() {
        $sql = "SELECT DISTINCT module FROM permissions WHERE module IS NOT NULL AND module != '' ORDER BY module";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'module');
    }
}