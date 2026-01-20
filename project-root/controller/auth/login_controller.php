<?php
require('../../model/db.php');
header('Content-Type: application/json');

// Start session if not active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate request
if (!isset($_POST['data'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

$data = json_decode($_POST['data'], true);
$usernameOrEmail = trim($data['usernameOrEmail'] ?? '');
$password = $data['password'] ?? '';

// Check empty fields
$errors = [];
if (empty($usernameOrEmail)) $errors['usernameOrEmail'] = "Username/Email is required.";
if (empty($password)) $errors['password'] = "Password is required.";

if (!empty($errors)) {
    echo json_encode(["status" => "field_error", "errors" => $errors]);
    exit();
}

// Database lookup
$stmt = mysqli_prepare($conn, "SELECT id, username, password_hash, role FROM users WHERE username = ? OR email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $usernameOrEmail, $usernameOrEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Verify credentials
if (!$user || !password_verify($password, $user['password_hash'])) {
    echo json_encode(["status" => "error", "message" => "Invalid credentials!"]);
    exit();
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

echo json_encode(["status" => "success", "role" => $user['role']]);
?>