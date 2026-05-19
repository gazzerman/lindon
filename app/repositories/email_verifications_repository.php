<?php
declare(strict_types=1);

function email_verifications_repository_create(
    PDO $pdo,
    int $member_id,
    string $selector,
    string $token_hash,
    string $expires_at
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO email_verifications (member_id, selector, token_hash, expires_at)
         VALUES (:member_id, :selector, :token_hash, :expires_at)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':selector', $selector);
    $stmt->bindValue(':token_hash', $token_hash);
    $stmt->bindValue(':expires_at', $expires_at);
    $stmt->execute();
}

function email_verifications_repository_find_active_by_selector(PDO $pdo, string $selector): ?array
{
    $stmt = $pdo->prepare(
        'SELECT * FROM email_verifications
         WHERE selector = :selector
           AND used_at IS NULL
           AND expires_at > NOW()
         LIMIT 1'
    );
    $stmt->bindValue(':selector', $selector);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function email_verifications_repository_mark_used(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('UPDATE email_verifications SET used_at = NOW() WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function email_verifications_repository_delete_active_by_member_id(PDO $pdo, int $member_id): void
{
    $stmt = $pdo->prepare(
        'DELETE FROM email_verifications WHERE member_id = :member_id AND used_at IS NULL'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

