<?php

class UniversalTable {
    private $config = [];
    private $data = [];
    private $totalRecords = 0;
    private $filteredRecords = 0;
    
    public function __construct($config = []) {
        $this->config = array_merge([
            'id' => 'universalTable',
            'class' => 'table table-striped table-hover',
            'responsive' => true,
            'pagination' => true,
            'search' => true,
            'perPage' => 10,
            'sortable' => true,
            'actions' => true
        ], $config);
    }
    
    public function setData($data, $totalRecords = null) {
        $this->data = $data;
        $this->totalRecords = $totalRecords ?? count($data);
        $this->filteredRecords = count($data);
        return $this;
    }
    
    public function addColumn($key, $label, $options = []) {
        $this->config['columns'][$key] = array_merge([
            'label' => $label,
            'sortable' => true,
            'searchable' => true,
            'format' => null,
            'class' => '',
            'width' => null
        ], $options);
        return $this;
    }
    
    public function render() {
        $html = '<div class="universal-table-wrapper">';
        
        // Search and filters
        if ($this->config['search']) {
            $html .= $this->renderFilters();
        }
        
        // Table
        $html .= $this->renderTable();
        
        // Pagination
        if ($this->config['pagination']) {
            $html .= $this->renderPagination();
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    private function renderFilters() {
        return '<div class="table-filters mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="ค้นหา..." id="universalSearch">
                        </div>
                        <div class="col-md-6 text-end">
                            <select class="form-select d-inline-block w-auto" id="perPageSelect">
                                <option value="10">10 รายการ</option>
                                <option value="25">25 รายการ</option>
                                <option value="50">50 รายการ</option>
                                <option value="100">100 รายการ</option>
                            </select>
                        </div>
                    </div>
                </div>';
    }
    
    private function renderTable() {
        $responsive = $this->config['responsive'] ? 'table-responsive' : '';
        
        $html = '<div class="' . $responsive . '">';
        $html .= '<table class="' . $this->config['class'] . '" id="' . $this->config['id'] . '">';
        
        // Header
        $html .= '<thead class="table-dark">';
        $html .= '<tr>';
        
        foreach ($this->config['columns'] as $key => $column) {
            $sortClass = $column['sortable'] ? 'sortable' : '';
            $width = $column['width'] ? 'width="' . $column['width'] . '"' : '';
            
            $html .= '<th class="' . $sortClass . '" data-key="' . $key . '" ' . $width . '>';
            $html .= $column['label'];
            
            if ($column['sortable']) {
                $html .= ' <i class="fas fa-sort ms-1"></i>';
            }
            
            $html .= '</th>';
        }
        
        if ($this->config['actions']) {
            $html .= '<th width="120">จัดการ</th>';
        }
        
        $html .= '</tr>';
        $html .= '</thead>';
        
        // Body
        $html .= '<tbody>';
        
        foreach ($this->data as $index => $row) {
            $html .= '<tr>';
            
            foreach ($this->config['columns'] as $key => $column) {
                $value = $row[$key] ?? '';
                
                if ($column['format'] && is_callable($column['format'])) {
                    $value = call_user_func($column['format'], $value, $row, $index);
                }
                
                $html .= '<td class="' . $column['class'] . '">' . $value . '</td>';
            }
            
            if ($this->config['actions']) {
                $html .= '<td>' . $this->renderActions($row, $index) . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        return $html;
    }
    
    private function renderActions($row, $index) {
        $id = $row['id'] ?? $index;
        
        return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editRecord(' . $id . ')" title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(' . $id . ')" title="ลบ">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
    }
    
    private function renderPagination() {
        $totalPages = ceil($this->totalRecords / $this->config['perPage']);
        $currentPage = 1; // This should come from request
        
        if ($totalPages <= 1) return '';
        
        $html = '<nav class="mt-3">';
        $html .= '<ul class="pagination justify-content-center">';
        
        // Previous
        $disabled = $currentPage <= 1 ? 'disabled' : '';
        $html .= '<li class="page-item ' . $disabled . '">';
        $html .= '<a class="page-link" href="#" data-page="' . ($currentPage - 1) . '">ก่อนหน้า</a>';
        $html .= '</li>';
        
        // Pages
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $active = $i == $currentPage ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '">';
            $html .= '<a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a>';
            $html .= '</li>';
        }
        
        // Next
        $disabled = $currentPage >= $totalPages ? 'disabled' : '';
        $html .= '<li class="page-item ' . $disabled . '">';
        $html .= '<a class="page-link" href="#" data-page="' . ($currentPage + 1) . '">ถัดไป</a>';
        $html .= '</li>';
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    public function getJavaScript() {
        return '<script>
        $(document).ready(function() {
            // Search functionality
            $("#universalSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#' . $this->config['id'] . ' tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Sort functionality
            $(".sortable").click(function() {
                var key = $(this).data("key");
                // Implement sorting logic here
            });
            
            // Pagination
            $(".pagination a").click(function(e) {
                e.preventDefault();
                var page = $(this).data("page");
                // Implement pagination logic here
            });
        });
        </script>';
    }
}