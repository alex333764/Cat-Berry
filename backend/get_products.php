<?php
    try{
        header("Access-Control-Allow-Origin: http://localhost:8080");
        header("Content-Type: application/json");

        $mysql = new mysqli ("localhost", "root", "", "cafe_db");

        $result = $mysql->query("SELECT * FROM menu");
        $products = [];

        while($row = $result->fetch_assoc()) {
             $products[] = $row;
        }
        echo json_encode($products);
        $mysql->close();
      } catch (mysqli_sql_exception $e) {
        echo '<b>Connection error:</b> <br> Error number: '. $e->getCode(). '<br> Error: '. $e->getMessage();
      }
?>
