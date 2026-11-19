<?php
include('../config/constant.php');
include('partials/login-check.php');

if (!isset($_GET['id'])) {
    header("Location: manage-dailyHacks.php");
    exit();
}

$hack_id = intval($_GET['id']);
$hack = db_fetch_one($conn, "SELECT * FROM tbl_dailyhacks WHERE id = ?", [$hack_id], "i");

if (!$hack) {
    header("Location: manage-dailyHacks.php");
    exit();
}

$error_msg = '';

if (isset($_POST['submit-update-hack'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_name = $hack['image'];

    // Handle Image Upload if changed
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "Hack_" . rand(1000, 9999) . "." . $ext;
        $dest = "../images/hacks_images/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    }

    if (!empty($title) && !empty($description)) {
        $updated = db_execute($conn, "UPDATE tbl_dailyhacks SET title = ?, description = ?, image = ? WHERE id = ?", [$title, $description, $image_name, $hack_id], "sssi");
        if ($updated) {
            header("Location: manage-dailyHacks.php?status=success&action=update-hack");
            exit();
        } else {
            $error_msg = "Database error updating hack.";
        }
    } else {
        $error_msg = "Please provide both article title and description.";
    }
}

include('partials/main.php');
?>

<div class="admin-card" style="max-width: 680px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Edit DIY Maintenance Hack</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Update instructions, tips, and cover photo.</p>
        </div>
        <a href="manage-dailyHacks.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Hacks
        </a>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Article Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($hack['title']); ?>" required />
        </div>

        <div class="form-group">
            <label for="description">Content &amp; Instructions</label>
            <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($hack['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Featured Cover Image</label>
            <?php if (!empty($hack['image'])): ?>
                <div style="margin-bottom: 8px;">
                    <img src="../images/hacks_images/<?php echo $hack['image']; ?>" alt="" style="width: 100px; height: 75px; object-fit: cover; border-radius: 8px;" onerror="this.style.display='none';">
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*" />
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" name="submit-update-hack" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save Hack Changes
            </button>
            <a href="manage-dailyHacks.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
