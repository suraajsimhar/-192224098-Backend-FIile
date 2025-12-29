<?php
include "../db.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  echo json_encode(["status"=>"error","message"=>"Only POST allowed"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
  empty($data['user_id']) ||
  empty($data['subject']) ||
  empty($data['message'])
) {
  echo json_encode(["status"=>"error","message"=>"All fields required"]);
  exit;
}

$user_id = $data['user_id'];
$subject = trim($data['subject']);
$message = trim($data['message']);

if (!is_numeric($user_id)) {
  echo json_encode(["status"=>"error","message"=>"Invalid user"]);
  exit;
}

if (strlen($subject) < 5) {
  echo json_encode(["status"=>"error","message"=>"Subject too short"]);
  exit;
}

if (strlen($message) < 10) {
  echo json_encode(["status"=>"error","message"=>"Message too short"]);
  exit;
}

/* Check user exists */
$check = $conn->prepare("SELECT id FROM users WHERE id=?");
$check->bind_param("i", $user_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
  echo json_encode(["status"=>"error","message"=>"User not found"]);
  exit;
}

$stmt = $conn->prepare(
  "INSERT INTO support_messages (user_id, subject, message)
   VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $user_id, $subject, $message);

if ($stmt->execute()) {
  echo json_encode([
    "status"=>"success",
    "message"=>"Support request sent"
  ]);
} else {
  echo json_encode([
    "status"=>"error",
    "message"=>"Failed to submit"
  ]);
}

$stmt->close();
$conn->close();
