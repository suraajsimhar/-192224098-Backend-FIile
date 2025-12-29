<?php
include "../db.php";
header("Content-Type: application/json");

// Force immediate output
ob_start();

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"POST only"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['user_id']) ||
    empty($data['disease']) ||
    empty($data['confidence']) ||
    empty($data['image_path'])
) {
    echo json_encode(["status"=>"error","message"=>"Missing fields"]);
    exit;
}

$user_id    = (int)$data['user_id'];
$disease    = trim($data['disease']);
$confidence = trim($data['confidence']);
$image_path = trim($data['image_path']);

$sql = "INSERT INTO reports (user_id, disease, confidence, image_path)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Prepare failed",
        "error"=>$conn->error
    ]);
    exit;
}

$stmt->bind_param("isss", $user_id, $disease, $confidence, $image_path);

if ($stmt->execute()) {
    echo json_encode([
        "status"=>"success",
        "message"=>"Report saved"
    ]);
} else {
    echo json_encode([
        "status"=>"error",
        "message"=>"Insert failed",
        "error"=>$stmt->error
    ]);
}

$stmt->close();
$conn->close();

// 🔥 FORCE RESPONSE TO CLIENT
ob_end_flush();
exit;
