<?php

class ServerSideTable {
    private $database;
    private $table;
    private $columns = [];
    private $searchColumns = [];
    private $orderColumn = '';
    private $orderDir = 'asc';
    
    public function __construct($database, $table) {
        $this->database = $database;
        $this->table = $table;
    }
    
    public function setColumns($columns) {
        $this->columns = $columns;
        return $this;
    }
    
    public function setSearchColumns($columns) {
        $this->searchColumns = $columns;
        return $this;
    }
    
    public function setOrder($column, $direction = 'asc') {
        $this->orderColumn = $column;
        $this->orderDir = $direction;
        return $this;
    }
    
    public function getData($request) {
        $draw = intval($request['draw']);
        $start = intval($request['start']);
        $length = intval($request['length']);
        $search = $request['search']['value'];
        
        // Build query
        $query = "SELECT " . implode(', ', $this->columns) . " FROM " . $this->table;
        $countQuery = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $params = [];
        $whereClause = '';
        
        // Add search condition
        if (!empty($search) && !empty($this->searchColumns)) {
            $searchConditions = [];
            foreach ($this->searchColumns as $column) {
                $searchConditions[] = "$column LIKE ?";
                $params[] = "%$search%";
            }
            $whereClause = " WHERE (" . implode(' OR ', $searchConditions) . ")";
        }
        
        // Add order
        $orderClause = '';
        if (!empty($this->orderColumn)) {
            $orderClause = " ORDER BY " . $this->orderColumn . " " . $this->orderDir;
        }
        
        // Add limit
        $limitClause = " LIMIT $start, $length";
        
        // Execute queries
        $totalRecords = $this->database->query($countQuery)->fetch()['total'];
        
        $filteredQuery = $countQuery . $whereClause;
        $filteredRecords = $this->database->query($filteredQuery, $params)->fetch()['total'];
        
        $dataQuery = $query . $whereClause . $orderClause . $limitClause;
        $data = $this->database->query($dataQuery, $params)->fetchAll();
        
        return [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];
    }
    
    public function renderTable($id = 'serverSideTable') {
        $html = '<table id="' . $id . '" class="table table-striped table-bordered">';
        $html .= '<thead><tr>';
        
        foreach ($this->columns as $column) {
            $html .= '<th>' . ucfirst(str_replace('_', ' ', $column)) . '</th>';
        }
        
        $html .= '</tr></thead>';
        $html .= '<tbody></tbody>';
        $html .= '</table>';
        
        return $html;
    }
    
    public function getJavaScript($tableId = 'serverSideTable', $ajaxUrl = '') {
        return "
        <script>
        $(document).ready(function() {
            $('#$tableId').DataTable({
                'processing': true,
                'serverSide': true,
                'ajax': '$ajaxUrl',
                'language': {
                    'url': '//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json'
                }
            });
        });
        </script>";
    }
}