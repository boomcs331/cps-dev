<?php

class TableHelper {
    
    public static function renderTable($data, $columns, $options = []) {
        $tableClass = $options['class'] ?? 'table table-striped';
        $tableId = $options['id'] ?? 'dataTable';
        
        $html = '<div class="table-responsive">';
        $html .= '<table class="' . $tableClass . '" id="' . $tableId . '">';
        
        // Header
        $html .= '<thead><tr>';
        foreach ($columns as $key => $column) {
            $label = is_array($column) ? $column['label'] : $column;
            $html .= '<th>' . htmlspecialchars($label) . '</th>';
        }
        $html .= '</tr></thead>';
        
        // Body
        $html .= '<tbody>';
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($columns as $key => $column) {
                if (is_array($column) && isset($column['callback'])) {
                    $value = call_user_func($column['callback'], $row, $key);
                } else {
                    $value = $row[$key] ?? '';
                }
                $html .= '<td>' . $value . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        
        $html .= '</table>';
        $html .= '</div>';
        
        return $html;
    }
    
    public static function renderPagination($currentPage, $totalPages, $baseUrl = '') {
        if ($totalPages <= 1) return '';
        
        $html = '<nav aria-label="Page navigation">';
        $html .= '<ul class="pagination justify-content-center">';
        
        // Previous button
        if ($currentPage > 1) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">ก่อนหน้า</a>';
            $html .= '</li>';
        }
        
        // Page numbers
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '">';
            $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a>';
            $html .= '</li>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">ถัดไป</a>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    public static function renderSearchBox($placeholder = 'ค้นหา...', $value = '') {
        return '<div class="search-box mb-3">
                    <input type="text" class="form-control" placeholder="' . $placeholder . '" 
                           value="' . htmlspecialchars($value) . '" name="search">
                </div>';
    }
    
    public static function renderActionButtons($id, $actions = []) {
        $html = '<div class="btn-group" role="group">';
        
        foreach ($actions as $action) {
            $class = $action['class'] ?? 'btn-primary';
            $icon = $action['icon'] ?? '';
            $title = $action['title'] ?? '';
            $onclick = $action['onclick'] ?? '';
            
            $html .= '<button type="button" class="btn ' . $class . ' btn-sm" ';
            $html .= 'title="' . $title . '" onclick="' . $onclick . '(' . $id . ')">';
            if ($icon) {
                $html .= '<i class="' . $icon . '"></i>';
            }
            $html .= '</button>';
        }
        
        $html .= '</div>';
        return $html;
    }
}