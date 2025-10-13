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
        $this->baseUrl = $_SERVER['REQUEST_URI'];
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
        // Search and Per Page Controls
        $html = '<div class="row mb-3">';
        $html .= '<div class="col-md-6">';
        $html .= '<form method="GET" class="d-flex">';
        
        // Keep existing parameters
        foreach ($_GET as $key => $value) {
            if ($key !== 'search' && $key !== 'per_page' && $key !== 'page') {
                $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        
        $html .= '<input type="text" name="search" class="form-control me-2" placeholder="ค้นหา..." value="' . htmlspecialchars($_GET['search'] ?? '') . '">';
        $html .= '<button type="submit" class="btn btn-primary">ค้นหา</button>';
        $html .= '</form>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-6">';
        $html .= '<form method="GET" class="d-flex justify-content-end">';
        
        // Keep existing parameters
        foreach ($_GET as $key => $value) {
            if ($key !== 'per_page' && $key !== 'page') {
                $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        
        $html .= '<select name="per_page" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">';
        $perPageOptions = [5, 10, 25, 50];
        foreach ($perPageOptions as $option) {
            $selected = ($this->perPage == $option) ? ' selected' : '';
            $html .= '<option value="' . $option . '"' . $selected . '>' . $option . ' รายการ</option>';
        }
        $html .= '</select>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-striped table-hover">';
        
        // Header
        $html .= '<thead><tr>';
        foreach ($this->columns as $label) {
            $html .= '<th>' . htmlspecialchars($label) . '</th>';
        }
        if (!empty($this->actions)) {
            $html .= '<th>จัดการ</th>';
        }
        $html .= '</tr></thead>';
        
        // Body
        $html .= '<tbody>';
        foreach ($this->data as $row) {
            $html .= '<tr>';
            foreach (array_keys($this->columns) as $key) {
                $value = $row[$key] ?? '';
                if ($key === 'name' && strpos($value, '.') !== false) {
                    $html .= '<td><code>' . htmlspecialchars($value) . '</code></td>';
                } elseif ($key === 'module') {
                    $html .= '<td><span class="badge bg-primary">' . htmlspecialchars($value) . '</span></td>';
                } else {
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
            }
            
            // Actions
            if (!empty($this->actions)) {
                $html .= '<td>';
                foreach ($this->actions as $action) {
                    $url = str_replace('{id}', $row['id'] ?? '', $action['url']);
                    $html .= '<a href="' . $url . '" class="btn btn-sm ' . $action['class'] . '">';
                    if ($action['icon']) {
                        $html .= '<i class="' . $action['icon'] . '"></i> ';
                    }
                    $html .= $action['label'] . '</a> ';
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';
        
        // Pagination
        $html .= $this->renderPagination();
        
        return $html;
    }
    
    private function renderPagination() {
        $totalPages = ceil($this->totalRecords / $this->perPage);
        
        if ($totalPages <= 1) return '';
        
        $html = '<nav><ul class="pagination justify-content-center">';
        
        // Previous
        if ($this->currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage - 1) . '">ก่อนหน้า</a></li>';
        }
        
        // Pages
        $start = max(1, $this->currentPage - 2);
        $end = min($totalPages, $this->currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $this->currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $this->getPageUrl($i) . '">' . $i . '</a></li>';
        }
        
        // Next
        if ($this->currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage + 1) . '">ถัดไป</a></li>';
        }
        
        $html .= '</ul></nav>';
        
        // Info
        $start = ($this->currentPage - 1) * $this->perPage + 1;
        $end = min($this->currentPage * $this->perPage, $this->totalRecords);
        $html .= '<div class="text-center mt-2">แสดง ' . $start . ' ถึง ' . $end . ' จาก ' . $this->totalRecords . ' รายการ</div>';
        
        return $html;
    }
    
    private function getPageUrl($page) {
        $params = $_GET;
        $params['page'] = $page;
        return '/cps/?' . http_build_query($params);
    }
    
    public static function create($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        return new self($data, $currentPage, $perPage, $totalRecords);
    }
}