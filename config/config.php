<?php
// การตั้งค่าพื้นฐานของแอปพลิเคชัน

// ตั้งค่า error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ตั้งค่า timezone
date_default_timezone_set('Asia/Bangkok');

// ตั้งค่า database (ถ้ามี)
define('DB_HOST', 'localhost');
define('DB_NAME', 'cps_cci');
define('DB_USER', 'root');
define('DB_PASS', '');

// ตั้งค่า URL base
define('BASE_URL', '/cps-dev/');

// ตั้งค่า path
define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . 'app/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('LIB_PATH', ROOT_PATH . 'lib/');
?> 