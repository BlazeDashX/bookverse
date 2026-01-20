<?php
require_once('auth/auth_guard.php');
require_once('../model/bookModel.php');

// Ensure only admin can access
protect_page('admin');

// Collect Inputs
$title       = trim($_POST['title'] ?? '');
$author      = trim($_POST['author'] ?? '');
$category    = trim($_POST['category'] ?? '');
$stock_qty   = (int)($_POST['stock_qty'] ?? 0);
$sell_price  = (float)($_POST['sell_price'] ?? 0);
$rent_price  = (float)($_POST['rent_price'] ?? 0);
$description = trim($_POST['description'] ?? '');

// Image Upload Logic
$imageName = "default_cover.png";
$targetDir = __DIR__ . "/../assets/images/uploaded/";

if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    // Ensure directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $generatedName = "book_" . time() . "." . $ext;
    $targetPath = $targetDir . $generatedName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imageName = $generatedName;
    }
}

// Determine Status
$status = 'sell';
if ($sell_price > 0 && $rent_price > 0) $status = 'both';
elseif ($rent_price > 0) $status = 'rent';

// Prepare Data Array
$data = [
    "title"       => $title,
    "author"      => $author,
    "category"    => $category,
    "sell_price"  => $sell_price,
    "rent_price"  => $rent_price,
    "stock_qty"   => $stock_qty,
    "description" => $description,
    "image"       => $imageName,
    "status"      => $status
];

// Execute and Redirect
if (addBook($data)) {
    header("Location: ../views/admin/inventory.php?msg=Book+Added");
} else {
    header("Location: ../views/admin/add_book.php?msg=Error");
}
exit();
?>