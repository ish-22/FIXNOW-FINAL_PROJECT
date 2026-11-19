<?php
include('../config/constant.php');
include('partials/login-check.php');

$error_msg = '';

if (isset($_POST['submit-hack'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Handle Image Upload
    $image_name = '';
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "Hack_" . rand(1000, 9999) . "." . $ext;
        $dest = "../images/hacks_images/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    }

    if (!empty($title) && !empty($description)) {
        $inserted = db_execute($conn, "INSERT INTO tbl_dailyhacks (title, description, image) VALUES (?, ?, ?)", [$title, $description, $image_name], "sss");
        if ($inserted) {
            header("Location: manage-dailyHacks.php?status=success&action=add-hack");
            exit();
        } else {
            $error_msg = "Database error saving hack.";
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
            <h2 class="main-welcome" style="margin-bottom: 4px;">Publish DIY Maintenance Hack</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Share troubleshooting guides and home improvement advice with customers.</p>
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
            <input type="text" id="title" name="title" placeholder="e.g. How to Safely Unclog a Sink Trap in 10 Minutes" required />
        </div>

        <div class="form-group">
            <label for="description">Content &amp; Instructions</label>
            <textarea id="description" name="description" placeholder="Write step-by-step DIY instructions and safety precautions..." rows="6" required></textarea>
        </div>

        <div class="form-group">
            <label for="image">Featured Cover Image</label>
            <input type="file" id="image" name="image" accept="image/*" />
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" name="submit-hack" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Publish Hack Article
            </button>
            <a href="manage-dailyHacks.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
