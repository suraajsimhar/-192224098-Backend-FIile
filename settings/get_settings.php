<?php
include "../db.php";
header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? '';

if (!$user_id) {
  echo json_encode(["status"=>"error","message"=>"User ID required"]);
  exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  echo json_encode(["status"=>"error","message"=>"Invalid user"]);
  exit;
}

echo json_encode([
  "status"=>"success",
  "settings"=>[
    "privacy_security"=>true,
    "notifications"=>true,
    "data_management"=>true,
    "about_privacy"=>true,
    "contact_support"=>true,
    "delete_account"=>true
  ]
]);
