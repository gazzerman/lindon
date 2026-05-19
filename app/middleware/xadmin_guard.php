<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/members_session_service.php';
require_once __DIR__ . '/../repositories/members_repository.php';

function xadmin_guard_require_admin(PDO $pdo): void
{
    members_session_try_auto_login($pdo);
    if (!members_session_is_logged_in()) {
        header('Location: /helloworldx%20-%20Final/members/index.php?action=login');
        exit;
    }

    $member_id = members_session_current_member_id();
    $member = members_repository_find_by_id($pdo, $member_id);
    if ($member === null || (int) ($member['is_admin'] ?? 0) !== 1) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}
