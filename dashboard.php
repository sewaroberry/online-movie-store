<?php
    include('includes/config.php');

    if (!isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $cart_sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_sql);
    $cart = mysqli_fetch_assoc($cart_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Dashboard - StreamBox</title>
    
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
        
        .user-welcome {
            color: white;
            margin-right: 20px;
            font-weight: 500;
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
        
        /* Dashboard Content */
        .dashboard-content {
            padding: 40px 0;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            margin-top: 40px;
        }
        
        .section-header h2 {
            font-size: 28px;
            font-weight: 700;
        }
        
        .btn-view-all {
            background: #ff4d4d;
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-view-all:hover {
            background: #ff3333;
            transform: translateY(-2px);
        }
        
        /* Welcome Box */
        .welcome-box {
            background: linear-gradient(135deg, #ff4d4d, #ff3333);
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .welcome-box h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .welcome-box p {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .user-plan {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #ff4d4d;
        }
        
        .stat-icon {
            font-size: 36px;
            color: #ff4d4d;
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Quick Actions */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .action-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            text-decoration: none;
            color: white;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .action-card:hover {
            background: rgba(255, 77, 77, 0.1);
            border-color: #ff4d4d;
            transform: translateY(-5px);
        }
        
        .action-card i {
            font-size: 32px;
            color: #ff4d4d;
            margin-bottom: 15px;
        }
        
        .action-card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .action-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }
        
        /* Featured Movies */
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .movie-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .movie-card:hover {
            transform: translateY(-5px);
            border-color: #ff4d4d;
        }
        
        .movie-poster {
            height: 300px;
            overflow: hidden;
        }
        
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .movie-badge {
            background: #ff4d4d;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin: 10px;
            border-radius: 5px;
        }
        
        .movie-info {
            padding: 20px;
        }
        
        .movie-info h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .movie-meta {
            display: flex;
            justify-content: space-between;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .movie-rating {
            color: #ffd700;
        }
        
        .movie-price {
            color: #ff4d4d;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .btn-add {
            width: 100%;
            padding: 12px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-add:hover {
            background: #ff3333;
        }
        
        @media (max-width: 768px) {
            .nav-links a:not(.btn-logout) {
                display: none;
            }
            
            .welcome-box h1 {
                font-size: 28px;
            }
            
            .stats-grid,
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .movies-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid,
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .movies-grid {
                grid-template-columns: 1fr;
            }
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
                    <span class="user-welcome">Welcome, <?php echo $user['username']; ?></span>
                    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="movies.php"><i class="fas fa-film"></i> Movies</a>
                    <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <?php 
                        if ($user['is_admin'] == 1) {
                            echo '<a href="admin/index.php"><i class="fas fa-crown"></i> Admin</a>';
                        }
                    ?>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <section class="dashboard-content">
        <div class="container">
            <!-- Welcome Message -->
            <div class="welcome-box">
                <h1>Welcome Back, <?php echo $user['full_name']; ?>!</h1>
                <p>Your ultimate destination for movies and TV shows</p>
                <span class="user-plan">Premium Member</span>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <h3>150+</h3>
                    <p>Movies Available</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3><?php echo 27 ?></h3>
                    <p>Favorites</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>45</h3>
                    <p>Hours Watched</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3><?php echo $cart['count']; ?></h3>
                    <p>Items in Cart</p>
                </div>
            </div>
            
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            <div class="actions-grid">
                <a href="movies.php" class="action-card">
                    <i class="fas fa-film"></i>
                    <h3>Browse Movies</h3>
                    <p>Watch new releases</p>
                </a>
                
                <a href="cart.php" class="action-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Shopping Cart</h3>
                    <p>View your cart</p>
                </a>
                
                <a href="profile.php" class="action-card">
                    <i class="fas fa-user"></i>
                    <h3>My Profile</h3>
                    <p>Edit account info</p>
                </a>
                
                <a href="favorites.php" class="action-card">
                    <i class="fas fa-heart"></i>
                    <h3>Favorites</h3>
                    <p>Your saved movies</p>
                </a>
            </div>
            
            <div class="section-header">
                <h2>Featured Movies</h2>
                <a href="movies.php" class="btn-view-all">
                    <i class="fas fa-arrow-right"></i>
                    View All Movies
                </a>
            </div>
            <div class="movies-grid">
                <?php
                    $movies_sql = "SELECT * FROM movies WHERE is_featured = 1 LIMIT 4";
                    $movies_result = mysqli_query($conn, $movies_sql);
                    
                    if(mysqli_num_rows($movies_result) > 0) {
                        while($movie = mysqli_fetch_array($movies_result, MYSQLI_ASSOC)) {
                            echo '<div class="movie-card">';
                                echo '<div class="movie-poster">';
                                    echo '<img src="' . $movie['poster_url'] . '" alt="' . $movie['title'] . '">';
                                    echo '<span class="movie-badge">Featured</span>';
                                echo '</div>';
                                echo '<div class="movie-info">';
                                    echo '<h3>' . $movie['title'] . '</h3>';
                                    echo '<div class="movie-meta">';
                                        echo '<span>' . $movie['release_year'] . '</span>';
                                        echo '<span class="movie-rating">';
                                        echo '<i class="fas fa-star"></i> ' . $movie['rating'];
                                        echo '</span>';
                                echo '</div>';
                                echo '<div class="movie-price">$' . $movie['price'] . '</div>';
                                
                                    echo '<form action="add_to_cart.php" method="post">';
                                        echo '<input type="hidden" name="movie_id" value="' . $movie['id'] . '">';
                                        echo '<button type="submit" class="btn-add">';
                                        echo '<i class="fas fa-shopping-cart"></i>';
                                        echo ' Add to Cart';
                                        echo '</button>';
                                    echo '</form>';

                                echo '</div>';
                            echo '</div>';
                        }
                    }
                    else {
                        echo '<div style="grid-column: 1 / -1; text-align: center; padding: 40px;">';
                            echo '<p style="color: rgba(255,255,255,0.6);">No featured movies available at the moment.</p>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </section>
</body>
</html>