<?php

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cart'];

$rules = json_decode(file_get_contents("./analysis/recommendations.json"), true);

$bestRule = null;
$bestConfidence = 0;

foreach ($rules as $rule) {
    $found = true;

    foreach ($rule["if"] as $product) {
        if (!in_array($product, $cart)) {
            $found = false;
            break;
        }
    }

    if ($found && $rule["confidence"] > $bestConfidence) {
        $bestConfidence = $rule["confidence"];
        $bestRule = $rule;
    }
}

if ($bestRule) {
    $product = $bestRule['then'][0];
    $mysql = new mysqli("localhost", "root", "", "cafe_db");

    $stmt = $mysql->prepare("SELECT id, name, image, category_id, price FROM menu WHERE name = ?");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();

    $item = $result->fetch_assoc();

    echo json_encode(["base" => $bestRule['if'][0], "recommendation" =>$item]);
    $stmt->close();
    exit;
}

echo json_encode(["recommendation" => null]);