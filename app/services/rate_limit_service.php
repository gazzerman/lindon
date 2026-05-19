<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../repositories/rate_limit_events_repository.php';

function rate_limit_service_client_ip(): string
{
    $ip = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));

    return $ip === '' ? 'unknown' : $ip;
}

function rate_limit_service_normalize_email(string $email): string
{
    return strtolower(trim($email));
}

function rate_limit_service_hash_value(string $value): string
{
    return hash('sha256', $value);
}

/**
 * @return array{ok:bool, code:string}
 */
function rate_limit_service_enforce(PDO $pdo, string $action_key, string $email): array
{
    $config = security_config();
    $window_seconds = (int) $config['rate_limit_window_seconds'];
    $max_ip = (int) $config['rate_limit_max_ip'];
    $max_email = (int) $config['rate_limit_max_email'];
    $cutoff = date('Y-m-d H:i:s', time() - $window_seconds);

    $ip_hash = rate_limit_service_hash_value(rate_limit_service_client_ip());
    $email_hash = rate_limit_service_hash_value(rate_limit_service_normalize_email($email));

    $ip_count = rate_limit_events_repository_count_recent_by_ip($pdo, $action_key, $ip_hash, $cutoff);
    if ($ip_count >= $max_ip) {
        return ['ok' => false, 'code' => 'rate_limited_ip'];
    }

    $email_count = rate_limit_events_repository_count_recent_by_email($pdo, $action_key, $email_hash, $cutoff);
    if ($email_count >= $max_email) {
        return ['ok' => false, 'code' => 'rate_limited_email'];
    }

    rate_limit_events_repository_insert($pdo, $action_key, $ip_hash, $email_hash);

    return ['ok' => true, 'code' => 'ok'];
}
