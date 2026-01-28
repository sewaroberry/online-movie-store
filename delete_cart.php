<?php
    include('includes/config.php');

    if(!isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if(isset($_POST['delete_items'])) {
        if(isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            foreach($_POST['selected_items'] as $item_id) {
                $sql = "DELETE FROM cart WHERE id = $item_id AND user_id = $user_id";
                mysqli_query($conn, $sql);
            }

            header("location: cart.php");
            exit();
        }
    }

    $sql = "SELECT c.*, m.title, m.price, m.poster_url 
            FROM cart c 
            JOIN movies m ON c.movie_id = m.id 
            WHERE c.user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    $has_items = mysqli_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Delete Cart Items - StreamBox</title>
    
    <!-- CSS Style Sheet for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Style Sheet for Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Style Sheet (Inline Style) -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #0f0f1a;
            color: white;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        .header {
            background: rgba(15, 15, 26, 0.95);
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .logo i {
            color: #ff4d4d;
            margin-right: 10px;
            font-size: 28px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 30px;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-links a:hover {
            color: #ff4d4d;
        }
        
        .btn-logout {
            background: #ff4d4d;
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: #ff3333;
        }
        
        /* Page Header */
        .page-header {
            padding: 40px 0;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Delete Cart Page */
        .delete-cart-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }
        
        .delete-cart-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .delete-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .delete-header i {
            font-size: 60px;
            color: #ff4d4d;
            margin-bottom: 20px;
            display: block;
        }
        
        .delete-header h2 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .delete-header p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Cart Items List */
        .cart-items-list {
            margin-bottom: 40px;
        }
        
        .cart-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s;
        }
        
        .cart-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #ff4d4d;
        }
        
        .cart-item-checkbox {
            width: 24px;
            height: 24px;
            accent-color: #ff4d4d;
        }
        
        .cart-item-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }
        
        .cart-item-info img {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .cart-item-details h4 {
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .cart-item-details p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #ff4d4d;
            font-weight: bold;
            font-size: 18px;
        }
        
        .select-all {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .select-all label {
            font-weight: 500;
            cursor: pointer;
        }
        
        /* No Items Message */
        .no-items {
            text-align: center;
            padding: 60px 20px;
        }
        
        .no-items i {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .no-items h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .no-items p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }
        
        /* Buttons */
        .delete-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }
        
        .btn {
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            border: none;
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #ff4d4d, #ff3333);
            color: white;
        }
        
        .btn-delete:hover {
            background: linear-gradient(135deg, #ff3333, #ff1a1a);
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }
        
        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="dashboard.php" class="logo">
                    <i class="fas fa-clapperboard"></i>
                    StreamBox
                </a>
                <div class="nav-links">
                    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="movies.php"><i class="fas fa-film"></i> Movies</a>
                    <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <?php 
                        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                            echo '<a href="admin/index.php"><i class="fas fa-crown"></i> Admin</a>';
                        }
                    ?>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <section class="page-header">
        <div class="container">
            <h1>Delete Cart Items</h1>
            <p>Select items you want to remove from your cart</p>
        </div>
    </section>
    
    <section class="delete-cart-section">
        <div class="container">
            <div class="delete-cart-container">
                <div class="delete-cart-box">
                    <div class="delete-header">
                        <i class="fas fa-trash-alt"></i>
                        <h2>Remove Items from Cart</h2>
                        <p>Select one or more items to delete</p>
                    </div>
                    
                    <?php 
                        if($has_items) {
                            echo '<form method="POST">';
                                echo '<div class="select-all">';
                                    echo '<input type="checkbox" id="select-all" value="1">';
                                    echo '<label for="select-all">Select All Items</label>';
                                echo '</div>';
                                
                                echo '<div class="cart-items-list">';
                                
                                while($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    echo '<div class="cart-item">';
                                        echo '<input type="checkbox" name="selected_items[]" value="' . $item['id'] . '" class="cart-item-checkbox">';
                                        echo '<div class="cart-item-info">';
                                            echo '<img src="' . $item['poster_url'] . '" alt="' . $item['title'] . '">';
                                            echo '<div class="cart-item-details">';
                                                echo '<h4>' . $item['title'] . '</h4>';
                                                echo '<p>Quantity: ' . $item['quantity'] . '</p>';
                                                echo '<p class="item-price">$' . number_format($item['price'], 2) . ' each</p>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                                
                                echo '</div>';
                                
                                echo '<div class="delete-actions">';
                                    echo '<a href="cart.php" class="btn btn-cancel"><i class="fas fa-times"></i> Cancel</a>';
                                    echo '<button type="submit" name="delete_items" class="btn btn-delete"><i class="fas fa-trash"></i> Delete Selected Items</button>';
                                echo '</div>';
                            echo '</form>';
                        }
                        else {
                            echo '<div class="no-items">';
                                echo '<i class="fas fa-shopping-cart"></i>';
                                echo '<h3>Your cart is empty</h3>';
                                echo '<p>There are no items to delete</p>';
                                echo '<a href="cart.php" class="btn btn-cancel"><i class="fas fa-arrow-left"></i> Back to Cart</a>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>