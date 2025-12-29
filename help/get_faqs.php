<?php
include "../db.php";
header("Content-Type: application/json");

$result = $conn->query(
  "SELECT question, answer FROM faqs WHERE is_active = 1"
);

$faqs = [];
while ($row = $result->fetch_assoc()) {
  $faqs[] = $row;
}

echo json_encode([
  "status" => "success",
  "faqs" => $faqs
]);
