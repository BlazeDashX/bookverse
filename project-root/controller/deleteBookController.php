<?php
require_once('auth/auth_guard.php');
require_once('../model/bookModel.php');

// Restrict to Admin
protect_page('admin');

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Attempt deletion
    if (deleteBook($id)) {
        header("Location: ../views/admin/inventory.php?msg=Book+Deleted+Successfully");
    } else {
        header("Location: ../views/admin/inventory.php?msg=Error+Deleting+Book");
    }
} else {
    header("Location: ../views/admin/inventory.php?msg=Invalid+Book+ID");
}
exit();
?>