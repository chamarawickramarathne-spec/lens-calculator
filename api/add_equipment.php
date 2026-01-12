<?php
require_once 'config.php';

header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON data from request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate required fields
$required = ['category_id', 'type', 'model', 'name', 'value'];
foreach ($required as $field) {
    if (!isset($data[$field]) || trim($data[$field]) === '') {
        $fieldName = ($field === 'value') ? 'local renting price' : $field;
        echo json_encode(['success' => false, 'message' => "Missing required field: $fieldName"]);
        exit;
    }
}

// Sanitize inputs
$categoryId = intval($data['category_id']);
$typeId = intval($data['type']);
$model = trim($data['model']);
$name = trim($data['name']);
$value = floatval($data['value']);
$description = isset($data['description']) ? trim($data['description']) : '';

$conn = getDBConnection();

// Validate category exists
$checkCat = $conn->prepare("SELECT id FROM category_types WHERE id = ? AND is_active = 1");
$checkCat->bind_param('i', $categoryId);
$checkCat->execute();
$result = $checkCat->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid category selected']);
    $conn->close();
    exit;
}

// Validate type exists
$checkType = $conn->prepare("SELECT id FROM equipment_types WHERE id = ? AND is_active = 1");
$checkType->bind_param('i', $typeId);
$checkType->execute();
$typeResult = $checkType->get_result();

if ($typeResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid equipment type selected']);
    $conn->close();
    exit;
}

// Insert new equipment with is_active = 0 (inactive)
$sql = "INSERT INTO equipment_details (category_id, type, model, name, value, description, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, 0)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iissds', $categoryId, $typeId, $model, $name, $value, $description);

if ($stmt->execute()) {
    $equipmentId = $conn->insert_id;
    echo json_encode([
        'success' => true, 
        'message' => 'Equipment added successfully! It will be inactive until approved from the database.',
        'equipment_id' => $equipmentId
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to add equipment: ' . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>
