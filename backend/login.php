<?php
    session_start();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit();
    }

    $mysql = new mysqli("localhost", "root", "", "cafe_db");

    $data =json_decode(file_get_contents("php://input"), true);
    $login = $data["login"];
    $password = $data["password"];

    $stmt = $mysql->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["field" => "login", "message" => "Такого логіну не існує"]);
        exit;
    } 

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        echo json_encode(["field" => "password", "message" => "Невірний пароль! Спробуйте ще раз"]);
        exit;
    }

    $_SESSION['user'] = ['login'=> $user['login'], 'name'=> $user['name']];
    echo json_encode(["success" => true, "user"=> $_SESSION['user']]);
    $stmt->close();
    $mysql->close();
?>