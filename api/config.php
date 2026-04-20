<?php
// Environment detection
$is_local = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1']);

if ($is_local) {
    // Local configuration (XAMPP/WAMP)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'lens_calculator');
} else {
    // Production configuration
    define('DB_HOST', 'localhost');
    define('DB_USER', 'hiresmcq_lens_me');
    define('DB_PASS', '~CzLGA~;v%Ye');
    define('DB_NAME', 'hiresmcq_lens_calculator');
}

// Create connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        // Return JSON error for API calls
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
    }
    
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Default API Headers (Can be overridden by specific API scripts)
if (!isset($skip_headers)) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}
?>
