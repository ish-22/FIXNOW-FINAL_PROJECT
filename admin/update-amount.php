<?php 
include('../config/constant.php');
include('partials/login-check.php');

// Fetch current fee
$payment = db_fetch_one($conn, "SELECT * FROM tbl_payment ORDER BY id DESC LIMIT 1");
$current_id = $payment ? $payment['id'] : 1;

if (isset($_POST['submit'])) {
    $amount = floatval($_POST['payment_amount'] ?? 0.00);
    if ($amount > 0) {
        db_execute($conn, "UPDATE tbl_payment SET payment_amount = ? WHERE id = ?", [$amount, $current_id], "di");
        header("Location: add-amount.php?status=success&action=fee_updated");
        exit();
    }
}

include('partials/main.php'); 
?>

<div class="admin-card" style="max-width: 600px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Update Monthly Subscription Fee</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Adjust recurring monthly platform dues charged to active specialists.</p>
        </div>
        <a href="add-amount.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Pricing
        </a>
    </div>

    <form action="" method="POST">
        <div class="form-group">
            <label for="payment_amount">Monthly Platform Due (LKR)</label>
            <input type="number" id="payment_amount" name="payment_amount" value="<?php echo htmlspecialchars($payment['payment_amount'] ?? '1500.00'); ?>" min="0" step="0.01" required>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" name="submit" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save New Rate
            </button>
            <a href="add-amount.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
