<?php 
include('../config/constant.php');
include('partials/login-check.php');

$msg = '';

// Check if form is submitted
if (isset($_POST['submit'])) {
    $registration_fee = floatval($_POST['fee_amount'] ?? 0.00);

    if ($registration_fee > 0) {
        db_execute($conn, "INSERT INTO tbl_registration_fees (fee_amount) VALUES (?)", [$registration_fee], "d");
        header("Location: manage-registrationFee.php?status=success&action=add_fee");
        exit();
    }
}

// Fetch current fee
$row = db_fetch_one($conn, "SELECT id, fee_amount FROM tbl_registration_fees ORDER BY id DESC LIMIT 1");
$current_fee = $row ? floatval($row['fee_amount']) : 2500.00;

include('partials/main.php'); 
?>

<div class="admin-card" style="max-width: 600px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Specialist Registration Fee</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">One-time onboarding and vetting fee required from new service providers.</p>
        </div>
        <a href="manage-invoice.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoices
        </a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> Registration fee updated successfully!
        </div>
    <?php endif; ?>

    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
        <span style="font-size: 12px; font-weight: 700; color: #059669; text-transform: uppercase;">Active Registration Charge</span>
        <div style="font-size: 32px; font-weight: 800; color: #059669; margin-top: 4px;">
            Rs. <?php echo number_format($current_fee, 2); ?>
        </div>
    </div>

    <form action="" method="POST">
        <div class="form-group">
            <label for="fee_amount">Set New Registration Fee (LKR)</label>
            <input type="number" id="fee_amount" name="fee_amount" placeholder="2500.00" min="0" step="0.01" required>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" name="submit" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save New Fee
            </button>
            <a href="manage-invoice.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
