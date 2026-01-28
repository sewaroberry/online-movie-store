<?php
    include('../includes/config.php');

    if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
        header("location: ../login.php");
        exit();
    }

    if(isset($_POST['delete_movie'])) {
        $movie_id = $_POST['movie_id'];
        $sql = "DELETE FROM movies WHERE id = $movie_id";
        mysqli_query($conn, $sql);
    }

    if(isset($_POST['edit_movie'])) {
        $movie_id = $_POST['movie_id'];
        $_SESSION['edit_movie_id'] = $movie_id;
        header("location: edit_movie.php");
        exit();
    }

    $sql = "SELECT * FROM movies ORDER BY title ASC";
    $result = mysqli_query($conn, $sql);

    $total_movies = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Manage Movies - StreamBox Admin</title>
    
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
        
        /* Movies Table */
        .movies-table {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .movies-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .movies-table th {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            text-align: left;
            font-weight: 600;
        }
        
        .movies-table td {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .movie-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .movie-info img {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .movie-info h4 {
            margin-bottom: 5px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .btn-edit {
            background: #ff4d4d;
            color: white;
        }
        
        .btn-edit:hover {
            background: #ff3333;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .btn-add {
            background: #28a745;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        
        .btn-add:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-delete-multiple {
            background: #dc3545;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .btn-delete-multiple:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .top-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .empty-message {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-message i {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .empty-message h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .empty-message p {
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
            <h1>Manage Movies</h1>
            <p>Add, edit or remove movies from catalog</p>
        </div>
    </section>
    
    <section class="movies-section">
        <div class="container">
            <div class="top-buttons">
                <a href="add_movie.php" class="btn-add">
                    <i class="fas fa-plus-circle"></i> Add New Movie
                </a>
                <a href="delete_movies.php" class="btn-delete-multiple">
                    <i class="fas fa-trash"></i> Delete Multiple Movies
                </a>
            </div>
            
            <?php 
                if($total_movies > 0) {
                    echo '<div class="movies-table">';
                    echo '<table>';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th>Movie</th>';
                                echo '<th>Year</th>';
                                echo '<th>Rating</th>';
                                echo '<th>Price</th>';
                                echo '<th>Featured</th>';
                                echo '<th>Actions</th>';
                            echo '</tr>';
                        echo '</thead>';
                    echo '<tbody>';
                    
                    while ($movie = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo '<tr>';
                            echo '<td>';
                                echo '<div class="movie-info">';
                                    echo '<img src="' . $movie['poster_url'] . '" alt="' . $movie['title'] . '">';
                                    echo '<div>';
                                        echo '<h4>' . $movie['title'] . '</h4>';
                                        echo '<p style="color: rgba(255,255,255,0.6); font-size: 14px;">' . $movie['genre'] . '</p>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</td>';
                            echo '<td>' . $movie['release_year'] . '</td>';
                            echo '<td>' . $movie['rating'] . '</td>';
                            echo '<td>$' . number_format($movie['price'], 2) . '</td>';
                            echo '<td>' . ($movie['is_featured'] == 1 ? 'Yes' : 'No') . '</td>';
                            echo '<td>';
                            echo '<div class="action-buttons">';
                            echo '<form method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="movie_id" value="' . $movie['id'] . '">';
                            echo '<button type="submit" name="edit_movie" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</button>';
                            echo '</form>';
                            echo '<form method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="movie_id" value="' . $movie['id'] . '">';
                            echo '<button type="submit" name="delete_movie" class="btn btn-delete"><i class="fas fa-trash"></i> Delete</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                }
                else {
                    echo '<div class="empty-message">';
                    echo '<i class="fas fa-film"></i>';
                    echo '<h3>No movies found</h3>';
                    echo '<p>Add your first movie to get started</p>';
                    echo '<div class="top-buttons" style="justify-content: center;">';
                    echo '<a href="add_movie.php" class="btn-add"><i class="fas fa-plus-circle"></i> Add New Movie</a>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </section>
</body>
</html>