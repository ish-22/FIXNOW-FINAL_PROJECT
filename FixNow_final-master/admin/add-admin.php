<?php 
include('../config/constant.php');
include('partials/login-check.php');

$error_msg = '';
if (isset($_POST['submit'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($full_name) && !empty($username) && !empty($email) && !empty($password)) {
        // Check if username or email exists
        $existing = db_fetch_one($conn, "SELECT id FROM tbl_admin WHERE username = ? OR email = ?", [$username, $email], "ss");
        if ($existing) {
            $error_msg = 'Username or email already in use by another administrator.';
        } else {
            $hashed = secure_password_hash($password);
            $inserted = db_execute($conn, "INSERT INTO tbl_admin (full_name, username, email, password) VALUES (?, ?, ?, ?)", [$full_name, $username, $email, $hashed], "ssss");
            if ($inserted) {
                header("Location: manage-user.php?status=success&action=add");
                exit();
            } else {
                $error_msg = 'Database error occurred while adding administrator.';
            }
        }
    } else {
        $error_msg = 'Please fill out all required fields.';
    }
}

include('partials/main.php'); 
?>

<div class="admin-card" style="max-width: 680px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Register New Administrator</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Grant moderation and administrative management access.</p>
        </div>
        <a href="manage-user.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="e.g. Ruwan Wickramasinghe" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required />
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="e.g. ruwan_admin" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required />
        </div>

        <div class="form-group">
            <label for="email">Admin Email Address</label>
            <input type="email" id="email" name="email" placeholder="e.g. ruwan@fixnow.lk" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
        </div>

        <div class="form-group">
            <label for="password">Security Password</label>
            <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required />
            <small style="color: #64748b; font-size: 12.5px; display: block; margin-top: 4px;">Passwords are automatically encrypted with salted Bcrypt algorithms.</small>
        </div>

        <div style="margin-top: 28px; display: flex; gap: 12px;">
            <button type="submit" name="submit" class="btn-primary">
                <i class="fa-solid fa-user-plus"></i> Create Admin Account
            </button>
            <a href="manage-user.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
