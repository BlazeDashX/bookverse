<?php
require_once __DIR__ . '/db.php';

/**
 * Fetch All Readers (Non-Admin Users)
 */
function getAllReaders() {
    global $conn;
    $sql = "SELECT id, username, email, created_at 
            FROM users 
            WHERE role = 'user' 
            ORDER BY created_at DESC";
    return mysqli_query($conn, $sql);
}

/**
 * Delete User (Safety: Only deletes 'user' role)
 */
function deleteUser(int $id): bool {
    global $conn;
    
    // Safety check: Ensure we never accidentally delete an 'admin'
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'user'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    return mysqli_stmt_execute($stmt);
}
?>