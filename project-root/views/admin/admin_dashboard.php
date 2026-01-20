<?php
require_once('../../controller/auth/auth_guard.php');
protect_page('admin'); 
require_once('../../model/bookModel.php');

$stats = getAdminStats();
$allBooks = getAllBooks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookVerse | Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">

<div class="sidebar">
    <div class="sidebar-logo">📖 BookVerse</div>
    <nav>
        <a href="admin_dashboard.php" class="active"><i class="fa fa-chart-line"></i> Dashboard</a>
        <a href="inventory.php"><i class="fa fa-book"></i> Inventory</a>
        <a href="readers.php"><i class="fa fa-users"></i> Readers</a>
        <a href="../../controller/auth/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

<main class="main-content">
    <div class="content-header">
        <h1>Welcome, Admin</h1>
        <a href="add_book.php" class="btn-add-main">+ Add New Book</a>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card solid-blue">
            <div class="kpi-label">Books Available</div>
            <div class="kpi-value"><?php echo $stats['total_books']; ?></div>
        </div>
        <div class="kpi-card solid-purple">
            <div class="kpi-label">Registered Readers</div>
            <div class="kpi-value"><?php echo $stats['total_users']; ?></div>
        </div>
        <div class="kpi-card solid-orange">
            <div class="kpi-label">Monthly Sales</div>
            <div class="kpi-value">$<?php echo number_format($stats['monthly_revenue'], 2); ?></div>
        </div>
        <div class="kpi-card solid-teal">
            <div class="kpi-label">Monthly Rent</div>
            <div class="kpi-value">$<?php echo number_format($stats['monthly_rent'], 2); ?></div>
        </div>
    </div>

    <div class="card-section">
        <h3>📚 Available Book List</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $availableBooks = getAllBooks();
                if(mysqli_num_rows($availableBooks) > 0): 
                    while($row = mysqli_fetch_assoc($availableBooks)): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo $row['stock_qty']; ?></td>
                        <td>$<?php echo number_format($row['sell_price'], 2); ?></td>
                        <td><a href="edit_book.php?id=<?php echo $row['id']; ?>" class="action-link">Edit</a></td>
                    </tr>
                <?php endwhile; 
                else: ?>
                    <tr><td colspan="5" class="empty-msg">No books are currently available in stock.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>