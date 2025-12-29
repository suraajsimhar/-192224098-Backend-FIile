<?php
include "../db.php";
header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? '';

if (!is_numeric($user_id)) {
    echo json_encode(["status"=>"error","message"=>"Invalid user"]);
    exit;
}

$result = $conn->query(
    "SELECT app_language FROM user_settings WHERE user_id='$user_id'"
);

if ($result->num_rows == 0) {
    echo json_encode(["status"=>"success","app_language"=>"English"]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "app_language"=>$result->fetch_assoc()['app_language']
]);
