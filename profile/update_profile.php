<?php
include "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

/* ---------- BASIC CHECK ---------- */
if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Invalid user ID"]);
    exit;
}

/* ---------- FULL NAME VALIDATION ---------- */
if (
    empty($data['full_name']) ||
    strlen(trim($data['full_name'])) < 3 ||
    !preg_match("/^[a-zA-Z ]+$/", $data['full_name'])
) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Full name must contain only letters and be at least 3 characters"
    ]);
    exit;
}

/* ---------- PHONE VALIDATION ---------- */
if (
    empty($data['phone']) ||
    !preg_match("/^[0-9]{10}$/", $data['phone'])
) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Phone number must be 10 digits"
    ]);
    exit;
}

/* ---------- STATE VALIDATION ---------- */
if (empty($data['state'])) {
    echo json_encode([
        "status"=>"error",
        "message"=>"State is required"
    ]);
    exit;
}

/* ---------- WATER TYPE VALIDATION ---------- */
$allowed_water_types = ["Pond", "River", "Lake", "Tank", "Coastal"];

if (
    empty($data['primary_water_type']) ||
    !in_array($data['primary_water_type'], $allowed_water_types)
) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid water type selected"
    ]);
    exit;
}

/* ---------- SANITIZE ---------- */
$user_id = intval($data['user_id']);
$full_name = trim($data['full_name']);
$phone = trim($data['phone']);
$state = trim($data['state']);
$primary_water_type = trim($data['primary_water_type']);

/* ---------- UPDATE ---------- */
$stmt = $conn->prepare(
    "UPDATE users 
     SET full_name=?, phone=?, state=?, primary_water_type=? 
     WHERE id=?"
);

$stmt->bind_param(
    "ssssi",
    $full_name,
    $phone,
    $state,
    $primary_water_type,
    $user_id
);

if ($stmt->execute()) {
    echo json_encode([
        "status"=>"success",
        "message"=>"Profile updated successfully"
    ]);
} else {
    echo json_encode([
        "status"=>"error",
        "message"=>"Profile update failed"
    ]);
}

$stmt->close();
$conn->close();
?>
