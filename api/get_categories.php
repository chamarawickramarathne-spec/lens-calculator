<?php
require_once 'config.php';

$conn = getDBConnection();

// Get all active categories
$sql = "SELECT * FROM category_types WHERE is_active = 1 ORDER BY display_order, name";
$result = $conn->query($sql);

$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $categories]);

$conn->close();
?>
