<?php
    include('../includes/config.php');

    if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
        header("location: ../login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StreamBox</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        
        /* Admin Dashboard */
        .admin-dashboard {
            padding: 40px 0;
        }
        
        .admin-welcome {
            background: linear-gradient(135deg, #ff4d4d, #ff3333);
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .admin-welcome h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .admin-welcome p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .admin-stats {
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
        
        .admin-actions {
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
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <i class="fas fa-clapperboard"></i>
                    StreamBox Admin
                </a>
                <div class="nav-links">
                    <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="manage_movies.php"><i class="fas fa-film"></i> Manage Movies</a>
                    <a href="../dashboard.php"><i class="fas fa-user"></i> User Panel</a>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <section class="page-header">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p>Manage your StreamBox website</p>
        </div>
    </section>
    
    <section class="admin-dashboard">
        <div class="container">
            <!-- Welcome Message -->
            <div class="admin-welcome">
                <h1>Welcome, Admin <?php echo $user['full_name']; ?>!</h1>
                <p>You have full control over the website content and settings</p>
            </div>
            
            <!-- Stats -->
            <div class="admin-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <h3>150+</h3>
                    <p>Total Movies</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>500+</h3>
                    <p>Total Users</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>45</h3>
                    <p>Orders Today</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>4.8</h3>
                    <p>Average Rating</p>
                </div>
            </div>
            
            <h2 style="margin-bottom: 20px; font-size: 28px;">Quick Actions</h2>
            <div class="admin-actions">
                <a href="manage_movies.php" class="action-card">
                    <i class="fas fa-film"></i>
                    <h3>Manage Movies</h3>
                    <p>Add, edit or remove movies</p>
                </a>
                
                <a href="add_movie.php" class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <h3>Add New Movie</h3>
                    <p>Add a new movie to catalog</p>
                </a>
                
                <a href="delete_movies.php" class="action-card">
                    <i class="fas fa-trash"></i>
                    <h3>Delete Movies</h3>
                    <p>Remove multiple movies</p>
                </a>
                
                <a href="../dashboard.php" class="action-card">
                    <i class="fas fa-user"></i>
                    <h3>User Panel</h3>
                    <p>Go to user dashboard</p>
                </a>
            </div>
        </div>
    </section>
</body>
</html>