<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_request'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $expiry = date("Y-m-d H:i:s", time() + 3600);

        $update = $conn->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE username=? AND email=?");
        $update->bind_param("ssss", $token, $expiry, $username, $email);
        $update->execute();

        echo "<p>Your reset code is: <strong>$token</strong></p>";
        echo '
        <form method="POST" action="process_reset.php">
            <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
            <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
            <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
            <div>
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>';
    } else {
        echo "<p>User not found with given credentials.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<p>Passwords do not match.</p>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND email=? AND reset_token=? AND token_expiry > NOW()");
    $stmt->bind_param("sss", $username, $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE username=? AND email=?");
        $update->bind_param("sss", $hashed_password, $username, $email);
        $update->execute();

        echo "<p>Password updated successfully. Redirecting to login...</p>";
        header("refresh:3;url=login.php");
        exit();
    } else {
        echo "<p>Invalid or expired token.</p>";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <form method="POST" action="process_reset.php">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <button type="submit" name="reset_request">Request Reset</button>
    </form>
</body>
</html>