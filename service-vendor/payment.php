<?php
include('../config/constant.php');

$vendor_id = isset($_SESSION['vendor_id']) ? intval($_SESSION['vendor_id']) : 0;
if ($vendor_id <= 0 && isset($_GET['service_provider_id'])) {
    $vendor_id = intval($_GET['service_provider_id']);
}
if ($vendor_id <= 0 && isset($_POST['service_provider_id'])) {
    $vendor_id = intval($_POST['service_provider_id']);
}

if ($vendor_id <= 0) {
    header("Location: " . SITEURL . "signin.php");
    exit();
}

// Fetch vendor details
$vendor_query = "SELECT sp.*, s.service_type 
                 FROM tbl_service_providers sp 
                 LEFT JOIN tbl_service s ON sp.servicetype_id = s.id 
                 WHERE sp.id = $vendor_id";
$vendor_res = mysqli_query($conn, $vendor_query);
$vendor = mysqli_fetch_assoc($vendor_res);

// Fetch monthly payment amount
$pay_query = "SELECT payment_amount FROM tbl_payment ORDER BY id DESC LIMIT 1";
$pay_res = mysqli_query($conn, $pay_query);
$monthly_amount = 1500.00;
if ($pay_res && mysqli_num_rows($pay_res) > 0) {
    $pay_row = mysqli_fetch_assoc($pay_res);
    $monthly_amount = floatval($pay_row['payment_amount']);
}

// Process payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    $current_month = date('F Y');
    $service_type_id = intval($vendor['servicetype_id'] ?? 0);
    $contact_no = mysqli_real_escape_string($conn, $vendor['contact_no'] ?? '');
    $email = mysqli_real_escape_string($conn, $vendor['email'] ?? '');
    $message = "Monthly subscription fee received. Account is in good standing.";

    $insert_invoice = "INSERT INTO tbl_invoice (service_provider_id, service_type_id, mobile_no, email, paid_month, paid_amount, message, status, created_at)
                       VALUES ($vendor_id, $service_type_id, '$contact_no', '$email', '$current_month', $monthly_amount, '$message', 'paid', NOW())";
    
    if (mysqli_query($conn, $insert_invoice)) {
        header("Location: invoice.php?status=success");
        exit();
    } else {
        $error = "Failed to record payment: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vendor Monthly Subscription Payment | FIXNOW</title>
    <link rel="shortcut icon" href="../assets/images/fixnow-logo-icon.png" type="image/svg+xml">
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
            padding: 28px 30px;
            text-align: center;
        }

        .card-header h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .card-header p {
            font-size: 15px;
            opacity: 0.9;
        }

        .card-body {
            padding: 30px;
        }

        .info-box {
            background: #f8fafd;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
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
        }

        .info-row strong {
            color: #0f172a;
        }

        .amount-highlight {
            font-size: 22px;
            font-weight: 700;
            color: #0d47a1;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
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
            padding: 12px 14px;
            font-size: 15px;
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

        .pay-btn {
            width: 100%;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #ffffff;
            font-size: 17px;
            font-weight: 700;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, background 0.2s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-btn:hover {
            background: linear-gradient(135deg, #15803d, #166534);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(22, 163, 74, 0.35);
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
    </style>
</head>
<body>

<div class="payment-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-receipt"></i> Monthly Subscription Payment</h2>
        <p>Maintain your active vendor account and client booking privileges</p>
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
                <strong><?php echo htmlspecialchars($vendor['full_name'] ?? 'Vendor'); ?></strong>
            </div>
            <div class="info-row">
                <span>Billing Month:</span>
                <strong><?php echo date('F Y'); ?></strong>
            </div>
            <div class="info-row">
                <span>Service Type:</span>
                <strong><?php echo htmlspecialchars($vendor['service_type'] ?? 'General Service'); ?></strong>
            </div>
            <div class="info-row">
                <span>Monthly Amount:</span>
                <span class="amount-highlight">Rs. <?php echo number_format($monthly_amount, 2); ?> /=</span>
            </div>
        </div>

        <form action="" method="POST">
            <input type="hidden" name="service_provider_id" value="<?php echo $vendor_id; ?>">

            <div class="form-group">
                <label for="card_name">Cardholder Name</label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" id="card_name" name="card_name" class="form-control" placeholder="Full name on card" value="<?php echo htmlspecialchars($vendor['full_name'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="card_number">Card Number</label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-credit-card"></i>
                    <input type="text" id="card_number" name="card_number" class="form-control" placeholder="4242 •••• •••• 4242" maxlength="19" value="4242 4242 4242 4242" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="exp_date">Expiry Date</label>
                    <input type="text" id="exp_date" name="exp_date" class="form-control" placeholder="MM / YY" maxlength="5" value="12/28" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV / CVC</label>
                    <input type="password" id="cvv" name="cvv" class="form-control" placeholder="•••" maxlength="4" value="123" required>
                </div>
            </div>

            <button type="submit" name="pay_now" class="pay-btn">
                <i class="fa-solid fa-lock"></i> Pay Rs. <?php echo number_format($monthly_amount, 2); ?> Now
            </button>
        </form>

        <div class="security-badge">
            <span><i class="fa-solid fa-circle-check"></i> 256-Bit SSL Encrypted</span>
            <span><i class="fa-brands fa-cc-visa fa-lg"></i></span>
            <span><i class="fa-brands fa-cc-mastercard fa-lg"></i></span>
            <span><i class="fa-brands fa-cc-amex fa-lg"></i></span>
        </div>

        <a href="invoice.php" class="back-link">Return to Invoice Details</a>
    </div>
</div>

</body>
</html>

