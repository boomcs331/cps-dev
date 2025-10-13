<?php
session_start();

echo "<h2>Session Debug Information</h2>";
echo "<h3>Session Status:</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . session_status() . "<br>";

echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Database Connection Test:</h3>";
try {
    require_once 'config/config.php';
    require_once 'core/Database.php';
    
    $db = Database::getInstance()->getConnection();
    echo "Database connection: SUCCESS<br>";
    
    // Test users table
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Users in database: " . $result['count'] . "<br>";
    
    // Show test users
    $stmt = $db->query("SELECT username, full_name, is_active FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h4>Test Users:</h4>";
    foreach ($users as $user) {
        echo "Username: " . $user['username'] . " | Name: " . $user['full_name'] . " | Active: " . ($user['is_active'] ? 'Yes' : 'No') . "<br>";
    }
    
} catch (Exception $e) {
    echo "Database connection: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<h3>Permission Debug:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "Current User ID: " . $_SESSION['user_id'] . "<br>";
    echo "Current Username: " . ($_SESSION['username'] ?? 'Not set') . "<br>";
    
    try {
        // Get user permissions
        $stmt = $db->prepare("
            SELECT DISTINCT p.name as permission_name, r.name as role_name
            FROM users u
            JOIN user_roles ur ON u.id = ur.user_id
            JOIN roles r ON ur.role_id = r.id
            JOIN role_permissions rp ON r.id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE u.id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h4>User Permissions:</h4>";
        if ($permissions) {
            $permissionList = [];
            $roleList = [];
            foreach ($permissions as $perm) {
                $permissionList[] = $perm['permission_name'];
                $roleList[] = $perm['role_name'];
            }
            $permissionList = array_unique($permissionList);
            $roleList = array_unique($roleList);
            
            echo "Roles: " . implode(', ', $roleList) . "<br>";
            echo "Permissions: " . implode(', ', $permissionList) . "<br>";
            
            echo "<h4>Session Permissions Array:</h4>";
            echo "<pre>" . print_r($_SESSION['permissions'] ?? 'Not set', true) . "</pre>";
        } else {
            echo "No permissions found<br>";
        }
        
    } catch (Exception $e) {
        echo "Permission check error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "No user logged in<br>";
}

echo "<h3>Test Login:</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "Attempting login with: " . $username . "<br>";
    
    try {
        require_once 'core/Model.php';
        require_once 'models/User.php';
        
        $userModel = new User();
        $user = $userModel->authenticate($username, $password);
        
        if ($user) {
            echo "Login SUCCESS!<br>";
            echo "User data: <pre>" . print_r($user, true) . "</pre>";
            
            // Test permission loading
            echo "<h4>Testing Permission Load:</h4>";
            $stmt = $db->prepare("
                SELECT DISTINCT p.name
                FROM users u
                JOIN user_roles ur ON u.id = ur.user_id
                JOIN roles r ON ur.role_id = r.id
                JOIN role_permissions rp ON r.id = rp.role_id
                JOIN permissions p ON rp.permission_id = p.id
                WHERE u.id = ?
            ");
            $stmt->execute([$user['id']]);
            $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Loaded permissions: " . implode(', ', $perms) . "<br>";
        } else {
            echo "Login FAILED!<br>";
        }
    } catch (Exception $e) {
        echo "Login error: " . $e->getMessage() . "<br>";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" value="admin"><br><br>
    <input type="password" name="password" placeholder="Password" value="password"><br><br>
    <button type="submit">Test Login</button>
</form>

<br><br>
<a href="/cps/">Back to Login</a> | 
<a href="/cps/?url=logout">Logout</a>