<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../repositories/email_verifications_repository.php';
require_once __DIR__ . '/../helpers/token_helper.php';
require_once __DIR__ . '/../helpers/mailer.php';

function members_verification_service_verify(PDO $pdo, string $raw_token): array
{
    $parsed = token_helper_parse_url_token($raw_token);
    if ($parsed === null) {
        return ['ok' => false, 'message' => 'Invalid verification token.'];
    }

    $record = email_verifications_repository_find_active_by_selector($pdo, $parsed['selector']);
    if ($record === null) {
        return ['ok' => false, 'message' => 'Verification token is invalid or expired.'];
    }

    $expected = (string) $record['token_hash'];
    $actual = token_helper_hash_validator($parsed['validator']);
    if (!hash_equals($expected, $actual)) {
        return ['ok' => false, 'message' => 'Verification token is invalid.'];
    }

    $member_id = (int) $record['member_id'];
    members_repository_mark_verified($pdo, $member_id);
    email_verifications_repository_mark_used($pdo, (int) $record['id']);

    return ['ok' => true, 'member_id' => $member_id];
}

function members_verification_service_resend(PDO $pdo, int $member_id): array
{
    $member = members_repository_find_by_id($pdo, $member_id);
    if ($member === null) {
        return ['ok' => false, 'message' => 'Account not found.'];
    }

    if ((int) $member['is_verified'] === 1) {
        return ['ok' => false, 'message' => 'Account is already verified.'];
    }

    $selector = token_helper_generate_selector();
    $validator = token_helper_generate_validator();
    $token_hash = token_helper_hash_validator($validator);
    $expires_at = date('Y-m-d H:i:s', time() + 86400);

    email_verifications_repository_delete_active_by_member_id($pdo, (int) $member['id']);
    email_verifications_repository_create($pdo, (int) $member['id'], $selector, $token_hash, $expires_at);

    $raw_token = token_helper_build_url_token($selector, $validator);
    members_mailer_send_verification_email((string) $member['email'], (string) $member['username'], $raw_token);

    return ['ok' => true];
}

