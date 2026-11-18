<?php
include('config/constant.php');
include('config/mail.php');

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_test'])) {
    $to = trim($_POST['recipient_email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $htmlBody = "
    <div style='font-family: Arial, sans-serif; padding: 20px; background: #f4f6f8;'>
        <div style='max-width: 500px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
            <h2 style='color: #1976d2; margin-top: 0;'>FIXNOW Mail System Test</h2>
            <p style='color: #444; font-size: 15px;'>Congratulations! PHPMailer is successfully connected to your <strong>FIXNOW</strong> platform.</p>
            <div style='background: #e3f2fd; padding: 15px; border-radius: 6px; margin: 15px 0;'>
                <strong>Test Message:</strong><br>
                " . nl2br(htmlspecialchars($message)) . "
            </div>
            <p style='color: #777; font-size: 12px; margin-bottom: 0;'>Sent on " . date('Y-m-d H:i:s') . "</p>
        </div>
    </div>";

    $result = sendFixNowMail($to, "Test User", $subject, $htmlBody);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPMailer Connection Test | FIXNOW</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'League Spartan', sans-serif; }
        body { min-height: 100vh; background: #f1f5f9; display: flex; align-items: center; justify-content: center; padding: 30px 15px; }
        .card { background: #fff; max-width: 650px; width: 100%; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); overflow: hidden; }
        .header { background: #1e293b; color: #fff; padding: 24px; text-align: center; }
        .header h1 { font-size: 24px; margin-bottom: 6px; }
        .header p { font-size: 14px; opacity: 0.85; }
        .body { padding: 28px; }
        .config-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px; font-size: 14px; }
        .config-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #cbd5e1; }
        .config-row:last-child { border-bottom: none; }
        .config-row strong { color: #0f172a; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px; color: #334155; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        .btn { width: 100%; padding: 12px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; transition: 0.2s; }
        .btn:hover { background: #1d4ed8; }
        .instruction { font-size: 13px; color: #64748b; margin-top: 15px; line-height: 1.5; background: #fffbeb; border: 1px solid #fef3c7; padding: 12px; border-radius: 6px; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h1><i class="fa-solid fa-envelope-circle-check"></i> PHPMailer System Check</h1>
        <p>Verify SMTP connection & test outgoing email capabilities</p>
    </div>

    <div class="body">
        <?php if ($result !== null): ?>
            <?php if ($result['success']): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> <strong>Success:</strong> <?php echo htmlspecialchars($result['message']); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> <strong>Connection Issue:</strong> <?php echo htmlspecialchars($result['message']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="config-box">
            <div class="config-row">
                <span>PHPMailer Status:</span>
                <strong style="color: #16a34a;"><i class="fa-solid fa-check"></i> Installed (v7.2.0 via Composer)</strong>
            </div>
            <div class="config-row">
                <span>SMTP Host:</span>
                <strong><?php echo MAIL_HOST; ?></strong>
            </div>
            <div class="config-row">
                <span>SMTP Port / Encryption:</span>
                <strong><?php echo MAIL_PORT . ' / ' . strtoupper(MAIL_ENCRYPTION); ?></strong>
            </div>
            <div class="config-row">
                <span>Sender Address:</span>
                <strong><?php echo MAIL_FROM_ADDRESS; ?> (<?php echo MAIL_FROM_NAME; ?>)</strong>
            </div>
            <div class="config-row">
                <span>Config File:</span>
                <code>config/mail.php</code>
            </div>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label for="recipient_email">Send Test Email To:</label>
                <input type="email" id="recipient_email" name="recipient_email" class="form-control" placeholder="your-personal-email@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" class="form-control" value="FixNow PHPMailer Test" required>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" class="form-control" rows="3" required>Hello! This is a test email sent from the FIXNOW service platform via PHPMailer.</textarea>
            </div>

            <button type="submit" name="send_test" class="btn">
                <i class="fa-solid fa-paper-plane"></i> Send Test Email
            </button>
        </form>

        <div class="instruction">
            <strong><i class="fa-solid fa-lightbulb"></i> How to connect your Gmail SMTP in <code>config/mail.php</code>:</strong><br>
            1. Go to your Google Account &rarr; <strong>Security</strong> &rarr; Turn on <strong>2-Step Verification</strong>.<br>
            2. Search for <strong>App Passwords</strong> and create an app password (select "Mail" & "Other (FixNow)").<br>
            3. Paste your Gmail address and the 16-character App Password into <code>config/mail.php</code>:
            <pre style="background: #f1f5f9; padding: 6px; margin-top: 4px; border-radius: 4px;">define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'xxxx xxxx xxxx xxxx');</pre>
        </div>
    </div>
</div>

</body>
</html>
