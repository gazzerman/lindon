<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/view_renderer.php';

function home_index_controller(PDO $pdo): void
{
    render('home/index', []);
}

