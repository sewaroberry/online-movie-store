<?php
include('../includes/config.php');

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("location: ../login.php");
    exit();
}

// Handle add movie
if(isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $release_year = $_POST['release_year'];
    $rating = $_POST['rating'];
    $price = $_POST['price'];
    $poster_url = $_POST['poster_url'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $sql = "INSERT INTO movies (title, description, genre, release_year, rating, price, poster_url, is_featured) 
            VALUES ('$title', '$description', '$genre', '$release_year', '$rating', '$price', '$poster_url', '$is_featured')";
    
    mysqli_query($conn, $sql);
    header("location: manage_movies.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Movie - StreamBox Admin</title>
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
        
        /* Add Movie Form */
        .add-movie-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }
        
        .add-movie-box {
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
            color: #28a745;
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
        
        /* Form Styles */
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
        
        /* Form Actions */
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
        
        .btn-add {
            background: #28a745;
            color: white;
        }
        
        .btn-add:hover {
            background: #218838;
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
            <h1>Add New Movie</h1>
            <p>Add a new movie to the catalog</p>
        </div>
    </section>
    
    <!-- Add Movie Form -->
    <section class="add-movie-section">
        <div class="container">
            <div class="add-movie-container">
                <div class="add-movie-box">
                    <div class="form-header">
                        <i class="fas fa-plus-circle"></i>
                        <h2>Add New Movie</h2>
                        <p>Fill in the details to add a new movie</p>
                    </div>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="title">Movie Title *</label>
                            <input type="text" id="title" name="title" placeholder="Enter movie title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Enter movie description"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="genre">Genre</label>
                            <input type="text" id="genre" name="genre" placeholder="e.g., Action, Comedy, Drama">
                        </div>
                        
                        <div class="form-group">
                            <label for="release_year">Release Year</label>
                            <input type="number" id="release_year" name="release_year" placeholder="e.g., 2023" min="1900" max="2030">
                        </div>
                        
                        <div class="form-group">
                            <label for="rating">Rating (0-10)</label>
                            <input type="number" id="rating" name="rating" placeholder="e.g., 7.5" step="0.1" min="0" max="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <input type="number" id="price" name="price" placeholder="e.g., 9.99" step="0.01" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="poster_url">Poster Image URL</label>
                            <input type="text" id="poster_url" name="poster_url" placeholder="https://example.com/poster.jpg">
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_featured" name="is_featured">
                                <label for="is_featured">Featured Movie</label>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="manage_movies.php" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" name="add_movie" class="btn btn-add">
                                <i class="fas fa-plus-circle"></i> Add Movie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>