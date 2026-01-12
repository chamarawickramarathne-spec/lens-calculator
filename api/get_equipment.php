<?php
require_once 'config.php';

$conn = getDBConnection();

// Get category ID from query string
$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($categoryId > 0) {
    // Get equipment for specific category
    $sql = "SELECT e.*, c.name as category_name 
            FROM equipment_details e
            INNER JOIN category_types c ON e.category_id = c.id
            WHERE e.category_id = ? AND e.is_active = 1 
            ORDER BY e.type, e.name";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Get all equipment
    $sql = "SELECT e.*, c.name as category_name 
            FROM equipment_details e
            INNER JOIN category_types c ON e.category_id = c.id
            WHERE e.is_active = 1 
            ORDER BY c.display_order, e.type, e.name";
    
    $result = $conn->query($sql);
}

$equipment = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $equipment[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $equipment]);

$conn->close();
?>
