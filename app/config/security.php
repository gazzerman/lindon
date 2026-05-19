<?php
declare(strict_types=1);

function security_is_localhost_request(): bool
{
    if (PHP_SAPI === 'cli') {
        return false;
    }

    $ip = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return true;
    }

    $raw = (string) ($_SERVER['HTTP_HOST'] ?? '');
    if ($raw === '') {
        return false;
    }

    if (str_starts_with($raw, '[')) {
        $end = strpos($raw, ']:');
        if ($end !== false) {
            $host = strtolower(substr($raw, 1, $end - 1));
        } else {
            $host = strtolower(trim($raw, '[]'));
        }
    } else {
        $host = strtolower((string) preg_replace('/:\d+$/', '', $raw));
    }

    return in_array($host, ['localhost', '127.0.0.1', '::1'], true);
}

function security_captcha_bypass(): bool
{
    $flag = trim((string) env('CAPTCHA_BYPASS', ''));
    if ($flag === '') {
        return security_is_localhost_request();
    }

    $f = strtolower($flag);
    if (in_array($f, ['0', 'false', 'no', 'off'], true)) {
        return false;
    }
    if (in_array($f, ['1', 'true', 'yes', 'on'], true)) {
        return true;
    }

    return security_is_localhost_request();
}

function security_config(): array
{
    $bypass = security_captcha_bypass();
    $provider = strtolower(trim((string) env('CAPTCHA_PROVIDER', 'turnstile')));
    if (!in_array($provider, ['turnstile', 'recaptcha'], true)) {
        $provider = 'turnstile';
    }

    $turnstile_site_key = trim((string) env('TURNSTILE_SITE_KEY', ''));
    $turnstile_secret_key = trim((string) env('TURNSTILE_SECRET_KEY', ''));
    $recaptcha_site_key = trim((string) env('RECAPTCHA_SITE_KEY', ''));
    $recaptcha_secret_key = trim((string) env('RECAPTCHA_SECRET_KEY', ''));

    return [
        'captcha_bypass' => $bypass,
        'captcha_provider' => $provider,
        'recaptcha_min_score' => (float) env('RECAPTCHA_MIN_SCORE', '0.5'),
        'turnstile_site_key' => $bypass ? '' : $turnstile_site_key,
        'turnstile_secret_key' => $bypass ? '' : $turnstile_secret_key,
        'recaptcha_site_key' => $bypass ? '' : $recaptcha_site_key,
        'recaptcha_secret_key' => $bypass ? '' : $recaptcha_secret_key,
        'rate_limit_window_seconds' => 900,
        'rate_limit_max_ip' => 5,
        'rate_limit_max_email' => 3,
    ];
}
