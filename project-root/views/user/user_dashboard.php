<?php
// views/user/user_dashboard.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once('../../controller/auth/auth_guard.php');

// This line includes the controller containing the function you need
require_once('../../controller/user_controller.php'); 

protect_page('user');

// Now this function call will work
$myBooks = getUserShelf($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shelf - BookVerse</title>

    <link rel="stylesheet" href="../../assets/css/user_dashboard_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="../../assets/js/user_dashboard.js" defer></script>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-book-open"></i> BookVerse
        </div>
        <nav>
            <a href="#" class="active"><i class="fas fa-bookmark"></i> My Shelf</a>
            <a href="store.php"><i class="fas fa-shopping-bag"></i> Browse Store</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="../../controller/auth/logout.php" style="margin-top: 50px; color: #e74a3b;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="content-header">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        </div>

        <div class="search-container">
            <i class="fas fa-search" style="color: #d81b60; font-size: 1.2rem;"></i>
            <input type="text" id="shelfSearch" class="search-input" placeholder="Search your purchased books..."
                onkeyup="searchBooks()">
        </div>

        <div class="card-section">
            <h3 style="color: #592d3e; margin-bottom: 20px;">My Library Collection</h3>

            <table class="inventory-table">
                <thead>
                    <tr>
                        <th width="10%">Cover</th>
                        <th width="30%">Book Details</th>
                        <th width="15%">Access Type</th>
                        <th width="20%">Acquired Date</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody id="bookList">
                    <?php if (mysqli_num_rows($myBooks) > 0): ?>
                    <?php while ($book = mysqli_fetch_assoc($myBooks)): ?>
                    <tr>
                        <td>
                            <div class="img-container">
                                <img src="../../assets/images/uploaded/<?php echo htmlspecialchars($book['image_filename']); ?>"
                                    alt="Cover" onerror="this.src='../../assets/images/default.png'">
                            </div>
                        </td>
                        <td>
                            <div class="book-info">
                                <span class="book-title"><?php echo htmlspecialchars($book['title']); ?></span>
                                <span class="book-author">by <?php echo htmlspecialchars($book['author']); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-pill <?php echo $book['payment_type'] == 'buy' ? 'sell' : 'rent'; ?>">
                                <?php echo strtoupper($book['payment_type']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo date("M d, Y", strtotime($book['payment_date'])); ?>
                        </td>
                        <td>
                            <a href="read_book.php?id=<?php echo $book['id']; ?>" class="btn-add-main"
                                style="padding: 8px 20px; font-size: 0.85rem; box-shadow: none;">
                                <i class="fas fa-book-reader"></i> Read
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-book" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                            You haven't purchased or rented any books yet.<br>
                            <a href="store.php" style="color: #ff4d6d; text-decoration: none; font-weight: 700;">Go to
                                Store</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>