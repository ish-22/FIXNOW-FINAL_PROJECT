<?php
/**
 * FixNow Payment Gateway Configuration
 * Supports PayHere (Sri Lanka LKR) & Instant Test Checkout
 */

defined('PAYMENT_MODE')           || define('PAYMENT_MODE', 'SIMULATION'); // 'SIMULATION' or 'PAYHERE'
defined('PAYHERE_ENV')            || define('PAYHERE_ENV', 'sandbox');    // 'sandbox' or 'live'
defined('PAYHERE_MERCHANT_ID')    || define('PAYHERE_MERCHANT_ID', '1211149'); // Default PayHere Sandbox ID
defined('PAYHERE_MERCHANT_SECRET')|| define('PAYHERE_MERCHANT_SECRET', '4N2Uu1068Zf4a6L0Q7j54h03Y5M3U78');
defined('PAYHERE_CURRENCY')       || define('PAYHERE_CURRENCY', 'LKR');

// PayHere Checkout URLs
defined('PAYHERE_CHECKOUT_URL')   || define(
    'PAYHERE_CHECKOUT_URL',
    (PAYHERE_ENV === 'live') ? 'https://www.payhere.lk/pay/checkout' : 'https://sandbox.payhere.lk/pay/checkout'
);

/**
 * Generate PayHere verification hash
 */
function generate_payhere_hash($order_id, $amount) {
    $formatted_amount = number_format($amount, 2, '.', '');
    $secret_hash = strtoupper(md5(PAYHERE_MERCHANT_SECRET));
    return strtoupper(md5(PAYHERE_MERCHANT_ID . $order_id . $formatted_amount . PAYHERE_CURRENCY . $secret_hash));
}

