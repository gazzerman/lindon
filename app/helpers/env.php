<?php
declare(strict_types=1);

/**
 * Minimal .env loader for local development.
 *
 * Supported syntax:
 * - KEY=VALUE
 * - Lines starting with # are ignored
 * - Surrounding single/double quotes around VALUE are removed
 */
function env_path_is_absolute(string $path): bool
{
    if ($path === '') {
        return false;
    }

    if (str_starts_with($path, '/')) {
        return true;
    }

    if (
        preg_match('~^[A-Za-z]:~', $path) === 1
        && isset($path[2])
        && ($path[2] === '\\' || $path[2] === '/')
    ) {
        return true;
    }

    return false;
}

function env(string $key, ?string $default = null): ?string
{
    static $values = null;

    if ($values === null) {
        $project_root = dirname(__DIR__, 2);
        $project_name = basename($project_root);

        $configured_env_file = trim((string) (
            getenv('APP_ENV_FILE')
            ?: ($_SERVER['APP_ENV_FILE'] ?? $_ENV['APP_ENV_FILE'] ?? '')
        ));

        // Production-style layout: /var/www/<project> plus /var/www/secure/<project>/.env
        $www_sibling_secure = dirname($project_root)
            . DIRECTORY_SEPARATOR . 'secure'
            . DIRECTORY_SEPARATOR . $project_name
            . DIRECTORY_SEPARATOR . '.env';

        // Portable XAMPP layout: <xampp>/htdocs/<project> plus <xampp>/../secure/<project>/.env (often /var/secure/...)
        $xampp_sibling_secure = dirname(dirname($project_root))
            . DIRECTORY_SEPARATOR . 'secure'
            . DIRECTORY_SEPARATOR . $project_name
            . DIRECTORY_SEPARATOR . '.env';

        $legacy_windows_secure = 'C:/xampp/secure/' . $project_name . '/.env';

        // Prefer project /.env next so localhost can use repo-local settings even when a
        // sibling secure path still exists from production-style layout.
        $env_candidates = [
            $configured_env_file,
            $project_root . DIRECTORY_SEPARATOR . '.env',
            $www_sibling_secure,
            $xampp_sibling_secure,
            $legacy_windows_secure,
        ];

        $env_file = '';
        $values = [];

        foreach ($env_candidates as $candidate) {
            if (!is_string($candidate)) {
                continue;
            }

            $candidate = trim($candidate);
            if ($candidate === '') {
                continue;
            }

            if (!env_path_is_absolute($candidate)) {
                $candidate = $project_root . '/' . ltrim($candidate, '/\\');
            }

            if (!is_file($candidate)) {
                continue;
            }

            $env_file = $candidate;
            break;
        }

        if ($env_file !== '' && is_file($env_file)) {
            $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines !== false) {
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#')) {
                        continue;
                    }

                    $pos = strpos($line, '=');
                    if ($pos === false) {
                        continue;
                    }

                    $k = trim(substr($line, 0, $pos));
                    $v = trim(substr($line, $pos + 1));

                    // Remove surrounding quotes.
                    if (
                        (str_starts_with($v, '"') && str_ends_with($v, '"')) ||
                        (str_starts_with($v, "'") && str_ends_with($v, "'"))
                    ) {
                        $v = substr($v, 1, -1);
                    }

                    $values[$k] = $v;
                }
            }
        }
    }

    return array_key_exists($key, $values) ? $values[$key] : $default;
}
