<?php
    session_start();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");

    session_destroy();

    echo json_encode(["success"=>true]);
?>