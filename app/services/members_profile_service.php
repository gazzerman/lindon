<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/members_repository.php';
require_once __DIR__ . '/../helpers/validation.php';

function members_profile_service_get(PDO $pdo, int $member_id): ?array
{
    return members_repository_find_by_id($pdo, $member_id);
}

function members_profile_service_update(PDO $pdo, int $member_id, array $input): array
{
    $first_name = trim((string) ($input['first_name'] ?? ''));
    $last_name = trim((string) ($input['last_name'] ?? ''));
    $phone = trim((string) ($input['phone'] ?? ''));
    $date_of_birth = trim((string) ($input['date_of_birth'] ?? ''));

    $errors = [];
    if (!validate_members_first_name($first_name)) {
        $errors['first_name'] = 'First name is required and must be 100 chars or less.';
    }
    if (!validate_members_last_name($last_name)) {
        $errors['last_name'] = 'Last name is required and must be 100 chars or less.';
    }
    if (!validate_members_date_of_birth($date_of_birth)) {
        $errors['date_of_birth'] = 'Date of birth must be a valid date in YYYY-MM-DD format.';
    }
    if (!validate_members_phone($phone)) {
        $errors['phone'] = 'Phone must be 30 chars or less and contain only digits/spaces/+/-/()/.';
    }

    if ($errors !== []) {
        return [
            'ok' => false,
            'errors' => $errors,
            'values' => [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'date_of_birth' => $date_of_birth,
            ],
        ];
    }

    members_repository_update_profile($pdo, $member_id, $first_name, $last_name, $phone, $date_of_birth);

    return ['ok' => true];
}

