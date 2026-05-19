<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/contact_repository.php';

function contact_submit_service(PDO $pdo, array $data): void
{
    contact_repository_insert($pdo, $data);
}

