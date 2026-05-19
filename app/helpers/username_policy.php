<?php
declare(strict_types=1);

function username_policy_reserved_list(): array
{
    return [
        'admin',
        'administrator',
        'administration',
    ];
}

function username_policy_is_reserved(string $username): bool
{
    $username = strtolower(trim($username));
    return in_array($username, username_policy_reserved_list(), true);
}

