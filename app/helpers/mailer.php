<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/token_helper.php';

function members_mailer_send_verification_email(string $to_email, string $username, string $raw_token): void
{
    $config = mail_config();
    $parts = token_helper_parse_url_token($raw_token);
    if ($parts === null) {
        return;
    }
    $verify_url = $config['app_url'] . '/index.php?action=verify'
        . '&selector=' . urlencode($parts['selector'])
        . '&validator=' . urlencode($parts['validator']);
    $subject = 'Verify your account';
    $text_body = "Hi {$username},\n\nPlease verify your account using this link:\n{$verify_url}\n\nIf you did not sign up, ignore this email.";
    $html_body = '<p>Hi ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ',</p>'
        . '<p>Please verify your account using this link:</p>'
        . '<p><a href="' . htmlspecialchars($verify_url, ENT_QUOTES, 'UTF-8') . '">Verify Account</a></p>'
        . '<p>If you did not sign up, ignore this email.</p>';

    members_mailer_send($to_email, $subject, $text_body, $html_body);
}

function members_mailer_send_password_reset_email(string $to_email, string $username, string $raw_token): void
{
    $config = mail_config();
    $parts = token_helper_parse_url_token($raw_token);
    if ($parts === null) {
        return;
    }
    $reset_url = $config['app_url'] . '/index.php?action=reset-password'
        . '&selector=' . urlencode($parts['selector'])
        . '&validator=' . urlencode($parts['validator']);
    $subject = 'Password reset request';
    $text_body = "Hi {$username},\n\nReset your password using this link:\n{$reset_url}\n\nIf you did not request this, ignore this email.";
    $html_body = '<p>Hi ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ',</p>'
        . '<p>Reset your password using this link:</p>'
        . '<p><a href="' . htmlspecialchars($reset_url, ENT_QUOTES, 'UTF-8') . '">Reset Password</a></p>'
        . '<p>If you did not request this, ignore this email.</p>';

    members_mailer_send($to_email, $subject, $text_body, $html_body);
}

function members_mailer_send(string $to_email, string $subject, string $text_body, string $html_body): void
{
    $config = mail_config();
    if ($config['mode'] === 'smtp') {
        $smtp_sent = members_mailer_send_smtp($config, $to_email, $subject, $text_body, $html_body);
        if ($smtp_sent) {
            return;
        }
    }

    members_mailer_log_fallback($to_email, $subject, $text_body);
}

function members_mailer_send_smtp(
    array $config,
    string $to_email,
    string $subject,
    string $text_body,
    string $html_body
): bool {
    $autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
    if (!is_file($autoload)) {
        return false;
    }

    require_once $autoload;
    if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
        return false;
    }

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->Port = (int) $config['smtp_port'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_user'];
        $mail->Password = $config['smtp_pass'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom($config['from_address'], $config['from_name']);
        $mail->addAddress($to_email);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $html_body;
        $mail->AltBody = $text_body;
        $mail->send();
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

function members_mailer_log_fallback(string $to_email, string $subject, string $body): void
{
    $log_dir = dirname(__DIR__, 2) . '/storage/logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }

    $log_file = $log_dir . '/mail.log';
    $entry = sprintf(
        "[%s]\nTO: %s\nSUBJECT: %s\n%s\n----------------\n",
        date('Y-m-d H:i:s'),
        $to_email,
        $subject,
        $body
    );
    file_put_contents($log_file, $entry, FILE_APPEND);
}

