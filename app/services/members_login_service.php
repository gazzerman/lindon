<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/members_session_service.php';

function members_login_service_throttle_state(): array
{
    $state = $_SESSION['login_throttle'] ?? [
        'fail_count' => 0,
        'locked_until' => 0,
    ];

    if ((int) $state['locked_until'] > 0 && time() >= (int) $state['locked_until']) {
        $state = ['fail_count' => 0, 'locked_until' => 0];
        $_SESSION['login_throttle'] = $state;
    }

    return $state;
}

function members_login_service_record_failed_attempt(): void
{
    $state = members_login_service_throttle_state();
    $state['fail_count'] = (int) $state['fail_count'] + 1;

    if ((int) $state['fail_count'] >= 5) {
        $state['locked_until'] = time() + 300;
    }

    $_SESSION['login_throttle'] = $state;
}

function members_login_service_reset_attempts(): void
{
    $_SESSION['login_throttle'] = [
        'fail_count' => 0,
        'locked_until' => 0,
    ];
}

function members_login_service_login(PDO $pdo, string $identifier, string $password, bool $remember): array
{
    $state = members_login_service_throttle_state();
    if ((int) $state['locked_until'] > time()) {
        return [
            'ok' => false,
            'code' => 'throttled',
            'message' => 'Too many attempts. Try again in a few minutes.',
            'retry_after' => (int) $state['locked_until'] - time(),
        ];
    }

    $member = members_repository_find_by_login_identifier($pdo, $identifier);
    if ($member === null || !password_verify($password, (string) $member['password_hash'])) {
        members_login_service_record_failed_attempt();
        return [
            'ok' => false,
            'code' => 'invalid_credentials',
            'message' => 'Invalid login credentials.',
        ];
    }

    if ((int) $member['is_banned'] === 1) {
        return [
            'ok' => false,
            'code' => 'banned',
            'message' => 'This account is banned.',
            'member_id' => (int) $member['id'],
        ];
    }

    if ((int) $member['is_verified'] !== 1) {
        return [
            'ok' => false,
            'code' => 'unverified',
            'message' => 'Please verify your account before logging in.',
            'member_id' => (int) $member['id'],
        ];
    }

    members_login_service_reset_attempts();
    members_session_start_for_member($member);
    if ($remember) {
        members_session_issue_persistent_cookie($pdo, (int) $member['id']);
    }

    return [
        'ok' => true,
        'code' => 'logged_in',
        'member_id' => (int) $member['id'],
    ];
}

