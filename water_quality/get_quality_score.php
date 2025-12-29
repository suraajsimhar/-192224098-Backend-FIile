<?php
include "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$ph = $data['ph'];
$temp = $data['temperature'];
$do = $data['oxygen'];
$ammonia = $data['ammonia'];

$ref = $conn->query("SELECT * FROM water_quality_parameters LIMIT 1")->fetch_assoc();

$status = "Excellent";
$analysis = [];

// pH
if ($ph < $ref['ph_min'] || $ph > $ref['ph_max']) {
    $analysis['ph'] = "Unsafe";
    $status = "Poor";
} else {
    $analysis['ph'] = "Safe";
}

// Temperature
if ($temp < $ref['temperature_min'] || $temp > $ref['temperature_max']) {
    $analysis['temperature'] = "Unsafe";
    $status = "Poor";
} else {
    $analysis['temperature'] = "Safe";
}

// Oxygen
if ($do < $ref['dissolved_oxygen_min']) {
    $analysis['oxygen'] = "Unsafe";
    $status = "Poor";
} else {
    $analysis['oxygen'] = "Safe";
}

// Ammonia
if ($ammonia > $ref['ammonia_max']) {
    $analysis['ammonia'] = "Unsafe";
    $status = "Poor";
} else {
    $analysis['ammonia'] = "Safe";
}

echo json_encode([
    "status" => "success",
    "overall_quality" => $status,
    "analysis" => $analysis
]);
