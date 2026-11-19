<?php 
include('../config/constant.php');
include('partials/login-check.php');

if (!isset($_GET['id'])) {
    header('Location: manage-user.php');
    exit();
}

$id = intval($_GET['id']);
$admin = db_fetch_one($conn, "SELECT * FROM tbl_admin WHERE id = ?", [$id], "i");

if (!$admin) {
    header('Location: manage-user.php');
    exit();
}

$error_msg = '';
if (isset($_POST['submit'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($full_name) && !empty($username) && !empty($email)) {
        if (!empty($password)) {
            $hashed = secure_password_hash($password);
            $updated = db_execute($conn, "UPDATE tbl_admin SET full_name = ?, username = ?, email = ?, password = ? WHERE id = ?", [$full_name, $username, $email, $hashed, $id], "ssssi");
        } else {
            $updated = db_execute($conn, "UPDATE tbl_admin SET full_name = ?, username = ?, email = ? WHERE id = ?", [$full_name, $username, $email, $id], "sssi");
        }

        if ($updated) {
            header("Location: manage-user.php?status=success&action=update");
            exit();
        } else {
            $error_msg = "Failed to update administrator details.";
        }
    } else {
        $error_msg = "Please fill out all required fields.";
    }
}

include('partials/main.php'); 
?>

<div class="admin-card" style="max-width: 680px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Update Administrator Details</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Modify administrator credentials and platform privileges.</p>
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
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($admin['full_name']); ?>" required />
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required />
        </div>

        <div class="form-group">
            <label for="email">Admin Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required />
        </div>

        <div class="form-group">
            <label for="password">Change Password <small style="font-weight: 400; color: #64748b;">(Leave blank to keep current password)</small></label>
            <input type="password" id="password" name="password" placeholder="New password" />
        </div>

        <div style="margin-top: 28px; display: flex; gap: 12px;">
            <button type="submit" name="submit" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save Changes
            </button>
            <a href="manage-user.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
