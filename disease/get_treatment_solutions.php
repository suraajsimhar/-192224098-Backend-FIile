<?php
include "../db.php";
header("Content-Type: application/json");

$disease = $_GET['disease'] ?? '';

if ($disease == '') {
    echo json_encode([
        "status" => "error",
        "message" => "Disease name required"
    ]);
    exit;
}

$result = $conn->query(
    "SELECT primary_treatment, chemical_treatment, natural_remedies, important_notes 
     FROM disease_treatments 
     WHERE disease_name='$disease' LIMIT 1"
);

if (!$result || $result->num_rows == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Treatment data not found"
    ]);
    exit;
}

echo json_encode([
    "status" => "success",
    "data" => $result->fetch_assoc()
]);
?>
