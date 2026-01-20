<?php
require_once('auth/auth_guard.php');
require_once('../model/userModel.php');

// Restrict access to Admins
protect_page('admin');

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Attempt deletion
    if (deleteUser($id)) {
        header("Location: ../views/admin/readers.php?msg=Reader+Removed+Successfully");
    } else {
        header("Location: ../views/admin/readers.php?msg=Error+Removing+Reader");
    }
} else {
    // Handle invalid ID
    header("Location: ../views/admin/readers.php?msg=Invalid+Reader+ID");
}
exit();
?>