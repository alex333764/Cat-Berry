<?php
    session_start();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json");

    $mysql = new mysqli ("localhost", "root", "", "cafe_db");

    if(!isset($_SESSION['user'])){
        echo json_encode([]);
        exit;
    }

    $login = $_SESSION['user']['login'];
    $orders=[];

    $stmt = $mysql->prepare("SELECT o.*, oi.*, m.name, m.image FROM orders o 
                             JOIN order_items oi ON oi.order_id = o.id
                             JOIN menu m ON m.id = oi.product_id
                             WHERE o.user = ?
                             ORDER BY o.date DESC ");

    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $id = $row['order_id'];

        if(!isset($orders[$id])){
            $orders[$id] = [
                'id'=>$id,
                'date'=>$row['date'],
                'total_price'=>$row['total_price'],
                'items'=>[]
            ];
        }

        $orders[$id]['items'][] = [
            'id'=>$row['product_id'],
            'name'=>$row['name'],
            'image'=>$row['image'],
            'size'=>$row['size'],
            'quantity'=>$row['quantity'],
            'price'=>$row['price']
        ];
    }
    echo json_encode(array_values($orders));
    $stmt->close();
    $mysql->close();
?>