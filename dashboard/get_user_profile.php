<?php
include "../db.php";
header("Content-Type: application/json");

// SAFELY read user_id
$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => "error",
        "message" => "user_id is required"
    ]);
    exit;
}

$result = $conn->query(
    "SELECT full_name, email, phone, state FROM users WHERE id='$user_id' LIMIT 1"
);

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

echo json_encode([
    "status" => "success",
    "data" => $result->fetch_assoc()
]);
?>
