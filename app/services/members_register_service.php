<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../repositories/email_verifications_repository.php';
require_once __DIR__ . '/../helpers/token_helper.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/username_policy.php';
require_once __DIR__ . '/../helpers/mailer.php';

function members_register_service_register(PDO $pdo, string $username, string $email, string $password): array
{
    $username = trim($username);
    $email = trim(strtolower($email));

    $errors = [];

    if (!validate_members_username($username)) {
        $errors['username'] = 'Username must be 3-30 chars and use only letters, numbers, underscore.';
    } elseif (username_policy_is_reserved($username)) {
        $errors['username'] = 'That username is not allowed.';
    } elseif (members_repository_username_exists($pdo, $username)) {
        $errors['username'] = 'Username already exists.';
    }

    if (!validate_members_email($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (members_repository_email_exists($pdo, $email)) {
        $errors['email'] = 'Email already exists.';
    }

    if (!validate_members_password($password)) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($errors !== []) {
        return ['ok' => false, 'errors' => $errors];
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $member_id = members_repository_create($pdo, $username, $email, $password_hash);

    $selector = token_helper_generate_selector();
    $validator = token_helper_generate_validator();
    $token_hash = token_helper_hash_validator($validator);
    $expires_at = date('Y-m-d H:i:s', time() + 86400);

    email_verifications_repository_delete_active_by_member_id($pdo, $member_id);
    email_verifications_repository_create($pdo, $member_id, $selector, $token_hash, $expires_at);

    $raw_token = token_helper_build_url_token($selector, $validator);
    members_mailer_send_verification_email($email, $username, $raw_token);

    return ['ok' => true, 'member_id' => $member_id];
}

