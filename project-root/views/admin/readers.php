<?php
require_once('../../controller/auth/auth_guard.php');
protect_page('admin'); 
require_once('../../model/userModel.php');

$allReaders = getAllReaders(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookVerse | Readers Management</title>
    <link rel="stylesheet" href="../../assets/css/inventory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">

    <div class="sidebar">
        <div class="sidebar-logo">📖 BookVerse</div>
        <nav>
            <a href="admin_dashboard.php"><i class="fa fa-chart-line"></i> Dashboard</a>
            <a href="inventory.php"><i class="fa fa-book"></i> Inventory</a>
            <a href="readers.php" class="active"><i class="fa fa-users"></i> Readers</a>
            <a href="../../controller/auth/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <main class="main-content">
        <div class="content-header">
            <h1>Readers Management</h1>
        </div>

        <div class="card-section">
            <h3 class="section-title">Registered Library Members</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($allReaders) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($allReaders)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="book-info">
                                    <span class="book-title"><?php echo htmlspecialchars($row['username']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn-delete" 
                                            onclick="confirmUserDelete(<?php echo $row['id']; ?>, '<?php echo addslashes($row['username']); ?>')">
                                        <i class="fa fa-user-minus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="empty-msg">No readers registered yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <div class="warning-circle" id="modalIconBox"><i class="fa fa-user-times" id="modalIcon"></i></div>
                <h3 id="modalTitle">Remove Reader?</h3>
            </div>
            <p id="modalMainText">Target: <span id="readerNameDisplay" style="font-weight:700; color:#ff8fa3;"></span></p>
            <p class="modal-subtext" id="modalSubText">Administrative Action Required</p>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn-danger-confirm">Remove Access</a>
            </div>
        </div>
    </div>

    <script src="../../assets/js/readers.js"></script>
</body>
</html>