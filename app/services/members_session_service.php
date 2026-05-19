<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../repositories/auth_tokens_repository.php';
require_once __DIR__ . '/../helpers/token_helper.php';

function members_session_start_for_member(array $member): void
{
    $_SESSION['member'] = [
        'id' => (int) $member['id'],
        'username' => (string) $member['username'],
        'email' => (string) $member['email'],
        'is_verified' => (int) $member['is_verified'],
        'is_admin' => (int) ($member['is_admin'] ?? 0),
        'is_banned' => (int) $member['is_banned'],
    ];
}

function members_session_is_logged_in(): bool
{
    return isset($_SESSION['member']['id']) && (int) $_SESSION['member']['id'] > 0;
}

function members_session_current_member_id(): int
{
    return isset($_SESSION['member']['id']) ? (int) $_SESSION['member']['id'] : 0;
}

function members_session_issue_persistent_cookie(PDO $pdo, int $member_id): void
{
    $selector = token_helper_generate_selector();
    $validator = token_helper_generate_validator();
    $token_hash = token_helper_hash_validator($validator);
    $expires_at = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

    auth_tokens_repository_create($pdo, $member_id, $selector, $token_hash, $expires_at);

    $cookie_value = token_helper_build_url_token($selector, $validator);
    setcookie(
        'remember_member',
        $cookie_value,
        [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]
    );
}

function members_session_clear_persistent_cookie(): void
{
    setcookie(
        'remember_member',
        '',
        [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]
    );
}

function members_session_logout(PDO $pdo): void
{
    if (!empty($_COOKIE['remember_member'])) {
        $parsed = token_helper_parse_url_token((string) $_COOKIE['remember_member']);
        if ($parsed !== null) {
            auth_tokens_repository_delete_by_selector($pdo, $parsed['selector']);
        }
    }

    members_session_clear_persistent_cookie();
    unset($_SESSION['member']);
}

function members_session_try_auto_login(PDO $pdo): bool
{
    if (members_session_is_logged_in()) {
        return true;
    }

    if (empty($_COOKIE['remember_member'])) {
        return false;
    }

    $parsed = token_helper_parse_url_token((string) $_COOKIE['remember_member']);
    if ($parsed === null) {
        members_session_clear_persistent_cookie();
        return false;
    }

    $record = auth_tokens_repository_find_by_selector($pdo, $parsed['selector']);
    if ($record === null) {
        members_session_clear_persistent_cookie();
        return false;
    }

    if (strtotime((string) $record['expires_at']) < time()) {
        auth_tokens_repository_delete_by_selector($pdo, $parsed['selector']);
        members_session_clear_persistent_cookie();
        return false;
    }

    $expected = (string) $record['token_hash'];
    $actual = token_helper_hash_validator($parsed['validator']);
    if (!hash_equals($expected, $actual)) {
        auth_tokens_repository_delete_by_selector($pdo, $parsed['selector']);
        members_session_clear_persistent_cookie();
        return false;
    }

    $member = members_repository_find_by_id($pdo, (int) $record['member_id']);
    if ($member === null || (int) $member['is_banned'] === 1) {
        auth_tokens_repository_delete_by_selector($pdo, $parsed['selector']);
        members_session_clear_persistent_cookie();
        return false;
    }

    // Rotate token each successful auto-login.
    auth_tokens_repository_delete_by_selector($pdo, $parsed['selector']);
    members_session_issue_persistent_cookie($pdo, (int) $member['id']);
    members_session_start_for_member($member);
    return true;
}

