<?php
require('../../model/db.php');
header('Content-Type: application/json');

// Validate request
if (!isset($_POST['data'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

$data = json_decode($_POST['data'], true);
$username = trim($data['username'] ?? '');
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirm  = $data['confirmPassword'] ?? '';

// Validation
$errors = [];

if (empty($username)) {
    $errors['username'] = "Username is required.";
} elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    $errors['username'] = "Username must be 3-20 chars (letters/numbers).";
}

if (empty($email)) {
    $errors['email'] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
}

if (empty($password)) {
    $errors['password'] = "Password is required.";
} elseif (strlen($password) < 6) {
    $errors['password'] = "Min 6 characters required.";
}

if (empty($confirm)) {
    $errors['confirmPassword'] = "Confirm password required.";
} elseif ($password !== $confirm) {
    $errors['confirmPassword'] = "Passwords do not match.";
}

if (!empty($errors)) {
    echo json_encode(["status" => "field_error", "errors" => $errors]);
    exit();
}

// Check duplicates
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo json_encode(["status" => "error", "message" => "Username or Email already registered."]);
    mysqli_stmt_close($stmt);
    exit();
}
mysqli_stmt_close($stmt);

// Create user
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Registration successful!"]);
} else {
    echo json_encode(["status" => "error", "message" => "System error. Try again."]);
}

mysqli_stmt_close($stmt);
?>