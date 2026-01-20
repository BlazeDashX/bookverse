<?php
require_once('auth/auth_guard.php');
require_once('../model/bookModel.php');
protect_page('admin'); 

$title       = trim($_POST['title'] ?? '');
$author      = trim($_POST['author'] ?? '');
$category    = trim($_POST['category'] ?? '');
$stock_qty   = (int)$_POST['stock_qty'];
$sell_price  = (float)$_POST['sell_price'];
$rent_price  = (float)$_POST['rent_price'];
$description = trim($_POST['description'] ?? '');

// Initialize default image
$imageName = "default_cover.png";

// Handle Image Upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = "book_" . time() . "." . $ext;
    
    // Use an absolute path to ensure it finds the folder
    $targetPath = __DIR__ . "/../assets/images/uploaded/" . $imageName;
    
    // Verify directory exists before moving
    if (!is_dir(__DIR__ . "/../assets/images/uploaded/")) {
        mkdir(__DIR__ . "/../assets/images/uploaded/", 0777, true);
    }

    move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
}

// Prepare Data (Ensure 'status' is calculated based on prices)
$status = 'sell';
if ($sell_price > 0 && $rent_price > 0) $status = 'both';
elseif ($rent_price > 0) $status = 'rent';

$data = [
    "title" => $title, 
    "author" => $author, 
    "category" => $category,
    "sell_price" => $sell_price, 
    "rent_price" => $rent_price,
    "stock_qty" => $stock_qty, 
    "description" => $description,
    "image" => $imageName,
    "status" => $status // Added this to match your DB schema
];

if (addBook($data)) {
    header("Location: ../views/admin/inventory.php?msg=Book+Added");
} else {
    header("Location: ../views/admin/add_book.php?msg=Error");
}