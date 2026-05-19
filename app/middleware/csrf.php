<?php
declare(strict_types=1);

function csrf_generate_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_verify_or_fail(?string $token): bool
{
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }

    $session_token = (string) $_SESSION['csrf_token'];
    $token = (string) $token;

    if (!hash_equals($session_token, $token)) {
        return false;
    }

    // Rotate token after successful verification.
    unset($_SESSION['csrf_token']);
    return true;
}

