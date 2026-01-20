<?php
require_once('../../controller/auth/auth_guard.php');
protect_page('admin'); 
require_once('../../model/bookModel.php');

// 1. Fetch the book data from the database
$id = (int)($_GET['id'] ?? 0);
$book = getBookById($id); 

if (!$book) {
    header("Location: inventory.php?msg=Book+not+found");
    exit();
}

// 2. Setup the image path logic
$imagePath = "../../assets/images/uploaded/" . $book['image_filename'];
$displayImg = (!empty($book['image_filename']) && file_exists(__DIR__ . "/../../assets/images/uploaded/" . $book['image_filename'])) 
              ? $imagePath 
              : "../../assets/images/placeholder_book.png";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Book Record | BookVerse</title>
    <link rel="stylesheet" href="../../assets/css/edit_book_style.css">
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
            <h1>Edit Book Record</h1>
            <a href="inventory.php" class="btn-back">Cancel</a>
        </div>

        <form id="editForm" action="../../controller/updateBook.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

            <div class="edit-container">
                
                <div class="cover-preview-card">
                    <div class="image-box">
                        <img src="<?php echo $displayImg; ?>" id="bookPreview" class="main-preview">
                    </div>

                    <label for="coverInput" class="btn-upload-label">
                        <i class="fa fa-camera"></i> Update Cover Image
                    </label>

                    <input type="file" name="image" id="coverInput" style="display: none;" accept="image/*">
                    <small class="err-msg" id="errImage"></small>
                </div>

                <div class="form-details-card">
                    <h2>✨ Book Details</h2>
                    
                    <div class="input-grid">
                        <div class="input-block">
                            <label>Book Title</label>
                            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>">
                            <small class="err-msg" id="errTitle"></small>
                        </div>
                        <div class="input-block">
                            <label>Author Name</label>
                            <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($book['author']); ?>">
                            <small class="err-msg" id="errAuthor"></small>
                        </div>
                    </div>

                    <div class="input-grid">
                        <div class="input-block">
                            <label>Category</label>
                            <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($book['category']); ?>">
                            <small class="err-msg" id="errCategory"></small>
                        </div>
                        <div class="input-block">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock_qty" id="stock_qty" value="<?php echo $book['stock_qty']; ?>">
                            <small class="err-msg" id="errStock"></small>
                        </div>
                    </div>

                    <div class="input-grid">
                        <div class="input-block">
                            <label>Sell Price ($)</label>
                            <input type="number" step="0.01" name="sell_price" id="sell_price" value="<?php echo $book['sell_price']; ?>">
                            <small class="err-msg" id="errSell"></small>
                        </div>
                        <div class="input-block">
                            <label>Rent Price ($/mo)</label>
                            <input type="number" step="0.01" name="rent_price" id="rent_price" value="<?php echo $book['rent_price_per_month']; ?>">
                            <small class="err-msg" id="errRent"></small>
                        </div>
                    </div>

                    <div class="input-block">
                        <label>Availability Mode</label>
                        <select name="status" id="status">
                            <option value="sell" <?php if($book['status']=='sell') echo 'selected'; ?>>Sell Only</option>
                            <option value="rent" <?php if($book['status']=='rent') echo 'selected'; ?>>Rent Only</option>
                            <option value="both" <?php if($book['status']=='both') echo 'selected'; ?>>Both (Sell & Rent)</option>
                        </select>
                        <small class="err-msg" id="errStatus"></small>
                    </div>

                    <div class="input-block">
                        <label>Short Description</label>
                        <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
                        <small class="err-msg" id="errDesc"></small>
                    </div>

                    <button type="submit" class="btn-primary-action">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script src="../../assets/js/edit_book.js"></script>

</body>
</html>