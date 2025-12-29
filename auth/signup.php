<?php
include "../db.php";
header("Content-Type: application/json");

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use POST."
    ]);
    exit;
}

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);

// ---------------- NAME VALIDATION ----------------
if (!isset($data['full_name']) || !preg_match("/^[A-Za-z ]{3,50}$/", trim($data['full_name']))) {
    echo json_encode([
        "status" => "error",
        "message" => "Full name must contain only letters and spaces (min 3 characters)"
    ]);
    exit;
}

// ---------------- EMAIL VALIDATION ----------------
if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email format"
    ]);
    exit;
}

$email = strtolower(trim($data['email']));

$allowedDomains = [
    "gmail.com",
    "yahoo.com",
    "outlook.com",
    "hotmail.com",
    "icloud.com"
];

$emailParts = explode("@", $email);
$domain = $emailParts[1] ?? "";

if (!in_array($domain, $allowedDomains)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please enter a valid email (example: user@gmail.com)"
    ]);
    exit;
}

// ---------------- PHONE VALIDATION ----------------
if (!isset($data['phone']) || !preg_match("/^[0-9]{10}$/", $data['phone'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Valid 10-digit phone number required"
    ]);
    exit;
}

// ---------------- PASSWORD VALIDATION ----------------
if (!isset($data['password']) || strlen($data['password']) < 6) {
    echo json_encode([
        "status" => "error",
        "message" => "Password must be at least 6 characters"
    ]);
    exit;
}

// ---------------- STATE VALIDATION ----------------
if (!isset($data['state']) || trim($data['state']) === "") {
    echo json_encode([
        "status" => "error",
        "message" => "State is required"
    ]);
    exit;
}

// ---------------- SANITIZE DATA ----------------
$full_name = trim($data['full_name']);
$phone     = trim($data['phone']);
$password  = password_hash($data['password'], PASSWORD_DEFAULT);
$state     = strtoupper(trim($data['state']));

// ---------------- CHECK EMAIL EXISTS ----------------
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email already exists"
    ]);
    exit;
}
$stmt->close();

// ---------------- CHECK PHONE EXISTS ----------------
$stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Phone number already exists"
    ]);
    exit;
}
$stmt->close();

// ---------------- INSERT USER ----------------
$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, phone, password, state)
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssss", $full_name, $email, $phone, $password, $state);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Signup successful"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Signup failed"
    ]);
}

$stmt->close();
$conn->close();
?>
