<?php
// This is the final, definitive version of this file.
// It includes robust error checking for directories and permissions.
header("Content-Type: application/json");

// Check if the user_id and image file were sent
if (!isset($_POST['user_id']) || !isset($_FILES['image'])) {
    echo json_encode(["status" => "error", "message" => "Required data missing: 'image' or 'user_id' was not received by the server."]);
    exit;
}

// Check for any upload errors
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "PHP file upload error code: " . $_FILES['image']['error']]);
    exit;
}

// --- Directory and Path Handling ---
// The target directory, relative to the main 'fish' folder
$target_parent_dir = "../uploads/";
$target_subdir = $target_parent_dir . "scans/";

// Check if the parent 'uploads' directory exists. If not, try to create it.
if (!file_exists($target_parent_dir)) {
    if (!@mkdir($target_parent_dir, 0777, true)) {
        echo json_encode(["status" => "error", "message" => "Failed to create parent directory: " . $target_parent_dir . ". Check permissions of the 'fish' folder."]);
        exit;
    }
}

// Check if the 'scans' subdirectory exists. If not, try to create it.
if (!file_exists($target_subdir)) {
    if (!@mkdir($target_subdir, 0777, true)) {
        echo json_encode(["status" => "error", "message" => "Failed to create subdirectory: " . $target_subdir . ". Check permissions of the 'uploads' folder."]);
        exit;
    }
}

// Check if the final directory is writable by the server
if (!is_writable($target_subdir)) {
    echo json_encode(["status" => "error", "message" => "The directory is not writable: " . $target_subdir . ". Please check folder permissions."]);
    exit;
}

// --- File Move Operation ---
// Create a unique file name to prevent overwrites
$unique_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
$target_file = $target_subdir . $unique_name;

// Attempt to move the uploaded file to its final destination
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    // Success! The file is uploaded.
    // The path to store in the database is relative to the 'fish' folder.
    $database_path = "uploads/scans/" . $unique_name;
    
    echo json_encode([
        "status" => "success", 
        "image_path" => $database_path, 
        "message" => "Image uploaded successfully."
    ]);
} else {
    // This is the final catch-all error if the move fails for an unknown reason.
    echo json_encode(["status" => "error", "message" => "Failed to move the uploaded file. Check server logs for more details."]);
}

?>