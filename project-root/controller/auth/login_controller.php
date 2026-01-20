<?php
require('../../model/db.php');
header('Content-Type: application/json');
session_start();

if(!isset($_POST['data'])){
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

$data = json_decode($_POST['data'], true);
$usernameOrEmail = trim($data['usernameOrEmail'] ?? '');
$password = $data['password'] ?? '';

$errors = [];
if($usernameOrEmail === '') $errors['usernameOrEmail'] = "Username/Email is required.";
if($password === '') $errors['password'] = "Password is required.";

if(count($errors) > 0){
    echo json_encode(["status" => "field_error", "errors" => $errors]);
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT id, username, password_hash, role FROM users WHERE username = ? OR email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $usernameOrEmail, $usernameOrEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if(!$user || !password_verify($password, $user['password_hash'])){
    echo json_encode(["status" => "error", "message" => "Invalid credentials!"]);
    exit();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

echo json_encode(["status" => "success", "role" => $user['role']]);
?>