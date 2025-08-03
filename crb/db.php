<?php
// Database configuration
define('DB_HOST', '41.80.37.9');
define('DB_USER', 'grandpac_nash1');
define('DB_PASS', 'Kenya@50');
define('DB_NAME', 'grandpac_crb_checker_ke');

// Create connection
try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper function to execute prepared statements
function executeQuery($sql, $params = []) {
    global $conn;
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }
}
?>