<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/xadmin_controller.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
xadmin_route_controller($pdo, $method, $_GET, $_POST);
