<?php
require_once('auth/auth_guard.php');
require_once('../model/bookModel.php');

// Restrict to Admin
protect_page('admin');

// 1. Collect & Sanitize Inputs
$id          = (int)($_POST['id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$author      = trim($_POST['author'] ?? '');
$category    = trim($_POST['category'] ?? '');
$stock_qty   = (int)($_POST['stock_qty'] ?? 0);
$sell_price  = (float)($_POST['sell_price'] ?? 0);
$rent_price  = (float)($_POST['rent_price'] ?? 0);
$status      = $_POST['status'] ?? 'sell';
$description = trim($_POST['description'] ?? '');

if ($id <= 0 || empty($title)) {
    header("Location: ../views/admin/inventory.php?msg=Invalid+Data");
    exit();
}

// 2. Handle Image Upload (If provided)
$imageName = null;
$uploadDir = __DIR__ . "/../assets/images/uploaded/";

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $generatedName = "book_" . time() . "_" . rand(1000, 9999) . "." . $ext;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $generatedName)) {
        $imageName = $generatedName;
    }
}

// 3. Prepare Data
$data = [
    "title"       => $title,
    "author"      => $author,
    "category"    => $category,
    "sell_price"  => $sell_price,
    "rent_price"  => $rent_price,
    "stock_qty"   => $stock_qty,
    "status"      => $status,
    "description" => $description
];

// 4. Update Database
if (updateBook($id, $data, $imageName)) {
    header("Location: ../views/admin/inventory.php?msg=Book+Updated+Successfully");
} else {
    header("Location: ../views/admin/edit_book.php?id=$id&msg=Update+Failed");
}
exit();
?>