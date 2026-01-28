<?php include('includes/config.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>StreamBox - Movies & Series Online</title>
    
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
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        .header {
            background: rgba(15, 15, 26, 0.95);
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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
        }
        
        .nav-links a:hover {
            color: #ff4d4d;
        }
        
        .btn-register {
            background: #ff4d4d;
            padding: 10px 25px;
            border-radius: 25px;
            margin-left: 30px;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background: #ff3333;
            transform: translateY(-2px);
        }
        
        /* Hero Section - FIXED */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 0 50px;
            margin-top: 70px;
        }
        
        .hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff4d4d, #ff3333);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 77, 77, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 77, 77, 0.4);
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid #ff4d4d;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 77, 77, 0.1);
            transform: translateY(-3px);
        }
        
        .login-form {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .login-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ff4d4d;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #ff4d4d;
            background: rgba(255, 255, 255, 0.15);
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-block {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        
        .error {
            color: #ff4d4d;
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            background: rgba(255, 77, 77, 0.1);
            border-radius: 5px;
        }
        
        /* Features Section */
        .section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            font-size: 36px;
            margin-bottom: 50px;
            font-weight: 700;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: #ff4d4d;
        }
        
        .feature-icon {
            font-size: 40px;
            color: #ff4d4d;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .feature-card p {
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
        }
        
        /* Footer */
        .footer {
            background: rgba(15, 15, 26, 0.95);
            padding: 60px 0 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .footer-logo i {
            color: #ff4d4d;
            font-size: 32px;
        }
        
        .footer-logo h3 {
            font-size: 24px;
            font-weight: bold;
        }
        
        .footer-section h4 {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 10px;
        }
        
        .footer-section ul a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-section ul a:hover {
            color: #ff4d4d;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .nav-links {
                display: none;
            }
            
            .hero {
                padding: 120px 20px 50px;
            }
            
            .login-form {
                padding: 30px 20px;
                margin: 0 20px;
            }
        }
        
        @media (max-width: 480px) {
            .hero h1 {
                font-size: 28px;
            }
            
            .section-title {
                font-size: 28px;
            }
            
            .feature-card {
                padding: 20px;
            }
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
                    StreamBox
                </a>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="movies.php">Movies</a>
                    <a href="#features">Features</a>
                    <a href="#pricing">Pricing</a>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="btn-register">Sign Up</a>
                </div>
            </nav>
        </div>
    </header>
    
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Unlimited Movies, TV Shows, and More</h1>
                <p>Watch anywhere. Cancel anytime. Ready to watch? Create your account today!</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-play-circle"></i> Get Started
                    </a>
                    <a href="login.php" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                </div>
                
                <div class="login-form">
                    <h2>Login to StreamBox</h2>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Enter your username" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>
                        
                        <button type="submit" name="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <h2 class="section-title">Why Choose StreamBox?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <h3>Vast Movie Library</h3>
                    <p>Thousands of movies & series across all genres in one place</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clapperboard"></i>
                    </div>
                    <h3>4K Streaming</h3>
                    <p>Crystal clear quality with smooth playback</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Watch Anywhere</h3>
                    <p>On your phone, tablet, laptop, or TV</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-clapperboard"></i>
                        <h3>StreamBox</h3>
                    </div>
                    <p>Your ultimate destination for movies and series. Watch anytime, anywhere.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="movies.php">Movies</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <ul>
                        <li>Email: info@streambox.com</li>
                        <li>Phone: +1 234 567 8900</li>
                        <li>Address: 123 Movie Street</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 StreamBox. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>