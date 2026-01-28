<?php
    include('includes/config.php');

    if(!isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit();
    }

    if(isset($_POST['movie_id'])) {
        $user_id = $_SESSION['user_id'];
        $movie_id = $_POST['movie_id'];
        
        $check_sql = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND movie_id = $movie_id";
        $check_result = mysqli_query($conn, $check_sql);
        
        if(mysqli_num_rows($check_result) > 0) {
            $row = mysqli_fetch_array($check_result, MYSQLI_ASSOC);
            $new_quantity = $row['quantity'] + 1;
            mysqli_query($conn, "UPDATE cart SET quantity = $new_quantity WHERE id = {$row['id']}");
        }
        else {
            mysqli_query($conn, "INSERT INTO cart (user_id, movie_id, quantity) VALUES ($user_id, $movie_id, 1)");
        }
        
        header("location: cart.php");
        exit();
    }
?>