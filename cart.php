<?php
    include('includes/config.php');

    if(!isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if(isset($_POST['remove_item'])) {
        $item_id = $_POST['item_id'];
        $sql = "DELETE FROM cart WHERE id = $item_id AND user_id = $user_id";
        mysqli_query($conn, $sql);
    }

    if(isset($_POST['update_quantity'])) {
        $item_id = $_POST['item_id'];
        $quantity = $_POST['quantity'];
        
        if($quantity > 0) {
            $sql = "UPDATE cart SET quantity = $quantity WHERE id = $item_id AND user_id = $user_id";
            mysqli_query($conn, $sql);
        }
        else {
            $sql = "DELETE FROM cart WHERE id = $item_id AND user_id = $user_id";
            mysqli_query($conn, $sql);
        }
    }

    $sql = "SELECT c.*, m.title, m.price, m.poster_url 
            FROM cart c
            JOIN movies m ON c.movie_id = m.id 
            WHERE c.user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    $total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Shopping Cart - StreamBox</title>
    
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
            padding-bottom: 60px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
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
        
        .page-header {
            padding: 40px 0;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .cart-table {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .cart-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .cart-table th {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            text-align: left;
            font-weight: 600;
        }
        
        .cart-table td {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .cart-item-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .cart-item-info img {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .cart-item-info h4 {
            margin-bottom: 5px;
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            color: white;
            padding: 8px;
        }
        
        .btn-update {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .btn-remove {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .cart-summary {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .cart-summary h3 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .summary-row.total {
            font-size: 20px;
            font-weight: 700;
            color: #ff4d4d;
        }
        
        .cart-actions {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: #ff4d4d;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: #ff3333;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #ff4d4d;
            color: #ff4d4d;
        }
        
        .btn-outline:hover {
            background: rgba(255, 77, 77, 0.1);
        }
        
        .btn-delete {
            background: #ff4d4d;
            color: white;
            text-decoration: none;
        }
        
        .btn-delete:hover {
            background: #ff3333;
            transform: translateY(-2px);
        }
        
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }
        
        .empty-cart i {
            font-size: 80px;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .empty-cart h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .empty-cart p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
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
                        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
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
            <h1>Shopping Cart</h1>
            <p>Review your selected movies</p>
        </div>
    </section>
    
    <section class="cart-section">
        <div class="container">
            <?php 
                if(mysqli_num_rows($result) > 0) {
                    echo '<div class="cart-table">';
                        echo '<table>';
                            echo '<thead>';
                                echo '<tr>';
                                    echo '<th>Movie</th>';
                                    echo '<th>Price</th>';
                                    echo '<th>Quantity</th>';
                                    echo '<th>Total</th>';
                                    echo '<th>Action</th>';
                                echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                    
                        while($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                            
                            echo '<tr>';
                            echo '<td>';
                            echo '<div class="cart-item-info">';
                            echo '<img src="' . $item['poster_url'] . '" alt="' . $item['title'] . '">';
                            echo '<div>';
                            echo '<h4>' . $item['title'] . '</h4>';
                            echo '</div>';
                            echo '</div>';
                            echo '</td>';
                            echo '<td>$' . number_format($item['price'], 2) . '</td>';
                            echo '<td>';
                            echo '<form method="POST" style="display: flex; align-items: center;">';
                            echo '<input type="hidden" name="item_id" value="' . $item['id'] . '">';
                            echo '<input type="number" name="quantity" value="' . $item['quantity'] . '" min="1" class="quantity-input">';
                            echo '<button type="submit" name="update_quantity" class="btn-update">Update</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '<td>$' . number_format($item_total, 2) . '</td>';
                            echo '<td>';
                            echo '<form method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="item_id" value="' . $item['id'] . '">';
                            echo '<button type="submit" name="remove_item" class="btn-remove">';
                            echo '<i class="fas fa-trash"></i> Remove';
                            echo '</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        
                        echo '<div class="cart-summary">';
                        echo '<h3>Order Summary</h3>';
                        echo '<div class="summary-row">';
                        echo '<span>Subtotal</span>';
                        echo '<span>$' . number_format($total, 2) . '</span>';
                        echo '</div>';
                        echo '<div class="summary-row">';
                        echo '<span>Tax (10%)</span>';
                        echo '<span>$' . number_format($total * 0.1, 2) . '</span>';
                        echo '</div>';
                        echo '<div class="summary-row total">';
                        echo '<span>Total</span>';
                        echo '<span>$' . number_format($total * 1.1, 2) . '</span>';
                        echo '</div>';
                        
                        echo '<div class="cart-actions">';
                        echo '<a href="movies.php" class="btn btn-outline">Continue Shopping</a>';
                        echo '<a href="delete_cart.php" class="btn btn-delete"><i class="fas fa-trash"></i> Delete Multiple Items</a>';
                        echo '<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                    else {
                        echo '<div class="empty-cart">';
                        echo '<i class="fas fa-shopping-cart"></i>';
                        echo '<h3>Your cart is empty</h3>';
                        echo '<p>Add some movies to get started!</p>';
                        echo '<a href="movies.php" class="btn btn-primary">Browse Movies</a>';
                        echo '</div>';
                    }
            ?>
        </div>
    </section>
</body>
</html>