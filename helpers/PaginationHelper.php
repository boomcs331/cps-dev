<?php

class PaginationHelper
{
    public static function render($currentPage, $totalRecords, $perPage, $baseUrl = '')
    {
        $totalPages = ceil($totalRecords / $perPage);
        
        if ($totalRecords == 0) {
            return '';
        }
        
        $start = (($currentPage - 1) * $perPage) + 1;
        $end = min($currentPage * $perPage, $totalRecords);
        
        $html = '<div class="d-flex justify-content-between align-items-center py-3 px-3 border-top bg-white">';
        
        // Left: Records info
        $html .= '<div class="text-muted small">แสดง ' . $start . ' ถึง ' . $end . ' จาก ' . number_format($totalRecords) . '</div>';
        
        // Right: Pagination controls
        $html .= '<div class="d-flex align-items-center gap-3">';
        
        // Rows per page
        $html .= '<div class="d-flex align-items-center gap-2">';
        $html .= '<span class="text-muted small">Rows per page</span>';
        $html .= '<select class="form-select form-select-sm" style="width: 70px;" onchange="changePerPage(this.value)">';
        foreach ([10, 25, 50, 100] as $option) {
            $selected = $perPage == $option ? 'selected' : '';
            $html .= "<option value=\"$option\" $selected>$option</option>";
        }
        $html .= '</select>';
        $html .= '</div>';
        
        // Pagination
        if ($totalPages > 1) {
            $html .= '<nav>';
            $html .= '<ul class="pagination pagination-sm mb-0">';
            
            // Previous
            $html .= '<li class="page-item' . ($currentPage <= 1 ? ' disabled' : '') . '">';
            if ($currentPage > 1) {
                $html .= '<a class="page-link" href="' . self::buildUrl($baseUrl, $currentPage - 1, $perPage) . '">Previous</a>';
            } else {
                $html .= '<span class="page-link text-muted">Previous</span>';
            }
            $html .= '</li>';
            
            // Current page info
            $html .= '<li class="page-item active">';
            $html .= '<span class="page-link">' . $currentPage . '</span>';
            $html .= '</li>';
            
            // Dots if needed
            if ($currentPage < $totalPages - 1) {
                $html .= '<li class="page-item disabled">';
                $html .= '<span class="page-link">...</span>';
                $html .= '</li>';
            }
            
            // Last page
            if ($currentPage < $totalPages) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . self::buildUrl($baseUrl, $totalPages, $perPage) . '">' . $totalPages . '</a>';
                $html .= '</li>';
            }
            
            // Next
            $html .= '<li class="page-item' . ($currentPage >= $totalPages ? ' disabled' : '') . '">';
            if ($currentPage < $totalPages) {
                $html .= '<a class="page-link" href="' . self::buildUrl($baseUrl, $currentPage + 1, $perPage) . '">Next</a>';
            } else {
                $html .= '<span class="page-link text-muted">Next</span>';
            }
            $html .= '</li>';
            
            $html .= '</ul>';
            $html .= '</nav>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    public static function renderWithPrefix($currentPage, $totalRecords, $perPage, $prefix = '')
    {
        $totalPages = ceil($totalRecords / $perPage);
        
        if ($totalRecords == 0) {
            return '';
        }
        
        $start = (($currentPage - 1) * $perPage) + 1;
        $end = min($currentPage * $perPage, $totalRecords);
        
        $html = '<div class="d-flex justify-content-between align-items-center py-3 px-3 border-top bg-white">';
        
        // Left: Records info
        $html .= '<div class="text-muted small">แสดง ' . $start . ' ถึง ' . $end . ' จาก ' . number_format($totalRecords) . '</div>';
        
        // Right: Pagination controls
        $html .= '<div class="d-flex align-items-center gap-3">';
        
        // Rows per page
        $html .= '<div class="d-flex align-items-center gap-2">';
        $html .= '<span class="text-muted small">Rows per page</span>';
        $html .= '<select class="form-select form-select-sm" style="width: 70px;" onchange="changePerPageWithPrefix(this.value, \'' . $prefix . '\')">';
        foreach ([10, 25, 50, 100] as $option) {
            $selected = $perPage == $option ? 'selected' : '';
            $html .= "<option value=\"$option\" $selected>$option</option>";
        }
        $html .= '</select>';
        $html .= '</div>';
        
        // Pagination
        if ($totalPages > 1) {
            $html .= '<nav>';
            $html .= '<ul class="pagination pagination-sm mb-0">';
            
            // Previous
            $html .= '<li class="page-item' . ($currentPage <= 1 ? ' disabled' : '') . '">';
            if ($currentPage > 1) {
                $html .= '<a class="page-link" href="' . self::buildUrlWithPrefix('', $currentPage - 1, $perPage, $prefix) . '">Previous</a>';
            } else {
                $html .= '<span class="page-link text-muted">Previous</span>';
            }
            $html .= '</li>';
            
            // Current page info
            $html .= '<li class="page-item active">';
            $html .= '<span class="page-link">' . $currentPage . '</span>';
            $html .= '</li>';
            
            // Dots if needed
            if ($currentPage < $totalPages - 1) {
                $html .= '<li class="page-item disabled">';
                $html .= '<span class="page-link">...</span>';
                $html .= '</li>';
            }
            
            // Last page
            if ($currentPage < $totalPages) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . self::buildUrlWithPrefix('', $totalPages, $perPage, $prefix) . '">' . $totalPages . '</a>';
                $html .= '</li>';
            }
            
            // Next
            $html .= '<li class="page-item' . ($currentPage >= $totalPages ? ' disabled' : '') . '">';
            if ($currentPage < $totalPages) {
                $html .= '<a class="page-link" href="' . self::buildUrlWithPrefix('', $currentPage + 1, $perPage, $prefix) . '">Next</a>';
            } else {
                $html .= '<span class="page-link text-muted">Next</span>';
            }
            $html .= '</li>';
            
            $html .= '</ul>';
            $html .= '</nav>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    private static function buildUrl($baseUrl, $page, $perPage)
    {
        $params = $_GET;
        $params['page'] = $page;
        $params['per_page'] = $perPage;
        
        return $baseUrl . '?' . http_build_query($params);
    }
    
    private static function buildUrlWithPrefix($baseUrl, $page, $perPage, $prefix = '')
    {
        $params = $_GET;
        $params[$prefix . 'page'] = $page;
        $params[$prefix . 'per_page'] = $perPage;
        
        return $baseUrl . '?' . http_build_query($params);
    }
}