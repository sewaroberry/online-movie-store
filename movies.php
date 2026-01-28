<?php
    include('includes/config.php');

    if(!isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit();
    }

    $search = '';
    $genre = '';
    $sort = 'title_asc';

    if(isset($_POST['apply_filters'])) {
        $search = $_POST['search'];
        $genre = $_POST['genre'];
        $sort = $_POST['sort'];
    }

    $sql = "SELECT * FROM movies WHERE 1=1 ";

    if($search != '') {
        $sql .= " AND (title LIKE '%$search%')";
    }

    if($genre != '' && $genre != 'all') {
        $sql .= " AND genre LIKE '%$genre%'";
    }

    if($sort == 'title_asc') {
        $sql .= " ORDER BY title ASC";
    }
    elseif($sort == 'title_desc') {
        $sql .= " ORDER BY title DESC";
    }
    elseif($sort == 'price_low') {
        $sql .= " ORDER BY price ASC";
    }
    elseif($sort == 'price_high') {
        $sql .= " ORDER BY price DESC";
    }
    elseif($sort == 'rating_high') {
        $sql .= " ORDER BY rating DESC";
    }
    elseif($sort == 'rating_low') {
        $sql .= " ORDER BY rating ASC";
    }
    elseif($sort == 'newest') {
        $sql .= " ORDER BY release_year DESC";
    }
    elseif($sort == 'oldest') {
        $sql .= " ORDER BY release_year ASC";
    }
    else {
        $sql .= " ORDER BY title ASC";
    }

    $result = mysqli_query($conn, $sql);

    $total_movies = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Movies - StreamBox</title>
    
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
        
        .filters {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-right: 95px;
            margin-left: 95px;
        }
        
        .filter-form {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        
        .filter-row {
            display: flex;
            gap: 20px;
            width: 100%;
            flex-wrap: wrap;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 16px;
        }
        
        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #ff4d4d;
        }

        .filter-group option {
            color: black;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        .btn {
            padding: 12px 30px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #ff3333;
            transform: translateY(-2px);
        }
        
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .movie-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .movie-card:hover {
            transform: translateY(-10px);
            border-color: #ff4d4d;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .movie-poster {
            height: 350px;
            overflow: hidden;
        }
        
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .movie-card:hover .movie-poster img {
            transform: scale(1.1);
        }
        
        .movie-info {
            padding: 25px;
        }
        
        .movie-info h3 {
            margin-bottom: 10px;
            font-size: 18px;
            min-height: 54px;
            line-height: 1.4;
        }
        
        .movie-meta {
            display: flex;
            justify-content: space-between;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .movie-price {
            color: #ff4d4d;
            font-weight: bold;
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .movie-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-view {
            flex: 1;
            padding: 12px;
            background: transparent;
            border: 2px solid #ff4d4d;
            color: #ff4d4d;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-view:hover {
            background: rgba(255, 77, 77, 0.1);
        }
        
        .btn-add {
            flex: 1;
            padding: 12px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-add:hover {
            background: #ff3333;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        
        .no-results i {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .no-results h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .no-results p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }
        
        .results-count {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        @media (max-width: 768px) {
            .nav-links a:not(.btn-logout) {
                display: none;
            }
            
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .movies-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 480px) {
            .movies-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                flex-direction: column;
            }
            
            .movie-actions {
                flex-direction: column;
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
            <h1>Browse Movies</h1>
            <p>Choose from our collection of movies</p>
        </div>
    </section>
    
    <section class="filters">
        <div class="container">
            <form method="POST" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" name="search" placeholder="Search movies..." value="<?php echo $search; ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label>Genre</label>
                        <select name="genre">
                            <option value="all">All Genres</option>
                            <option value="Action" <?php if($genre == 'Action') { echo 'selected'; } ?>>Action</option>
                            <option value="Comedy" <?php if($genre == 'Comedy') { echo 'selected'; } ?>>Comedy</option>
                            <option value="Drama" <?php if($genre == 'Drama') { echo 'selected'; } ?>>Drama</option>
                            <option value="Horror" <?php if($genre == 'Horror') { echo 'selected'; } ?>>Horror</option>
                            <option value="Sci-Fi" <?php if($genre == 'Sci-Fi') { echo 'selected'; } ?>>Sci-Fi</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Sort By</label>
                        <select name="sort">
                            <option value="title_asc" <?php if($sort == 'title_asc') { echo 'selected'; } ?>>Title (A-Z)</option>
                            <option value="title_desc" <?php if($sort == 'title_desc') { echo 'selected'; } ?>>Title (Z-A)</option>
                            <option value="price_low" <?php if($sort == 'price_low') { echo 'selected'; } ?>>Price (Low to High)</option>
                            <option value="price_high" <?php if($sort == 'price_high') { echo 'selected'; } ?>>Price (High to Low)</option>
                            <option value="rating_high" <?php if($sort == 'rating_high') { echo 'selected'; } ?>>Rating (High to Low)</option>
                            <option value="rating_low" <?php if($sort == 'rating_low') { echo 'selected'; } ?>>Rating (Low to High)</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" name="apply_filters" class="btn">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </section>
    
    <section class="movies-section">
        <div class="container">
            <div class="results-count">
                <?php 
                    echo $total_movies . ' movie';
                    if($total_movies != 1) {
                        echo 's';
                    }
                    echo ' found';
                ?>
            </div>
            
            <div class="movies-grid">
                <?php 
                    if(mysqli_num_rows($result) > 0) {
                        while($movie = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo '<div class="movie-card">';
                                echo '<div class="movie-poster">';
                                    echo '<img src="' . $movie['poster_url'] . '" alt="' . $movie['title'] . '">';
                                    echo '</div>';
                                        echo '<div class="movie-info">';
                                        echo '<h3>' . $movie['title'] . '</h3>';
                                        echo '<div class="movie-meta">';
                                            echo '<span>' . $movie['release_year'] . '</span>';
                                            echo '<span><i class="fas fa-star"></i> ' . $movie['rating'] . '</span>';
                                        echo '</div>';
                                        echo '<div class="movie-price">$' . $movie['price'] . '</div>';
                                        echo '<div class="movie-actions">';
                                        echo '<button class="btn-view">';
                                        echo '<i class="fas fa-eye"></i> View';
                                        echo '</button>';
                                        
                                        echo '<form action="add_to_cart.php" method="post" style="display: inline;">';
                                            echo '<input type="hidden" name="movie_id" value="' . $movie['id'] . '">';
                                            echo '<button type="submit" class="btn-add">';
                                            echo '<i class="fas fa-shopping-cart"></i> Add';
                                            echo '</button>';
                                        echo '</form>';
                                    
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                    else {
                        echo '<div class="no-results">';
                            echo '<i class="fas fa-film"></i>';
                            echo '<h3>No movies found</h3>';
                            echo '<p>Try adjusting your search or filter to find what you\'re looking for.</p>';
                            echo '<a href="movies.php" class="btn">Browse All Movies</a>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </section>
</body>
</html>