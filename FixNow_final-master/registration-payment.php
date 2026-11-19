<?php
include('./config/constant.php');
require_once('./config/payment.php');

$service_provider_id = isset($_GET['service_provider_id']) ? intval($_GET['service_provider_id']) : 0;

if ($service_provider_id <= 0 && isset($_POST['service_provider_id'])) {
    $service_provider_id = intval($_POST['service_provider_id']);
}

// Fetch registration fee
$fee_query = "SELECT fee_amount FROM tbl_registration_fees ORDER BY id DESC LIMIT 1";
$fee_res = mysqli_query($conn, $fee_query);
$fee_amount = 2500.00; // default fallback
if ($fee_res && mysqli_num_rows($fee_res) > 0) {
    $fee_row = mysqli_fetch_assoc($fee_res);
    $fee_amount = floatval($fee_row['fee_amount']);
}

// Fetch vendor details securely
$vendor = null;
if ($service_provider_id > 0) {
    $vendor_query = "SELECT sp.*, s.service_type 
                     FROM tbl_service_providers sp 
                     LEFT JOIN tbl_service s ON sp.servicetype_id = s.id 
                     WHERE sp.id = ?";
    $vendor = db_fetch_one($vendor_query, "i", [$service_provider_id]);
}

$order_id = "REG-" . $service_provider_id;
$payhere_amount = number_format($fee_amount, 2, '.', '');
$payhere_hash = generate_payhere_hash($order_id, $fee_amount);

// Process instant test payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_test_now'])) {
    $service_provider_id = intval($_POST['service_provider_id']);
    
    if ($service_provider_id > 0) {
        $update_query = "UPDATE tbl_service_providers 
                         SET registration_fee_paid = 'Yes', payment_status = 'Paid' 
                         WHERE id = ?";
        
        $update_res = db_execute($update_query, "i", [$service_provider_id]);
        if (!empty($update_res['success'])) {
            $_SESSION['payment_success'] = "Registration payment successful! Please log in.";
            header("Location: " . SITEURL . "signin.php?service_provider_id=" . $service_provider_id);
            exit();
        } else {
            $error = "Payment failed to update database.";
        }
    } else {
        $error = "Invalid service provider reference.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Payment | FIXNOW</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'League Spartan', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 50%, #90caf9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .payment-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 50, 120, 0.15);
            max-width: 650px;
            width: 100%;
            overflow: hidden;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: linear-gradient(135deg, #1976d2, #0d47a1);
            color: #ffffff;
            padding: 26px 30px;
            text-align: center;
        }

        .card-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .card-header p {
            font-size: 15px;
            opacity: 0.9;
        }

        .card-body {
            padding: 28px 30px;
        }

        .info-box {
            background: #f8fafd;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 15px;
            color: #334155;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-top: 10px;
        }

        .amount-highlight {
            font-size: 22px;
            font-weight: 700;
            color: #0d47a1;
        }

        .payment-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .tab-btn {
            flex: 1;
            background: #f1f5f9;
            border: 2px solid transparent;
            padding: 12px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn.active {
            background: #eef2ff;
            border-color: #3b82f6;
            color: #1d4ed8;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        .input-with-icon input {
            padding-left: 42px !important;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .pay-btn-gateway {
            width: 100%;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-btn-gateway:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.35);
        }

        .pay-btn-test {
            width: 100%;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-btn-test:hover {
            background: linear-gradient(135deg, #15803d, #166534);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.35);
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            color: #64748b;
            font-size: 13px;
        }

        .security-badge i {
            color: #16a34a;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #475569;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link:hover {
            color: #1976d2;
            text-decoration: underline;
        }

        .gateway-banner {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="payment-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-id-card"></i> Vendor Onboarding Fee</h2>
        <p>Complete your registration payment to activate your service provider account</p>
    </div>

    <div class="card-body">
        <?php if (isset($error)): ?>
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <div class="info-row">
                <span>Service Provider:</span>
                <strong><?php echo htmlspecialchars($vendor['full_name'] ?? 'New Provider'); ?></strong>
            </div>
            <div class="info-row">
                <span>Registration No:</span>
                <strong><?php echo htmlspecialchars($vendor['reg_no'] ?? ('REG-' . $service_provider_id)); ?></strong>
            </div>
            <div class="info-row">
                <span>Trade / Category:</span>
                <strong><?php echo htmlspecialchars($vendor['service_type'] ?? 'General Service'); ?></strong>
            </div>
            <div class="info-row">
                <span>One-Time Registration Fee:</span>
                <span class="amount-highlight">Rs. <?php echo number_format($fee_amount, 2); ?> /=</span>
            </div>
        </div>

        <div class="payment-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('payhere')">
                <i class="fa-solid fa-building-columns"></i> PayHere Sri Lanka Gateway
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('testcard')">
                <i class="fa-solid fa-vial"></i> Instant Test Card (Dev Mode)
            </button>
        </div>

        <!-- PayHere Tab -->
        <div id="tab-payhere" class="tab-content active">
            <div class="gateway-banner">
                <i class="fa-solid fa-circle-info fa-lg"></i>
                <div>
                    <strong>PayHere Gateway (LKR):</strong> Secure processing via Sri Lankan debit/credit cards and mobile wallets.
                </div>
            </div>

            <form action="<?php echo htmlspecialchars(PAYHERE_CHECKOUT_URL); ?>" method="POST">
                <input type="hidden" name="merchant_id" value="<?php echo htmlspecialchars(PAYHERE_MERCHANT_ID); ?>">
                <input type="hidden" name="return_url" value="<?php echo SITEURL; ?>signin.php?payment=success&service_provider_id=<?php echo $service_provider_id; ?>">
                <input type="hidden" name="cancel_url" value="<?php echo SITEURL; ?>registration-payment.php?service_provider_id=<?php echo $service_provider_id; ?>">
                <input type="hidden" name="notify_url" value="<?php echo SITEURL; ?>payment_notify.php">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                <input type="hidden" name="items" value="Vendor Onboarding Fee">
                <input type="hidden" name="currency" value="<?php echo htmlspecialchars(PAYHERE_CURRENCY); ?>">
                <input type="hidden" name="amount" value="<?php echo htmlspecialchars($payhere_amount); ?>">
                <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($vendor['full_name'] ?? 'Provider'); ?>">
                <input type="hidden" name="last_name" value="Registration">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($vendor['email'] ?? 'vendor@fixnow.lk'); ?>">
                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($vendor['contact_no'] ?? '0770000000'); ?>">
                <input type="hidden" name="address" value="Sri Lanka">
                <input type="hidden" name="city" value="Colombo">
                <input type="hidden" name="country" value="Sri Lanka">
                <input type="hidden" name="hash" value="<?php echo htmlspecialchars($payhere_hash); ?>">

                <button type="submit" class="pay-btn-gateway">
                    <i class="fa-solid fa-lock"></i> Checkout with PayHere &bull; Rs. <?php echo number_format($fee_amount, 2); ?>
                </button>
            </form>
        </div>

        <!-- Instant Test Card Tab -->
        <div id="tab-testcard" class="tab-content">
            <form action="" method="POST">
                <input type="hidden" name="service_provider_id" value="<?php echo $service_provider_id; ?>">

                <div class="form-group">
                    <label for="card_name">Cardholder Name</label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" id="card_name" name="card_name" class="form-control" value="<?php echo htmlspecialchars($vendor['full_name'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="card_number">Card Number</label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-credit-card"></i>
                        <input type="text" id="card_number" name="card_number" class="form-control" value="4242 &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 4242" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="exp_date">Expiry Date</label>
                        <input type="text" id="exp_date" name="exp_date" class="form-control" value="12/28" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="password" id="cvv" name="cvv" class="form-control" value="123" required>
                    </div>
                </div>

                <button type="submit" name="pay_test_now" class="pay-btn-test">
                    <i class="fa-solid fa-circle-check"></i> Complete Instant Test Payment (Dev Mode)
                </button>
            </form>
        </div>

        <div class="security-badge">
            <span><i class="fa-solid fa-shield-halved"></i> 256-Bit SSL Protection</span>
            <span><i class="fa-brands fa-cc-visa fa-lg"></i></span>
            <span><i class="fa-brands fa-cc-mastercard fa-lg"></i></span>
        </div>

        <a href="signin.php" class="back-link">&larr; Return to Sign In</a>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

    if (tab === 'payhere') {
        document.querySelectorAll('.tab-btn')[0].classList.add('active');
        document.getElementById('tab-payhere').classList.add('active');
    } else {
        document.querySelectorAll('.tab-btn')[1].classList.add('active');
        document.getElementById('tab-testcard').classList.add('active');
    }
}
</script>

</body>
</html>
