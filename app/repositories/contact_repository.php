<?php
declare(strict_types=1);

function contact_repository_insert(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO contact (first_name, email, query) VALUES (:first_name, :email, :query)'
    );

    $stmt->bindValue(':first_name', $data['first_name']);
    $stmt->bindValue(':email', $data['email']);
    $stmt->bindValue(':query', $data['query']);

    $stmt->execute();
}

function contact_repository_fetch_first_name(PDO $pdo): ?string
{
    $stmt = $pdo->prepare('SELECT first_name FROM contact ORDER BY id ASC LIMIT 1');
    $stmt->execute();

    $name = $stmt->fetchColumn();
    if ($name === false || $name === null) {
        return null;
    }

    $trimmed = trim((string) $name);
    return $trimmed === '' ? null : $trimmed;
}

