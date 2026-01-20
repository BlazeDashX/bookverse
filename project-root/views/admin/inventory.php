<?php
require_once('../../controller/auth/auth_guard.php');
protect_page('admin'); 
require_once('../../model/bookModel.php');

$allBooks = getAllBooks(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookVerse | Inventory Management</title>
    <link rel="stylesheet" href="../../assets/css/inventory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="admin-body">

    <div class="sidebar">
        <div class="sidebar-logo">📖 BookVerse</div>
        <nav>
            <a href="admin_dashboard.php"><i class="fa fa-chart-line"></i> Dashboard</a>
            <a href="inventory.php" class="active"><i class="fa fa-book"></i> Inventory</a>
            <a href="readers.php"><i class="fa fa-users"></i> Readers</a>
            <a href="../../controller/auth/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <main class="main-content">
        <div class="content-header">
            <h1>Inventory Management</h1>
            <a href="add_book.php" class="btn-add-main">+ New Arrival</a>
        </div>

        <div class="card-section">
            <h3 class="section-title">Complete Book List</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Book Details</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Prices (S/R)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($allBooks)): ?>
                    <?php 
                        $imgPath = "../../assets/images/uploaded/" . $row['image_filename'];
                        $displayImg = (!empty($row['image_filename']) && file_exists($imgPath)) ? $imgPath : "../../assets/images/placeholder_book.png";
                    ?>
                    <tr>
                        <td>
                            <div class="img-container">
                                <img src="<?= $displayImg ?>" alt="Cover">
                            </div>
                        </td>
                        <td>
                            <div class="book-info">
                                <span class="book-title"><?= htmlspecialchars($row['title']) ?></span>
                                <span class="book-author"><?= htmlspecialchars($row['author']) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>
                            <span class="badge <?= $row['stock_qty'] > 0 ? 'bg-green' : 'bg-red' ?>">
                                <?= $row['stock_qty'] > 0 ? $row['stock_qty'] : 'Out' ?>
                            </span>
                        </td>
                        <td>
                            <div class="price-stack">
                                <span><small>S:</small> $<?= number_format($row['sell_price'], 2) ?></span>
                                <span><small>R:</small> $<?= number_format($row['rent_price_per_month'], 2) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-pill <?= strtolower($row['status']) ?>">
                                <?= strtoupper($row['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit_book.php?id=<?= $row['id'] ?>" class="btn-edit" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button type="button" class="btn-delete" 
                                        onclick="confirmDelete(<?= $row['id'] ?>, '<?= addslashes($row['title']) ?>')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <div class="warning-circle"><i class="fa fa-exclamation-triangle"></i></div>
                <h3>Are you sure?</h3>
            </div>
            <p>Target: <span id="bookTitleDisplay"></span></p>
            <p class="modal-subtext">BookVerse Administrative Action</p>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn-danger-confirm">Yes, Delete</a>
            </div>
        </div>
    </div>

    <script src="../../assets/js/inventory.js"></script>
</body>
</html>