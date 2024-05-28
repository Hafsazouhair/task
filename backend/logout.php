<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the home page
header("Location: login.php");
exit; // Ensure that no further code is executed after the redirect
?>
