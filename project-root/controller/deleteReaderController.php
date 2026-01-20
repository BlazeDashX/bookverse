<?php
require_once('auth/auth_guard.php');
require_once('../model/userModel.php');
protect_page('admin');

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    if (deleteUser($id)) {
        header("Location: ../views/admin/readers.php?msg=Reader+Removed");
    } else {
        header("Location: ../views/admin/readers.php?msg=Error+Removing+Reader");
    }
}
exit();