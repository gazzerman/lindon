<?php
declare(strict_types=1);

require_once __DIR__ . '/e.php';

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);

    $view_path = __DIR__ . '/../views/' . $view . '.php';
    if (!is_file($view_path)) {
        http_response_code(500);
        echo 'View not found';
        exit;
    }

    require $view_path;
}

