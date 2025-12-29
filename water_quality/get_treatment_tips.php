<?php
include "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$tips = [];

foreach ($data as $param => $value) {
    if ($value === "Unsafe") {
        $res = $conn->query(
            "SELECT * FROM water_treatment_tips WHERE parameter='$param'"
        );
        while ($row = $res->fetch_assoc()) {
            $tips[] = $row;
        }
    }
}

echo json_encode([
    "status" => "success",
    "treatments" => $tips
]);
