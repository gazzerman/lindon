<?php
declare(strict_types=1);

function mail_config(): array
{
    return [
        'app_url' => rtrim((string) env('APP_URL', 'https://localhost/helloworldx/members'), '/'),
        'mode' => strtolower((string) env('MAIL_MODE', 'log')),
        'smtp_host' => (string) env('SMTP_HOST', ''),
        'smtp_port' => (int) env('SMTP_PORT', '587'),
        'smtp_user' => (string) env('SMTP_USER', ''),
        'smtp_pass' => (string) env('SMTP_PASS', ''),
        'from_address' => (string) env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'from_name' => (string) env('MAIL_FROM_NAME', 'My Sample Site'),
    ];
}

