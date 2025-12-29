<?php
include "../db.php";
header("Content-Type: application/json");

$result = $conn->query(
    "SELECT DISTINCT category FROM disease_library"
);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row['category'];
}

echo json_encode([
    "status" => "success",
    "categories" => $data
]);
?>
