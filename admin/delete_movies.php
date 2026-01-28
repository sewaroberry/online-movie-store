<?php
include('../includes/config.php');

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("location: ../login.php");
    exit();
}

// Handle delete multiple movies
if (isset($_POST['delete_selected'])) {
    if (isset($_POST['selected_movies']) && !empty($_POST['selected_movies'])) {
        foreach ($_POST['selected_movies'] as $movie_id) {
            mysqli_query($conn, "DELETE FROM movies WHERE id = $movie_id");
        }
        header("location: manage_movies.php");
        exit();
    }
}

// Get all movies
$sql = "SELECT * FROM movies ORDER BY title ASC";
$result = mysqli_query($conn, $sql);

$total_movies = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Movies - StreamBox Admin</title>
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
        
        .page-header p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Delete Movies Page */
        .delete-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }
        
        .delete-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .delete-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .delete-header i {
            font-size: 60px;
            color: #ff4d4d;
            margin-bottom: 20px;
            display: block;
        }
        
        .delete-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .delete-header p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Movies List */
        .movies-list {
            margin-bottom: 30px;
        }
        
        .movie-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .movie-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        
        .movie-checkbox {
            width: 20px;
            height: 20px;
            accent-color: #ff4d4d;
        }
        
        .movie-details h4 {
            margin-bottom: 5px;
            font-size: 16px;
        }
        
        .movie-details p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }
        
        /* No Movies Message */
        .no-movies {
            text-align: center;
            padding: 40px 20px;
        }
        
        .no-movies i {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .no-movies h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .no-movies p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
        }
        
        /* Buttons */
        .delete-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
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
    
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Delete Multiple Movies</h1>
            <p>Select movies you want to remove</p>
        </div>
    </section>
    
    <!-- Delete Movies Content -->
    <section class="delete-section">
        <div class="container">
            <div class="delete-container">
                <div class="delete-box">
                    <div class="delete-header">
                        <i class="fas fa-trash-alt"></i>
                        <h2>Delete Movies</h2>
                        <p>Select one or more movies to delete permanently</p>
                    </div>
                    
                    <?php 
                    if ($total_movies > 0) {
                        echo '<form method="POST">';
                        echo '<div class="movies-list">';
                        
                        while ($movie = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo '<div class="movie-item">';
                            echo '<input type="checkbox" name="selected_movies[]" value="' . $movie['id'] . '" class="movie-checkbox">';
                            echo '<div class="movie-details">';
                            echo '<h4>' . $movie['title'] . '</h4>';
                            echo '<p>' . $movie['release_year'] . ' | ' . $movie['genre'] . ' | $' . number_format($movie['price'], 2) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                        
                        echo '<div class="delete-actions">';
                        echo '<a href="manage_movies.php" class="btn btn-cancel"><i class="fas fa-times"></i> Cancel</a>';
                        echo '<button type="submit" name="delete_selected" class="btn btn-delete"><i class="fas fa-trash"></i> Delete Selected Movies</button>';
                        echo '</div>';
                        echo '</form>';
                    } else {
                        echo '<div class="no-movies">';
                        echo '<i class="fas fa-film"></i>';
                        echo '<h3>No movies found</h3>';
                        echo '<p>There are no movies to delete</p>';
                        echo '<a href="manage_movies.php" class="btn btn-cancel"><i class="fas fa-arrow-left"></i> Back to Movies</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>