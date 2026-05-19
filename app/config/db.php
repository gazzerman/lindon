<?php
declare(strict_types=1);

function db_get_pdo(): PDO
{
    $host = env('DB_HOST', '127.0.0.1');
    $port = env('DB_PORT', '3306');
    $db_name = env('DB_NAME', 'hello');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');
    $charset = env('DB_CHARSET', 'utf8mb4');

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $db_name, $charset);

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    return new PDO($dsn, $user, $pass, $options);
}

