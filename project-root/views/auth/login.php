<!DOCTYPE html>
<html>
<head>
    <title>BookVerse | Login</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body class="auth-page">
    <a href="../index.php" class="back-home">← Back to Home</a>
<div class="auth-container">
    <div class="logo">📖 <span>BookVerse</span></div>
    <h2>Sign In</h2>
    <form id="loginForm" novalidate>
        <input type="text" id="usernameOrEmail" placeholder="Username or Email">
        <small class="err" id="errUser"></small>

        <input type="password" id="password" placeholder="Password">
        <small class="err" id="errPass"></small>

        <button type="submit">Login</button>
    </form>
    <div class="bottom-link">New reader? <a href="register.php">Register Here</a></div>
</div>
<script src="../../assets/js/login.js"></script>
</body>
</html>