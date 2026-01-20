<?php
// views/user/store.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once('../../controller/auth/auth_guard.php');
require_once('../../controller/store_controller.php'); 

protect_page('user');

// Fetch available books via PHP Controller Function
$books = getAvailableBooks($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store - BookVerse</title>

    <link rel="stylesheet" href="../../assets/css/store_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="../../assets/js/store.js" defer></script>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo"><i class="fas fa-book-open"></i> BookVerse</div>
        <nav>
            <a href="user_dashboard.php"><i class="fas fa-bookmark"></i> My Shelf</a>
            <a href="#" class="active"><i class="fas fa-shopping-bag"></i> Browse Store</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="../../controller/auth/logout.php" style="margin-top: 50px; color: #e74a3b;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="content-header">
            <h1>Browse Collection</h1>
        </div>

        <?php if(isset($_GET['msg'])): ?>
        <div class="msg-box <?php echo ($_GET['type'] == 'error') ? 'msg-error' : 'msg-success'; ?>">
            <?php echo htmlspecialchars(str_replace('+', ' ', $_GET['msg'])); ?>
        </div>
        <?php endif; ?>

        <div class="book-grid">
            <?php if (mysqli_num_rows($books) > 0): ?>
            <?php while ($book = mysqli_fetch_assoc($books)): ?>
            <div class="book-card">
                <div class="card-img">
                    <a href="view_book.php?id=<?php echo $book['id']; ?>" class="card-img">
                        <img src="../../assets/images/uploaded/<?php echo htmlspecialchars($book['image_filename']); ?>"
                            alt="Cover" onerror="this.src='../../assets/images/default.png'">

                        <div
                            style="position: absolute; bottom: 0; left: 0; width: 100%; background: rgba(0,0,0,0.5); color: white; text-align: center; padding: 5px; opacity: 0; transition: 0.3s;">
                            View Details
                        </div>
                    </a>
                    <style>
                    .card-img:hover div {
                        opacity: 1;
                    }
                    </style>
                </div>
                <div class="card-body">
                    <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                    <div class="book-author">by <?php echo htmlspecialchars($book['author']); ?></div>

                    <div style="margin-top: auto;">
                        <div class="price-tag">
                            Buy: $<?php echo number_format($book['sell_price'], 2); ?>
                        </div>
                        <?php if($book['status'] == 'both' || $book['status'] == 'rent'): ?>
                        <div class="price-tag" style="background: #f3e5f5; color: #7b1fa2; margin-left: 5px;">
                            Rent: $<?php echo number_format($book['rent_price_per_month'], 2); ?>
                        </div>
                        <?php endif; ?>

                        <div class="card-actions">
                            <?php if($book['status'] == 'sell' || $book['status'] == 'both'): ?>
                            <button class="btn-buy"
                                onclick="openCheckout(<?php echo $book['id']; ?>, '<?php echo addslashes($book['title']); ?>', <?php echo $book['sell_price']; ?>, 'buy')">
                                Buy Now
                            </button>
                            <?php endif; ?>

                            <?php if($book['status'] == 'rent' || $book['status'] == 'both'): ?>
                            <button class="btn-rent"
                                onclick="openCheckout(<?php echo $book['id']; ?>, '<?php echo addslashes($book['title']); ?>', <?php echo $book['rent_price_per_month']; ?>, 'rent')">
                                Rent
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p style="color: #888; text-align: center; width: 100%;">No books available in the store right now.</p>
            <?php endif; ?>
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

                <div class="input-group">
                    <label>Card Holder Name</label>
                    <input type="text" name="card_holder" class="checkout-input" placeholder="Name on Card">
                </div>

                <div class="input-group">
                    <label>Card Number</label>
                    <input type="text" name="card_number" class="checkout-input" placeholder="1234 5678 1234 5678"
                        maxlength="19">
                </div>

                <div class="checkout-row">
                    <div class="input-group">
                        <label>Expiry Date</label>
                        <input type="text" name="expiry_date" class="checkout-input" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="input-group">
                        <label>CVC</label>
                        <input type="password" name="cvc" class="checkout-input" placeholder="123" maxlength="4">
                    </div>
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
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2 style="color: #2d3436; margin: 10px 0;">Payment Successful!</h2>
            <p style="color: #636e72; margin-bottom: 25px;">The book has been added to your shelf.</p>

            <button onclick="redirectToShelf()" class="btn-confirm"
                style="width: 100%; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);">
                Go to My Shelf
            </button>
        </div>
    </div>

</body>

</html>