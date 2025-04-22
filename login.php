<?php
session_start(); 
require 'db.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "Please fill in all fields.";
        exit();
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            
            if (password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: home.php'); 
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Database query failed.";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container" id="login-form">
            <h2>Login</h2>
            <form id="loginForm" action="login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" id="login-username" required>
                    <label for="login-username">Username or Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="login-password" required>
                    <label for="login-password">Password</label>
                    <div class="forgot-password">
                        <a href="process_reset.php" id="forgotpassword">Forgot Password?</a>
                    </div>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="toggle-form">
                Don't have an account? <a href="signup.php" id="register">Sign Up</a>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('register').addEventListener('click', function(event) {
        event.preventDefault();
        window.location.href = 'signup.php';
    });
    </script>
</body>
</html>