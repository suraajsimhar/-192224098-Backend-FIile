<?php
header("Content-Type: application/json");

/*
 Logout does NOT delete account
 It only ends login session
*/

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID required"
    ]);
    exit;
}

/* If you use sessions */
session_start();
session_unset();
session_destroy();

/* If token-based auth is added later
   token can be invalidated here */

echo json_encode([
    "status" => "success",
    "message" => "Logged out successfully"
]);
