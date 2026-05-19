<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/about_service.php';
require_once __DIR__ . '/../helpers/view_renderer.php';

function about_index_controller(PDO $pdo): void
{
    $data = about_index_service($pdo);

    // No extra logic; keep views dumb.
    $data['contact_first_name'] = $data['contact_first_name'] ?? null;

    render('about/index', $data);
}

