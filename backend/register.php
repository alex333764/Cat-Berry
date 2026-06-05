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
    $name=$data['name'];
    $login=$data['login'];
    $password=$data['password'];

    $stmt = $mysql->prepare("SELECT login FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $exists = $stmt->get_result();

    if ($exists->num_rows > 0){
        echo json_encode(["field" => "login", "message" => "Такий логін вже існує"]);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysql->prepare("INSERT INTO users(login, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $login, $password, $name);
    $stmt->execute();

    $_SESSION['user']=['login'=> $login, 'name'=> $name];
    echo json_encode(["success" => true, "user"=> $_SESSION['user']]);
    $mysql->close();
?>