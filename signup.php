<?php
session_start();
require 'db.php'; // Ensure this file contains the database connection setup

// Redirect to home if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        echo "Please fill in all fields.";
        exit();
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if username or email already exists
    $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $check_sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Username or email already exists.";
            mysqli_stmt_close($stmt);
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Database query failed.";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php"); // Redirect to login page
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">     
        <div class="form-container" id="signup-form">
            <h2>Sign Up</h2>
            <form id="signupForm" action="signup.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" id="signup-username" required>
                    <label for="signup-username">Username</label>
                </div>
                <div class="input-group">
                    <input type="email" name="email" id="signup-email" required>
                    <label for="signup-email">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="signup-password" required>
                    <label for="signup-password">Password</label>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" id="signup-confirm-password" required>
                    <label for="signup-confirm-password">Confirm Password</label>
                </div>
                <button type="submit" class="btn">Sign Up</button>
            </form>
            <div class="toggle-form">
                Already have an account? <a href="login.php" id="show-login">Login</a>
            </div>
        </div>
    </div>
</body>
</html>