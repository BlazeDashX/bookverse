<?php
// views/user/read_book.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once('../../controller/auth/auth_guard.php');
require_once('../../controller/store_controller.php'); 

protect_page('user');

$bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Fetch Book
$book = getBookDetail($conn, $bookId);

// 2. SECURITY: Verify Ownership
$ownership = checkBookOwnership($conn, $_SESSION['user_id'], $bookId);

if (!$book || !$ownership) {
    // If book doesn't exist OR user hasn't bought/rented it
    header("Location: store.php?msg=Access+Denied");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading: <?php echo htmlspecialchars($book['title']); ?></title>
    
    <link rel="stylesheet" href="../../assets/css/read_book_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="reader-nav">
        <div class="nav-left">
            <a href="user_dashboard.php">
                <i class="fas fa-arrow-left"></i> Back to Shelf
            </a>
        </div>
        <div class="book-meta">
            <div class="meta-title"><?php echo htmlspecialchars($book['title']); ?></div>
            <div class="meta-author"><?php echo htmlspecialchars($book['author']); ?></div>
        </div>
        <div class="nav-right">
            <i class="fas fa-cog" style="color: #ccc; cursor: pointer;"></i>
        </div>
    </nav>

    <div class="reader-canvas">
        
        <h2 class="chapter-title">Chapter One: The Beginning</h2>

        <img src="../../assets/images/uploaded/<?php echo htmlspecialchars($book['image_filename']); ?>" 
             class="book-cover-display" alt="Cover">

        <p>
            <span class="drop-cap"><?php echo substr($book['description'], 0, 1); ?></span>
            <?php echo substr(htmlspecialchars($book['description']), 1); ?>
        </p>

        <p>
            The library was quiet, a stillness that felt almost heavy in the afternoon light. Dust motes danced in the shafts of sun that pierced the high windows, illuminating rows upon rows of leather-bound spines. It was here that the journey truly began, not with a shout, but with a whisper of turning pages.
        </p>
        <p>
            She traced her fingers along the wooden shelf, feeling the texture of history beneath her fingertips. Every book held a world, a trapped universe waiting for a mind to unlock it. The smell of old paper and ink was intoxicating, a perfume that only those who loved stories could truly appreciate.
        </p>
        <p>
            "Knowledge," the old librarian had said, "is not just about knowing facts. It is about understanding the spaces between them." The words echoed in the silence. It was a lesson hard-learned, paid for in sleepless nights and endless searching. But today, the search might finally be over.
        </p>
        <p>
            Outside, the city bustled with its usual chaotic rhythm—horns honking, people shouting, the relentless march of commerce. But inside these walls, time seemed to slow, to bend around the gravity of so many words gathered in one place. It was a sanctuary. A fortress of solitude for the mind.
        </p>
        <p>
            She pulled the volume from the shelf. It was heavier than it looked. The cover was worn, the title faded to near illegibility. Yet, as she opened it, the text was crisp, sharp as the day it was printed. This was the book. The key to everything that had happened, and everything that was yet to come.
        </p>

        <div class="reader-controls">
            <button class="btn-nav" disabled>Previous</button>
            <span style="align-self:center; font-size: 0.9rem; color:#888;">Page 1 of 240</span>
            <button class="btn-nav" onclick="alert('This is a demo version. Full content coming soon!')">Next Chapter</button>
        </div>

    </div>

</body>
</html>