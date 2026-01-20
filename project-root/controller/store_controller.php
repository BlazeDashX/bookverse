<?php
// controller/store_controller.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/db.php';
require_once __DIR__ . '/auth/auth_guard.php';

// Ensure user is logged in
protect_page('user');
$userId = $_SESSION['user_id'];

// FETCH AVAILABLE BOOKS
function getAvailableBooks($conn) {
    $sql = "SELECT * FROM books WHERE stock_qty > 0 ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// GET SINGLE BOOK DETAILS
function getBookDetail($conn, $bookId) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

//CHECK OWNERSHIP STATUS
function checkBookOwnership($conn, $userId, $bookId) {
    $stmt = mysqli_prepare($conn, "SELECT payment_type FROM payments WHERE user_id = ? AND book_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $userId, $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['payment_type']; // Returns 'buy' or 'rent'
    }
    return false;
}

// PROCESS PURCHASE / RENT (
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'purchase_book') {
    
    // 1. Sanitize Inputs
    $bookId   = (int)$_POST['book_id'];
    $type     = $_POST['payment_type']; 
    $cardName = trim($_POST['card_holder']);
    $cardNum  = trim($_POST['card_number']);
    $expiry   = trim($_POST['expiry_date']);
    $cvc      = trim($_POST['cvc']);

    // 2. Validation
    if ($bookId <= 0 || empty($cardName) || strlen($cardNum) < 16 || empty($expiry) || strlen($cvc) < 3) {
        echo json_encode(["status" => "error", "message" => "Invalid Payment Details."]);
        exit();
    }

    // 3. Duplicate Purchase Check
    $checkStmt = mysqli_prepare($conn, "SELECT payment_type FROM payments WHERE user_id = ? AND book_id = ?");
    mysqli_stmt_bind_param($checkStmt, "ii", $userId, $bookId);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        mysqli_stmt_bind_result($checkStmt, $existingType);
        mysqli_stmt_fetch($checkStmt);
        $msg = ($existingType === 'buy') ? "You have already purchased this book." : "You are already renting this book.";
        echo json_encode(["status" => "error", "message" => $msg]);
        exit();
    }
    mysqli_stmt_close($checkStmt);

    // 4. Fetch Price & Check Stock
    $stmt = mysqli_prepare($conn, "SELECT sell_price, rent_price, stock_qty FROM books WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$book || $book['stock_qty'] < 1) {
        echo json_encode(["status" => "error", "message" => "Book Out of Stock"]);
        exit();
    }

    $amount = ($type === 'buy') ? $book['sell_price'] : $book['rent_price'];
    $last4  = substr($cardNum, -4);

    // 5. Execute Transaction
    mysqli_begin_transaction($conn);

    try {
        // A. Insert Payment Record
        $paySql = "INSERT INTO payments (user_id, book_id, payment_type, amount, card_holder_name, card_last4, payment_status, payment_date) 
                   VALUES (?, ?, ?, ?, ?, ?, 'success', NOW())";
        $payStmt = mysqli_prepare($conn, $paySql);
        mysqli_stmt_bind_param($payStmt, "iisdss", $userId, $bookId, $type, $amount, $cardName, $last4);
        mysqli_stmt_execute($payStmt);

        // B. Decrement Stock
        $stockSql = "UPDATE books SET stock_qty = stock_qty - 1 WHERE id = ?";
        $stockStmt = mysqli_prepare($conn, $stockSql);
        mysqli_stmt_bind_param($stockStmt, "i", $bookId);
        mysqli_stmt_execute($stockStmt);

        // C. Commit
        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Payment Successful"]);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(["status" => "error", "message" => "Transaction Failed."]);
    }
    exit();
}
?>