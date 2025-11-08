<?php

class UniversalTable {
    private $data;
    private $columns;
    private $actions;
    private $filters;
    private $currentPage;
    private $perPage;
    private $totalRecords;
    private $title;
    private $subtitle;
    private $theme;
    private $searchable;
    private $sortable;
    private $filterHelper;
    
    public function __construct($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        $this->data = $data;
        $this->columns = [];
        $this->actions = [];
        $this->filters = [];
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->totalRecords = $totalRecords;
        $this->title = 'รายการข้อมูล';
        $this->subtitle = '';
        $this->theme = 'modern'; // modern, classic, minimal
        $this->searchable = true;
        $this->sortable = true;
        $this->filterHelper = null;
    }
    
    public function setTitle($title, $subtitle = '') {
        $this->title = $title;
        $this->subtitle = $subtitle;
        return $this;
    }
    
    public function setTheme($theme) {
        $this->theme = $theme;
        return $this;
    }
    
    public function addColumn($key, $label, $options = []) {
        $this->columns[$key] = array_merge([
            'label' => $label,
            'width' => null,
            'sortable' => true,
            'formatter' => null
        ], $options);
        return $this;
    }
    
    public function addFilter($name, $options, $selected = '') {
        $this->filters[] = ['name' => $name, 'options' => $options, 'selected' => $selected];
        return $this;
    }
    
    public function setFilterHelper($filterHelper) {
        $this->filterHelper = $filterHelper;
        return $this;
    }
    
    public function addAction($label, $url, $class = 'btn-primary', $icon = '') {
        $this->actions[] = ['label' => $label, 'url' => $url, 'class' => $class, 'icon' => $icon];
        return $this;
    }
    
    public function render() {
        $html = $this->getThemeCSS();
        $html .= '<div class="universal-table-wrapper theme-' . $this->theme . '">';
        
        if ($this->theme === 'modern') {
            $html .= $this->renderModernTable();
        } else {
            $html .= $this->renderClassicTable();
        }
        
        $html .= '</div>';
        return $html;
    }
    
    private function renderModernTable() {
        $html = '<div class="card modern-card">';
        $html .= $this->renderModernHeader();
        $html .= $this->renderTableContent();
        $html .= $this->renderModernFooter();
        $html .= '</div>';
        return $html;
    }
    
    private function renderClassicTable() {
        $html = '<div class="card classic-card">';
        $html .= $this->renderClassicHeader();
        $html .= $this->renderTableContent();
        $html .= $this->renderClassicFooter();
        $html .= '</div>';
        return $html;
    }
    
    private function renderModernHeader() {
        $html = '<div class="card-header modern-header">';
        $html .= '<div class="header-content">';
        $html .= '<div class="title-section">';
        $html .= '<h2 class="table-title">' . htmlspecialchars($this->title) . '</h2>';
        if ($this->subtitle) {
            $html .= '<p class="table-subtitle">' . htmlspecialchars($this->subtitle) . '</p>';
        }
        $html .= '</div>';
        
        $html .= '<div class="controls-section">';
        foreach ($this->filters as $filter) {
            $html .= $this->renderFilter($filter);
        }
        $html .= '</div></div></div>';
        
        // Add advanced filters if FilterHelper is set
        if ($this->filterHelper) {
            $html .= $this->filterHelper->renderFilters();
        }
        
        return $html;
    }
    
    private function renderClassicHeader() {
        $html = '<div class="card-header classic-header">';
        $html .= '<h3 class="card-title">' . htmlspecialchars($this->title) . '</h3>';
        if ($this->searchable || !empty($this->filters)) {
            $html .= '<div class="card-tools">';
            foreach ($this->filters as $filter) {
                $html .= $this->renderFilter($filter);
            }
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
    
    private function renderFilter($filter) {
        $html = '<select class="form-select filter-select" name="' . $filter['name'] . '" onchange="applyFilter()">';
        foreach ($filter['options'] as $value => $label) {
            $selected = ($value == ($_GET[$filter['name']] ?? $filter['selected'])) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    
    private function renderSearchBox() {
        return '<div class="search-container">
            <input type="text" class="form-control search-input" placeholder="ค้นหา..." 
                   value="' . htmlspecialchars($_GET['search'] ?? '') . '" 
                   onkeyup="debounceSearch(this.value)">
            <i class="fas fa-search search-icon"></i>
        </div>';
    }
    
    private function renderTableContent() {
        $html = '<div class="table-container">';
        $html .= '<table class="table universal-table">';
        
        // Header with sorting
        $columns = $this->columns;
        if ($this->filterHelper) {
            $columns = $this->filterHelper->renderSortHeaders($columns);
        }
        
        $html .= '<thead><tr>';
        foreach ($columns as $key => $column) {
            $width = $column['width'] ? ' style="width:' . $column['width'] . '"' : '';
            $sortClass = isset($column['sortable']) && $column['sortable'] ? ' sortable-header' . ($column['sort_class'] ?? '') : '';
            
            if (isset($column['sort_url'])) {
                $html .= '<th' . $width . ' class="' . $sortClass . '" onclick="window.location.href=\'' . $column['sort_url'] . '\'">'
                    . htmlspecialchars($column['label'])
                    . ' <i class="' . ($column['sort_icon'] ?? 'fas fa-sort') . ' sort-icon"></i></th>';
            } else {
                $html .= '<th' . $width . ' class="' . $sortClass . '">' . htmlspecialchars($column['label']) . '</th>';
            }
        }
        if (!empty($this->actions)) {
            $html .= '<th class="actions-header">การจัดการ</th>';
        }
        $html .= '</tr></thead>';
        
        // Body
        $html .= '<tbody>';
        if (empty($this->data)) {
            $colCount = count($this->columns) + (!empty($this->actions) ? 1 : 0);
            $html .= '<tr class="empty-row"><td colspan="' . $colCount . '" class="empty-cell">';
            $html .= '<div class="empty-state"><i class="fas fa-inbox"></i><p>ไม่พบข้อมูล</p></div>';
            $html .= '</td></tr>';
        } else {
            foreach ($this->data as $index => $row) {
                $html .= '<tr class="data-row" data-index="' . $index . '">';
                foreach (array_keys($this->columns) as $key) {
                    $value = $row[$key] ?? '';
                    $formatter = $this->columns[$key]['formatter'];
                    $html .= '<td class="data-cell cell-' . $key . '">';
                    $html .= $formatter ? $formatter($value, $row) : $this->autoFormat($key, $value, $row);
                    $html .= '</td>';
                }
                
                if (!empty($this->actions)) {
                    $html .= '<td class="actions-cell">';
                    $html .= '<div class="action-buttons">';
                    foreach ($this->actions as $action) {
                        $url = str_replace('{id}', $row['id'] ?? $row[array_keys($row)[0]], $action['url']);
                        $onclick = strpos($url, 'javascript:') === 0 ? 
                            ' onclick="' . substr($url, 11) . '"' : 
                            ' onclick="window.location.href=\'' . $url . '\'"';
                        
                        $html .= '<button class="btn ' . $action['class'] . ' action-btn"' . $onclick . '>';
                        if ($action['icon']) $html .= '<i class="' . $action['icon'] . '"></i>';
                        $html .= '<span>' . $action['label'] . '</span>';
                        $html .= '</button>';
                    }
                    $html .= '</div></td>';
                }
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function autoFormat($key, $value, $row) {
        // Auto-format based on key patterns
        if (strpos($key, 'code') !== false) {
            return '<code class="code-badge">' . htmlspecialchars($value) . '</code>';
        }
        
        if (strpos($key, 'name') !== false && isset($row['description']) && !empty($row['description'])) {
            return '<div class="name-cell">
                <div class="primary-text">' . htmlspecialchars($value) . '</div>
                <div class="secondary-text">' . htmlspecialchars($row['description']) . '</div>
            </div>';
        }
        
        if ($key === 'is_active' || strpos($key, 'status') !== false) {
            $statusMap = [
                'ใช้งาน' => 'success', 'UP' => 'success', '1' => 'success', 'active' => 'success',
                'WARN' => 'warning', 'warning' => 'warning',
                'ปิดใช้งาน' => 'danger', 'DOWN' => 'danger', '0' => 'danger', 'inactive' => 'danger'
            ];
            $class = $statusMap[$value] ?? 'secondary';
            return '<span class="status-badge status-' . $class . '">' . htmlspecialchars($value) . '</span>';
        }
        
        if (strpos($key, 'email') !== false) {
            return '<a href="mailto:' . htmlspecialchars($value) . '" class="email-link">' . htmlspecialchars($value) . '</a>';
        }
        
        if (strpos($key, 'date') !== false || strpos($key, 'time') !== false) {
            return '<span class="date-text">' . htmlspecialchars($value) . '</span>';
        }
        
        return htmlspecialchars($value);
    }
    
    private function renderModernFooter() {
        return '<div class="card-footer modern-footer">' . $this->renderPaginationInfo() . $this->renderPagination() . '</div>';
    }
    
    private function renderClassicFooter() {
        return '<div class="card-footer classic-footer">' . $this->renderPagination() . '</div>';
    }
    
    private function renderPaginationInfo() {
        $start = ($this->currentPage - 1) * $this->perPage + 1;
        $end = min($this->currentPage * $this->perPage, $this->totalRecords);
        return '<div class="pagination-info">แสดง ' . $start . '-' . $end . ' จาก ' . number_format($this->totalRecords) . ' รายการ</div>';
    }
    
    private function renderPagination() {
        $totalPages = ceil($this->totalRecords / $this->perPage);
        if ($totalPages <= 1) return '<div class="pagination-placeholder"></div>';
        
        $html = '<nav class="pagination-nav"><ul class="pagination">';
        
        // Previous
        if ($this->currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage - 1) . '">‹</a></li>';
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
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->getPageUrl($this->currentPage + 1) . '">›</a></li>';
        }
        
        $html .= '</ul></nav>';
        return $html;
    }
    
    private function getPageUrl($page) {
        $currentUrl = $_GET['url'] ?? 'materials';
        return '?url=' . $currentUrl . '&page=' . $page . '&per_page=' . ($_GET['per_page'] ?? 10);
    }
    
    private function getThemeCSS() {
        $filterCSS = $this->filterHelper ? $this->filterHelper->getFilterCSS() : '';
        $filterJS = $this->filterHelper ? $this->filterHelper->getFilterJS() : '';
        
        return $filterCSS . '<style>
        .universal-table-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f7f9fc; min-height: 100vh; }
        
        /* Clean Modern Theme */
        .theme-modern .modern-card { background: #ffffff; border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin: 20px; }
        .theme-modern .modern-header { background: #ffffff; color: #1a202c; padding: 24px 32px; border-bottom: 1px solid #e2e8f0; }
        .theme-modern .header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; }
        .theme-modern .table-title { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1a202c; }
        .theme-modern .table-subtitle { margin: 4px 0 0 0; font-size: 0.875rem; color: #718096; }
        .theme-modern .controls-section { display: flex; gap: 12px; align-items: center; }
        .theme-modern .filter-select { border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #4a5568; padding: 8px 12px; font-size: 14px; }
        .theme-modern .search-container { position: relative; }
        .theme-modern .search-input { border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #4a5568; padding: 8px 40px 8px 12px; min-width: 240px; font-size: 14px; }
        .theme-modern .search-input::placeholder { color: #a0aec0; }
        .theme-modern .search-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0; }
        .theme-modern .table-container { background: #ffffff; }
        .theme-modern .universal-table { margin: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
        .theme-modern .universal-table thead th { background: #f7fafc; border: none; padding: 16px 24px; font-weight: 600; color: #4a5568; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .theme-modern .universal-table .sortable { cursor: pointer; user-select: none; transition: all 0.15s; }
        .theme-modern .universal-table .sortable:hover { background: #edf2f7; color: #2d3748; }
        .theme-modern .data-row { transition: all 0.15s; border-bottom: 1px solid #f7fafc; }
        .theme-modern .data-row:hover { background: #f7fafc; }
        .theme-modern .data-row:last-child { border-bottom: none; }
        .theme-modern .data-cell { padding: 16px 24px; border: none; vertical-align: middle; color: #2d3748; font-size: 14px; }
        .theme-modern .modern-footer { background: #f7fafc; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; }
        
        /* Clean Classic Theme */
        .theme-classic .classic-card { background: #ffffff; border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin: 20px; }
        .theme-classic .classic-header { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 24px 32px; display: flex; justify-content: space-between; align-items: center; }
        .theme-classic .card-title { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1a202c; }
        .theme-classic .card-tools { display: flex; gap: 12px; align-items: center; }
        .theme-classic .filter-select { border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; padding: 8px 12px; font-size: 14px; }
        .theme-classic .search-container { position: relative; }
        .theme-classic .search-input { border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; padding: 8px 40px 8px 12px; min-width: 240px; font-size: 14px; }
        .theme-classic .search-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0; }
        .theme-classic .universal-table thead th { background: #f7fafc; border: none; padding: 16px 24px; font-weight: 600; color: #4a5568; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; }
        .theme-classic .data-cell { padding: 16px 24px; border-bottom: 1px solid #f7fafc; vertical-align: middle; color: #2d3748; font-size: 14px; }
        .theme-classic .classic-footer { border-top: 1px solid #e2e8f0; padding: 16px 32px; background: #f7fafc; }
        
        /* Enhanced Components */
        .code-badge { background: #edf2f7; color: #4a5568; padding: 4px 8px; border-radius: 6px; font-size: 13px; font-weight: 500; font-family: "SF Mono", Monaco, monospace; }
        .name-cell .primary-text { font-weight: 500; color: #1a202c; font-size: 14px; }
        .name-cell .secondary-text { font-size: 13px; color: #718096; margin-top: 2px; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.025em; }
        .status-success { background: #c6f6d5; color: #22543d; }
        .status-warning { background: #faf089; color: #744210; }
        .status-danger { background: #fed7d7; color: #742a2a; }
        .status-secondary { background: #e2e8f0; color: #4a5568; }
        .email-link { color: #3182ce; text-decoration: none; }
        .email-link:hover { text-decoration: underline; }
        .action-buttons { display: flex; gap: 8px; }
        .action-btn { padding: 6px 12px; border-radius: 6px; border: 1px solid transparent; font-size: 13px; cursor: pointer; transition: all 0.15s; font-weight: 500; }
        .action-btn:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .btn-primary { background: #3182ce; color: white; }
        .btn-primary:hover { background: #2c5aa0; }
        .btn-danger { background: #e53e3e; color: white; }
        .btn-danger:hover { background: #c53030; }
        .btn-warning { background: #d69e2e; color: white; }
        .btn-warning:hover { background: #b7791f; }
        .btn-info { background: #0bc5ea; color: white; }
        .btn-info:hover { background: #00a3c4; }
        .empty-state { text-align: center; padding: 60px 20px; color: #a0aec0; }
        .empty-state i { font-size: 3rem; margin-bottom: 16px; opacity: 0.5; }
        .pagination { margin: 0; display: flex; gap: 4px; }
        .pagination .page-item .page-link { border-radius: 6px; border: 1px solid #e2e8f0; color: #4a5568; padding: 8px 12px; text-decoration: none; font-size: 14px; transition: all 0.15s; }
        .pagination .page-item.active .page-link { background: #3182ce; border-color: #3182ce; color: white; }
        .pagination .page-item:hover .page-link { background: #edf2f7; }
        .pagination-info { font-size: 14px; color: #718096; }
        
        @media (max-width: 768px) {
            .header-content, .controls-section, .card-tools { flex-direction: column; align-items: stretch; gap: 12px; }
            .search-input { min-width: 100%; }
            .action-buttons { flex-wrap: wrap; }
        }
        </style>
        
        <script>
        let searchTimeout;
        function debounceSearch(value) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location);
                if (value) url.searchParams.set("search", value);
                else url.searchParams.delete("search");
                url.searchParams.delete("page");
                window.location = url;
            }, 500);
        }
        
        function applyFilter() {
            const url = new URL(window.location);
            document.querySelectorAll(".filter-select").forEach(select => {
                if (select.value) url.searchParams.set(select.name, select.value);
                else url.searchParams.delete(select.name);
            });
            url.searchParams.delete("page");
            window.location = url;
        }
        </script>' . $filterJS;
    }
    
    public static function create($data = [], $currentPage = 1, $perPage = 10, $totalRecords = 0) {
        return new self($data, $currentPage, $perPage, $totalRecords);
    }
}