<?php
// Log Access API - Records page access with IP and timestamp
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Accept both POST and GET requests for easier testing
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    $conn = getDBConnection();
    
    // Check if table exists, create it if not
    $checkTable = $conn->query("SHOW TABLES LIKE 'access_logs'");
    if ($checkTable->num_rows === 0) {
        $createTable = "CREATE TABLE IF NOT EXISTS access_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            request_uri VARCHAR(255),
            accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_ip (ip_address),
            INDEX idx_accessed (accessed_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if (!$conn->query($createTable)) {
            throw new Exception('Failed to create access_logs table: ' . $conn->error);
        }
    }
    
    // Get IP address (handle proxy/load balancer scenarios)
    $ip_address = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Get first IP if multiple are present
        $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip_address = trim($ip_list[0]);
    }
    
    // Get user agent
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    
    // Get request URI
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    
    // Prepare and execute insert statement
    $stmt = $conn->prepare("INSERT INTO access_logs (ip_address, user_agent, request_uri) VALUES (?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $stmt->bind_param("sss", $ip_address, $user_agent, $request_uri);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Access logged successfully',
            'log_id' => $stmt->insert_id,
            'ip_address' => $ip_address
        ]);
    } else {
        throw new Exception('Failed to execute statement: ' . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
