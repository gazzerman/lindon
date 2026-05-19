<?php
declare(strict_types=1);

function token_helper_generate_selector(): string
{
    return bin2hex(random_bytes(12));
}

function token_helper_generate_validator(): string
{
    return bin2hex(random_bytes(32));
}

function token_helper_hash_validator(string $validator): string
{
    return hash('sha256', $validator);
}

function token_helper_build_url_token(string $selector, string $validator): string
{
    return $selector . ':' . $validator;
}

function token_helper_parse_url_token(string $raw_token): ?array
{
    $parts = explode(':', $raw_token, 2);
    if (count($parts) !== 2) {
        return null;
    }

    [$selector, $validator] = $parts;
    if ($selector === '' || $validator === '') {
        return null;
    }

    return [
        'selector' => $selector,
        'validator' => $validator,
    ];
}

