<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function protect_page($required_role = 'user') {
    // Check login status
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../views/index.php?error=please_login");
        exit();
    }

    // Verify role permissions
    $user_role = $_SESSION['role'] ?? '';
    
    if ($user_role !== $required_role) {
        // Redirect based on actual role
        if ($user_role === 'admin') {
            header("Location: ../../views/admin/admin_dashboard.php");
        } else {
            header("Location: ../../views/index.php?error=unauthorized");
        }
        exit();
    }
}
?>