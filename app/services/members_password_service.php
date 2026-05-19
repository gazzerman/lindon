<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../repositories/password_resets_repository.php';
require_once __DIR__ . '/../helpers/token_helper.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/mailer.php';

function members_password_service_request_reset(PDO $pdo, string $email): array
{
    $email = trim(strtolower($email));
    if (!validate_members_email($email)) {
        return ['ok' => true];
    }

    $member = members_repository_find_by_email($pdo, $email);
    if ($member === null) {
        return ['ok' => true];
    }

    $selector = token_helper_generate_selector();
    $validator = token_helper_generate_validator();
    $token_hash = token_helper_hash_validator($validator);
    $expires_at = date('Y-m-d H:i:s', time() + 3600);

    password_resets_repository_delete_active_by_member_id($pdo, (int) $member['id']);
    password_resets_repository_create($pdo, (int) $member['id'], $selector, $token_hash, $expires_at);

    $raw_token = token_helper_build_url_token($selector, $validator);
    members_mailer_send_password_reset_email((string) $member['email'], (string) $member['username'], $raw_token);

    return ['ok' => true];
}

function members_password_service_reset_password(PDO $pdo, string $raw_token, string $new_password): array
{
    if (!validate_members_password($new_password)) {
        return ['ok' => false, 'message' => 'Password must be at least 8 characters.'];
    }

    $parsed = token_helper_parse_url_token($raw_token);
    if ($parsed === null) {
        return ['ok' => false, 'message' => 'Invalid reset token.'];
    }

    $record = password_resets_repository_find_active_by_selector($pdo, $parsed['selector']);
    if ($record === null) {
        return ['ok' => false, 'message' => 'Reset token is invalid or expired.'];
    }

    $expected = (string) $record['token_hash'];
    $actual = token_helper_hash_validator($parsed['validator']);
    if (!hash_equals($expected, $actual)) {
        return ['ok' => false, 'message' => 'Reset token is invalid.'];
    }

    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    members_repository_update_password_hash($pdo, (int) $record['member_id'], $password_hash);
    password_resets_repository_mark_used($pdo, (int) $record['id']);

    return ['ok' => true];
}

