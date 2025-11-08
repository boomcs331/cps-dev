<?php

class ServerSideTable {
    private $data;
    private $columns;
    private $actions;
    private $currentPage;
    private $perPage;
    private $totalRecords;
    private $baseUrl;
    
    public function __construct($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        $this->data = $data;
        $this->columns = [];
        $this->actions = [];
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->totalRecords = $totalRecords;
    $this->baseUrl = defined('BASE_URL') ? BASE_URL : '/';
    }
    
    public function addColumn($key, $label) {
        $this->columns[$key] = $label;
        return $this;
    }
    
    public function addAction($label, $url, $class = 'btn-primary', $icon = '') {
        $this->actions[] = [
            'label' => $label,
            'url' => $url,
            'class' => $class,
            'icon' => $icon
        ];
        return $this;
    }
    
    public function render($tableId = 'dataTable') {
        $html = '<div class="modern-table-container">';
        
        // Enhanced Controls Bar
        $html .= '<div class="table-controls">';
        $html .= '<div class="controls-left">';
        $html .= '<div class="search-box">';
        $html .= '<form method="GET" class="search-form">';
        
        foreach ($_GET as $key => $value) {
            if ($key !== 'search' && $key !== 'per_page' && $key !== 'page') {
                $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        
        $html .= '<div class="input-group">';
        $html .= '<span class="input-group-text"><i class="fas fa-search"></i></span>';
        $html .= '<input type="text" name="search" class="form-control" placeholder="ค้นหาข้อมูล..." value="' . htmlspecialchars($_GET['search'] ?? '') . '">';
        $html .= '<button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>';
        $html .= '</div></form></div></div>';
        
        $html .= '<div class="controls-right">';
        $html .= '<div class="per-page-selector">';
        $html .= '<form method="GET" class="d-flex align-items-center">';
        
        foreach ($_GET as $key => $value) {
            if ($key !== 'per_page' && $key !== 'page') {
                $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        
        $html .= '<label class="me-2 text-muted">แสดง:</label>';
        $html .= '<select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">';
        $perPageOptions = [5, 10, 25, 50];
        foreach ($perPageOptions as $option) {
            $selected = ($this->perPage == $option) ? ' selected' : '';
            $html .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
        }
        $html .= '</select><span class="ms-2 text-muted">รายการ</span>';
        $html .= '</form></div></div></div>';
        
        // Enhanced Table
        $html .= '<div class="table-wrapper">';
        $html .= '<table class="table modern-table">';
        
        // Enhanced Header
        $html .= '<thead class="table-header"><tr>';
        foreach ($this->columns as $label) {
            $html .= '<th class="sortable">' . htmlspecialchars($label) . '</th>';
        }
        if (!empty($this->actions)) {
            $html .= '<th class="actions-column">การจัดการ</th>';
        }
        $html .= '</tr></thead>';
        
        // Enhanced Body
        $html .= '<tbody>';
        if (empty($this->data)) {
            $colCount = count($this->columns) + (!empty($this->actions) ? 1 : 0);
            $html .= '<tr><td colspan="' . $colCount . '" class="no-data"><div class="empty-state"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><p class="text-muted">ไม่พบข้อมูล</p></div></td></tr>';
        } else {
            foreach ($this->data as $index => $row) {
                $html .= '<tr class="table-row" data-index="' . $index . '">';
                foreach (array_keys($this->columns) as $key) {
                    $value = $row[$key] ?? '';
                    $html .= '<td class="cell-' . $key . '">';
                    
                    if ($key === 'is_active') {
                        $statusClass = ($value === 'ใช้งาน') ? 'success' : 'secondary';
                        $html .= '<span class="badge bg-' . $statusClass . '">' . htmlspecialchars($value) . '</span>';
                    } elseif ($key === 'material_code') {
                        $html .= '<code class="code-highlight">' . htmlspecialchars($value) . '</code>';
                    } elseif (strpos($key, 'name') !== false) {
                        $html .= '<span class="fw-medium">' . htmlspecialchars($value) . '</span>';
                    } else {
                        $html .= htmlspecialchars($value);
                    }
                    
                    $html .= '</td>';
                }
                
                // Enhanced Actions
                if (!empty($this->actions)) {
                    $html .= '<td class="actions-cell">';
                    $html .= '<div class="btn-group" role="group">';
                    foreach ($this->actions as $action) {
                        $url = str_replace('{id}', $row['id'] ?? '', $action['url']);
                        $html .= '<button type="button" onclick="' . (strpos($url, 'javascript:') === 0 ? substr($url, 11) : "window.location.href='" . $url . "'") . '" class="btn btn-sm ' . $action['class'] . '" title="' . $action['label'] . '">';
                        if ($action['icon']) {
                            $html .= '<i class="' . $action['icon'] . '"></i>';
                        } else {
                            $html .= $action['label'];
                        }
                        $html .= '</button>';
                    }
                    $html .= '</div></td>';
                }
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></div>';
        
        // Enhanced Pagination
        $html .= $this->renderPagination();
        $html .= '</div>';
        
        // Add CSS
        $html .= $this->getCSS();
        
        return $html;
    }
    
    private function renderPagination() {
        $totalPages = ceil($this->totalRecords / $this->perPage);
        
        if ($totalPages <= 1) return '<div class="pagination-info"><span class="text-muted">แสดงทั้งหมด ' . $this->totalRecords . ' รายการ</span></div>';
        
        $html = '<div class="pagination-container">';
        
        // Info
        $start = ($this->currentPage - 1) * $this->perPage + 1;
        $end = min($this->currentPage * $this->perPage, $this->totalRecords);
        $html .= '<div class="pagination-info"><span class="text-muted">แสดง ' . $start . '-' . $end . ' จาก ' . number_format($this->totalRecords) . ' รายการ</span></div>';
        
        $html .= '<nav class="pagination-nav"><ul class="pagination pagination-modern">';
        
        // First & Previous
        if ($this->currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl(1) . '" title="หน้าแรก"><i class="fas fa-angle-double-left"></i></a></li>';
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage - 1) . '" title="ก่อนหน้า"><i class="fas fa-angle-left"></i></a></li>';
        }
        
        // Pages
        $start = max(1, $this->currentPage - 2);
        $end = min($totalPages, $this->currentPage + 2);
        
        if ($start > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl(1) . '">1</a></li>';
            if ($start > 2) $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $this->currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $this->getPageUrl($i) . '">' . $i . '</a></li>';
        }
        
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($totalPages) . '">' . $totalPages . '</a></li>';
        }
        
        // Next & Last
        if ($this->currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage + 1) . '" title="ถัดไป"><i class="fas fa-angle-right"></i></a></li>';
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($totalPages) . '" title="หน้าสุดท้าย"><i class="fas fa-angle-double-right"></i></a></li>';
        }
        
        $html .= '</ul></nav></div>';
        
        return $html;
    }
    
    private function getPageUrl($page) {
        $params = $_GET;
        $params['page'] = $page;
        
        // Get current URL path
        $currentUrl = $_GET['url'] ?? 'materials';
        
        return (defined('BASE_URL') ? BASE_URL : '/') . '?url=' . $currentUrl . '&' . http_build_query($params);
    }
    
    private function getCSS() {
        return '<style>
        .modern-table-container { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden; }
        .table-controls { padding: 20px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .search-form .input-group { min-width: 300px; }
        .search-form .input-group-text { background: #fff; border-right: 0; }
        .search-form .form-control { border-left: 0; border-right: 0; }
        .search-form .btn { border-left: 0; }
        .per-page-selector { display: flex; align-items: center; gap: 8px; }
        .table-wrapper { overflow-x: auto; }
        .modern-table { margin: 0; }
        .table-header th { background: #f8f9fa; font-weight: 600; color: #495057; border-bottom: 2px solid #dee2e6; padding: 15px 12px; white-space: nowrap; }
        .table-header th.sortable { cursor: pointer; transition: all 0.2s; }
        .table-header th.sortable:hover { background: #e9ecef; }
        .table-row { transition: all 0.2s; }
        .table-row:hover { background: #f8f9fa; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .table-row td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #f1f3f4; }
        .code-highlight { background: #f8f9fa; color: #e83e8c; padding: 4px 8px; border-radius: 4px; font-size: 0.875em; }
        .actions-cell { text-align: center; }
        .actions-column { text-align: center; width: 120px; }
        .btn-group .btn { margin: 0 2px; }
        .no-data { text-align: center; padding: 60px 20px; }
        .empty-state { display: flex; flex-direction: column; align-items: center; }
        .pagination-container { padding: 20px; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .pagination-modern { margin: 0; }
        .pagination-modern .page-link { border: 0; margin: 0 2px; border-radius: 8px; padding: 8px 12px; transition: all 0.2s; }
        .pagination-modern .page-item.active .page-link { background: #007bff; color: white; }
        .pagination-modern .page-link:hover { background: #e9ecef; }
        .pagination-info { font-size: 0.875em; }
        @media (max-width: 768px) {
            .table-controls { flex-direction: column; align-items: stretch; }
            .search-form .input-group { min-width: 100%; }
            .pagination-container { flex-direction: column; gap: 15px; }
        }
        </style>';
    }
    
    public static function create($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        return new self($data, $currentPage, $perPage, $totalRecords);
    }
}