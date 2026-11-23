<?php 
include_once('../config/constant.php');

if (!isset($_SESSION['vendor_id'])) {
    header("Location: ../signin.php");
    exit();
}

$vendor_id = intval($_SESSION['vendor_id']);

// Fetch service provider details
$service_provider = db_fetch_one(
    $conn,
    "SELECT sp.*, s.service_type, s.booking_price 
     FROM tbl_service_providers sp 
     LEFT JOIN tbl_service s ON sp.servicetype_id = s.id 
     WHERE sp.id = ?",
    [$vendor_id],
    "i"
);

if (!$service_provider) {
    header("Location: ../signin.php");
    exit();
}

// Fetch fixed monthly subscription fee from tbl_payment
$payment = db_fetch_one($conn, "SELECT payment_amount FROM tbl_payment ORDER BY id DESC LIMIT 1");
$monthly_amount = $payment ? floatval($payment['payment_amount']) : 1500.00;

// Fetch invoice status from tbl_invoice
$invoice = db_fetch_one(
    $conn,
    "SELECT status, created_at FROM tbl_invoice WHERE service_provider_id = ? ORDER BY id DESC LIMIT 1",
    [$vendor_id],
    "i"
);
$invoice_status = $invoice ? $invoice['status'] : 'Pending';

$current_page = 'invoice.php';
include('./partials/header.php');
?>

<!-- Hero Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-file-invoice-dollar"></i> Partner Subscription Statement
        </span>
        <h1 style="margin-bottom: 10px; font-size: 32px; font-weight: 900; color: #0f172a;">
            Specialist Membership Invoice
        </h1>
        <p style="color: #64748b; font-size: 15.5px; margin: 0 auto; max-width: 600px; line-height: 1.6;">
            Review your active monthly partnership subscription status, account verification tier, and billing details.
        </p>
    </div>
</div>

<!-- Main Invoice Statement -->
<section style="padding: 40px 20px 80px 20px; max-width: 900px; margin: 0 auto;">
    
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        
        <!-- Header: Invoice ID & Status -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 22px; margin-bottom: 24px;">
            <div>
                <span style="font-size: 13px; font-weight: 700; color: #0284c7; text-transform: uppercase;">FixNow Billing Statement</span>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 2px 0 0 0;">
                    INV-<?php echo date('Ym') . '-' . $vendor_id; ?>
                </h2>
            </div>
            <div>
                <?php if (strtolower($invoice_status) === 'paid' || strtolower($invoice_status) === 'confirmed'): ?>
                    <span style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-circle-check"></i> Subscription Active
                    </span>
                <?php else: ?>
                    <span style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a; padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-clock"></i> Payment Required
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Provider Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 28px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Specialist Name</span>
                <strong style="color: #0f172a; font-size: 15px;"><?php echo htmlspecialchars($service_provider['full_name']); ?></strong>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Partner ID / Code</span>
                <strong style="color: #0f172a; font-size: 15px;"><?php echo htmlspecialchars($service_provider['registration_code'] ?? ('REG' . (1000 + $vendor_id))); ?></strong>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Registered Trade</span>
                <strong style="color: #0284c7; font-size: 15px;"><?php echo htmlspecialchars($service_provider['service_type'] ?? 'General Specialist'); ?></strong>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Monthly Fee</span>
                <strong style="color: #10b981; font-size: 17px;">LKR <?php echo number_format($monthly_amount, 2); ?></strong>
            </div>
        </div>

        <!-- Explanatory Terms Callout -->
        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 14px; padding: 22px; margin-bottom: 28px;">
            <h4 style="font-size: 15px; font-weight: 800; color: #0369a1; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-info"></i> Partner Subscription Policy
            </h4>
            <ul style="font-size: 13.5px; color: #334155; line-height: 1.7; margin: 0; padding-left: 20px;">
                <li>Service providers pay a fixed monthly subscription fee for the first 3 billing cycles following verification.</li>
                <li>Upon completing 3 consecutive months, your specialist account transitions to a <strong>Permanent Accredited Tier</strong> with zero recurring monthly fees.</li>
                <li>Active accounts receive automated dispatch matching, media profile promotion, and priority client listing in your town.</li>
                <li>Subscriptions must be cleared before the 30th of each calendar month to avoid automated listing suspension.</li>
            </ul>
        </div>

        <!-- Action Area -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; border-top: 1px solid #e2e8f0; padding-top: 24px;">
            <a href="payment.php?service_provider_id=<?php echo $vendor_id; ?>" style="background: #0284c7; color: #ffffff; text-decoration: none; padding: 13px 30px; font-size: 15px; font-weight: 800; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(2,132,199,0.25);">
                <i class="fa-solid fa-lock"></i> Agree &amp; Proceed to Payment
            </a>
            <a href="index.php" style="color: #64748b; font-size: 14px; font-weight: 600; text-decoration: none;">
                Return to Dashboard
            </a>
        </div>

    </div>

</section>

<?php 
include('./partials/footer.php'); 
?>
