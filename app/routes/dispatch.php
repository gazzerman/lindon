<?php
declare(strict_types=1);

require_once __DIR__ . '/web.php';

$routes = web_routes();
if (!isset($route_key) || !is_string($route_key) || !isset($routes[$route_key])) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (!in_array($method, $routes[$route_key]['methods'], true)) {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

if ($pdo === null) {
    // If DB isn't configured yet, fail fast with a safe error message.
    http_response_code(500);
    echo 'Database not configured';
    exit;
}

switch ($route_key) {
    case 'home':
        if ($method !== 'GET') {
            http_response_code(405);
            echo 'Method Not Allowed';
            exit;
        }
        require_once __DIR__ . '/../controllers/home_controller.php';
        home_index_controller($pdo);
        return;

    case 'about':
        if ($method !== 'GET') {
            http_response_code(405);
            echo 'Method Not Allowed';
            exit;
        }
        require_once __DIR__ . '/../controllers/about_controller.php';
        about_index_controller($pdo);
        return;

    case 'contact':
        require_once __DIR__ . '/../controllers/contact_controller.php';
        if ($method === 'POST') {
            contact_submit_controller($pdo, $_POST);
            return;
        }
        contact_index_controller($pdo);
        return;

    case 'members':
        require_once __DIR__ . '/../controllers/members_controller.php';
        members_route_controller($pdo, $method, $_GET, $_POST);
        return;

    default:
        http_response_code(404);
        echo 'Not Found';
        exit;
}

