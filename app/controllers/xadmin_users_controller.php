<?php
declare(strict_types=1);

require_once __DIR__ . '/../middleware/csrf.php';
require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../services/members_session_service.php';

/**
 * @return array{type:string,message:string}|null
 */
function xadmin_users_flash_from_query(array $get): ?array
{
    if (isset($get['saved']) && (string) $get['saved'] === '1') {
        return ['type' => 'ok', 'message' => 'User updated.'];
    }
    $err = isset($get['err']) ? (string) $get['err'] : '';
    if ($err === 'self_admin') {
        return ['type' => 'err', 'message' => 'You cannot remove your own admin access.'];
    }
    if ($err === 'self_ban') {
        return ['type' => 'err', 'message' => 'You cannot ban your own account.'];
    }

    return null;
}

function xadmin_users_handle_post(PDO $pdo, array $post): void
{
    if (!csrf_verify_or_fail($post['csrf_token'] ?? null)) {
        http_response_code(403);
        echo 'Invalid session';
        exit;
    }

    $form = isset($post['form']) ? (string) $post['form'] : '';
    if ($form !== 'member_flag_toggle') {
        http_response_code(400);
        echo 'Bad Request';
        exit;
    }

    $member_id = isset($post['member_id']) ? (int) $post['member_id'] : 0;
    if ($member_id < 1) {
        http_response_code(400);
        echo 'Bad Request';
        exit;
    }

    $flag = isset($post['flag']) ? (string) $post['flag'] : '';
    if (!in_array($flag, ['verified', 'admin', 'banned'], true)) {
        http_response_code(400);
        echo 'Bad Request';
        exit;
    }

    $new_on = isset($post['value']) && (string) $post['value'] === '1';
    $target = members_repository_find_by_id($pdo, $member_id);
    if ($target === null) {
        http_response_code(404);
        echo 'Not Found';
        exit;
    }

    $self_id = members_session_current_member_id();
    if ($flag === 'admin' && $member_id === $self_id && !$new_on) {
        header('Location: ' . xadmin_users_build_redirect_url($post, 'err=self_admin'));
        exit;
    }
    if ($flag === 'banned' && $member_id === $self_id && $new_on) {
        header('Location: ' . xadmin_users_build_redirect_url($post, 'err=self_ban'));
        exit;
    }

    if ($flag === 'verified') {
        members_repository_set_is_verified($pdo, $member_id, $new_on);
    } elseif ($flag === 'admin') {
        members_repository_set_is_admin($pdo, $member_id, $new_on);
    } else {
        members_repository_set_is_banned($pdo, $member_id, $new_on);
    }

    header('Location: ' . xadmin_users_build_redirect_url($post, 'saved=1'));
    exit;
}

function xadmin_users_build_redirect_url(array $post, string $query_suffix): string
{
    $dir = isset($post['redirect_dir']) ? strtolower((string) $post['redirect_dir']) : 'desc';
    if ($dir !== 'asc' && $dir !== 'desc') {
        $dir = 'desc';
    }

    return 'index.php?action=users&dir=' . rawurlencode($dir) . '&' . $query_suffix;
}
