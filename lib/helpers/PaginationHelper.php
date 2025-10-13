<?php
class PaginationHelper
{
    /**
     * สร้าง HTML สำหรับ pagination
     */
    public static function render($currentPage, $totalPages, $queryParams = [])
    {
        if ($totalPages <= 1) {
            return '';
        }

        $queryString = http_build_query($queryParams);
        $html = '<div class="pagination-controls">';
        
        // ปุ่มก่อนหน้า
        if ($currentPage > 1) {
            $html .= '<a href="?page=' . ($currentPage - 1) . '&' . $queryString . '" class="page-btn">';
            $html .= '<i class="fas fa-chevron-left"></i></a>';
        }
        
        // ปุ่มหมายเลขหน้า
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)) {
                $activeClass = $i == $currentPage ? 'active' : '';
                $html .= '<a href="?page=' . $i . '&' . $queryString . '" class="page-btn ' . $activeClass . '">' . $i . '</a>';
            } elseif ($i == $currentPage - 2 || $i == $currentPage + 2) {
                $html .= '<span style="padding: 0 0.5rem;">...</span>';
            }
        }
        
        // ปุ่มถัดไป
        if ($currentPage < $totalPages) {
            $html .= '<a href="?page=' . ($currentPage + 1) . '&' . $queryString . '" class="page-btn">';
            $html .= '<i class="fas fa-chevron-right"></i></a>';
        }
        
        $html .= '</div>';
        return $html;
    }

    /**
     * สร้างข้อมูล pagination info
     */
    public static function getInfo($currentPage, $limit, $totalItems)
    {
        $startItem = ($currentPage - 1) * $limit + 1;
        $endItem = min($currentPage * $limit, $totalItems);
        
        return [
            'start' => $startItem,
            'end' => $endItem,
            'total' => $totalItems,
            'current_page' => $currentPage,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }
}
?>