<?php

class Material extends Model
{
    protected $table = 'materials';

    private const STATUS_ACTIVE = 'ใช้งาน';
    private const STATUS_INACTIVE = 'ปิดใช้งาน';

    private const ERROR_MISSING_FOREIGN_KEYS = 'กรุณาเลือกหน่วยนับและตำแหน่งจัดเก็บให้ครบถ้วน';
    private const ERROR_UNIT_NOT_FOUND = 'ไม่พบบันทึกหน่วยนับที่เลือก';
    private const ERROR_LOCATION_NOT_FOUND = 'ไม่พบตำแหน่งจัดเก็บที่เลือก';

    public function getAllMaterials(): array
    {
        $sql = "
            SELECT m.material_id as id, m.material_code, 
                   COALESCE(mn.name, m.description, 'N/A') as material_name,
                   COALESCE(u.unit_name, u.unit_code, 'N/A') as unit,
                   0 as stock_quantity, 0.00 as unit_price, 0 as min_stock,
                   'N/A' as supplier_name, m.is_active
            FROM materials m
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            ORDER BY m.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaterialsWithRelations(): array
    {
        $sql = "
            SELECT m.material_id as id, m.material_code, m.description, m.is_active,
                   u.unit_code, u.unit_name,
                   l.location_code, l.location_name,
                   COALESCE(mn.name, 'N/A') as material_name
            FROM materials m
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN locations l ON m.location_id = l.location_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            ORDER BY m.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($materials as &$material) {
            $material['is_active'] = ((int)($material['is_active'] ?? 0) === 1)
                ? self::STATUS_ACTIVE
                : self::STATUS_INACTIVE;
            $material['unit_name'] = $material['unit_name'] ?? $material['unit_code'];
            $material['location_name'] = $material['location_name'] ?? $material['location_code'];
        }

        return $materials;
    }

    public function getMaterialsWithPagination(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $searchCondition = '';
        $params = [];
        
        if (!empty($search)) {
            $searchCondition = "WHERE (m.material_code LIKE ? OR mn.name LIKE ? OR u.unit_name LIKE ? OR l.location_name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql = "
            SELECT m.material_id as id, m.material_code, m.description, m.is_active,
                   u.unit_code, u.unit_name,
                   l.location_code, l.location_name,
                   COALESCE(mn.name, 'N/A') as material_name
            FROM materials m
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN locations l ON m.location_id = l.location_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            $searchCondition
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($materials as &$material) {
            $material['is_active'] = ((int)($material['is_active'] ?? 0) === 1)
                ? self::STATUS_ACTIVE
                : self::STATUS_INACTIVE;
            $material['unit_name'] = $material['unit_name'] ?? $material['unit_code'];
            $material['location_name'] = $material['location_name'] ?? $material['location_code'];
        }

        return $materials;
    }

    public function getTotalMaterials(string $search = ''): int
    {
        $searchCondition = '';
        $params = [];
        
        if (!empty($search)) {
            $searchCondition = "WHERE (m.material_code LIKE ? OR mn.name LIKE ? OR u.unit_name LIKE ? OR l.location_name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql = "
            SELECT COUNT(DISTINCT m.material_id) as total
            FROM materials m
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN locations l ON m.location_id = l.location_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            $searchCondition
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($result['total'] ?? 0);
    }

    public function getUnits(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM units ORDER BY unit_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocations(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM locations ORDER BY location_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensureForeignKeysExist(?int $unitId, ?int $locationId): void
    {
        if (empty($unitId) || empty($locationId)) {
            throw new Exception(self::ERROR_MISSING_FOREIGN_KEYS);
        }

        $stmt = $this->db->prepare("SELECT unit_id FROM units WHERE unit_id = ?");
        $stmt->execute([$unitId]);
        if (!$stmt->fetch()) {
            throw new Exception(self::ERROR_UNIT_NOT_FOUND);
        }

        $stmt = $this->db->prepare("SELECT location_id FROM locations WHERE location_id = ?");
        $stmt->execute([$locationId]);
        if (!$stmt->fetch()) {
            throw new Exception(self::ERROR_LOCATION_NOT_FOUND);
        }
    }

    public function createMaterial(array $materialData, string $materialName): bool
    {
        $this->ensureForeignKeysExist(
            isset($materialData['default_unit']) ? (int)$materialData['default_unit'] : null,
            isset($materialData['location_id']) ? (int)$materialData['location_id'] : null
        );

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO materials (material_code, default_unit, location_id, description, is_active) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $materialData['material_code'] ?? '',
                $materialData['default_unit'],
                $materialData['location_id'],
                $materialData['description'] ?? '',
                isset($materialData['is_active']) ? (int)$materialData['is_active'] : 1,
            ]);

            $materialId = (int)$this->db->lastInsertId();

            $stmt = $this->db->prepare("
                INSERT INTO material_names (material_id, language_code, name, is_primary) 
                VALUES (?, 'th', ?, 1)
            ");
            $stmt->execute([$materialId, $materialName]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function findMaterialWithName(int $materialId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT m.material_id, m.material_code, m.default_unit, m.location_id, 
                   m.description, m.is_active, mn.name as material_name
            FROM materials m
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            WHERE m.material_id = ?
        ");
        $stmt->execute([$materialId]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);

        return $material ?: null;
    }

    public function updateMaterial(int $materialId, array $materialData, string $materialName): bool
    {
        $this->ensureForeignKeysExist(
            isset($materialData['default_unit']) ? (int)$materialData['default_unit'] : null,
            isset($materialData['location_id']) ? (int)$materialData['location_id'] : null
        );

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE materials SET material_code = ?, default_unit = ?, location_id = ?, 
                       description = ?, is_active = ? 
                WHERE material_id = ?
            ");
            $result = $stmt->execute([
                $materialData['material_code'] ?? '',
                $materialData['default_unit'],
                $materialData['location_id'],
                $materialData['description'] ?? '',
                isset($materialData['is_active']) ? (int)$materialData['is_active'] : 1,
                $materialId
            ]);

            if ($result) {
                $stmt = $this->db->prepare("
                    UPDATE material_names SET name = ? 
                    WHERE material_id = ? AND language_code = 'th' AND is_primary = 1
                ");
                $stmt->execute([$materialName, $materialId]);
            }

            $this->db->commit();
            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteMaterial(int $materialId): bool
    {
        if ($materialId <= 0) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM material_names WHERE material_id = ?");
            $stmt->execute([$materialId]);

            $stmt = $this->db->prepare("DELETE FROM materials WHERE material_id = ?");
            $stmt->execute([$materialId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getMaterialsWithFilters(int $page = 1, int $perPage = 10): array
    {
        require_once 'helpers/TableFilterHelper.php';
        
        $filterHelper = TableFilterHelper::create()
            ->addFilter('m.is_active', 'สถานะ', ['1' => 'ใช้งาน', '0' => 'ปิดใช้งาน'], 'select')
            ->addFilter('m.location_id', 'คลัง', array_column($this->getLocations(), 'location_name', 'location_id'), 'select')
            ->addSearchField('m.material_code')
            ->addSearchField('mn.name');
        
        $offset = ($page - 1) * $perPage;
        
        $baseQuery = "
            SELECT m.material_id as id, m.material_code, m.description, m.is_active,
                   u.unit_code, u.unit_name,
                   l.location_code, l.location_name,
                   COALESCE(mn.name, 'N/A') as material_name
            FROM materials m
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN locations l ON m.location_id = l.location_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
        ";
        
        $whereClause = $filterHelper->buildWhereClause($baseQuery);
        $orderClause = $filterHelper->buildOrderClause() ?: ' ORDER BY m.created_at DESC';
        
        $sql = $baseQuery . $whereClause['query'] . $orderClause . " LIMIT ? OFFSET ?";
        
        $params = array_merge($whereClause['params'], [$perPage, $offset]);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($materials as &$material) {
            $material['is_active'] = ((int)($material['is_active'] ?? 0) === 1)
                ? self::STATUS_ACTIVE
                : self::STATUS_INACTIVE;
            $material['unit_name'] = $material['unit_name'] ?? $material['unit_code'];
            $material['location_name'] = $material['location_name'] ?? $material['location_code'];
        }

        return $materials;
    }
    
    public function getTotalMaterialsWithFilters(): int
    {
        require_once 'helpers/TableFilterHelper.php';
        
        $filterHelper = TableFilterHelper::create()
            ->addFilter('m.is_active', 'สถานะ', ['1' => 'ใช้งาน', '0' => 'ปิดใช้งาน'], 'select')
            ->addFilter('m.location_id', 'คลัง', array_column($this->getLocations(), 'location_name', 'location_id'), 'select')
            ->addSearchField('m.material_code')
            ->addSearchField('mn.name');
        
        $baseQuery = "
            SELECT COUNT(DISTINCT m.material_id) as total
            FROM materials m
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN locations l ON m.location_id = l.location_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
        ";
        
        $whereClause = $filterHelper->buildWhereClause($baseQuery);
        $sql = $baseQuery . $whereClause['query'];
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($whereClause['params']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($result['total'] ?? 0);
    }

    public function getDashboardData(): array
    {
        return [
            'inventoryTrend' => [
                'categories' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'series' => [
                    [
                        'name' => 'Plan',
                        'data' => [85, 88, 90, 93],
                    ],
                    [
                        'name' => 'Actual',
                        'data' => [82, 84, 87, 91],
                    ],
                ],
            ],
            'materialBreakdown' => [
                'labels' => ['Raw Materials', 'Components', 'Assemblies', 'Packaging'],
                'series' => [35, 28, 22, 15],
            ],
        ];
    }

    public function getMaterialReceipts(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "
            SELECT mr.id, mr.receipt_no, mr.receipt_date, mr.supplier_name, mr.created_at,
                   mri.received_qty, mri.total_box_count, m.material_code,
                   COALESCE(mn.name, 'N/A') as material_name, l.location_name
            FROM material_receipts mr
            LEFT JOIN material_receipt_items mri ON mr.id = mri.receipt_id
            LEFT JOIN materials m ON mri.material_id = m.material_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            LEFT JOIN locations l ON mr.location_id = l.location_id
            ORDER BY mr.receipt_date DESC, mr.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalMaterialReceipts(): int
    {
        $sql = "SELECT COUNT(*) as total FROM material_receipts";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    public function getMaterialReceiptsWithFilters(int $page = 1, int $perPage = 10): array
    {
        require_once 'helpers/TableFilterHelper.php';
        
        $filterHelper = TableFilterHelper::create()
            ->addSearchField('mr.receipt_no')
            ->addSearchField('mr.supplier_name')
            ->addSearchField('m.material_code')
            ->addSearchField('mn.name');
        
        $offset = ($page - 1) * $perPage;
        
        $baseQuery = "
            SELECT mr.id, mr.receipt_no, mr.receipt_date, mr.supplier_name, mr.created_at,
                   mri.received_qty, mri.total_box_count, m.material_code,
                   COALESCE(mn.name, 'N/A') as material_name, l.location_name
            FROM material_receipts mr
            LEFT JOIN material_receipt_items mri ON mr.id = mri.receipt_id
            LEFT JOIN materials m ON mri.material_id = m.material_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            LEFT JOIN locations l ON mr.location_id = l.location_id
        ";
        
        $whereClause = $filterHelper->buildWhereClause($baseQuery);
        $orderClause = $filterHelper->buildOrderClause() ?: ' ORDER BY mr.receipt_date DESC, mr.created_at DESC';
        
        $sql = $baseQuery . $whereClause['query'] . $orderClause . " LIMIT ? OFFSET ?";
        
        $params = array_merge($whereClause['params'], [$perPage, $offset]);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalMaterialReceiptsWithFilters(): int
    {
        require_once 'helpers/TableFilterHelper.php';
        
        $filterHelper = TableFilterHelper::create()
            ->addSearchField('mr.receipt_no')
            ->addSearchField('mr.supplier_name')
            ->addSearchField('m.material_code')
            ->addSearchField('mn.name');
        
        $baseQuery = "
            SELECT COUNT(DISTINCT mr.id) as total
            FROM material_receipts mr
            LEFT JOIN material_receipt_items mri ON mr.id = mri.receipt_id
            LEFT JOIN materials m ON mri.material_id = m.material_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            LEFT JOIN locations l ON mr.location_id = l.location_id
        ";
        
        $whereClause = $filterHelper->buildWhereClause($baseQuery);
        $sql = $baseQuery . $whereClause['query'];
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($whereClause['params']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($result['total'] ?? 0);
    }

    public function createMaterialReceipt(array $receiptData): array
    {
        if (empty($receiptData['receipt_date']) || empty($receiptData['supplier_name']) || empty($receiptData['material_id']) || empty($receiptData['quantity'])) {
            throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
        }

        try {
            $this->db->beginTransaction();
            
            // Ensure location exists
            $this->db->exec("INSERT IGNORE INTO locations (location_code, location_name) VALUES ('RM-A', 'คลังวัตถุดิบ A')");
            $stmt = $this->db->prepare("SELECT location_id FROM locations WHERE location_code = 'RM-A'");
            $stmt->execute();
            $locationId = $stmt->fetchColumn();

            // Get material packing info
            $stmt = $this->db->prepare("SELECT packing_qty FROM materials WHERE material_id = ?");
            $stmt->execute([$receiptData['material_id']]);
            $material = $stmt->fetch();
            $packingQty = $material['packing_qty'] ?? 1;

            $receiptNo = !empty($receiptData['reference_no']) ? $receiptData['reference_no'] : 'RCP-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            // Insert receipt header
            $stmt = $this->db->prepare("INSERT INTO material_receipts (receipt_no, receipt_date, supplier_name, location_id, created_by) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$receiptNo, $receiptData['receipt_date'], $receiptData['supplier_name'], $locationId, $receiptData['created_by'] ?? null]);
            $receiptId = $this->db->lastInsertId();

            // Calculate packing details
            $receivedQty = (int)$receiptData['quantity'];
            $fullBoxCount = intval($receivedQty / $packingQty);
            $partialBoxQty = $receivedQty % $packingQty;
            $totalBoxCount = $fullBoxCount + ($partialBoxQty > 0 ? 1 : 0);
            $lotNo = 'LOT-' . date('Ymd') . '-' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);

            // Insert receipt item
            $stmt = $this->db->prepare("INSERT INTO material_receipt_items (receipt_id, material_id, received_qty, packing_qty, full_box_count, partial_box_qty, total_box_count, lot_no, location_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$receiptId, $receiptData['material_id'], $receivedQty, $packingQty, $fullBoxCount, $partialBoxQty, $totalBoxCount, $lotNo, $locationId]);
            $receiptItemId = $this->db->lastInsertId();

            // Generate QR codes for each box
            $qrCodes = [];
            for ($i = 1; $i <= $totalBoxCount; $i++) {
                $qrCode = $lotNo . '-BOX-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $packSize = ($i <= $fullBoxCount) ? $packingQty : $partialBoxQty;
                
                $stmt = $this->db->prepare("INSERT INTO material_stock_lots (receipt_item_id, material_id, location_id, lot_no, pack_no, pack_size, qr_code, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'AVAILABLE')");
                $stmt->execute([$receiptItemId, $receiptData['material_id'], $locationId, $lotNo, $i, $packSize, $qrCode]);
                
                $qrCodes[] = [
                    'qr_code' => $qrCode,
                    'pack_no' => $i,
                    'pack_size' => $packSize
                ];
            }

            $this->db->commit();
            return [
                'receipt_id' => $receiptId,
                'receipt_no' => $receiptNo,
                'qr_codes' => $qrCodes
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteMaterialReceipt(int $receiptId): bool
    {
        if ($receiptId <= 0) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM material_receipts WHERE id = ?");
        return $stmt->execute([$receiptId]);
    }

    public function getReceiptDetail(int $receiptId): ?array
    {
        $sql = "
            SELECT mr.id, mr.receipt_no, mr.receipt_date, mr.supplier_name, mr.created_at,
                   mri.id as item_id, mri.received_qty, mri.packing_qty, mri.full_box_count, 
                   mri.partial_box_qty, mri.total_box_count, mri.lot_no,
                   m.material_code, COALESCE(mn.name, 'N/A') as material_name,
                   u.unit_name, l.location_name
            FROM material_receipts mr
            LEFT JOIN material_receipt_items mri ON mr.id = mri.receipt_id
            LEFT JOIN materials m ON mri.material_id = m.material_id
            LEFT JOIN material_names mn ON m.material_id = mn.material_id 
                AND mn.language_code = 'th' AND mn.is_primary = 1
            LEFT JOIN units u ON m.default_unit = u.unit_id
            LEFT JOIN locations l ON mr.location_id = l.location_id
            WHERE mr.id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$receiptId]);
        $receiptData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$receiptData) {
            return null;
        }
        
        // Get QR codes for this receipt
        $qrSql = "
            SELECT msl.qr_code, msl.pack_no, msl.pack_size, msl.status
            FROM material_stock_lots msl
            WHERE msl.receipt_item_id = ?
            ORDER BY msl.pack_no
        ";
        
        $qrStmt = $this->db->prepare($qrSql);
        $qrStmt->execute([$receiptData['item_id']]);
        $qrCodes = $qrStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $receiptData['qr_codes'] = $qrCodes;
        
        return $receiptData;
    }
}