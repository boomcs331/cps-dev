<?php

class TableFilterHelper {
    private $filters = [];
    private $sorts = [];
    private $searchFields = [];
    
    public function addFilter($key, $label, $options = [], $type = 'select') {
        // Extract simple parameter name from complex key (e.g., 'm.is_active' -> 'is_active')
        $paramName = strpos($key, '.') !== false ? substr($key, strpos($key, '.') + 1) : $key;
        
        $this->filters[$key] = [
            'label' => $label,
            'options' => $options,
            'type' => $type,
            'value' => $_GET[$paramName] ?? '',
            'param_name' => $paramName
        ];
        return $this;
    }
    
    public function addSort($key, $label, $default = false) {
        $this->sorts[$key] = [
            'label' => $label,
            'default' => $default
        ];
        return $this;
    }
    
    public function addSearchField($field) {
        $this->searchFields[] = $field;
        return $this;
    }
    
    public function buildWhereClause($baseQuery = '') {
        $conditions = [];
        $params = [];
        
        // Apply filters
        foreach ($this->filters as $key => $filter) {
            $paramName = $filter['param_name'] ?? $key;
            $value = $_GET[$paramName] ?? '';
            if ($value !== '' && $value !== null) {
                switch ($filter['type']) {
                    case 'select':
                        $conditions[] = "$key = ?";
                        $params[] = $value;
                        break;
                    case 'date_range':
                        if (isset($_GET[$paramName . '_from']) && !empty($_GET[$paramName . '_from'])) {
                            $conditions[] = "$key >= ?";
                            $params[] = $_GET[$paramName . '_from'];
                        }
                        if (isset($_GET[$paramName . '_to']) && !empty($_GET[$paramName . '_to'])) {
                            $conditions[] = "$key <= ?";
                            $params[] = $_GET[$paramName . '_to'];
                        }
                        break;
                    case 'number_range':
                        if (isset($_GET[$paramName . '_min']) && $_GET[$paramName . '_min'] !== '') {
                            $conditions[] = "$key >= ?";
                            $params[] = $_GET[$paramName . '_min'];
                        }
                        if (isset($_GET[$paramName . '_max']) && $_GET[$paramName . '_max'] !== '') {
                            $conditions[] = "$key <= ?";
                            $params[] = $_GET[$paramName . '_max'];
                        }
                        break;
                }
            }
        }
        
        // Apply search
        $search = $_GET['search'] ?? '';
        if ($search !== '' && !empty($this->searchFields)) {
            $searchConditions = [];
            foreach ($this->searchFields as $field) {
                $searchConditions[] = "$field LIKE ?";
                $params[] = "%$search%";
            }
            if (!empty($searchConditions)) {
                $conditions[] = '(' . implode(' OR ', $searchConditions) . ')';
            }
        }
        
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = (strpos(strtoupper($baseQuery), 'WHERE') !== false ? ' AND ' : ' WHERE ') . implode(' AND ', $conditions);
        }
        
        return ['query' => $whereClause, 'params' => $params];
    }
    
    public function buildOrderClause() {
        $sortBy = $_GET['sort'] ?? '';
        $sortDir = $_GET['dir'] ?? 'asc';
        
        // Validate sort direction
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? strtolower($sortDir) : 'asc';
        
        // Check if sort field is allowed
        if (!empty($sortBy) && isset($this->sorts[$sortBy])) {
            return " ORDER BY $sortBy $sortDir";
        }
        
        // Use default sort if available
        foreach ($this->sorts as $key => $sort) {
            if ($sort['default']) {
                return " ORDER BY $key asc";
            }
        }
        
        return '';
    }
    
    public function renderFilters() {
        if (empty($this->filters) && empty($this->searchFields)) return '';
        
        $html = '<div class="table-filters">';
        $html .= '<div class="filters-left">';
        
        foreach ($this->filters as $key => $filter) {
            $html .= '<div class="filter-group">';
            $html .= '<label class="filter-label">' . htmlspecialchars($filter['label']) . '</label>';
            
            switch ($filter['type']) {
                case 'select':
                    $paramName = $filter['param_name'] ?? $key;
                    $html .= '<select name="' . $paramName . '" class="filter-select" onchange="applyFilters()">';
                    $html .= '<option value="">ทั้งหมด</option>';
                    foreach ($filter['options'] as $value => $label) {
                        $selected = ($filter['value'] == $value) ? ' selected' : '';
                        $html .= '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>';
                    }
                    $html .= '</select>';
                    break;
                    
                case 'date_range':
                    $html .= '<div class="date-range">';
                    $html .= '<input type="date" name="' . $key . '_from" class="filter-input" value="' . ($_GET[$key . '_from'] ?? '') . '" onchange="applyFilters()" placeholder="จาก">';
                    $html .= '<span class="range-separator">ถึง</span>';
                    $html .= '<input type="date" name="' . $key . '_to" class="filter-input" value="' . ($_GET[$key . '_to'] ?? '') . '" onchange="applyFilters()" placeholder="ถึง">';
                    $html .= '</div>';
                    break;
                    
                case 'number_range':
                    $html .= '<div class="number-range">';
                    $html .= '<input type="number" name="' . $key . '_min" class="filter-input" value="' . ($_GET[$key . '_min'] ?? '') . '" onchange="applyFilters()" placeholder="ต่ำสุด">';
                    $html .= '<span class="range-separator">-</span>';
                    $html .= '<input type="number" name="' . $key . '_max" class="filter-input" value="' . ($_GET[$key . '_max'] ?? '') . '" onchange="applyFilters()" placeholder="สูงสุด">';
                    $html .= '</div>';
                    break;
                    
                case 'text':
                    $html .= '<input type="text" name="' . $key . '" class="filter-input" value="' . htmlspecialchars($filter['value']) . '" onchange="applyFilters()" placeholder="' . htmlspecialchars($filter['label']) . '">';
                    break;
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        // Right side with search and actions
        $html .= '<div class="filters-right">';
        
        // Search box if search fields are defined
        if (!empty($this->searchFields)) {
            $html .= '<div class="search-group">';
            $html .= '<div class="search-container">';
            $html .= '<input type="text" class="search-input" placeholder="ค้นหา..." value="' . htmlspecialchars($_GET['search'] ?? '') . '" onkeyup="debounceSearch(this.value)">';
            $html .= '<i class="fas fa-search search-icon"></i>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        // Clear filters button
        $html .= '<div class="filter-actions">';
        $html .= '<button type="button" class="btn-clear-filters" onclick="clearFilters()">ล้างตัวกรอง</button>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    public function renderSortHeaders($columns) {
        $currentSort = $_GET['sort'] ?? '';
        $currentDir = $_GET['dir'] ?? 'asc';
        
        foreach ($columns as $key => &$column) {
            if (isset($this->sorts[$key])) {
                $newDir = ($currentSort === $key && $currentDir === 'asc') ? 'desc' : 'asc';
                $sortClass = '';
                $sortIcon = 'fas fa-sort';
                
                if ($currentSort === $key) {
                    $sortClass = ' sorted';
                    $sortIcon = $currentDir === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
                }
                
                $column['sortable'] = true;
                $column['sort_url'] = $this->buildSortUrl($key, $newDir);
                $column['sort_class'] = $sortClass;
                $column['sort_icon'] = $sortIcon;
            }
        }
        
        return $columns;
    }
    
    private function buildSortUrl($sortBy, $direction) {
        $params = $_GET;
        $params['sort'] = $sortBy;
        $params['dir'] = $direction;
        unset($params['page']); // Reset to first page when sorting
        
        $currentUrl = $_GET['url'] ?? '';
        return (defined('BASE_URL') ? BASE_URL : '/') . '?url=' . $currentUrl . '&' . http_build_query($params);
    }
    
    public function getFilterCSS() {
        return '<style>
        .table-filters { 
            display: flex; 
            justify-content: space-between;
            align-items: end;
            gap: 16px; 
            padding: 16px 24px; 
            background: #f7fafc; 
            border-bottom: 1px solid #e2e8f0; 
        }
        .filters-left {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: end;
        }
        .filters-right {
            display: flex;
            gap: 12px;
            align-items: end;
        }
        .filter-group { 
            display: flex; 
            flex-direction: column; 
            gap: 4px; 
            min-width: 120px; 
        }
        .filter-label { 
            font-size: 12px; 
            font-weight: 500; 
            color: #4a5568; 
            text-transform: uppercase; 
            letter-spacing: 0.025em; 
        }
        .filter-select, .filter-input { 
            padding: 6px 10px; 
            border: 1px solid #e2e8f0; 
            border-radius: 6px; 
            font-size: 14px; 
            background: white; 
        }
        .date-range, .number-range { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        .range-separator { 
            font-size: 12px; 
            color: #718096; 
        }
        .search-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .search-container { 
            position: relative; 
        }
        .search-input { 
            padding: 6px 32px 6px 10px; 
            border: 1px solid #e2e8f0; 
            border-radius: 6px; 
            font-size: 14px; 
            background: white; 
            min-width: 200px;
        }
        .search-input::placeholder { 
            color: #a0aec0; 
        }
        .search-icon { 
            position: absolute; 
            right: 10px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #a0aec0; 
            font-size: 12px;
        }
        .filter-actions { 
            display: flex; 
            align-items: end; 
        }
        .btn-clear-filters { 
            padding: 6px 12px; 
            background: #e2e8f0; 
            border: none; 
            border-radius: 6px; 
            font-size: 13px; 
            cursor: pointer; 
            color: #4a5568; 
        }
        .btn-clear-filters:hover { 
            background: #cbd5e0; 
        }
        .sortable-header { 
            cursor: pointer; 
            user-select: none; 
            transition: background 0.15s; 
        }
        .sortable-header:hover { 
            background: #edf2f7; 
        }
        .sortable-header.sorted { 
            background: #e6fffa; 
            color: #234e52; 
        }
        .sort-icon { 
            margin-left: 6px; 
            font-size: 10px; 
            opacity: 0.6; 
        }
        .sorted .sort-icon { 
            opacity: 1; 
        }
        th {
            font-size: 1.1em;
            font-weight: bold;
        }
        .action-buttons {
            display: inline-flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
            padding: 4px 0;
        }
        .action-buttons button {
            min-width: 44px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.01em;
            padding: 8px 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            position: relative;
            overflow: hidden;
            background: #ffffff;
            color: #1f2937;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        }
        .action-buttons button i {
            font-size: 15px;
        }
        .action-buttons button::before {
            content: "";
            position: absolute;
            inset: -40% -10%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.55), transparent 70%);
            opacity: 0;
            transform: scale(0.2);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .action-buttons button:hover {
            transform: translateY(-2px) scale(1.015);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.18);
        }
        .action-buttons button:hover::before {
            opacity: 1;
            transform: scale(1);
        }
        .action-buttons button:active {
            transform: translateY(0);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
        }
        .action-buttons .action-edit {
            background: linear-gradient(120deg, #f0fdf4, #dcfce7);
            border-color: #86efac;
            color: #166534;
        }
        .action-buttons .action-delete {
            background: linear-gradient(120deg, #fef2f2, #fee2e2);
            border-color: #fca5a5;
            color: #b91c1c;
        }
        td:last-child {
            text-align: center;
            width: 160px;
        }
        @media (max-width: 768px) {
            .table-filters { 
                flex-direction: column; 
                align-items: stretch; 
            }
            .filters-left, .filters-right {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group, .search-group { 
                min-width: 100%; 
            }
            .search-input {
                min-width: 100%;
            }
        }
        </style>';
    }
    
    public function getFilterJS() {
        return '<script>
        function applyFilters() {
            const url = new URL(window.location);
            const form = document.querySelector(".table-filters");
            if (!form) return;
            
            // Get all filter inputs (exclude search input)
            const inputs = form.querySelectorAll("select, input:not(.search-input)");
            inputs.forEach(input => {
                if (!input.name) return;
                const value = input.value;
                if (value !== "" && value !== null) {
                    url.searchParams.set(input.name, value);
                } else {
                    url.searchParams.delete(input.name);
                }
            });
            
            // Reset to first page
            url.searchParams.delete("page");
            window.location = url;
        }
        
        let searchTimeout;
        function debounceSearch(value) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location);
                if (value !== "") {
                    url.searchParams.set("search", value);
                } else {
                    url.searchParams.delete("search");
                }
                url.searchParams.delete("page");
                window.location = url;
            }, 500);
        }
        
        function clearFilters() {
            const url = new URL(window.location);
            const form = document.querySelector(".table-filters");
            if (!form) return;
            
            // Clear all filter and search parameters
            const inputs = form.querySelectorAll("select, input");
            inputs.forEach(input => {
                if (input.name) {
                    url.searchParams.delete(input.name);
                }
            });
            
            // Also clear search parameter
            url.searchParams.delete("search");
            url.searchParams.delete("sort");
            url.searchParams.delete("dir");
            url.searchParams.delete("page");
            
            // Keep only essential params
            const keepParams = ["url"];
            const newUrl = new URL(window.location.origin + window.location.pathname);
            keepParams.forEach(param => {
                if (url.searchParams.has(param)) {
                    newUrl.searchParams.set(param, url.searchParams.get(param));
                }
            });
            
            window.location = newUrl;
        }
        
        function sortTable(sortBy, direction) {
            const url = new URL(window.location);
            url.searchParams.set("sort", sortBy);
            url.searchParams.set("dir", direction);
            url.searchParams.delete("page");
            window.location = url;
        }
        </script>';
    }
    
    public static function create() {
        return new self();
    }
}
