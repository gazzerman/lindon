<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/turnstile_service.php';
require_once __DIR__ . '/recaptcha_service.php';

/**
 * @return array{
 *   captcha_provider:string,
 *   captcha_bypass:bool,
 *   turnstile_site_key:string,
 *   recaptcha_site_key:string
 * }
 */
function captcha_service_public_config(): array
{
    $security = security_config();

    return [
        'captcha_provider' => (string) $security['captcha_provider'],
        'captcha_bypass' => (bool) $security['captcha_bypass'],
        'turnstile_site_key' => (string) $security['turnstile_site_key'],
        'recaptcha_site_key' => (string) $security['recaptcha_site_key'],
    ];
}

/**
 * @return array{ok:bool, code:string, provider:string}
 */
function captcha_service_verify_submission(array $input, ?string $remote_ip, string $action): array
{
    if (security_captcha_bypass()) {
        return ['ok' => true, 'code' => 'bypass', 'provider' => 'bypass'];
    }

    $security = security_config();
    $preferred = (string) $security['captcha_provider'];
    $has_turnstile = turnstile_service_is_enabled();
    $has_recaptcha = recaptcha_service_is_enabled();

    if ($preferred === 'recaptcha') {
        return captcha_service_verify_recaptcha_first($input, $remote_ip, $action, $has_turnstile);
    }

    return captcha_service_verify_turnstile_first($input, $remote_ip, $action, $has_recaptcha);
}

/**
 * @return array{ok:bool, code:string, provider:string}
 */
function captcha_service_verify_turnstile_first(array $input, ?string $remote_ip, string $action, bool $allow_recaptcha_fallback): array
{
    $turnstile = turnstile_service_verify_response(
        isset($input['cf-turnstile-response']) ? (string) $input['cf-turnstile-response'] : null,
        $remote_ip
    );
    if ($turnstile['ok']) {
        return ['ok' => true, 'code' => 'ok', 'provider' => 'turnstile'];
    }

    if (!$allow_recaptcha_fallback || !captcha_service_should_fallback($turnstile['code'])) {
        return ['ok' => false, 'code' => $turnstile['code'], 'provider' => 'turnstile'];
    }

    $recaptcha = recaptcha_service_verify_response(
        isset($input['g-recaptcha-response']) ? (string) $input['g-recaptcha-response'] : null,
        $remote_ip,
        $action
    );
    if ($recaptcha['ok']) {
        return ['ok' => true, 'code' => 'ok', 'provider' => 'recaptcha'];
    }

    return ['ok' => false, 'code' => $recaptcha['code'], 'provider' => 'recaptcha'];
}

/**
 * @return array{ok:bool, code:string, provider:string}
 */
function captcha_service_verify_recaptcha_first(array $input, ?string $remote_ip, string $action, bool $allow_turnstile_fallback): array
{
    $recaptcha = recaptcha_service_verify_response(
        isset($input['g-recaptcha-response']) ? (string) $input['g-recaptcha-response'] : null,
        $remote_ip,
        $action
    );
    if ($recaptcha['ok']) {
        return ['ok' => true, 'code' => 'ok', 'provider' => 'recaptcha'];
    }

    if (!$allow_turnstile_fallback || !captcha_service_should_fallback($recaptcha['code'])) {
        return ['ok' => false, 'code' => $recaptcha['code'], 'provider' => 'recaptcha'];
    }

    $turnstile = turnstile_service_verify_response(
        isset($input['cf-turnstile-response']) ? (string) $input['cf-turnstile-response'] : null,
        $remote_ip
    );
    if ($turnstile['ok']) {
        return ['ok' => true, 'code' => 'ok', 'provider' => 'turnstile'];
    }

    return ['ok' => false, 'code' => $turnstile['code'], 'provider' => 'turnstile'];
}

function captcha_service_should_fallback(string $code): bool
{
    return in_array($code, [
        'captcha_not_configured',
        'captcha_unreachable',
        'captcha_invalid_json',
        'captcha_missing',
    ], true);
}
