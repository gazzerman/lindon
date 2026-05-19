<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/contact_service.php';
require_once __DIR__ . '/../middleware/csrf.php';
require_once __DIR__ . '/../helpers/view_renderer.php';
require_once __DIR__ . '/../services/captcha_service.php';
require_once __DIR__ . '/../services/rate_limit_service.php';

function contact_index_controller(PDO $pdo): void
{
    $success = isset($_GET['success']) && $_GET['success'] === '1';
    $captcha = captcha_service_public_config();

    $data = [
        'success' => $success,
        'errors' => [],
        'old' => [
            'first_name' => '',
            'email' => '',
            'query' => '',
        ],
        'csrf_token' => csrf_generate_token(),
        'captcha' => $captcha,
    ];

    render('contact/index', $data);
}

function contact_submit_controller(PDO $pdo, array $input): void
{
    $len = function (string $s): int {
        return function_exists('mb_strlen') ? mb_strlen($s) : strlen($s);
    };

    $first_name = trim((string) ($input['first_name'] ?? ''));
    $email = trim((string) ($input['email'] ?? ''));
    $query = trim((string) ($input['query'] ?? ''));
    $csrf_token = $input['csrf_token'] ?? null;

    $errors = [];

    if ($first_name === '') {
        $errors['first_name'] = 'First name is required.';
    } elseif ($len($first_name) > 100) {
        $errors['first_name'] = 'First name must be 100 characters or less.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email address is not valid.';
    } elseif ($len($email) > 150) {
        $errors['email'] = 'Email must be 150 characters or less.';
    }

    if ($query === '') {
        $errors['query'] = 'Query is required.';
    } elseif ($len($query) > 2000) {
        $errors['query'] = 'Query must be 2000 characters or less.';
    }

    if (!csrf_verify_or_fail(is_string($csrf_token) ? $csrf_token : null)) {
        $errors['csrf'] = 'Invalid CSRF token. Please refresh the page and try again.';
    }

    $captcha = captcha_service_verify_submission(
        $input,
        isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : null,
        'contact_submit'
    );
    if (!$captcha['ok']) {
        $errors['form'] = 'Security check failed. Please try again.';
    }

    $limit = rate_limit_service_enforce($pdo, 'contact_submit', $email);
    if (!$limit['ok']) {
        $errors['form'] = 'Too many requests. Please wait and try again.';
    }

    if ($errors !== []) {
        $data = [
            'success' => false,
            'errors' => $errors,
            'old' => [
                'first_name' => $first_name,
                'email' => $email,
                'query' => $query,
            ],
            'csrf_token' => csrf_generate_token(),
            'captcha' => captcha_service_public_config(),
        ];

        render('contact/index', $data);
        return;
    }

    contact_submit_service($pdo, [
        'first_name' => $first_name,
        'email' => $email,
        'query' => $query,
    ]);

    header('Location: contact.php?success=1');
    exit;
}

