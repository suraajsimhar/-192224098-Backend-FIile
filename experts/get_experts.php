<?php
include "../db.php";
header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM experts");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "count" => count($data),
    "experts" => $data
]);
?>
