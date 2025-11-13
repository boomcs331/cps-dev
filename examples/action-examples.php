<?php
require_once '../helpers/ActionHelper.php';

// ตัวอย่างการใช้งาน ActionHelper

// 1. Dropdown Actions (เหมาะสำหรับ action หลายตัว)
$dropdownActions = [
    ['type' => 'link', 'label' => 'ดูรายละเอียด', 'url' => '/view/{id}', 'icon' => 'fas fa-eye'],
    ['type' => 'link', 'label' => 'แก้ไข', 'url' => '/edit/{id}', 'icon' => 'fas fa-edit', 'class' => 'text-warning'],
    ['type' => 'divider'],
    ['type' => 'modal', 'label' => 'ลบ', 'target' => '#deleteModal', 'icon' => 'fas fa-trash', 'class' => 'text-danger'],
];

// 2. Button Group (เหมาะสำหรับ action หลัก 2-3 ตัว)
$buttonActions = [
    ['type' => 'link', 'label' => 'ดู', 'url' => '/view/{id}', 'icon' => 'fas fa-eye', 'class' => 'btn-outline-primary', 'title' => 'ดูรายละเอียด'],
    ['type' => 'link', 'label' => 'แก้ไข', 'url' => '/edit/{id}', 'icon' => 'fas fa-edit', 'class' => 'btn-outline-warning', 'title' => 'แก้ไขข้อมูล'],
    ['type' => 'modal', 'label' => 'ลบ', 'target' => '#deleteModal', 'icon' => 'fas fa-trash', 'class' => 'btn-outline-danger', 'title' => 'ลบข้อมูล'],
];

// 3. Inline Links (เหมาะสำหรับ action เรียบง่าย)
$inlineActions = [
    ['type' => 'link', 'label' => 'ดู', 'url' => '/view/{id}', 'class' => 'text-primary'],
    ['type' => 'link', 'label' => 'แก้ไข', 'url' => '/edit/{id}', 'class' => 'text-warning'],
    ['type' => 'modal', 'label' => 'ลบ', 'target' => '#deleteModal', 'class' => 'text-danger'],
];

$recordId = 123;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Action Examples</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>ตัวอย่างการใช้งาน ActionHelper</h2>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>1. Dropdown Actions</h5>
                        <small class="text-muted">เหมาะสำหรับ action หลายตัว</small>
                    </div>
                    <div class="card-body">
                        <?= ActionHelper::renderDropdown($dropdownActions, $recordId) ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>2. Button Group</h5>
                        <small class="text-muted">เหมาะสำหรับ action หลัก 2-3 ตัว</small>
                    </div>
                    <div class="card-body">
                        <?= ActionHelper::renderButtons($buttonActions, $recordId) ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>3. Inline Links</h5>
                        <small class="text-muted">เหมาะสำหรับ action เรียบง่าย</small>
                    </div>
                    <div class="card-body">
                        <?= ActionHelper::renderInline($inlineActions, $recordId) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>ตัวอย่างในตาราง</h3>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>ชื่อ</th>
                        <th>สถานะ</th>
                        <th width="120">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>รายการ A</td>
                        <td><span class="badge bg-success">ใช้งาน</span></td>
                        <td><?= ActionHelper::renderDropdown($dropdownActions, 1) ?></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>รายการ B</td>
                        <td><span class="badge bg-warning">รอดำเนินการ</span></td>
                        <td><?= ActionHelper::renderButtons($buttonActions, 2) ?></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>รายการ C</td>
                        <td><span class="badge bg-danger">ปิดใช้งาน</span></td>
                        <td><?= ActionHelper::renderInline($inlineActions, 3) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>