<?php
header("Content-Type: application/json");

echo json_encode([
  "status"=>"success",
  "app"=>[
    "name"=>"AquaGuard AI",
    "version"=>"1.0.0",
    "description"=>"AI-powered aquaculture disease and water quality monitoring app."
  ],
  "about_us"=>"AquaGuard AI helps fish farmers monitor fish health, detect diseases early, and maintain optimal water quality using AI.",
  "privacy_policy"=>[
    "data_collection"=>"We collect fish images, water quality data, and location to improve recommendations.",
    "data_usage"=>"Data is used only for disease detection and insights.",
    "data_security"=>"All data is encrypted and securely stored.",
    "user_rights"=>"Users can request account deletion at any time."
  ],
  "contact"=>[
    "email"=>"support@aquaguardai.com",
    "phone"=>"+91 8800 123 456",
    "website"=>"https://www.aquaguardai.com"
  ]
]);
