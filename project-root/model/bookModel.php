<?php
require_once(__DIR__ . '/db.php');

function getAdminStats(): array {
    global $conn;
    $stats = [
        "total_books" => 0,
        "total_users" => 0,
        "monthly_revenue" => 0.0,
        "monthly_rent" => 0.0
    ];

    // 1. Books Available (Total titles)
    $resBooks = mysqli_query($conn, "SELECT COUNT(*) as total FROM books");
    $stats["total_books"] = mysqli_fetch_assoc($resBooks)['total'] ?? 0;

    // 2. Registered Users Number
    $resUsers = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $stats["total_users"] = mysqli_fetch_assoc($resUsers)['total'] ?? 0;

    // 3. Monthly Sales Revenue (payment_type = 'buy')
    $resSales = mysqli_query($conn, "SELECT SUM(amount) as rev FROM payments WHERE payment_type = 'buy' AND MONTH(payment_date) = MONTH(CURRENT_DATE())");
    $stats["monthly_revenue"] = (float)(mysqli_fetch_assoc($resSales)['rev'] ?? 0);

    // 4. Monthly Rent Revenue (payment_type = 'rent')
    $resRent = mysqli_query($conn, "SELECT SUM(amount) as rev FROM payments WHERE payment_type = 'rent' AND MONTH(payment_date) = MONTH(CURRENT_DATE())");
    $stats["monthly_rent"] = (float)(mysqli_fetch_assoc($resRent)['rev'] ?? 0);

    return $stats;
}

function getAllBooks() {
    global $conn;
    return mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
}

function getBookById(int $id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function addBook($data) {
    global $conn;
    // Removed is_active from the column list and the VALUES list
    $sql = "INSERT INTO books (title, author, category, sell_price, rent_price_per_month, stock_qty, description, image_filename, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    // Updated to 9 parameters (sssddisss) instead of 10
    mysqli_stmt_bind_param($stmt, "sssddisss", 
        $data['title'], 
        $data['author'], 
        $data['category'], 
        $data['sell_price'], 
        $data['rent_price'], 
        $data['stock_qty'], 
        $data['description'], 
        $data['image'],
        $data['status']
    );
    
    return mysqli_stmt_execute($stmt);
}

function updateBook(int $id, array $data, ?string $imageName = null): bool {
    global $conn;
    
    if ($imageName) {
        // Update with new image
        $sql = "UPDATE books SET title=?, author=?, category=?, sell_price=?, 
                rent_price_per_month=?, stock_qty=?, status=?, description=?, image_filename=? 
                WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssddisssi", 
            $data['title'], $data['author'], $data['category'], $data['sell_price'], 
            $data['rent_price'], $data['stock_qty'], $data['status'], 
            $data['description'], $imageName, $id);
    } else {
        // Update without changing the image
        $sql = "UPDATE books SET title=?, author=?, category=?, sell_price=?, 
                rent_price_per_month=?, stock_qty=?, status=?, description=? 
                WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssddissi", 
            $data['title'], $data['author'], $data['category'], $data['sell_price'], 
            $data['rent_price'], $data['stock_qty'], $data['status'], 
            $data['description'], $id);
    }
    
    return mysqli_stmt_execute($stmt);
}

function deleteBook($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM books WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>