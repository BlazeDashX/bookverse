<?php
require_once('db.php'); 

/**
 * Fetch all users with the role 'user' (Readers)
 */
function getAllReaders() {
    global $conn;
    // Column 'username' used to match your DB schema
    $sql = "SELECT id, username, email, created_at 
            FROM users 
            WHERE role = 'user' 
            ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    return $result;
}

/**
 * Delete a user by ID
 */
function deleteUser($id) {
    global $conn;
    $id = (int)$id;
    
    // Ensure we only delete 'user' roles, not 'admin'
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'user'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    return mysqli_stmt_execute($stmt);
}
?>