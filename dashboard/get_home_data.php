<?php
include "../db.php";
header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode([
        "status" => "error",
        "message" => "user_id is required"
    ]);
    exit;
}

$result = $conn->query(
    "SELECT full_name FROM users WHERE id='$user_id' LIMIT 1"
);

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

$user = $result->fetch_assoc();

echo json_encode([
    "status" => "success",
    "welcome_name" => $user['full_name'],
    "quick_actions" => [
        "water_quality" => true,
        "disease_library" => true,
        "learning_center" => true,
        "expert_consultation" => true
    ]
]);
?>
