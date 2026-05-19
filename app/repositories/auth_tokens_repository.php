<?php
declare(strict_types=1);

function auth_tokens_repository_create(
    PDO $pdo,
    int $member_id,
    string $selector,
    string $token_hash,
    string $expires_at
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO auth_tokens (member_id, selector, token_hash, expires_at)
         VALUES (:member_id, :selector, :token_hash, :expires_at)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':selector', $selector);
    $stmt->bindValue(':token_hash', $token_hash);
    $stmt->bindValue(':expires_at', $expires_at);
    $stmt->execute();
}

function auth_tokens_repository_find_by_selector(PDO $pdo, string $selector): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM auth_tokens WHERE selector = :selector LIMIT 1');
    $stmt->bindValue(':selector', $selector);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function auth_tokens_repository_delete_by_selector(PDO $pdo, string $selector): void
{
    $stmt = $pdo->prepare('DELETE FROM auth_tokens WHERE selector = :selector');
    $stmt->bindValue(':selector', $selector);
    $stmt->execute();
}

function auth_tokens_repository_delete_by_member_id(PDO $pdo, int $member_id): void
{
    $stmt = $pdo->prepare('DELETE FROM auth_tokens WHERE member_id = :member_id');
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

