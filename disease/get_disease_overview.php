<?php
include "../db.php";
header("Content-Type: application/json");

$disease = $_GET['disease'];

$result = $conn->query(
"SELECT * FROM disease_library WHERE disease_name='$disease'"
);

echo json_encode($result->fetch_assoc());
?>
