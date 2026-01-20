<?php
// Initialize session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear and destroy session
session_unset();
session_destroy();

// Redirect to home/login page
header("Location: ../../views/index.php");
exit();
?>