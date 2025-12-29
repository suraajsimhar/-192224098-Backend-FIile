<?php
include "../db.php";
header("Content-Type: application/json");

$result = $conn->query(
  "SELECT state_name, aquaculture_level FROM states"
);

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode([
  "status" => "success",
  "states" => $data
]);
