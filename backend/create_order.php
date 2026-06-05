<?php
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit();
    }

    $mysql = new mysqli("localhost", "root", "", "cafe_db");

    $data = json_decode(file_get_contents("php://input"), true);
    $user =isset($data['user']) ? $data['user'] : null;
    $cart = $data['cart'];
    $total_price = $data['total_price'];
    $to_go = $data['to_go'];

    $stmt = $mysql->prepare("INSERT INTO orders (user, to_go, total_price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $user, $to_go, $total_price);
    $stmt->execute();

    $order_id = $mysql->insert_id;

    foreach ($cart as $item) {
        $product_id = $item['id'];
        $size = $item['size'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $mysql->query("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (
            '$order_id', '$product_id', '$size', '$quantity', '$price')");
    }
    
    echo json_encode(["message" => "Замовлення успішно оформлено! Дякуємо ;)"]);
    $stmt->close();
    $mysql->close();
?>