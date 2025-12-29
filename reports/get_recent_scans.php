<?php
include "../db.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    echo json_encode(["status"=>"error","message"=>"GET only"]);
    exit;
}

$user_id = $_GET['user_id'] ?? null;

if (empty($user_id)) {
    echo json_encode(["status"=>"error","message"=>"User ID required"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, disease, confidence, image_path, created_at
     FROM reports
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 10"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reports = [];

while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

echo json_encode([
    "status" => "success",
    "reports" => $reports
]);

$stmt->close();
$conn->close();
