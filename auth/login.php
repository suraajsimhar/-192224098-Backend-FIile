<?php
include "../db.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"Only POST method allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email']) || empty($data['password'])) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Email and password are required"
    ]);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

$stmt = $conn->prepare(
    "SELECT id, full_name, email, phone, state, password 
     FROM users WHERE email = ? LIMIT 1"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Email not registered"
    ]);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Incorrect password"
    ]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "message"=>"Login successful",
    "user"=>[
        "id"=>$user['id'],
        "full_name"=>$user['full_name'],
        "email"=>$user['email'],
        "phone"=>$user['phone'],
        "state"=>$user['state']
    ]
]);

$stmt->close();
$conn->close();
?>
