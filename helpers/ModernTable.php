<?php

class ModernTable {
    private $data;
    private $columns;
    private $actions;
    private $currentPage;
    private $perPage;
    private $totalRecords;
    private $title;
    private $subtitle;
    private $filters;
    
    public function __construct($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        $this->data = $data;
        $this->columns = [];
        $this->actions = [];
        $this->filters = [];
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->totalRecords = $totalRecords;
        $this->title = 'รายการข้อมูล';
        $this->subtitle = 'จัดการข้อมูลในระบบ';
    }
    
    public function setTitle($title, $subtitle = '') {
        $this->title = $title;
        $this->subtitle = $subtitle;
        return $this;
    }
    
    public function addColumn($key, $label, $width = null) {
        $this->columns[$key] = ['label' => $label, 'width' => $width];
        return $this;
    }
    
    public function addFilter($name, $options, $selected = '') {
        $this->filters[] = ['name' => $name, 'options' => $options, 'selected' => $selected];
        return $this;
    }
    
    public function addAction($label, $url, $class = 'btn-outline-primary', $icon = '') {
        $this->actions[] = ['label' => $label, 'url' => $url, 'class' => $class, 'icon' => $icon];
        return $this;
    }
    
    public function render() {
        $html = $this->getCSS();
        $html .= '<div class="modern-table-wrapper">';
        $html .= $this->renderHeader();
        $html .= $this->renderCard();
        $html .= '</div>';
        return $html;
    }
    
    private function renderHeader() {
        return '<div class="page-header d-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0">' . htmlspecialchars($this->title) . '</h1>
            <button class="btn btn-primary btn-pill" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> เพิ่มข้อมูล
            </button>
        </div>';
    }
    
    private function renderCard() {
        $html = '<div class="card">';
        $html .= $this->renderCardHeader();
        $html .= $this->renderTable();
        $html .= $this->renderPagination();
        $html .= '</div>';
        return $html;
    }
    
    private function renderCardHeader() {
        $html = '<div class="card-header d-flex flex-wrap align-items-center justify-content-between">';
        $html .= '<div class="mb-2 mb-sm-0">';
        $html .= '<h2 class="h5 mb-1">' . htmlspecialchars($this->title) . '</h2>';
        $html .= '<small class="text-muted">' . htmlspecialchars($this->subtitle) . '</small>';
        $html .= '</div>';
        
        $html .= '<div class="d-flex align-items-center">';
        foreach ($this->filters as $filter) {
            $html .= '<div class="mr-2">';
            $html .= '<select class="form-control form-control-sm btn-pill" name="' . $filter['name'] . '">';
            foreach ($filter['options'] as $value => $label) {
                $selected = ($value == $filter['selected']) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>';
            }
            $html .= '</select></div>';
        }
        $html .= '<input type="text" class="form-control form-control-sm btn-pill search-inline" placeholder="ค้นหา…" name="search" value="' . htmlspecialchars($_GET['search'] ?? '') . '" />';
        $html .= '</div></div>';
        
        return $html;
    }
    
    private function renderTable() {
        $html = '<div class="card-body p-0">';
        $html .= '<div class="table-responsive p-3">';
        $html .= '<table class="table table-striped table-hover w-100">';
        
        // Header
        $html .= '<thead><tr>';
        foreach ($this->columns as $key => $column) {
            $width = $column['width'] ? ' style="width:' . $column['width'] . '"' : '';
            $html .= '<th' . $width . '>' . htmlspecialchars($column['label']) . '</th>';
        }
        if (!empty($this->actions)) {
            $html .= '<th style="width:120px">Actions</th>';
        }
        $html .= '</tr></thead>';
        
        // Body
        $html .= '<tbody>';
        if (empty($this->data)) {
            $colCount = count($this->columns) + (!empty($this->actions) ? 1 : 0);
            $html .= '<tr><td colspan="' . $colCount . '" class="text-center py-4 text-muted">ไม่พบข้อมูล</td></tr>';
        } else {
            foreach ($this->data as $row) {
                $html .= '<tr>';
                foreach (array_keys($this->columns) as $key) {
                    $value = $row[$key] ?? '';
                    $html .= '<td>' . $this->formatCell($key, $value, $row) . '</td>';
                }
                
                if (!empty($this->actions)) {
                    $html .= '<td><div class="btn-group btn-group-sm" role="group">';
                    foreach ($this->actions as $action) {
                        $url = str_replace('{id}', $row['id'] ?? '', $action['url']);
                        $onclick = strpos($url, 'javascript:') === 0 ? ' onclick="' . substr($url, 11) . '"' : ' onclick="window.location.href=\'' . $url . '\'"';
                        $html .= '<button class="btn ' . $action['class'] . ' btn-pill"' . $onclick . '>';
                        if ($action['icon']) $html .= '<i class="' . $action['icon'] . '"></i> ';
                        $html .= $action['label'] . '</button>';
                    }
                    $html .= '</div></td>';
                }
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></div></div>';
        
        return $html;
    }
    
    private function formatCell($key, $value, $row) {
        if (strpos($key, 'name') !== false && isset($row['description'])) {
            return '<div class="font-weight-bold">' . htmlspecialchars($value) . '</div>
                    <small class="text-muted">' . htmlspecialchars($row['description']) . '</small>';
        }
        
        if ($key === 'is_active' || strpos($key, 'status') !== false) {
            $class = ($value === 'ใช้งาน' || $value === 'UP') ? 'success' : 
                    (($value === 'WARN') ? 'warning' : 'danger');
            return '<span class="badge-soft ' . $class . '">' . htmlspecialchars($value) . '</span>';
        }
        
        if (strpos($key, 'code') !== false) {
            return '<code>' . htmlspecialchars($value) . '</code>';
        }
        
        return htmlspecialchars($value);
    }
    
    private function renderPagination() {
        $totalPages = ceil($this->totalRecords / $this->perPage);
        if ($totalPages <= 1) return '';
        
        $html = '<div class="card-footer d-flex align-items-center justify-content-between">';
        
        $start = ($this->currentPage - 1) * $this->perPage + 1;
        $end = min($this->currentPage * $this->perPage, $this->totalRecords);
        $html .= '<small class="text-muted">แสดง ' . $start . ' ถึง ' . $end . ' จาก ' . number_format($this->totalRecords) . ' รายการ</small>';
        
        $html .= '<nav><ul class="pagination mb-0">';
        
        if ($this->currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage - 1) . '">Previous</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        $start = max(1, $this->currentPage - 2);
        $end = min($totalPages, $this->currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $this->currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $this->getPageUrl($i) . '">' . $i . '</a></li>';
        }
        
        if ($this->currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage + 1) . '">Next</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        $html .= '</ul></nav></div>';
        
        return $html;
    }
    
    private function getPageUrl($page) {
        $params = $_GET;
        $params['page'] = $page;
        $currentUrl = $_GET['url'] ?? '';
        return (defined('BASE_URL') ? BASE_URL : '/') . '?url=' . $currentUrl . '&' . http_build_query($params);
    }
    
    private function getCSS() {
        return '<style>
        .modern-table-wrapper { background: #f8fafc; }
        .page-header { padding: 24px 0 12px; }
        .card { border: 0; box-shadow: 0 4px 16px rgba(15,23,42,.08); border-radius: 18px; }
        .card-header { background: linear-gradient(180deg,#fff,#f9fbff); border-bottom: 0; border-radius: 18px 18px 0 0; }
        .table thead th { border-bottom: 0!important; font-weight: 700; color: #374151; }
        table.table td, table.table th { vertical-align: middle; }
        .badge-soft { background: rgba(59,130,246,.12); color: #2563eb; border-radius: 9999px; padding: .35rem .7rem; font-weight: 600; }
        .badge-soft.success { background: rgba(16,185,129,.14); color: #059669; }
        .badge-soft.warning { background: rgba(245,158,11,.15); color: #b45309; }
        .badge-soft.danger { background: rgba(239,68,68,.15); color: #b91c1c; }
        .btn-pill { border-radius: 12px; }
        .search-inline { max-width: 260px; }
        </style>';
    }
    
    public static function create($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        return new self($data, $currentPage, $perPage, $totalRecords);
    }
}