<?php

class ModernTable {
    private $columns = [];
    private $data = [];
    private $options = [];
    
    public function __construct($options = []) {
        $this->options = array_merge([
            'class' => 'table table-striped',
            'id' => 'modernTable',
            'responsive' => true,
            'pagination' => true,
            'search' => true
        ], $options);
    }
    
    public function addColumn($key, $label, $options = []) {
        $this->columns[$key] = array_merge([
            'label' => $label,
            'sortable' => false,
            'searchable' => true,
            'class' => '',
            'format' => null
        ], $options);
        return $this;
    }
    
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    
    public function render() {
        $html = '<div class="modern-table-container">';
        
        if ($this->options['search']) {
            $html .= $this->renderSearch();
        }
        
        $html .= '<div class="table-responsive">';
        $html .= '<table class="' . $this->options['class'] . '" id="' . $this->options['id'] . '">';
        $html .= $this->renderHeader();
        $html .= $this->renderBody();
        $html .= '</table>';
        $html .= '</div>';
        
        if ($this->options['pagination']) {
            $html .= $this->renderPagination();
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    private function renderSearch() {
        return '<div class="table-search mb-3">
                    <input type="text" class="form-control" placeholder="ค้นหา..." id="tableSearch">
                </div>';
    }
    
    private function renderHeader() {
        $html = '<thead><tr>';
        foreach ($this->columns as $key => $column) {
            $class = $column['sortable'] ? 'sortable' : '';
            $html .= '<th class="' . $class . '" data-key="' . $key . '">';
            $html .= $column['label'];
            if ($column['sortable']) {
                $html .= ' <i class="fas fa-sort"></i>';
            }
            $html .= '</th>';
        }
        $html .= '</tr></thead>';
        return $html;
    }
    
    private function renderBody() {
        $html = '<tbody>';
        foreach ($this->data as $row) {
            $html .= '<tr>';
            foreach ($this->columns as $key => $column) {
                $value = $row[$key] ?? '';
                if ($column['format'] && is_callable($column['format'])) {
                    $value = call_user_func($column['format'], $value, $row);
                }
                $html .= '<td class="' . $column['class'] . '">' . $value . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        return $html;
    }
    
    private function renderPagination() {
        return '<nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><a class="page-link" href="#">ก่อนหน้า</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">ถัดไป</a></li>
                    </ul>
                </nav>';
    }
}