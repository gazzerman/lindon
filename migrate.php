<?php
declare(strict_types=1);

/**
 * One-time migration runner for local development.
 *
 * Usage:
 *   php migrate.php
 */

require_once __DIR__ . '/app/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "This script can only run in CLI mode.\n";
    exit(1);
}

if (!$pdo instanceof PDO) {
    echo "Database connection failed. Check your .env settings.\n";
    exit(1);
}

$sql_files = [
    __DIR__ . '/storage/sql/create_contact_table.sql',
    __DIR__ . '/storage/sql/create_members_tables.sql',
    __DIR__ . '/storage/sql/alter_members_add_profile_fields.sql',
    __DIR__ . '/storage/sql/alter_members_add_admin_phone.sql',
    __DIR__ . '/storage/sql/create_auth_tokens_tables.sql',
    __DIR__ . '/storage/sql/create_rate_limit_events_table.sql',
    __DIR__ . '/storage/sql/create_members_learning_tables.sql',
    __DIR__ . '/storage/sql/seed_members_learning_annual_family_meeting.sql',
];

foreach ($sql_files as $file) {
    if (!is_file($file)) {
        echo "Skipped (not found): {$file}\n";
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        echo "Failed to read: {$file}\n";
        exit(1);
    }

    $statements = migrate_split_sql_statements($sql);
    if ($statements === []) {
        echo "No SQL statements found in: {$file}\n";
        continue;
    }

    try {
        $pdo->beginTransaction();
        foreach ($statements as $statement) {
            $pdo->exec($statement);
        }
        $pdo->commit();
        echo "Applied: {$file}\n";
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "Migration failed for {$file}.\n";
        echo $e->getMessage() . "\n";
        exit(1);
    }
}

echo "Done.\n";

/**
 * Split SQL by semicolon while skipping comments/empty statements.
 */
function migrate_split_sql_statements(string $sql): array
{
    $lines = preg_split('/\R/', $sql);
    if ($lines === false) {
        return [];
    }

    $filtered = [];
    foreach ($lines as $line) {
        $trimmed = ltrim($line);
        if (str_starts_with($trimmed, '--')) {
            continue;
        }
        $filtered[] = $line;
    }

    $clean_sql = trim(implode("\n", $filtered));
    if ($clean_sql === '') {
        return [];
    }

    $parts = array_map('trim', explode(';', $clean_sql));
    $statements = [];
    foreach ($parts as $part) {
        if ($part !== '') {
            $statements[] = $part;
        }
    }

    return $statements;
}

