<?php
    include('includes/config.php');

    $message = "";

    if(isset($_POST['submit'])) {
        // 1- receive values from form.
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $full_name = $_POST['full_name'];
        
        // 2- check if user exists.
        $check_sql = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if(mysqli_num_rows($check_result) > 0) {
            $message = "Username or Email already exists";
        }
        else {
            // 3- insert new user.
            $sql = "INSERT INTO users (username, email, password, full_name) 
                    VALUES ('$username', '$email', '$password', '$full_name')";

            $result = mysqli_query($conn, $sql);
            
            if($result) {
                header("location: login.php");
                exit();
            }
            else {
                $message = "Registration failed";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Register - StreamBox</title>
    
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
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), 
                        url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        /* Register Container */
        .register-container {
            width: 100%;
            max-width: 450px;
        }
        
        .register-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 50px 40px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(15px);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        
        .register-box:hover {
            transform: translateY(-5px);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .register-header i {
            color: #ff4d4d;
            font-size: 60px;
            margin-bottom: 20px;
            display: block;
        }
        
        .register-header h1 {
            font-size: 36px;
            color: white;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .register-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-size: 18px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #ff4d4d;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.2);
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        .btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #ff4d4d, #ff3333);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(255, 77, 77, 0.3);
        }
        
        .btn:hover {
            background: linear-gradient(135deg, #ff3333, #ff1a1a);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 77, 77, 0.4);
        }
        
        .btn:active {
            transform: translateY(-1px);
        }
        
        .error-message {
            color: #ff6b6b;
            text-align: center;
            margin-top: 15px;
            padding: 15px;
            background: rgba(255, 107, 107, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 107, 107, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 14px;
        }
        
        .form-options {
            display: flex;
            align-items: center;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .terms-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .terms-checkbox input {
            width: 18px;
            height: 18px;
            accent-color: #ff4d4d;
        }
        
        .terms-link {
            color: #ff4d4d;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .terms-link:hover {
            text-decoration: underline;
        }
        
        .register-footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }
        
        .register-footer p {
            margin-bottom: 10px;
        }
        
        .register-footer a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            background: rgba(15, 15, 26, 0.95);
            padding: 40px 0 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
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
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .register-box {
                padding: 40px 25px;
                margin: 20px;
            }
            
            .register-header h1 {
                font-size: 28px;
            }
            
            .register-header i {
                font-size: 50px;
            }
            
            .form-group input {
                padding: 14px 14px 14px 45px;
            }
        }
        
        @media (max-width: 480px) {
            .register-header h1 {
                font-size: 24px;
            }
            
            .register-header p {
                font-size: 14px;
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
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="register-container">
                <div class="register-box">
                    <div class="register-header">
                        <i class="fas fa-user-plus"></i>
                        <h1>Create Account</h1>
                        <p>Join StreamBox today and start watching</p>
                    </div>
                    
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <div class="form-group">
                            <label>Full Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" name="full_name" placeholder="Enter your full name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Username</label>
                            <div class="input-with-icon">
                                <i class="fas fa-at"></i>
                                <input type="text" name="username" placeholder="Choose a username" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="Create a password" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <label class="terms-checkbox">
                                <input type="checkbox" name="terms" required>
                                I agree to the <a href="#" class="terms-link">Terms & Conditions</a>
                            </label>
                        </div>
                        
                        <button type="submit" name="submit" class="btn">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </button>
                        
                        <?php
                            if($message != "") {
                                echo '<div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        ' . $message . '
                                    </div>';
                            }
                        ?>
                    </form>
                    
                    <div class="register-footer">
                        <p>Already have an account? <a href="login.php">Sign In</a></p>
                        <p><a href="index.php"><i class="fas fa-arrow-left"></i> Back to home</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
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