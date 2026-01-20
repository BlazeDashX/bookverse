<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookVerse | Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Quicksand:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/register.css">
</head>
<body class="auth-page">

<div class="auth-overlay">
    <a href="../index.php" class="back-home">← Back to Home</a>

    <div class="auth-container">
        <div class="brand-logo">📖 BookVerse</div>
        <h2>Create Account</h2>
        <p>Start your reading journey today.</p>

        <form id="registerForm" novalidate>
            <div class="form-field">
                <label>Username</label>
                <input type="text" id="username" placeholder="Username">
                <small class="err" id="errUsername"></small>
            </div>

            <div class="form-field">
                <label>Email</label>
                <input type="text" id="email" placeholder="Email">
                <small class="err" id="errEmail"></small>
            </div>

            <div class="form-field">
                <label>Password</label>
                <input type="password" id="password" placeholder="Password">
                <small class="err" id="errPassword"></small>
            </div>

            <div class="form-field">
                <label>Confirm Password</label>
                <input type="password" id="confirmPassword" placeholder="Confirm Password">
                <small class="err" id="errConfirm"></small>
            </div>

            <button type="submit" class="submit-btn">Register</button>
        </form>

        <p id="resultMsg" style="margin-top:10px;"></p>

        <div class="switch-form">
            Already have an account? <a href="login.php">Login Here</a>
        </div>
    </div>
</div>

<script src="../../assets/js/register.js"></script>
</body>
</html>