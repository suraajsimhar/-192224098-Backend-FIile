<?php
include "../db.php";
header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? '';

if (!is_numeric($user_id)) {
    echo json_encode(["status"=>"error","message"=>"Invalid user"]);
    exit;
}

$result = $conn->query(
    "SELECT disease_alerts, water_quality_warnings, scan_results,
            weekly_reports, expert_tips, system_updates
     FROM notification_settings WHERE user_id='$user_id'"
);

if ($result->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"Settings not found"]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "data"=>$result->fetch_assoc()
]);
