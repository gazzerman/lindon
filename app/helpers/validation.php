<?php
declare(strict_types=1);

function validate_members_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && strlen($email) <= 150;
}

function validate_members_password(string $password): bool
{
    return strlen($password) >= 8 && strlen($password) <= 255;
}

function validate_members_username(string $username): bool
{
    if (strlen($username) < 3 || strlen($username) > 30) {
        return false;
    }

    return preg_match('/^[a-zA-Z0-9_]+$/', $username) === 1;
}

function validate_members_first_name(string $first_name): bool
{
    $first_name = trim($first_name);
    return $first_name !== '' && strlen($first_name) <= 100;
}

function validate_members_last_name(string $last_name): bool
{
    $last_name = trim($last_name);
    return $last_name !== '' && strlen($last_name) <= 100;
}

function validate_members_date_of_birth(string $date_of_birth): bool
{
    $date_of_birth = trim($date_of_birth);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_of_birth)) {
        return false;
    }

    [$year, $month, $day] = array_map('intval', explode('-', $date_of_birth));
    if (!checkdate($month, $day, $year)) {
        return false;
    }

    return strtotime($date_of_birth) <= time();
}

function validate_members_phone(string $phone): bool
{
    $phone = trim($phone);
    if ($phone === '') {
        return true;
    }

    if (strlen($phone) > 30) {
        return false;
    }

    return preg_match('/^[0-9+\-\s().]+$/', $phone) === 1;
}

