<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/members_session_service.php';

function members_guard_require_login(): void
{
    if (!members_session_is_logged_in()) {
        header('Location: index.php?action=login');
        exit;
    }
}

