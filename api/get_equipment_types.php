<?php
require_once 'config.php';

$conn = getDBConnection();

// Get all active equipment types
$sql = "SELECT * FROM equipment_types WHERE is_active = 1 ORDER BY type";
$result = $conn->query($sql);

$types = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $types[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $types]);

$conn->close();
?>
