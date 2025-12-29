<?php
include("../db.php");

header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM disease_library");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "count" => count($data),
    "data" => $data
]);
?>
