<?php
include "../db.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  echo json_encode(["status"=>"error","message"=>"POST required"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
  empty($data['user_id']) ||
  empty($data['confirm'])
) {
  echo json_encode(["status"=>"error","message"=>"Missing fields"]);
  exit;
}

if ($data['confirm'] !== "DELETE") {
  echo json_encode([
    "status"=>"error",
    "message"=>"Type DELETE to confirm account deletion"
  ]);
  exit;
}

$user_id = (int)$data['user_id'];

$check = $conn->prepare("SELECT id FROM users WHERE id=?");
$check->bind_param("i", $user_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
  echo json_encode(["status"=>"error","message"=>"User not found"]);
  exit;
}

/* DELETE USER */
$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
  echo json_encode([
    "status"=>"success",
    "message"=>"Account permanently deleted"
  ]);
} else {
  echo json_encode([
    "status"=>"error",
    "message"=>"Deletion failed"
  ]);
}

$conn->close();
