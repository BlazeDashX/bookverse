<?php
require('../../model/db.php'); 
header('Content-Type: application/json');

/* Initialize basic response */
$response = ["status" => "error", "message" => "Invalid request"];

/* Validate incoming AJAX data */
if (!isset($_POST['data'])) {
    echo json_encode($response);
    exit();
}

$data = json_decode($_POST['data'], true);

$username = trim($data['username'] ?? '');
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirm  = $data['confirmPassword'] ?? '';

/* Strict Server-Side Validation (Manual Checks) */
$errors = [];

if ($username === '') {
    $errors['username'] = "Username is required.";
} else if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    $errors['username'] = "Username must be 3-20 characters (letters/numbers/underscore).";
}

if ($email === '') {
    $errors['email'] = "Email is required.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
}

if ($password === '') {
    $errors['password'] = "Password is required.";
} else if (strlen($password) < 6) {
    $errors['password'] = "Min 6 characters required for security.";
}

if ($confirm === '') {
    $errors['confirmPassword'] = "Please confirm your password.";
} else if ($password !== '' && $password !== $confirm) {
    $errors['confirmPassword'] = "Passwords do not match.";
}

/* Return field errors to JS if validation fails */
if (count($errors) > 0) {
    echo json_encode([
        "status" => "field_error",
        "errors" => $errors
    ]);
    exit();
}

/* Check for existing Username or Email */
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo json_encode([
        "status" => "error", 
        "message" => "Username or Email is already registered."
    ]);
    mysqli_stmt_close($stmt);
    exit();
}
mysqli_stmt_close($stmt);

/* Insert New User Record */
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')"
);
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        "status" => "success",
        "message" => "Registration successful!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "System error. Please try again later."
    ]);
}

mysqli_stmt_close($stmt);
?>