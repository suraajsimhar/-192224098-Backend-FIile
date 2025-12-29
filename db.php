<?php
$servername = "localhost";
$username   = "root";
$password   = "";          // XAMPP default
$dbname     = "fish";
$port       = 3307;        // ✅ IMPORTANT

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}
?>
