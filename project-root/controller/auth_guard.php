<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function check_auth($role = 'user') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>