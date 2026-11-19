<?php 
include('../config/constant.php');
include('partials/login-check.php');

$msg = '';

// Handle Monthly Subscription Fee update
if (isset($_POST['submit_monthly_fee'])) {
    $amount = floatval($_POST['monthly_fee'] ?? 0.00);
    if ($amount > 0) {
        db_execute($conn, "INSERT INTO tbl_payment (payment_amount) VALUES (?)", [$amount], "d");
        $msg = "Monthly subscription rate updated to Rs. " . number_format($amount, 2);
    }
}

// Handle One-Time Registration Fee update
if (isset($_POST['submit_reg_fee'])) {
    $reg_amount = floatval($_POST['reg_fee'] ?? 0.00);
    if ($reg_amount > 0) {
        db_execute($conn, "INSERT INTO tbl_registration_fees (fee_amount) VALUES (?)", [$reg_amount], "d");
        $msg = "Specialist registration fee updated to Rs. " . number_format($reg_amount, 2);
    }
}

// Fetch current fees
$curr_monthly = db_fetch_one($conn, "SELECT payment_amount FROM tbl_payment ORDER BY id DESC LIMIT 1");
$monthly_amount = $curr_monthly ? floatval($curr_monthly['payment_amount']) : 1500.00;

$curr_reg = db_fetch_one($conn, "SELECT fee_amount FROM tbl_registration_fees ORDER BY id DESC LIMIT 1");
$reg_amount = $curr_reg ? floatval($curr_reg['fee_amount']) : 2500.00;

include('partials/main.php'); 
?>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Platform Fee &amp; Subscription Controls</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Configure specialist onboarding dues, monthly subscriptions, and platform pricing.</p>
        </div>
        <a href="manage-invoice.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoices
        </a>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">
        <!-- Card 1: Monthly Vendor Subscription Fee -->
        <div class="admin-card">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                <div class="admin-stat-icon primary">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #0f172a;">Monthly Subscription</h3>
                    <p style="color: var(--fn-text-muted); font-size: 13px; margin: 0;">Recurring monthly specialist listing fee</p>
                </div>
            </div>

            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                <span style="font-size: 12px; font-weight: 700; color: #0284c7; text-transform: uppercase;">Current Monthly Rate</span>
                <div style="font-size: 28px; font-weight: 800; color: #0284c7; margin-top: 4px;">
                    Rs. <?php echo number_format($monthly_amount, 2); ?>
                </div>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="monthly_fee">Update Monthly Fee (LKR):</label>
                    <input type="number" id="monthly_fee" name="monthly_fee" placeholder="1500.00" step="0.01" min="0" required />
                </div>
                <button type="submit" name="submit_monthly_fee" class="btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Monthly Fee
                </button>
            </form>
        </div>

        <!-- Card 2: One-Time Specialist Onboarding Fee -->
        <div class="admin-card">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                <div class="admin-stat-icon success">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #0f172a;">Onboarding Registration Fee</h3>
                    <p style="color: var(--fn-text-muted); font-size: 13px; margin: 0;">One-time vetting &amp; profile activation charge</p>
                </div>
            </div>

            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                <span style="font-size: 12px; font-weight: 700; color: #059669; text-transform: uppercase;">Current Registration Fee</span>
                <div style="font-size: 28px; font-weight: 800; color: #059669; margin-top: 4px;">
                    Rs. <?php echo number_format($reg_amount, 2); ?>
                </div>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="reg_fee">Update Registration Fee (LKR):</label>
                    <input type="number" id="reg_fee" name="reg_fee" placeholder="2500.00" step="0.01" min="0" required />
                </div>
                <button type="submit" name="submit_reg_fee" class="btn-primary" style="width: 100%; background: #10b981;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Registration Fee
                </button>
            </form>
        </div>
    </div>
</div>

<?php 
include('partials/footer.php'); 
?>
