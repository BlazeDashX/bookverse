<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/db.php';
require_once __DIR__ . '/auth/auth_guard.php';

// Restrict to User
protect_page('user');
$userId = $_SESSION['user_id'];

// AJAX: Search Shelf
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
    $content = file_get_contents("php://input");
    $decoded = json_decode($content, true);
    
    if (isset($decoded['search_term'])) {
        $term = "%" . $decoded['search_term'] . "%";
        $sql = "SELECT b.id, b.title, b.author, b.image_filename, p.payment_type, p.payment_date 
                FROM payments p 
                JOIN books b ON p.book_id = b.id 
                WHERE p.user_id = ? AND b.title LIKE ?
                ORDER BY p.payment_date DESC";     
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $userId, $term);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $books = [];
        while($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "data" => $books]);
        exit();
    }
}

// Action: Update Profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $email = trim($_POST['email']);
    $currentPass = $_POST['current_password'];
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';
    
    // Validate Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../views/user/profile.php?msg=Invalid+Email&type=error");
        exit();
    }
    
    // Verify Current Password
    $stmt = mysqli_prepare($conn, "SELECT password_hash FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $currentUser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$currentUser || !password_verify($currentPass, $currentUser['password_hash'])) {
        header("Location: ../views/user/profile.php?msg=Incorrect+Password&type=error");
        exit();
    }

    // Update Logic
    if (!empty($newPass)) {
        if ($newPass !== $confirmPass) {
            header("Location: ../views/user/profile.php?msg=Passwords+do+not+match&type=error");
            exit();
        }
        $newHash = password_hash($newPass, PASSWORD_DEFAULT);
        $updateStmt = mysqli_prepare($conn, "UPDATE users SET email = ?, password_hash = ? WHERE id = ?");
        mysqli_stmt_bind_param($updateStmt, "ssi", $email, $newHash, $userId);
    } else {
        $updateStmt = mysqli_prepare($conn, "UPDATE users SET email = ? WHERE id = ?");
        mysqli_stmt_bind_param($updateStmt, "si", $email, $userId);
    }

    if (mysqli_stmt_execute($updateStmt)) {
        header("Location: ../views/user/profile.php?msg=Profile+Updated&type=success");
    } else {
        header("Location: ../views/user/profile.php?msg=Error+Updating&type=error");
    }
    exit();
}

// Action: Delete Account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_account') {
    $currentPass = $_POST['confirm_delete_pass'];
    
    // Verify Password
    $stmt = mysqli_prepare($conn, "SELECT password_hash FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $currentUser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!password_verify($currentPass, $currentUser['password_hash'])) {
        header("Location: ../views/user/profile.php?msg=Incorrect+Password&type=error");
        exit();
    }

    // Delete User
    $delStmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($delStmt, "i", $userId);

    if (mysqli_stmt_execute($delStmt)) {
        session_unset();
        session_destroy();
        header("Location: ../views/auth/login.php?msg=Account+Deleted");
    }
    exit();
}

function getUserProfile($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT username, email, created_at FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

function getUserShelf($conn, $userId) {
    $sql = "SELECT b.id, b.title, b.author, b.image_filename, p.payment_type, p.payment_date 
            FROM payments p 
            JOIN books b ON p.book_id = b.id 
            WHERE p.user_id = ? 
            ORDER BY p.payment_date DESC";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
?>