<?php
declare(strict_types=1);

function rate_limit_events_repository_count_recent_by_ip(PDO $pdo, string $action_key, string $ip_hash, string $cutoff): int
{
    $sql = 'SELECT COUNT(*) FROM rate_limit_events
            WHERE action_key = :action_key
              AND ip_hash = :ip_hash
              AND created_at >= :cutoff';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'action_key' => $action_key,
        'ip_hash' => $ip_hash,
        'cutoff' => $cutoff,
    ]);

    return (int) $stmt->fetchColumn();
}

function rate_limit_events_repository_count_recent_by_email(PDO $pdo, string $action_key, string $email_hash, string $cutoff): int
{
    $sql = 'SELECT COUNT(*) FROM rate_limit_events
            WHERE action_key = :action_key
              AND email_hash = :email_hash
              AND created_at >= :cutoff';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'action_key' => $action_key,
        'email_hash' => $email_hash,
        'cutoff' => $cutoff,
    ]);

    return (int) $stmt->fetchColumn();
}

function rate_limit_events_repository_insert(PDO $pdo, string $action_key, string $ip_hash, string $email_hash): void
{
    $sql = 'INSERT INTO rate_limit_events (action_key, ip_hash, email_hash, created_at)
            VALUES (:action_key, :ip_hash, :email_hash, NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'action_key' => $action_key,
        'ip_hash' => $ip_hash,
        'email_hash' => $email_hash,
    ]);
}
