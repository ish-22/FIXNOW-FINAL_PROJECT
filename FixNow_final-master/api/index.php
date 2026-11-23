<?php
/**
 * FIXNOW - Vercel Serverless Front Controller / Router
 */

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$parsed = parse_url($request_uri);
$path = ltrim($parsed['path'] ?? '', '/');

if ($path === '' || $path === '/') {
    $path = 'index.php';
}

$root_dir = realpath(__DIR__ . '/..');
$target_file = realpath($root_dir . '/' . $path);

// If target file exists directly (e.g. services.php, contact.php, booking.php)
if ($target_file && strpos($target_file, $root_dir) === 0 && is_file($target_file)) {
    $ext = pathinfo($target_file, PATHINFO_EXTENSION);
    if ($ext === 'php') {
        chdir(dirname($target_file));
        require $target_file;
        exit();
    } else {
        $mimes = [
            'css'   => 'text/css',
            'js'    => 'application/javascript',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'png'   => 'image/png',
            'gif'   => 'image/gif',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'json'  => 'application/json',
        ];
        $contentType = $mimes[$ext] ?? 'application/octet-stream';
        header("Content-Type: " . $contentType);
        readfile($target_file);
        exit();
    }
}

// Try appending .php if clean URL without extension (e.g. /services -> /services.php)
$target_php = realpath($root_dir . '/' . $path . '.php');
if ($target_php && strpos($target_php, $root_dir) === 0 && is_file($target_php)) {
    chdir(dirname($target_php));
    require $target_php;
    exit();
}

// If subfolder like /admin or /service-vendor, route to their index.php
$target_sub_index = realpath($root_dir . '/' . $path . '/index.php');
if ($target_sub_index && strpos($target_sub_index, $root_dir) === 0 && is_file($target_sub_index)) {
    chdir(dirname($target_sub_index));
    require $target_sub_index;
    exit();
}

// 404 Handler
http_response_code(404);
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>404 Not Found | FIXNOW</title><style>body{font-family:sans-serif;background:#f8fafc;color:#0f172a;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;}.box{background:#fff;padding:40px;border-radius:16px;border:1px solid #e2e8f0;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.05);max-width:480px;}a{display:inline-block;margin-top:20px;background:#0284c7;color:#fff;text-decoration:none;padding:10px 24px;border-radius:50px;font-weight:700;}</style></head><body><div class='box'><h1>404</h1><p>The requested page was not found on FIXNOW.</p><a href='/'>Return to Home</a></div></body></html>";
exit();
