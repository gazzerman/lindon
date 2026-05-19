<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/view_renderer.php';
require_once __DIR__ . '/../middleware/csrf.php';
require_once __DIR__ . '/../middleware/xadmin_guard.php';
require_once __DIR__ . '/../repositories/members_repository.php';

function xadmin_route_controller(PDO $pdo, string $method, array $get, array $post): void
{
    xadmin_guard_require_admin($pdo);

    if ($method === 'POST') {
        require_once __DIR__ . '/xadmin_users_controller.php';
        xadmin_users_handle_post($pdo, $post);
        return;
    }

    $action = isset($get['action']) ? (string) $get['action'] : 'home';
    if ($action === 'users') {
        require_once __DIR__ . '/xadmin_users_controller.php';
        $dir_raw = isset($get['dir']) ? strtolower((string) $get['dir']) : 'desc';
        $created_dir = $dir_raw === 'asc' ? 'ASC' : 'DESC';
        $members = members_repository_list_all_for_admin($pdo, $created_dir);
        render('xadmin/users', [
            'page_title' => 'Users - Admin',
            'xadmin_action' => 'users',
            'breadcrumbs' => [
                ['label' => 'Admin', 'href' => 'index.php'],
                ['label' => 'Users', 'href' => null],
            ],
            'members' => $members,
            'csrf_token' => csrf_generate_token(),
            'users_sort_dir' => $dir_raw === 'asc' ? 'asc' : 'desc',
            'current_admin_member_id' => members_session_current_member_id(),
            'users_flash' => xadmin_users_flash_from_query($get),
        ]);
        return;
    }

    render('xadmin/dashboard', [
        'page_title' => 'Admin',
        'xadmin_action' => 'home',
        'breadcrumbs' => [
            ['label' => 'Admin', 'href' => null],
        ],
    ]);
}
