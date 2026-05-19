<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';

function recaptcha_service_is_enabled(): bool
{
    $config = security_config();

    return $config['recaptcha_site_key'] !== '' && $config['recaptcha_secret_key'] !== '';
}

/**
 * @return array{ok:bool, code:string}
 */
function recaptcha_service_verify_response(?string $response_token, ?string $remote_ip, string $expected_action): array
{
    if (security_captcha_bypass()) {
        return ['ok' => true, 'code' => 'bypass'];
    }

    $config = security_config();
    $secret = (string) $config['recaptcha_secret_key'];
    if ($secret === '') {
        return ['ok' => false, 'code' => 'captcha_not_configured'];
    }

    $token = trim((string) $response_token);
    if ($token === '') {
        return ['ok' => false, 'code' => 'captcha_missing'];
    }

    $payload = [
        'secret' => $secret,
        'response' => $token,
    ];
    $ip = trim((string) $remote_ip);
    if ($ip !== '') {
        $payload['remoteip'] = $ip;
    }

    $body = http_build_query($payload);
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                . 'Content-Length: ' . strlen($body) . "\r\n",
            'content' => $body,
            'timeout' => 5,
        ],
    ]);

    $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    if (!is_string($response) || $response === '') {
        return ['ok' => false, 'code' => 'captcha_unreachable'];
    }

    $json = json_decode($response, true);
    if (!is_array($json)) {
        return ['ok' => false, 'code' => 'captcha_invalid_json'];
    }

    if (empty($json['success'])) {
        return ['ok' => false, 'code' => 'captcha_failed'];
    }

    $action = trim((string) ($json['action'] ?? ''));
    if ($action !== $expected_action) {
        return ['ok' => false, 'code' => 'captcha_action_mismatch'];
    }

    $score = (float) ($json['score'] ?? 0.0);
    $min_score = (float) $config['recaptcha_min_score'];
    if ($score < $min_score) {
        return ['ok' => false, 'code' => 'captcha_low_score'];
    }

    return ['ok' => true, 'code' => 'ok'];
}
