<?php
include "../db.php";
header("Content-Type: application/json");

$category = $_GET['category'] ?? '';

if (!$category) {
    echo json_encode(["status"=>"error","message"=>"Category required"]);
    exit;
}

$result = $conn->query(
    "SELECT DISTINCT disease_name FROM disease_library WHERE category='$category'"
);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row['disease_name'];
}

echo json_encode([
    "status" => "success",
    "diseases" => $data
]);
?>
