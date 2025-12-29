<?php
include "../db.php";
header("Content-Type: application/json");

$disease = $_GET['disease'] ?? '';

$result = $conn->query(
    "SELECT causes FROM disease_library WHERE disease_name='$disease' LIMIT 1"
);

echo json_encode($result->fetch_assoc());
?>
