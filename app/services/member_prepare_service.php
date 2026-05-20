<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/member_prepare_repository.php';
require_once __DIR__ . '/../repositories/member_learning_repository.php';

function member_prepare_service_get_payload(PDO $pdo, int $member_id, int $question_id): array
{
    $overview = member_learning_repository_get_event_overview($pdo, $question_id);
    $suggested = member_prepare_service_decode_json_list((string) ($overview['suggested_questions_json'] ?? '[]'));
    $checklist = member_prepare_service_decode_json_list((string) ($overview['prepare_checklist_json'] ?? '[]'));
    $progress = member_prepare_repository_get_progress($pdo, $member_id, $question_id);
    $saved_questions = member_prepare_repository_list_saved_questions($pdo, $member_id, $question_id);

    $checked_items = member_prepare_service_decode_json_list((string) ($progress['checklist_json'] ?? '[]'));
    $checked_map = [];
    foreach ($checked_items as $item) {
        $checked_map[$item] = true;
    }

    return [
        'suggested_questions' => $suggested,
        'checklist' => $checklist,
        'checked_map' => $checked_map,
        'notes_text' => (string) ($progress['notes_text'] ?? ''),
        'is_ready' => (bool) ($progress !== null && (int) ($progress['is_ready'] ?? 0) === 1),
        'saved_questions' => $saved_questions,
    ];
}

function member_prepare_service_save_progress(PDO $pdo, int $member_id, int $question_id, array $checklist_items, string $notes): void
{
    $clean = [];
    foreach ($checklist_items as $item) {
        $value = trim((string) $item);
        if ($value !== '') {
            $clean[] = $value;
        }
    }
    $clean = array_values(array_unique($clean));
    member_prepare_repository_upsert_progress($pdo, $member_id, $question_id, (string) json_encode($clean), trim($notes), false);
}

function member_prepare_service_mark_ready(PDO $pdo, int $member_id, int $question_id): void
{
    $existing = member_prepare_repository_get_progress($pdo, $member_id, $question_id);
    $checklist_json = (string) ($existing['checklist_json'] ?? '[]');
    $notes_text = (string) ($existing['notes_text'] ?? '');
    member_prepare_repository_upsert_progress($pdo, $member_id, $question_id, $checklist_json, $notes_text, true);
}

function member_prepare_service_is_ready(PDO $pdo, int $member_id, int $question_id): bool
{
    $progress = member_prepare_repository_get_progress($pdo, $member_id, $question_id);
    return $progress !== null && (int) ($progress['is_ready'] ?? 0) === 1;
}

function member_prepare_service_save_question(PDO $pdo, int $member_id, int $question_id, ?int $lesson_module_id, string $saved_question_text): bool
{
    $text = trim($saved_question_text);
    if ($text === '') {
        return false;
    }
    member_prepare_repository_save_question($pdo, $member_id, $question_id, $lesson_module_id, $text);
    return true;
}

function member_prepare_service_decode_json_list(string $json): array
{
    $decoded = json_decode($json, true);
    if (!is_array($decoded)) {
        return [];
    }
    $list = [];
    foreach ($decoded as $item) {
        if (is_string($item) && trim($item) !== '') {
            $list[] = trim($item);
        }
    }
    return $list;
}
