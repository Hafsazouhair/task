<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_type'] != 'user') {
    header("Location: login.php");
    exit();
}

echo "Welcome to the User Page, " . $_SESSION['username'];
// Add user-specific functionality here
?>

<a href="logout.php">Logout</a>