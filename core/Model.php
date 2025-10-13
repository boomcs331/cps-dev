<?php
/**
 * Base Model Class
 * คลาสหลักสำหรับจัดการฐานข้อมูล
 */
class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * ดึงข้อมูลทั้งหมด
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * ดึงข้อมูลตาม ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * เพิ่มข้อมูลใหม่
     */
    public function create($data)
    {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $field_list = implode(', ', $fields);
        
        $sql = "INSERT INTO {$this->table} ({$field_list}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * อัปเดตข้อมูล
     */
    public function update($id, $data)
    {
        $fields = array_keys($data);
        $set_clause = '';
        
        foreach ($fields as $field) {
            $set_clause .= "{$field} = :{$field}, ";
        }
        $set_clause = rtrim($set_clause, ', ');
        
        $sql = "UPDATE {$this->table} SET {$set_clause} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    /**
     * ลบข้อมูล
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * ค้นหาข้อมูล
     */
    public function where($conditions)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $where_clause = '';
        
        foreach ($conditions as $field => $value) {
            $where_clause .= "{$field} = :{$field} AND ";
        }
        $where_clause = rtrim($where_clause, ' AND ');
        
        $sql .= $where_clause;
        $stmt = $this->db->prepare($sql);
        
        foreach ($conditions as $field => $value) {
            $stmt->bindValue(":{$field}", $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * ดึงข้อมูลแบบ pagination
     */
    public function paginate($page = 1, $per_page = 10)
    {
        $offset = ($page - 1) * $per_page;
        
        // นับจำนวนทั้งหมด
        $count_sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute();
        $total = $count_stmt->fetch()['total'];
        
        // ดึงข้อมูล
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $per_page,
            'current_page' => $page,
            'last_page' => ceil($total / $per_page)
        ];
    }

    /**
     * Get database connection
     */
    public function getDb()
    {
        return $this->db;
    }
}
?> 