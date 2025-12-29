<?php
include "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Invalid user"]);
    exit;
}

if (!isset($data['app_language']) || trim($data['app_language']) === "") {
    echo json_encode(["status"=>"error","message"=>"Language required"]);
    exit;
}

$allowed = [
  "English","Hindi","Tamil","Telugu",
  "Malayalam","Kannada","Bengali","Marathi"
];

if (!in_array($data['app_language'], $allowed)) {
    echo json_encode(["status"=>"error","message"=>"Unsupported language"]);
    exit;
}

$user_id = $data['user_id'];
$lang = $data['app_language'];

/* UPSERT */
$conn->query(
"INSERT INTO user_settings (user_id, app_language)
 VALUES ('$user_id','$lang')
 ON DUPLICATE KEY UPDATE app_language='$lang'"
);

echo json_encode(["status"=>"success","message"=>"Language updated"]);
