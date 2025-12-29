<?php
include "../db.php";
header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? '';

$total_scans = $conn->query(
    "SELECT COUNT(*) AS total FROM scans WHERE user_id='$user_id'"
)->fetch_assoc()['total'];

$total_reports = $conn->query(
    "SELECT COUNT(*) AS total FROM reports WHERE user_id='$user_id'"
)->fetch_assoc()['total'];

$days_active = $conn->query(
    "SELECT DATEDIFF(CURDATE(), DATE(created_at)) AS days 
     FROM users WHERE id='$user_id'"
)->fetch_assoc()['days'];

echo json_encode([
    "status"=>"success",
    "stats"=>[
        "total_scans"=>$total_scans,
        "reports"=>$total_reports,
        "days_active"=>$days_active
    ]
]);
