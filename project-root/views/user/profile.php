<?php
// views/user/profile.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once('../../controller/auth/auth_guard.php');
require_once('../../controller/user_controller.php'); 

protect_page('user');

$user = getUserProfile($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - BookVerse</title>
    
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="../../assets/js/profile.js" defer></script>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-book-open"></i> BookVerse
        </div>
        <nav>
            <a href="user_dashboard.php"><i class="fas fa-bookmark"></i> My Shelf</a>
            <a href="store.php"><i class="fas fa-shopping-bag"></i> Browse Store</a>
            <a href="#" class="active"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="../../controller/auth/logout.php" style="margin-top: 50px; color: #e74a3b;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        
        <div class="content-header">
            <h1>Account Settings</h1>
        </div>

        <div class="profile-container">
            
            <div class="info-panel">
                <div class="avatar-circle">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <h2 class="user-name"><?php echo htmlspecialchars($user['username']); ?></h2>
                <div class="user-role">Reader</div>
                <div class="join-date">Joined <?php echo date("F Y", strtotime($user['created_at'])); ?></div>
            </div>

            <div class="action-panel">
                
                <?php if(isset($_GET['msg'])): ?>
                    <div class="alert <?php echo ($_GET['type'] == 'error') ? 'alert-error' : 'alert-success'; ?>">
                        <?php echo htmlspecialchars(str_replace('+', ' ', $_GET['msg'])); ?>
                    </div>
                <?php endif; ?>

                <div class="section-card">
                    <h3><i class="fas fa-user-edit"></i> Edit Profile & Security</h3>
                    
                    <form id="updateProfileForm" action="../../controller/user_controller.php" method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" class="custom-input readonly" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>

                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" id="email" class="custom-input" value="<?php echo htmlspecialchars($user['user_email'] ?? $user['email']); ?>">
                            <small id="err-email" class="err-msg"></small>
                        </div>

                        <div style="margin-top: 30px; border-top: 2px dashed #fce4ec; padding-top: 20px;">
                            <h4 style="color: #592d3e; margin-bottom: 15px;">Change Password (Optional)</h4>
                            
                            <div class="input-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" id="newPass" class="custom-input password-field" placeholder="Leave blank to keep current">
                                <small id="err-newPass" class="err-msg"></small>
                            </div>

                            <div class="input-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" id="confirmPass" class="custom-input password-field" placeholder="Confirm new password">
                                <small id="err-confirmPass" class="err-msg"></small>
                            </div>
                        </div>

                        <div style="margin-top: 20px; background: #fffafa; padding: 15px; border-radius: 10px; border: 1px solid #fce4ec;">
                            <div class="input-group" style="margin-bottom: 0;">
                                <label>Current Password (Required to Save) <span style="color:#d81b60">*</span></label>
                                <input type="password" name="current_password" id="currentPass" class="custom-input password-field" placeholder="Enter current password">
                                <small id="err-currentPass" class="err-msg"></small>
                            </div>
                        </div>

                        <label class="toggle-box">
                            <input type="checkbox" onclick="togglePasswordVisibility()"> Show Passwords
                        </label>

                        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Save All Changes</button>
                    </form>
                </div>

                <div class="section-card delete-zone">
                    <h3><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
                    <p class="warning-text">Warning: Deleting your account is permanent. All your purchased books and reading history will be lost.</p>
                    
                    <form id="deleteAccountForm" action="../../controller/user_controller.php" method="POST" onsubmit="return confirm('Are you strictly sure you want to delete your account?');">
                        <input type="hidden" name="action" value="delete_account">
                        
                        <div class="input-group">
                            <label style="color: var(--danger);">Enter Password to Confirm</label>
                            <input type="password" name="confirm_delete_pass" id="confirmDeletePass" class="custom-input" style="border-color: #ffcccb;">
                            <small id="err-delete" class="err-msg"></small>
                        </div>

                        <button type="submit" class="btn btn-danger">Permanently Delete Account</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>