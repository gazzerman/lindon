<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/view_renderer.php';
require_once __DIR__ . '/../middleware/csrf.php';
require_once __DIR__ . '/../middleware/members_guard.php';
require_once __DIR__ . '/../services/members_login_service.php';
require_once __DIR__ . '/../services/members_register_service.php';
require_once __DIR__ . '/../services/members_password_service.php';
require_once __DIR__ . '/../services/members_verification_service.php';
require_once __DIR__ . '/../services/members_session_service.php';
require_once __DIR__ . '/../services/members_profile_service.php';
require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../helpers/username_policy.php';
require_once __DIR__ . '/../helpers/token_helper.php';
require_once __DIR__ . '/../services/captcha_service.php';
require_once __DIR__ . '/../services/rate_limit_service.php';

function members_route_controller(PDO $pdo, string $method, array $get, array $post): void
{
    members_session_try_auto_login($pdo);

    if ($method === 'POST') {
        members_handle_post($pdo, $post);
        return;
    }

    $action = isset($get['action']) ? (string) $get['action'] : 'login';
    members_render_action($pdo, $action, $get);
}

function members_render_action(PDO $pdo, string $action, array $get): void
{
    $flash = members_consume_flash();
    $captcha = captcha_service_public_config();

    switch ($action) {
        case 'register':
            render('members/register', [
                'csrf_token' => csrf_generate_token(),
                'errors' => [],
                'old' => [],
                'flash' => $flash,
                'captcha' => $captcha,
            ]);
            return;

        case 'forgot-password':
            render('members/forgot_password', [
                'csrf_token' => csrf_generate_token(),
                'errors' => [],
                'flash' => $flash,
                'old' => ['email' => ''],
                'captcha' => $captcha,
            ]);
            return;

        case 'reset-password':
            $token = members_collect_token_from_query($get);
            render('members/reset_password', [
                'csrf_token' => csrf_generate_token(),
                'errors' => [],
                'token' => $token,
                'flash' => $flash,
            ]);
            return;

        case 'verify':
            $token = members_collect_token_from_query($get);
            $result = members_verification_service_verify($pdo, $token);
            if ($result['ok']) {
                members_set_flash('success', 'Your account is now verified. Please log in.');
                header('Location: index.php?action=login');
                exit;
            }

            render('members/verify_notice', [
                'csrf_token' => csrf_generate_token(),
                'message' => (string) $result['message'],
                'member_id' => 0,
                'flash' => $flash,
            ]);
            return;

        case 'verify-notice':
            render('members/verify_notice', [
                'csrf_token' => csrf_generate_token(),
                'message' => 'Please verify your account by clicking the link in your email.',
                'member_id' => isset($get['member_id']) ? (int) $get['member_id'] : 0,
                'flash' => $flash,
            ]);
            return;

        case 'banned':
            render('members/banned', ['flash' => $flash]);
            return;

        case 'hello':
            members_guard_require_login();
            $member = members_profile_service_get($pdo, members_session_current_member_id());
            render('members/hello', [
                'member' => $member,
                'csrf_token' => csrf_generate_token(),
                'flash' => $flash,
            ]);
            return;

        case 'profile':
            members_guard_require_login();
            $member = members_profile_service_get($pdo, members_session_current_member_id());
            render('members/profile', [
                'member' => $member,
                'csrf_token' => csrf_generate_token(),
                'flash' => $flash,
                'current_page' => 'profile',
                'breadcrumbs' => [
                    ['label' => 'Members', 'url' => 'index.php?action=hello'],
                    ['label' => 'Profile', 'url' => ''],
                ],
            ]);
            return;

        case 'edit-profile':
            members_guard_require_login();
            $member = members_profile_service_get($pdo, members_session_current_member_id());
            render('members/edit_profile', [
                'member' => $member,
                'errors' => [],
                'flash' => $flash,
                'csrf_token' => csrf_generate_token(),
                'current_page' => 'profile',
                'breadcrumbs' => [
                    ['label' => 'Members', 'url' => 'index.php?action=hello'],
                    ['label' => 'Profile', 'url' => 'index.php?action=profile'],
                    ['label' => 'Edit Profile', 'url' => ''],
                ],
            ]);
            return;

        case 'login':
        default:
            if (members_session_is_logged_in()) {
                header('Location: index.php?action=hello');
                exit;
            }

            render('members/login', [
                'csrf_token' => csrf_generate_token(),
                'errors' => [],
                'flash' => $flash,
            ]);
            return;
    }
}

function members_handle_post(PDO $pdo, array $post): void
{
    $action = isset($post['action']) ? (string) $post['action'] : '';

    if (in_array($action, ['check-username', 'check-email'], true)) {
        members_handle_ajax_checks($pdo, $action, $post);
        return;
    }

    if (!csrf_verify_or_fail(isset($post['csrf_token']) ? (string) $post['csrf_token'] : null)) {
        members_set_flash('error', 'Invalid security token. Please try again.');
        header('Location: index.php?action=login');
        exit;
    }

    switch ($action) {
        case 'register':
            $captcha = captcha_service_verify_submission(
                $post,
                isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : null,
                'members_register'
            );
            if (!$captcha['ok']) {
                render('members/register', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => ['form' => 'Security check failed. Please try again.'],
                    'old' => [
                        'username' => (string) ($post['username'] ?? ''),
                        'email' => (string) ($post['email'] ?? ''),
                    ],
                    'flash' => members_consume_flash(),
                    'captcha' => captcha_service_public_config(),
                ]);
                return;
            }

            $limit = rate_limit_service_enforce($pdo, 'members_register', (string) ($post['email'] ?? ''));
            if (!$limit['ok']) {
                render('members/register', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => ['form' => 'Too many requests. Please wait and try again.'],
                    'old' => [
                        'username' => (string) ($post['username'] ?? ''),
                        'email' => (string) ($post['email'] ?? ''),
                    ],
                    'flash' => members_consume_flash(),
                    'captcha' => captcha_service_public_config(),
                ]);
                return;
            }

            $result = members_register_service_register(
                $pdo,
                (string) ($post['username'] ?? ''),
                (string) ($post['email'] ?? ''),
                (string) ($post['password'] ?? '')
            );

            if (!$result['ok']) {
                render('members/register', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => $result['errors'],
                    'old' => [
                        'username' => (string) ($post['username'] ?? ''),
                        'email' => (string) ($post['email'] ?? ''),
                    ],
                    'flash' => members_consume_flash(),
                    'captcha' => captcha_service_public_config(),
                ]);
                return;
            }

            members_set_flash('success', 'Account created. Check your email for a verification link.');
            header('Location: index.php?action=login');
            exit;

        case 'login':
            $remember = isset($post['remember']) && (string) $post['remember'] === '1';
            $result = members_login_service_login(
                $pdo,
                (string) ($post['identifier'] ?? ''),
                (string) ($post['password'] ?? ''),
                $remember
            );

            if (!$result['ok']) {
                if ($result['code'] === 'banned') {
                    header('Location: index.php?action=banned');
                    exit;
                }

                if ($result['code'] === 'unverified') {
                    header('Location: index.php?action=verify-notice&member_id=' . (int) $result['member_id']);
                    exit;
                }

                render('members/login', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => ['login' => (string) $result['message']],
                    'flash' => members_consume_flash(),
                ]);
                return;
            }

            header('Location: index.php?action=hello');
            exit;

        case 'forgot-password':
            $email = (string) ($post['email'] ?? '');
            $captcha = captcha_service_verify_submission(
                $post,
                isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : null,
                'members_forgot_password'
            );
            if (!$captcha['ok']) {
                render('members/forgot_password', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => ['form' => 'Security check failed. Please try again.'],
                    'old' => ['email' => $email],
                    'flash' => members_consume_flash(),
                    'captcha' => captcha_service_public_config(),
                ]);
                return;
            }

            $limit = rate_limit_service_enforce($pdo, 'members_forgot_password', $email);
            if (!$limit['ok']) {
                render('members/forgot_password', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => ['form' => 'Too many requests. Please wait and try again.'],
                    'old' => ['email' => $email],
                    'flash' => members_consume_flash(),
                    'captcha' => captcha_service_public_config(),
                ]);
                return;
            }

            members_password_service_request_reset($pdo, $email);
            members_set_flash('success', 'If your email exists, a reset link has been sent.');
            header('Location: index.php?action=forgot-password');
            exit;

        case 'reset-password':
            $result = members_password_service_reset_password(
                $pdo,
                (string) ($post['token'] ?? ''),
                (string) ($post['password'] ?? '')
            );

            if (!$result['ok']) {
                render('members/reset_password', [
                    'csrf_token' => csrf_generate_token(),
                    'errors' => ['password' => (string) $result['message']],
                    'token' => (string) ($post['token'] ?? ''),
                    'flash' => members_consume_flash(),
                ]);
                return;
            }

            members_set_flash('success', 'Your password has been reset. Please log in.');
            header('Location: index.php?action=login');
            exit;

        case 'resend-verification':
            $member_id = (int) ($post['member_id'] ?? 0);
            $result = members_verification_service_resend($pdo, $member_id);
            members_set_flash(
                $result['ok'] ? 'success' : 'error',
                $result['ok'] ? 'Verification email resent.' : (string) $result['message']
            );
            header('Location: index.php?action=verify-notice&member_id=' . $member_id);
            exit;

        case 'logout':
            members_session_logout($pdo);
            header('Location: index.php?action=login');
            exit;

        case 'update-profile':
            members_guard_require_login();
            $member_id = members_session_current_member_id();
            $result = members_profile_service_update($pdo, $member_id, $post);
            if (!$result['ok']) {
                render('members/edit_profile', [
                    'member' => array_merge(
                        members_profile_service_get($pdo, $member_id) ?? [],
                        $result['values']
                    ),
                    'errors' => $result['errors'],
                    'flash' => members_consume_flash(),
                    'csrf_token' => csrf_generate_token(),
                    'current_page' => 'profile',
                    'breadcrumbs' => [
                        ['label' => 'Members', 'url' => 'index.php?action=hello'],
                        ['label' => 'Profile', 'url' => 'index.php?action=profile'],
                        ['label' => 'Edit Profile', 'url' => ''],
                    ],
                ]);
                return;
            }

            members_set_flash('success', 'Profile updated successfully.');
            header('Location: index.php?action=profile');
            exit;

        default:
            http_response_code(400);
            echo 'Unknown action';
            return;
    }
}

function members_handle_ajax_checks(PDO $pdo, string $action, array $post): void
{
    header('Content-Type: application/json');
    if ($action === 'check-username') {
        $username = trim((string) ($post['username'] ?? ''));
        echo json_encode([
            'exists' => members_repository_username_exists($pdo, $username),
            'reserved' => username_policy_is_reserved($username),
        ]);
        return;
    }

    $email = trim(strtolower((string) ($post['email'] ?? '')));
    echo json_encode(['exists' => members_repository_email_exists($pdo, $email)]);
}

function members_set_flash(string $type, string $message): void
{
    $_SESSION['members_flash'] = ['type' => $type, 'message' => $message];
}

function members_consume_flash(): ?array
{
    $flash = $_SESSION['members_flash'] ?? null;
    unset($_SESSION['members_flash']);
    return $flash;
}

function members_collect_token_from_query(array $get): string
{
    if (isset($get['token']) && (string) $get['token'] !== '') {
        return (string) $get['token'];
    }

    $selector = isset($get['selector']) ? trim((string) $get['selector']) : '';
    $validator = isset($get['validator']) ? trim((string) $get['validator']) : '';
    if ($selector === '' || $validator === '') {
        return '';
    }

    return token_helper_build_url_token($selector, $validator);
}

