<?php
require_once 'config.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

$conn = getDBConnection();

// Start transaction
$conn->begin_transaction();

try {
    // Insert package
    $sql = "INSERT INTO packages (
        package_name, client_name, template_id, labor_hours, hourly_rate, 
        margin_percentage, equipment_total, labor_total, subtotal, 
        margin_amount, final_total, notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssiidddddds',
        $data['package_name'],
        $data['client_name'],
        $data['template_id'],
        $data['labor_hours'],
        $data['hourly_rate'],
        $data['margin_percentage'],
        $data['equipment_total'],
        $data['labor_total'],
        $data['subtotal'],
        $data['margin_amount'],
        $data['final_total'],
        $data['notes']
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to save package');
    }
    
    $packageId = $conn->insert_id;
    
    // Insert equipment items
    if (!empty($data['equipment'])) {
        $equipSql = "INSERT INTO package_equipment (
            package_id, equipment_id, quantity, unit_value, total_value
        ) VALUES (?, ?, ?, ?, ?)";
        
        $equipStmt = $conn->prepare($equipSql);
        
        foreach ($data['equipment'] as $item) {
            $equipStmt->bind_param(
                'iiidd',
                $packageId,
                $item['equipment_id'],
                $item['quantity'],
                $item['unit_value'],
                $item['total_value']
            );
            
            if (!$equipStmt->execute()) {
                throw new Exception('Failed to save equipment items');
            }
        }
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Package saved successfully',
        'package_id' => $packageId
    ]);
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
