<?php
    session_start();

    // Database connection
    $server = "localhost";
    $login = "root";
    $pass = "";
    $dbname = "streambox_db";

    $conn = mysqli_connect($server, $login, $pass, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>