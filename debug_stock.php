<?php
session_start();
require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Material.php';

try {
    echo "<h2>Debug Stock Data</h2>";
    
    $materialModel = new Material();
    
    echo "<h3>1. ทดสอบ getAllMaterials()</h3>";
    $materials = $materialModel->getAllMaterials();
    echo "จำนวน materials: " . count($materials) . "<br>";
    if (!empty($materials)) {
        echo "ตัวอย่างข้อมูล: <pre>" . print_r(array_slice($materials, 0, 2), true) . "</pre>";
    }
    
    echo "<h3>2. ทดสอบ getLocations()</h3>";
    $locations = $materialModel->getLocations();
    echo "จำนวน locations: " . count($locations) . "<br>";
    if (!empty($locations)) {
        echo "ตัวอย่างข้อมูล: <pre>" . print_r($locations, true) . "</pre>";
    }
    
    echo "<h3>3. ทดสอบ getStockLots()</h3>";
    $stockLots = $materialModel->getStockLots(1, 10);
    echo "จำนวน stockLots: " . count($stockLots) . "<br>";
    if (!empty($stockLots)) {
        echo "ตัวอย่างข้อมูล: <pre>" . print_r(array_slice($stockLots, 0, 2), true) . "</pre>";
    }
    
    echo "<h3>4. ทดสอบ getMaterialsWithRelations()</h3>";
    $materialsWithRelations = $materialModel->getMaterialsWithRelations();
    echo "จำนวน materialsWithRelations: " . count($materialsWithRelations) . "<br>";
    if (!empty($materialsWithRelations)) {
        echo "ตัวอย่างข้อมูล: <pre>" . print_r(array_slice($materialsWithRelations, 0, 2), true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>";
    echo "<h3>เกิดข้อผิดพลาด:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
?>