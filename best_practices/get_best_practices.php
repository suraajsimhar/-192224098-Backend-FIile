<?php
include "../db.php";
header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM best_practices ORDER BY category");

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['category']][] = [
        "title" => $row['title'],
        "description" => $row['description'],
        "tip" => $row['tip']
    ];
}

echo json_encode([
    "status" => "success",
    "best_practices" => $data
]);
?>
