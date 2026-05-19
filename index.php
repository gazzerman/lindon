<?php
declare(strict_types=1);

$route_key = 'home';

require_once __DIR__ . '/app/bootstrap.php';

$parsed = parse_url($_SERVER['REQUEST_URI'] ?? '/');
$path = $parsed['path'] ?? '/';
if (str_ends_with($path, '/index.php')) {
    $dir = dirname($path);
    $new_path = ($dir === '/' || $dir === '\\' || $dir === '.')
        ? '/'
        : str_replace('\\', '/', $dir) . '/';
    $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $https ? 'https' : 'http';
    header('Location: ' . $scheme . '://' . $host . $new_path . $query, true, 301);
    exit;
}

require_once __DIR__ . '/app/routes/dispatch.php';

