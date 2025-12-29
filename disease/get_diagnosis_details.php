<?php
include "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$disease = trim($data['disease'] ?? '');
$confidence = $data['confidence'] ?? '';

if ($disease == '') {
    echo json_encode(["status"=>"error","message"=>"Disease required"]);
    exit;
}

$stmt = $conn->prepare(
  "SELECT disease_name, treatment, prevention 
   FROM disease_library 
   WHERE disease_name = ? LIMIT 1"
);
$stmt->bind_param("s", $disease);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"Disease not found in library"]);
    exit;
}

$row = $result->fetch_assoc();

echo json_encode([
  "status" => "success",
  "result" => [
    "disease" => $row['disease_name'],
    "confidence" => $confidence,
    "treatment" => explode(",", $row['treatment']),
    "prevention" => explode(",", $row['prevention'])
  ]
]);

$stmt->close();
$conn->close();
?>
