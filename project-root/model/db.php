<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "bookverse";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to handle special characters/emojis correctly
mysqli_set_charset($conn, "utf8mb4");
?>