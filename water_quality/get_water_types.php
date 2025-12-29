<?php
include "../db.php";
header("Content-Type: application/json");

$result = $conn->query(
  "SELECT type_name, description FROM water_types"
);

$data = [];
while($row = $result->fetch_assoc()){
  $data[] = $row;
}

echo json_encode([
  "status"=>"success",
  "water_types"=>$data
]);
