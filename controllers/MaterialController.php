<?php

class MaterialController extends Controller
{
    private $materialModel;

    public function __construct()
    {
        $this->materialModel = $this->model('Material');
        require_once 'helpers/TableFilterHelper.php';
    }

    public function index()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        
        $receiptPage = (int)($_GET['receipt_page'] ?? 1);
        $receiptPerPage = (int)($_GET['receipt_per_page'] ?? 10);
        
        $materials = $this->materialModel->getMaterialsWithFilters($page, $perPage);
        $totalRecords = $this->materialModel->getTotalMaterialsWithFilters();
        $receipts = $this->materialModel->getMaterialReceiptsWithFilters($receiptPage, $receiptPerPage);
        $totalReceiptRecords = $this->materialModel->getTotalMaterialReceiptsWithFilters();
        
        $data = [
            'materials' => $materials,
            'receipts' => $receipts,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords,
            'receiptCurrentPage' => $receiptPage,
            'receiptPerPage' => $receiptPerPage,
            'totalReceiptRecords' => $totalReceiptRecords,
            'units' => $this->materialModel->getUnits(),
            'locations' => $this->materialModel->getLocations(),
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้งาน',
        ];

        $this->view('materials/index', $data);
    }

    public function store()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'รูปแบบคำขอไม่ถูกต้อง']);
        }

        $payload = [
            'material_code' => trim((string)$this->getPost('material_code')),
            'default_unit' => $this->getPost('default_unit'),
            'location_id' => $this->getPost('location_id'),
            'description' => trim((string)$this->getPost('description')),
            'is_active' => (int)($this->getPost('is_active') ?? 1),
        ];
        $materialName = trim((string)$this->getPost('material_name'));

        try {
            $this->materialModel->createMaterial($payload, $materialName);
            $this->json(['success' => true]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        $materialId = (int)($this->getGet('id') ?? 0);
        if ($materialId <= 0) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'ไม่พบข้อมูลวัสดุ']);
        }

        $material = $this->materialModel->findMaterialWithName($materialId);

        if (!$material) {
            $this->json(['success' => false, 'message' => 'ไม่พบข้อมูลวัสดุ']);
        }

        $this->json(['success' => true, 'material' => $material]);
    }

    public function update()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'รูปแบบคำขอไม่ถูกต้อง']);
        }

        $materialId = (int)($this->getPost('material_id') ?? 0);
        if ($materialId <= 0) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'รหัสวัสดุไม่ถูกต้อง']);
        }

        $payload = [
            'material_code' => trim((string)$this->getPost('material_code')),
            'default_unit' => $this->getPost('default_unit'),
            'location_id' => $this->getPost('location_id'),
            'description' => trim((string)$this->getPost('description')),
            'is_active' => (int)($this->getPost('is_active') ?? 1),
        ];
        $materialName = trim((string)$this->getPost('material_name'));

        try {
            $result = $this->materialModel->updateMaterial($materialId, $payload, $materialName);
            $this->json(['success' => (bool)$result]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit($id = null)
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        $materialId = (int)($id ?? 0);
        if ($materialId <= 0) {
            header('Location: ' . BASE_URL . '?url=materials');
            exit;
        }

        $material = $this->materialModel->findMaterialWithName($materialId);
        if (!$material) {
            header('Location: ' . BASE_URL . '?url=materials');
            exit;
        }

        $data = [
            'material' => $material,
            'units' => $this->materialModel->getUnits(),
            'locations' => $this->materialModel->getLocations(),
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้งาน',
        ];

        $this->view('materials/edit', $data);
    }

    public function delete()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'รูปแบบคำขอไม่ถูกต้อง']);
        }

        $materialId = (int)($this->getPost('material_id') ?? 0);
        if ($materialId <= 0) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'รหัสวัสดุไม่ถูกต้อง']);
        }

        try {
            $result = $this->materialModel->deleteMaterial($materialId);
            $this->json(['success' => (bool)$result]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function receipt()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        
        $receipts = $this->materialModel->getMaterialReceiptsWithFilters($page, $perPage);
        $totalRecords = $this->materialModel->getTotalMaterialReceiptsWithFilters();
        $materials = $this->materialModel->getMaterialsWithRelations();
        
        $data = [
            'receipts' => $receipts,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords,
            'materials' => $materials,
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้งาน',
        ];

        $this->view('materials/receipt/index', $data);
    }

    public function storeReceipt()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'รูปแบบคำขอไม่ถูกต้อง']);
        }

        $payload = [
            'receipt_date' => $this->getPost('receipt_date'),
            'material_id' => (int)$this->getPost('material_id'),
            'quantity' => (int)$this->getPost('quantity'),
            'supplier_name' => trim((string)$this->getPost('supplier_name')),
            'reference_no' => trim((string)$this->getPost('reference_no')),
            'created_by' => $_SESSION['user_id'] ?? null,
        ];

        try {
            $result = $this->materialModel->createMaterialReceipt($payload);
            $this->json(['success' => true, 'data' => $result]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteReceipt()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'รูปแบบคำขอไม่ถูกต้อง']);
        }

        $receiptId = (int)($this->getPost('receipt_id') ?? 0);
        if ($receiptId <= 0) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'รหัสรายการไม่ถูกต้อง']);
        }

        try {
            $result = $this->materialModel->deleteMaterialReceipt($receiptId);
            $this->json(['success' => (bool)$result]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function issue()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        $data = [
            'issues' => [],
            'totalRecords' => 0,
            'currentPage' => 1,
            'perPage' => 10,
            'materials' => $this->materialModel->getAllMaterials(),
            'username' => $_SESSION['full_name'] ?? 'ผู้ใช้งาน',
        ];

        $this->view('materials/issue/index', $data);
    }
}