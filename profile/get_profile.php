<?php
include "../db.php";
header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? '';

if (!$user_id) {
    echo json_encode(["status"=>"error","message"=>"User ID required"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT full_name,email,phone,state,primary_water_type,profile_photo 
     FROM users WHERE id=?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status"=>"error","message"=>"User not found"]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "profile"=>$result->fetch_assoc()
]);
