<?php
session_start();

$_SESSION = array();
session_destroy();

echo "success";
header('Location: login.php');
?>