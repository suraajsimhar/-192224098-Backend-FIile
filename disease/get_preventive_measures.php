<?php
include "../db.php";
header("Content-Type: application/json");

if (!isset($_GET['disease']) || empty($_GET['disease'])) {
    echo json_encode(["status"=>"error","message"=>"Disease name required"]);
    exit;
}

$disease = $conn->real_escape_string($_GET['disease']);

$result = $conn->query(
    "SELECT * FROM disease_prevention WHERE disease_name='$disease' LIMIT 1"
);

if ($result->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"No preventive data found"]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "data"=>$result->fetch_assoc()
]);
?>
