<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Path to Composer autoloader
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    $autoloadPath = __DIR__ . '/vendor/autoload.php';
}

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// SMTP Configuration Settings
// Change these settings to your real SMTP credentials (e.g. Gmail App Password, Mailtrap, SendGrid, etc.)
defined('MAIL_HOST')        || define('MAIL_HOST', 'smtp.gmail.com');
defined('MAIL_PORT')        || define('MAIL_PORT', 587); // 587 for TLS, 465 for SSL
defined('MAIL_ENCRYPTION')  || define('MAIL_ENCRYPTION', 'tls'); // 'tls' or 'ssl'
defined('MAIL_USERNAME')    || define('MAIL_USERNAME', 'fixnow.service.platform@gmail.com'); // Your SMTP email
defined('MAIL_PASSWORD')    || define('MAIL_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Your SMTP App password
defined('MAIL_FROM_ADDRESS')|| define('MAIL_FROM_ADDRESS', 'noreply@fixnow.lk');
defined('MAIL_FROM_NAME')   || define('MAIL_FROM_NAME', 'FIXNOW Service Platform');

/**
 * Send an email using PHPMailer
 *
 * @param string $toEmail Recipient email address
 * @param string $toName Recipient name
 * @param string $subject Subject of the email
 * @param string $htmlBody HTML content of the email
 * @param string $altBody Plain-text content fallback (optional)
 * @return array ['success' => bool, 'message' => string]
 */
function sendFixNowMail($toEmail, $toName, $subject, $htmlBody, $altBody = '') {
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        return [
            'success' => false,
            'message' => 'PHPMailer class is not loaded.'
        ];
    }

    $mail = new PHPMailer(true);

    try {
        // Server configuration
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = (MAIL_ENCRYPTION === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;

        // SSL Certificate Verification (set to false for local dev environments if needed)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Recipients
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = !empty($altBody) ? $altBody : strip_tags($htmlBody);

        $mail->send();
        return [
            'success' => true,
            'message' => 'Email sent successfully.'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
        ];
    }
}

