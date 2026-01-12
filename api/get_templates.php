<?php
require_once 'config.php';

$conn = getDBConnection();

// Get all active templates
$sql = "SELECT * FROM templates WHERE is_active = 1 ORDER BY name";
$result = $conn->query($sql);

$templates = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $templateId = $row['id'];
        
        // Get equipment for this template
        $equipSql = "SELECT te.*, e.name, e.type, e.model, e.value, e.category_id, c.name as category_name
                     FROM template_equipment te
                     INNER JOIN equipment_details e ON te.equipment_id = e.id
                     INNER JOIN category_types c ON e.category_id = c.id
                     WHERE te.template_id = ?
                     ORDER BY c.display_order, e.type, e.name";
        
        $stmt = $conn->prepare($equipSql);
        $stmt->bind_param('i', $templateId);
        $stmt->execute();
        $equipResult = $stmt->get_result();
        
        $row['equipment'] = [];
        while ($equipRow = $equipResult->fetch_assoc()) {
            $row['equipment'][] = $equipRow;
        }
        
        $templates[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $templates]);

$conn->close();
?>
