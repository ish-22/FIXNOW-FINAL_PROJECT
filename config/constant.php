<?php

// Start session safely if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dynamic SITEURL detection (works seamlessly on both localhost and Vercel)
if (!defined('SITEURL')) {
    if (isset($_ENV['SITEURL']) && !empty($_ENV['SITEURL'])) {
        $siteurl = rtrim($_ENV['SITEURL'], '/') . '/';
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
        if (strpos($_SERVER['REQUEST_URI'] ?? '', '/FIXNOW-FINAL_PROJECT') !== false) {
            $siteurl = $protocol . $_SERVER['HTTP_HOST'] . '/FIXNOW-FINAL_PROJECT/';
        } elseif (strpos($_SERVER['REQUEST_URI'] ?? '', '/fixnow') !== false) {
            $siteurl = $protocol . $_SERVER['HTTP_HOST'] . '/fixnow/';
        } else {
            $siteurl = $protocol . $_SERVER['HTTP_HOST'] . '/';
        }
    } else {
        $siteurl = 'http://localhost/fixnow/';
    }
    define('SITEURL', $siteurl);
}

// Database configuration: Supports Environment Variables for Vercel & cloud DBs
if (!defined('LOCALHOST')) {
    $db_host = getenv('DB_HOST') ?: getenv('LOCALHOST') ?: 'localhost';
    $db_user = getenv('DB_USERNAME') ?: 'fixnow';
    $db_pass = getenv('DB_PASSWORD') ?: 'mysql';
    $db_name = getenv('DB_NAME') ?: 'fixnow';
    $db_port = getenv('DB_PORT') ? intval(getenv('DB_PORT')) : 3306;

    define('LOCALHOST', $db_host);
    define('DB_USERNAME', $db_user);
    define('DB_PASSWORD', $db_pass);
    define('DB_NAME', $db_name);
    define('DB_PORT', $db_port);
}

// Establish database connection if not already connected
if (!isset($conn) || !($conn instanceof mysqli)) {
    try {
        $conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME, defined('DB_PORT') ? DB_PORT : 3306);
    } catch (Exception $e) {
        $conn = false;
    }

    if (!$conn) {
        // On Vercel, if DB is not configured, show a helpful setup guide
        if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false)) {
            die("
            <div style='max-width:620px;margin:80px auto;padding:40px;background:#ffffff;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,0.1);font-family:-apple-system,BlinkMacSystemFont,sans-serif;border:1px solid #e2e8f0;'>
                <div style='display:inline-block;padding:8px 16px;background:#e0f2fe;color:#0284c7;border-radius:50px;font-size:13px;font-weight:700;margin-bottom:16px;'>FixNow Vercel Cloud Deployment</div>
                <h2 style='color:#0f172a;margin-top:0;font-size:24px;'>Connect Your Cloud MySQL Database</h2>
                <p style='color:#64748b;line-height:1.6;'>FixNow is deployed on Vercel. Because Vercel serverless functions run in the cloud, they require a remote cloud MySQL database (e.g. Aiven, PlanetScale, TiDB, Clever Cloud, or cPanel MySQL).</p>
                <p style='color:#0f172a;font-weight:700;margin-bottom:8px;'>Configure these Environment Variables in your Vercel Dashboard:</p>
                <div style='background:#f8fafc;padding:16px;border-radius:10px;font-family:monospace;font-size:13.5px;color:#0369a1;border:1px solid #e2e8f0;line-height:1.8;'>
                    <strong>DB_HOST</strong> = &lt;your-cloud-db-host&gt;<br>
                    <strong>DB_USERNAME</strong> = &lt;your-db-username&gt;<br>
                    <strong>DB_PASSWORD</strong> = &lt;your-db-password&gt;<br>
                    <strong>DB_NAME</strong> = fixnow<br>
                    <strong>DB_PORT</strong> = 3306
                </div>
                <p style='color:#94a3b8;font-size:12.5px;margin-top:14px;margin-bottom:0;'>Navigate to <strong>Project Settings &gt; Environment Variables</strong> in your Vercel Dashboard, add the variables above, and redeploy.</p>
            </div>
            ");
        }
        header('Location: ' . SITEURL . 'error.php?message=database_connection_failed');
        exit(); 
    }
}

// Include parameterized DB helper functions
require_once __DIR__ . '/db_helper.php';
