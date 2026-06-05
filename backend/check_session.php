<?php
    session_start();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json");

    if (isset($_SESSION['user'])) {
        echo json_encode([
            "authorized" => true,
            "user" => $_SESSION['user']
        ]);
    }
    else {
        echo json_encode(["authorized" => false]);
    }
?>