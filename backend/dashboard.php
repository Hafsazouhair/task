<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

echo "Welcome to the Admin Dashboard, " . $_SESSION['username'];
// Add admin-specific functionality here
?>

<a href="logout.php">Logout</a>
