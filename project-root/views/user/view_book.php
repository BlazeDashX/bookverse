<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once('../../controller/auth/auth_guard.php');
require_once('../../controller/store_controller.php'); 

protect_page('user');

$bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$book = getBookDetail($conn, $bookId);
$ownership = checkBookOwnership($conn, $_SESSION['user_id'], $bookId);

if (!$book) {
    echo "Book not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Details</title>
    <link rel="stylesheet" href="../../assets/css/view_book_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="../../assets/js/store.js" defer></script> </head>
<body>

    <div class="sidebar">
        <div class="sidebar-logo"><i class="fas fa-book-open"></i> BookVerse</div>
        <nav>
            <a href="user_dashboard.php"><i class="fas fa-bookmark"></i> My Shelf</a>
            <a href="store.php"><i class="fas fa-shopping-bag"></i> Browse Store</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="../../controller/auth/logout.php" style="margin-top: 50px; color: #e74a3b;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        
        <a href="store.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Store</a>

        <div class="book-details-container">
            <div class="cover-wrapper">
                <img src="../../assets/images/uploaded/<?php echo htmlspecialchars($book['image_filename']); ?>" 
                     alt="Cover" onerror="this.src='../../assets/images/default.png'">
            </div>

            <div class="info-wrapper">
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>
                <div class="author">by <?php echo htmlspecialchars($book['author']); ?></div>

                <div class="badges">
                    <span class="badge category"><?php echo htmlspecialchars($book['category']); ?></span>
                    <?php if($book['stock_qty'] > 0): ?>
                        <span class="badge stock">In Stock (<?php echo $book['stock_qty']; ?>)</span>
                    <?php else: ?>
                        <span class="badge out">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="description-box">
                    <h3>Synopsys</h3>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>

                <div class="action-area">
                    
                    <?php if ($ownership): ?>
                        <div class="price-display">
                            <span class="price-main" style="color: #2ecc71;">Owned</span>
                            <span class="price-sub">Access Type: <?php echo strtoupper($ownership); ?></span>
                        </div>
                        <div style="margin-left: auto;">
                            <a href="#" class="btn-action btn-read" onclick="alert('Opening Book Reader Interface...')">
                                <i class="fas fa-book-open"></i> Read Now
                            </a>
                        </div>

                    <?php else: ?>
                        <div class="price-display">
                            <span class="price-main">$<?php echo number_format($book['sell_price'], 2); ?></span>
                            <span class="price-sub">Purchase Price</span>
                        </div>

                        <div style="margin-left: auto; display: flex; gap: 15px;">
                            <?php if($book['stock_qty'] > 0): ?>
                                
                                <?php if($book['status'] == 'rent' || $book['status'] == 'both'): ?>
                                    <button class="btn-action btn-rent" 
                                        onclick="openCheckout(<?php echo $book['id']; ?>, '<?php echo addslashes($book['title']); ?>', <?php echo $book['rent_price_per_month']; ?>, 'rent')">
                                        Rent ($<?php echo $book['rent_price_per_month']; ?>)
                                    </button>
                                <?php endif; ?>

                                <?php if($book['status'] == 'sell' || $book['status'] == 'both'): ?>
                                    <button class="btn-action btn-buy" 
                                        onclick="openCheckout(<?php echo $book['id']; ?>, '<?php echo addslashes($book['title']); ?>', <?php echo $book['sell_price']; ?>, 'buy')">
                                        Buy Now
                                    </button>
                                <?php endif; ?>

                            <?php else: ?>
                                <button class="btn-action" style="background: #ccc; cursor: not-allowed;" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="checkoutModal">
        <div class="checkout-card">
            <div class="checkout-header">
                <h3>Checkout</h3>
                <span id="modalBookTitle" style="font-size: 0.9rem; color: #666;">Book Title</span>
                <span class="total-amount" id="modalPrice">$0.00</span>
            </div>
            <form id="checkoutForm">
                <input type="hidden" name="action" value="purchase_book">
                <input type="hidden" name="book_id" id="inputBookId">
                <input type="hidden" name="payment_type" id="inputType">
                <input type="text" name="card_holder" class="checkout-input" placeholder="Name on Card">
                <input type="text" name="card_number" class="checkout-input" placeholder="1234 5678 1234 5678" maxlength="19">
                <div class="checkout-row">
                    <input type="text" name="expiry_date" class="checkout-input" placeholder="MM/YY" maxlength="5">
                    <input type="password" name="cvc" class="checkout-input" placeholder="123" maxlength="4">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeCheckout()">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="successModal">
        <div class="success-card">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h2 style="color: #2d3436;">Success!</h2>
            <p style="color: #636e72;">Book added to your shelf.</p>
            <button onclick="window.location.reload()" class="btn-confirm" style="width: 100%;">Read Now</button>
        </div>
    </div>

</body>
</html>