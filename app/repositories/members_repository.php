<?php
declare(strict_types=1);

function members_repository_find_by_email(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM members WHERE email = :email LIMIT 1');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function members_repository_find_by_username(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM members WHERE username = :username LIMIT 1');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function members_repository_find_by_login_identifier(PDO $pdo, string $identifier): ?array
{
    $stmt = $pdo->prepare(
        'SELECT * FROM members WHERE email = :email_identifier OR username = :username_identifier LIMIT 1'
    );
    $stmt->bindValue(':email_identifier', $identifier);
    $stmt->bindValue(':username_identifier', $identifier);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function members_repository_find_by_id(PDO $pdo, int $member_id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM members WHERE id = :id LIMIT 1');
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function members_repository_email_exists(PDO $pdo, string $email): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM members WHERE email = :email');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    return (int) $stmt->fetchColumn() > 0;
}

function members_repository_username_exists(PDO $pdo, string $username): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM members WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    return (int) $stmt->fetchColumn() > 0;
}

function members_repository_create(PDO $pdo, string $username, string $email, string $password_hash): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO members (username, email, password_hash, is_verified, is_banned)
         VALUES (:username, :email, :password_hash, 0, 0)'
    );
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password_hash', $password_hash);
    $stmt->execute();

    return (int) $pdo->lastInsertId();
}

function members_repository_mark_verified(PDO $pdo, int $member_id): void
{
    $stmt = $pdo->prepare('UPDATE members SET is_verified = 1 WHERE id = :id');
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

function members_repository_update_password_hash(PDO $pdo, int $member_id, string $password_hash): void
{
    $stmt = $pdo->prepare('UPDATE members SET password_hash = :password_hash WHERE id = :id');
    $stmt->bindValue(':password_hash', $password_hash);
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

function members_repository_update_profile(
    PDO $pdo,
    int $member_id,
    string $first_name,
    string $last_name,
    string $phone,
    string $date_of_birth
): void {
    $stmt = $pdo->prepare(
        'UPDATE members
         SET first_name = :first_name,
             last_name = :last_name,
             phone = :phone,
             date_of_birth = :date_of_birth
         WHERE id = :id'
    );
    $stmt->bindValue(':first_name', $first_name);
    $stmt->bindValue(':last_name', $last_name);
    $stmt->bindValue(':phone', $phone === '' ? null : $phone, $phone === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':date_of_birth', $date_of_birth);
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

function members_repository_list_all_for_admin(PDO $pdo, string $created_dir = 'DESC'): array
{
    $dir = strtoupper($created_dir) === 'ASC' ? 'ASC' : 'DESC';
    $stmt = $pdo->query(
        'SELECT id, username, email, first_name, last_name, phone, is_verified, is_admin, is_banned, created_at
         FROM members
         ORDER BY created_at ' . $dir . ', id ' . $dir
    );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return is_array($rows) ? $rows : [];
}

function members_repository_set_is_verified(PDO $pdo, int $member_id, bool $new_on): void
{
    $stmt = $pdo->prepare('UPDATE members SET is_verified = :value WHERE id = :id');
    $stmt->bindValue(':value', $new_on ? 1 : 0, PDO::PARAM_INT);
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

function members_repository_set_is_admin(PDO $pdo, int $member_id, bool $new_on): void
{
    $stmt = $pdo->prepare('UPDATE members SET is_admin = :value WHERE id = :id');
    $stmt->bindValue(':value', $new_on ? 1 : 0, PDO::PARAM_INT);
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

function members_repository_set_is_banned(PDO $pdo, int $member_id, bool $new_on): void
{
    $stmt = $pdo->prepare('UPDATE members SET is_banned = :value WHERE id = :id');
    $stmt->bindValue(':value', $new_on ? 1 : 0, PDO::PARAM_INT);
    $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
}

