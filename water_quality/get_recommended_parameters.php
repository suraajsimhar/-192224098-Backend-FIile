<?php
include "../db.php";
header("Content-Type: application/json");

$type = $_GET['type'] ?? '';

if ($type == '') {
  echo json_encode(["status"=>"error","message"=>"Water type required"]);
  exit;
}

$stmt = $conn->prepare(
  "SELECT * FROM water_quality_parameters WHERE water_type=?"
);
$stmt->bind_param("s",$type);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode([
  "status"=>"success",
  "parameters"=>$result->fetch_assoc()
]);
