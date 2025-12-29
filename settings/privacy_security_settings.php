<?php
header("Content-Type: application/json");

echo json_encode([
  "status" => "success",
  "privacy_security" => [
    [
      "title" => "Data Encryption",
      "description" => "All user data is encrypted and securely stored."
    ],
    [
      "title" => "Secure Login",
      "description" => "Passwords are hashed and never stored in plain text."
    ],
    [
      "title" => "Account Control",
      "description" => "Users have full control over their account and data."
    ],
    [
      "title" => "Two-Factor Authentication",
      "description" => "Additional security layer planned for future updates."
    ]
  ]
]);
