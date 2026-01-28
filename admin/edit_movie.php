<?php
include('../includes/config.php');

if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("location: ../login.php");
    exit();
}

$movie_id = 0;
if(isset($_SESSION['edit_movie_id'])) {
    $movie_id = $_SESSION['edit_movie_id'];
}

$movie = null;
if($movie_id > 0) {
    $sql = "SELECT * FROM movies WHERE id = $movie_id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        $movie = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }
}

if(isset($_POST['update_movie'])) {
    // Escape all string inputs to prevent SQL injection and syntax errors
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $release_year = mysqli_real_escape_string($conn, $_POST['release_year']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $poster_url = mysqli_real_escape_string($conn, $_POST['poster_url']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $movie_id = mysqli_real_escape_string($conn, $_POST['movie_id']);
    
    $sql = "UPDATE movies SET 
            title = '$title',
            description = '$description',
            genre = '$genre',
            release_year = '$release_year',
            rating = '$rating',
            price = '$price',
            poster_url = '$poster_url',
            is_featured = '$is_featured'
            WHERE id = $movie_id";
    
    if(mysqli_query($conn, $sql)) {
        // Success - redirect to manage movies page
        header("location: manage_movies.php");
        exit();
    } else {
        // Error handling - show error message
        $error_message = "Error updating movie: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - StreamBox Admin</title>
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
        
        .edit-movie-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }
        
        .edit-movie-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-header i {
            font-size: 60px;
            color: #ff4d4d;
            margin-bottom: 20px;
            display: block;
        }
        
        .form-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff4d4d;
            background: rgba(255, 255, 255, 0.12);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .checkbox-group input {
            width: 20px;
            height: 20px;
            accent-color: #ff4d4d;
        }
        
        .checkbox-group label {
            margin: 0;
            cursor: pointer;
        }
        
        .form-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }
        
        .btn {
            padding: 15px 40px;
            border-radius: 10px;
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
        
        .btn-update {
            background: #ff4d4d;
            color: white;
        }
        
        .btn-update:hover {
            background: #ff3333;
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
        
        .movie-not-found {
            text-align: center;
            padding: 60px 20px;
        }
        
        .movie-not-found i {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .movie-not-found h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .movie-not-found p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
        }
        
        .error-message {
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid #ff4d4d;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #ff9999;
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
            <h1>Edit Movie</h1>
            <p>Update movie information</p>
        </div>
    </section>
    
    <section class="edit-movie-section">
        <div class="container">
            <div class="edit-movie-container">
                <div class="edit-movie-box">
                    <?php 
                    if (isset($error_message)) {
                        echo '<div class="error-message">' . $error_message . '</div>';
                    }
                    
                    if ($movie) {
                        echo '
                        <div class="form-header">
                            <i class="fas fa-edit"></i>
                            <h2>Edit Movie: ' . htmlspecialchars($movie['title']) . '</h2>
                            <p>Update the movie details below</p>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="movie_id" value="' . $movie['id'] . '">
                            
                            <div class="form-group">
                                <label for="title">Movie Title *</label>
                                <input type="text" id="title" name="title" value="' . htmlspecialchars($movie['title']) . '" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description">' . htmlspecialchars($movie['description']) . '</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="genre">Genre</label>
                                <input type="text" id="genre" name="genre" value="' . htmlspecialchars($movie['genre']) . '">
                            </div>
                            
                            <div class="form-group">
                                <label for="release_year">Release Year</label>
                                <input type="number" id="release_year" name="release_year" value="' . htmlspecialchars($movie['release_year']) . '">
                            </div>
                            
                            <div class="form-group">
                                <label for="rating">Rating (0-10)</label>
                                <input type="number" id="rating" name="rating" value="' . htmlspecialchars($movie['rating']) . '" step="0.1" min="0" max="10">
                            </div>
                            
                            <div class="form-group">
                                <label for="price">Price ($)</label>
                                <input type="number" id="price" name="price" value="' . htmlspecialchars($movie['price']) . '" step="0.01" min="0">
                            </div>
                            
                            <div class="form-group">
                                <label for="poster_url">Poster Image URL</label>
                                <input type="text" id="poster_url" name="poster_url" value="' . htmlspecialchars($movie['poster_url']) . '">
                            </div>
                            
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="is_featured" name="is_featured" ' . ($movie['is_featured'] == 1 ? 'checked' : '') . '>
                                    <label for="is_featured">Featured Movie</label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <a href="manage_movies.php" class="btn btn-cancel">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" name="update_movie" class="btn btn-update">
                                    <i class="fas fa-save"></i> Update Movie
                                </button>
                            </div>
                        </form>';
                    }
                    else {
                        echo '
                        <div class="movie-not-found">
                            <i class="fas fa-film"></i>
                            <h3>Movie Not Found</h3>
                            <p>The movie you\'re trying to edit doesn\'t exist</p>
                            <a href="manage_movies.php" class="btn btn-cancel">
                                <i class="fas fa-arrow-left"></i> Back to Movies
                            </a>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>