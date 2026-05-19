<?php
declare(strict_types=1);

function web_routes(): array
{
    return [
        'home' => [
            'script' => '/index.php',
            'methods' => ['GET'],
        ],
        'about' => [
            'script' => '/about.php',
            'methods' => ['GET'],
        ],
        'contact' => [
            'script' => '/contact.php',
            'methods' => ['GET', 'POST'],
        ],
        'members' => [
            'script' => '/members/index.php',
            'methods' => ['GET', 'POST'],
        ],
    ];
}

