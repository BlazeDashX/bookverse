<?php
require_once('../../controller/auth/auth_guard.php');
protect_page('admin'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Arrival | BookVerse</title>
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
            <h1>New Library Entry</h1>
            <a href="inventory.php" class="btn-back">Cancel</a>
        </div>

        <div class="edit-container">
            <div class="cover-preview-card">
                <div class="image-box">
                    <img src="../../assets/images/placeholder_book.png" id="bookPreview" class="main-preview">
                </div>
                <label for="coverInput" class="btn-upload-label">
                    <i class="fa fa-camera"></i> Select Cover Image
                </label>
                <input type="file" name="image" id="coverInput" form="addForm" style="display: none;" accept="image/*">
                <small class="err-msg" id="errImage"></small>
            </div>

            <div class="form-details-card">
                <h2>✨ Add New Book</h2>
                <form id="addForm" action="../../controller/addBookController.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="input-grid">
                        <div class="input-block">
                            <label>Book Title</label>
                            <input type="text" name="title" id="title" placeholder="e.g. Pride and Prejudice">
                            <small class="err-msg" id="errTitle"></small>
                        </div>
                        <div class="input-block">
                            <label>Author</label>
                            <input type="text" name="author" id="author" placeholder="e.g. Jane Austen">
                            <small class="err-msg" id="errAuthor"></small>
                        </div>
                    </div>

                    <div class="input-grid">
                        <div class="input-block">
                            <label>Category</label>
                            <input type="text" name="category" id="category" placeholder="e.g. Romance">
                            <small class="err-msg" id="errCategory"></small>
                        </div>
                        <div class="input-block">
                            <label>Initial Stock</label>
                            <input type="number" name="stock_qty" id="stock_qty" value="1">
                            <small class="err-msg" id="errStock"></small>
                        </div>
                    </div>

                    <div class="input-grid">
                        <div class="input-block">
                            <label>Sell Price ($)</label>
                            <input type="number" step="0.01" name="sell_price" id="sell_price" value="0.00">
                            <small class="err-msg" id="errSell"></small>
                        </div>
                        <div class="input-block">
                            <label>Rent Price ($/mo)</label>
                            <input type="number" step="0.01" name="rent_price" id="rent_price" value="0.00">
                            <small class="err-msg" id="errRent"></small>
                        </div>
                    </div>

                    <div class="input-block">
                        <label>Description</label>
                        <textarea name="description" id="description" rows="4" placeholder="Write a short summary..."></textarea>
                        <small class="err-msg" id="errDesc"></small>
                    </div>

                    <button type="submit" class="btn-primary-action">
                        <i class="fa fa-plus-circle"></i> Add to Library
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="../../assets/js/add_book.js"></script>
</body>
</html>