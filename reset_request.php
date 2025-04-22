<?php
session_start();
?>

<form method="POST" action="process_reset.php">
    <h2>Reset Password</h2>
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <button type="submit">Request Reset Code</button>
</form>
