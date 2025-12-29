<?php
include "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

/* VALIDATION */
if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Invalid user"]);
    exit;
}

$fields = [
    'disease_alerts',
    'water_quality_warnings',
    'scan_results',
    'weekly_reports',
    'expert_tips',
    'system_updates'
];

foreach ($fields as $f) {
    if (!isset($data[$f]) || !in_array($data[$f],[0,1],true)) {
        echo json_encode(["status"=>"error","message"=>"Invalid value for $f"]);
        exit;
    }
}

$user_id = $data['user_id'];

/* UPDATE */
$sql = "UPDATE notification_settings SET
    disease_alerts='{$data['disease_alerts']}',
    water_quality_warnings='{$data['water_quality_warnings']}',
    scan_results='{$data['scan_results']}',
    weekly_reports='{$data['weekly_reports']}',
    expert_tips='{$data['expert_tips']}',
    system_updates='{$data['system_updates']}'
WHERE user_id='$user_id'";

if ($conn->query($sql)) {
    echo json_encode(["status"=>"success","message"=>"Settings updated"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}
