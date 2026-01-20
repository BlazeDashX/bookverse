<?php
// Relative path to reach the auth guard and model
require_once('auth/auth_guard.php');
require_once('../model/bookModel.php');

// Security check to ensure only admins can update data
protect_page('admin'); 

// 1. Capture and Sanitize Inputs
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

// 2. Handle New Image Upload (Optional)
$imageName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName    = $_FILES['image']['name'];
    $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Generate unique name to avoid overwriting
    $imageName = time() . "_" . rand(1000, 9999) . "." . $fileExt;
    $uploadDir = "../assets/images/uploaded/";

    // Create directory if missing
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    move_uploaded_file($fileTmpPath, $uploadDir . $imageName);
}

// 3. Prepare Data for Model
$data = [
    "title" => $title,
    "author" => $author,
    "category" => $category,
    "sell_price" => $sell_price,
    "rent_price" => $rent_price,
    "stock_qty" => $stock_qty,
    "status" => $status,
    "description" => $description
];

// 4. Execute Update in Database
if (updateBook($id, $data, $imageName)) {
    header("Location: ../views/admin/inventory.php?msg=Book+Updated+Successfully");
} else {
    header("Location: ../views/admin/edit_book.php?id=$id&msg=Update+Failed");
}
exit();