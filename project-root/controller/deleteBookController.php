<?php
require_once('auth/auth_guard.php');
require_once('../model/bookModel.php');
protect_page('admin');

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    if (deleteBook($id)) {
        header("Location: ../views/admin/inventory.php?msg=deleted");
    } else {
        header("Location: ../views/admin/inventory.php?msg=error");
    }
}
exit();