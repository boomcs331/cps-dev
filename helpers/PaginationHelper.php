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
        
        $html = '<div class="d-flex justify-content-between align-items-center py-3 px-3" style="background: #f8f9fa; border-radius: 8px; margin-top: 20px; border: 1px solid #e9ecef;">';
        
        // Left: Records info
        $html .= '<div class="d-flex align-items-center gap-3">';
        $html .= '<small class="text-muted fw-medium">' . number_format($start) . '-' . number_format($end) . ' จาก ' . number_format($totalRecords) . '</small>';
        
        // Per page selector
        if ($totalRecords > 10) {
            $html .= '<select class="form-select form-select-sm" style="width: 70px; font-size: 12px;" onchange="changePerPage(this.value)">';
            foreach ([10, 25, 50, 100] as $option) {
                $selected = $perPage == $option ? 'selected' : '';
                $html .= "<option value=\"$option\" $selected>$option</option>";
            }
            $html .= '</select>';
        }
        $html .= '</div>';
        
        // Right: Pagination
        if ($totalPages > 1) {
            $html .= '<div class="btn-group btn-group-sm" role="group">';
            
            // Previous
            if ($currentPage > 1) {
                $html .= '<a href="' . self::buildUrl($baseUrl, $currentPage - 1, $perPage) . '" class="btn btn-outline-secondary" title="ก่อนหน้า">';
                $html .= '<i class="fas fa-chevron-left"></i>';
                $html .= '</a>';
            }
            
            // Page numbers (show max 5)
            $pageStart = max(1, $currentPage - 2);
            $pageEnd = min($totalPages, $currentPage + 2);
            
            // Adjust if at beginning or end
            if ($pageEnd - $pageStart < 4) {
                if ($pageStart == 1) {
                    $pageEnd = min($totalPages, $pageStart + 4);
                } else {
                    $pageStart = max(1, $pageEnd - 4);
                }
            }
            
            for ($i = $pageStart; $i <= $pageEnd; $i++) {
                $class = $i == $currentPage ? 'btn-primary' : 'btn-outline-secondary';
                $html .= '<a href="' . self::buildUrl($baseUrl, $i, $perPage) . '" class="btn ' . $class . '" style="min-width: 35px;">' . $i . '</a>';
            }
            
            // Next
            if ($currentPage < $totalPages) {
                $html .= '<a href="' . self::buildUrl($baseUrl, $currentPage + 1, $perPage) . '" class="btn btn-outline-secondary" title="ถัดไป">';
                $html .= '<i class="fas fa-chevron-right"></i>';
                $html .= '</a>';
            }
            
            $html .= '</div>';
        }
        
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
        
        $html = '<div class="d-flex justify-content-between align-items-center py-3 px-3" style="background: #f8f9fa; border-radius: 8px; margin-top: 20px; border: 1px solid #e9ecef;">';
        
        // Left: Records info
        $html .= '<div class="d-flex align-items-center gap-3">';
        $html .= '<small class="text-muted fw-medium">' . number_format($start) . '-' . number_format($end) . ' จาก ' . number_format($totalRecords) . '</small>';
        
        // Per page selector
        if ($totalRecords > 10) {
            $html .= '<select class="form-select form-select-sm" style="width: 70px; font-size: 12px;" onchange="changePerPageWithPrefix(this.value, \'' . $prefix . '\')">'; 
            foreach ([10, 25, 50, 100] as $option) {
                $selected = $perPage == $option ? 'selected' : '';
                $html .= "<option value=\"$option\" $selected>$option</option>";
            }
            $html .= '</select>';
        }
        $html .= '</div>';
        
        // Right: Pagination
        if ($totalPages > 1) {
            $html .= '<div class="btn-group btn-group-sm" role="group">';
            
            // Previous
            if ($currentPage > 1) {
                $html .= '<a href="' . self::buildUrlWithPrefix('', $currentPage - 1, $perPage, $prefix) . '" class="btn btn-outline-secondary" title="ก่อนหน้า">';
                $html .= '<i class="fas fa-chevron-left"></i>';
                $html .= '</a>';
            }
            
            // Page numbers (show max 5)
            $pageStart = max(1, $currentPage - 2);
            $pageEnd = min($totalPages, $currentPage + 2);
            
            // Adjust if at beginning or end
            if ($pageEnd - $pageStart < 4) {
                if ($pageStart == 1) {
                    $pageEnd = min($totalPages, $pageStart + 4);
                } else {
                    $pageStart = max(1, $pageEnd - 4);
                }
            }
            
            for ($i = $pageStart; $i <= $pageEnd; $i++) {
                $class = $i == $currentPage ? 'btn-primary' : 'btn-outline-secondary';
                $html .= '<a href="' . self::buildUrlWithPrefix('', $i, $perPage, $prefix) . '" class="btn ' . $class . '" style="min-width: 35px;">' . $i . '</a>';
            }
            
            // Next
            if ($currentPage < $totalPages) {
                $html .= '<a href="' . self::buildUrlWithPrefix('', $currentPage + 1, $perPage, $prefix) . '" class="btn btn-outline-secondary" title="ถัดไป">';
                $html .= '<i class="fas fa-chevron-right"></i>';
                $html .= '</a>';
            }
            
            $html .= '</div>';
        }
        
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