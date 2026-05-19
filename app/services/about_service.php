<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/contact_repository.php';

function about_index_service(PDO $pdo): array
{
    $contact_first_name = contact_repository_fetch_first_name($pdo);

    return [
        'contact_first_name' => $contact_first_name,
    ];
}

