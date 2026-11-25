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
    $db_host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?: (getenv('LOCALHOST') ?: 'localhost');
    $db_user = $_ENV['DB_USERNAME'] ?? $_SERVER['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'fixnow';
    $db_pass = $_ENV['DB_PASSWORD'] ?? $_SERVER['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: 'mysql';
    $db_name = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?: 'fixnow';
    $raw_port = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? getenv('DB_PORT');
    $db_port = (!empty($raw_port)) ? intval($raw_port) : 3306;

    define('LOCALHOST', $db_host);
    define('DB_USERNAME', $db_user);
    define('DB_PASSWORD', $db_pass);
    define('DB_NAME', $db_name);
    define('DB_PORT', $db_port);
}

// Establish database connection if not already connected
if (!isset($conn) || !($conn instanceof mysqli)) {
    $db_conn_error = '';
    try {
        if (LOCALHOST === 'localhost' || LOCALHOST === '127.0.0.1') {
            $conn = @mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME, defined('DB_PORT') ? DB_PORT : 3306);
            if (!$conn) {
                $db_conn_error = mysqli_connect_error();
            }
        } else {
            // Cloud MySQL / TiDB requires SSL connection
            $conn = mysqli_init();
            if ($conn) {
                if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
                    $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
                }
                if (defined('MYSQLI_OPT_CONNECT_TIMEOUT')) {
                    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
                }
                $ssl_flag = defined('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT') ? MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT : (defined('MYSQLI_CLIENT_SSL') ? MYSQLI_CLIENT_SSL : 0);
                $connected = @$conn->real_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME, defined('DB_PORT') ? DB_PORT : 3306, NULL, $ssl_flag);
                if (!$connected) {
                    $db_conn_error = $conn->connect_error ?: mysqli_connect_error();
                    // Fallback to standard connect without SSL flag if provider handles it transparently
                    $conn = @mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME, defined('DB_PORT') ? DB_PORT : 3306);
                    if (!$conn) {
                        $db_conn_error = mysqli_connect_error() ?: $db_conn_error;
                    }
                }
            }
        }
    } catch (\Throwable $e) {
        $conn = false;
        $db_conn_error = $e->getMessage();
    }

    if (!$conn) {
        // On Vercel, if DB is not configured, show a helpful setup guide with current status
        if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false)) {
            $safe_host = htmlspecialchars(LOCALHOST);
            $safe_user = htmlspecialchars(DB_USERNAME);
            $safe_port = htmlspecialchars(strval(DB_PORT));
            $safe_name = htmlspecialchars(DB_NAME);
            $safe_err  = htmlspecialchars($db_conn_error ?: 'Could not establish connection to the specified database host.');

            die("
            <div style='max-width:680px;margin:60px auto;padding:40px;background:#ffffff;border-radius:18px;box-shadow:0 12px 36px rgba(0,0,0,0.08);font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif;border:1px solid #e2e8f0;'>
                <div style='display:inline-block;padding:6px 14px;background:#fee2e2;color:#dc2626;border-radius:50px;font-size:12.5px;font-weight:700;margin-bottom:14px;'>Database Connection Error</div>
                <h2 style='color:#0f172a;margin-top:0;font-size:24px;margin-bottom:8px;'>Connect Your Cloud MySQL Database</h2>
                <p style='color:#64748b;font-size:14px;line-height:1.6;margin-bottom:20px;'>FixNow is running on Vercel. Because Vercel serverless functions run in the cloud, they cannot connect to <code>localhost</code> and require your cloud MySQL credentials (e.g. TiDB Cloud, Aiven, or Railway).</p>
                
                <div style='background:#fef2f2;border:1px solid #fecaca;padding:14px 16px;border-radius:10px;margin-bottom:20px;font-size:13px;color:#991b1b;word-break:break-all;'>
                    <strong>Current Attempt:</strong> Host: <code>{$safe_host}:{$safe_port}</code> | User: <code>{$safe_user}</code> | DB: <code>{$safe_name}</code><br>
                    <strong>Error:</strong> {$safe_err}
                </div>

                <div style='background:#f8fafc;padding:18px;border-radius:12px;font-family:monospace;font-size:13px;color:#0369a1;border:1px solid #e2e8f0;line-height:1.9;'>
                    <strong style='color:#0f172a;'>Required Vercel Environment Variables:</strong><br>
                    <strong>DB_HOST</strong> = <em>your-cluster-host (e.g. gateway01.us-west-2.prod.aws.tidbcloud.com)</em><br>
                    <strong>DB_PORT</strong> = <em>4000 (for TiDB) or 3306</em><br>
                    <strong>DB_USERNAME</strong> = <em>your-cluster-username (e.g. 3PPpaaGaoG3WpuE.root)</em><br>
                    <strong>DB_PASSWORD</strong> = <em>your-cluster-password</em><br>
                    <strong>DB_NAME</strong> = fixnow
                </div>

                <p style='color:#64748b;font-size:13px;margin-top:18px;line-height:1.6;margin-bottom:0;'>
                    👉 In your Vercel Dashboard, go to <strong>Settings &gt; Environment Variables</strong>, add or update the 5 keys above, then go to the <strong>Deployments</strong> tab, click the <strong>...</strong> menu on the latest deployment, and click <strong>Redeploy</strong>.
                </p>
            </div>
            ");
        }
        header('Location: ' . SITEURL . 'error.php?message=database_connection_failed');
        exit(); 
    }
}

// Include parameterized DB helper functions
require_once __DIR__ . '/db_helper.php';
