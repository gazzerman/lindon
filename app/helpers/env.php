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
function env(string $key, ?string $default = null): ?string
{
    static $values = null;

    if ($values === null) {
        $project_root = dirname(__DIR__, 2);
        $project_name = basename($project_root);
        $secure_env_file = 'C:/xampp/secure/' . $project_name . '/.env';
        $local_env_file = $project_root . '/.env';
        $values = [];
        $env_file = is_file($secure_env_file) ? $secure_env_file : $local_env_file;

        if (is_file($env_file)) {
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

