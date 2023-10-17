<?php
    $server = 'localhost';
    $username = 'root';
    $password = "";
    $database = 'manageattendance';

    $conn = mysqli_connect($server, $username, $password, $database);

    $pdo = new PDO("mysql:host=localhost;dbname=manageattendance","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>