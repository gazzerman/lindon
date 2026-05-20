<?php
declare(strict_types=1);

session_start();

// Enforce HTTPS for all browser requests.
if (PHP_SAPI !== 'cli') {
    $https_on = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $server_port = (string) ($_SERVER['SERVER_PORT'] ?? '');
    $skip_https_enforcement = ($server_port === '81');
    if (!$https_on && !$skip_https_enforcement) {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: https://' . $host . $request_uri, true, 301);
        exit;
    }
}

// Basic bootstrap:
// - load .env values
// - create PDO connection to MySQL
// - expose $pdo to controllers
$project_root = dirname(__DIR__);

$pdo = null;

require_once __DIR__ . '/helpers/env.php';

try {
    require_once __DIR__ . '/config/db.php';
    $pdo = db_get_pdo();
} catch (Throwable $e) {
    // Don't expose details to users; dispatch will show a generic message.
    $pdo = null;
}