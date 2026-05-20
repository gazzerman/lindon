<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/member_learning_repository.php';
require_once __DIR__ . '/member_badges_service.php';
require_once __DIR__ . '/member_prepare_service.php';

function member_learning_service_get_preferences(PDO $pdo, int $member_id): array
{
    $prefs = member_learning_repository_get_preferences($pdo, $member_id);
    return [
        'preferred_learning_style' => (string) ($prefs['preferred_learning_style'] ?? 'reading'),
        'confidence_level' => (string) ($prefs['confidence_level'] ?? 'beginner'),
        'current_selected_question_id' => isset($prefs['current_selected_question_id']) ? (int) $prefs['current_selected_question_id'] : null,
    ];
}

function member_learning_service_save_preferences(PDO $pdo, int $member_id, array $input): array
{
    $allowed_styles = ['reading', 'visual', 'audio', 'quiz-first', 'scenario-based', 'reflection-based'];
    $allowed_confidence = ['beginner', 'familiar', 'confident'];

    $style = trim((string) ($input['preferred_learning_style'] ?? 'reading'));
    if (!in_array($style, $allowed_styles, true)) {
        $style = 'reading';
    }

    $confidence = trim((string) ($input['confidence_level'] ?? 'beginner'));
    if (!in_array($confidence, $allowed_confidence, true)) {
        $confidence = 'beginner';
    }

    $question_id = isset($input['current_selected_question_id']) && (string) $input['current_selected_question_id'] !== ''
        ? (int) $input['current_selected_question_id']
        : null;

    if ($question_id !== null && member_learning_repository_find_event($pdo, $question_id) === null) {
        $question_id = null;
    }

    member_learning_repository_upsert_preferences($pdo, $member_id, $style, $confidence, $question_id);

    return member_learning_service_get_preferences($pdo, $member_id);
}

function member_learning_service_select_event(PDO $pdo, int $member_id, int $question_id): bool
{
    if (member_learning_repository_find_event($pdo, $question_id) === null) {
        return false;
    }
    member_learning_repository_select_event($pdo, $member_id, $question_id);
    return true;
}

function member_learning_service_get_journey_payload(PDO $pdo, int $member_id): array
{
    $prefs = member_learning_service_get_preferences($pdo, $member_id);
    $events = member_learning_repository_list_events($pdo);
    $badges = member_learning_repository_list_badges($pdo, $member_id);

    $progress_map = [];
    foreach ($events as $event) {
        $question_id = (int) ($event['id'] ?? 0);
        $completed = member_learning_repository_count_completed_lessons($pdo, $member_id, $question_id);
        $total = member_learning_repository_count_total_lessons($pdo, $question_id);
        $progress_map[$question_id] = ['completed' => $completed, 'total' => $total];
    }

    return [
        'preferences' => $prefs,
        'events' => $events,
        'badges' => $badges,
        'progress_map' => $progress_map,
    ];
}

function member_learning_service_get_event_payload(PDO $pdo, int $member_id, int $question_id): ?array
{
    $event = member_learning_repository_find_event($pdo, $question_id);
    if ($event === null) {
        return null;
    }
    $overview = member_learning_repository_get_event_overview($pdo, $question_id);
    $lessons = member_learning_repository_list_lessons_with_progress($pdo, $question_id, $member_id);
    $prefs = member_learning_service_get_preferences($pdo, $member_id);
    $completed = member_learning_repository_count_completed_lessons($pdo, $member_id, $question_id);
    $total = member_learning_repository_count_total_lessons($pdo, $question_id);

    return [
        'event' => $event,
        'overview' => $overview,
        'lessons' => $lessons,
        'preferences' => $prefs,
        'completed' => $completed,
        'total' => $total,
        'what_you_will_learn' => member_prepare_service_decode_json_list((string) ($overview['what_you_will_learn_json'] ?? '[]')),
    ];
}

function member_learning_service_get_lesson_payload(PDO $pdo, int $member_id, int $lesson_id): ?array
{
    $lesson = member_learning_repository_find_lesson_by_id($pdo, $lesson_id, $member_id);
    if ($lesson === null) {
        return null;
    }
    $prefs = member_learning_service_get_preferences($pdo, $member_id);
    $all_lessons = member_learning_repository_list_lessons_with_progress($pdo, (int) $lesson['question_id'], $member_id);

    return [
        'lesson' => $lesson,
        'preferences' => $prefs,
        'lesson_count' => count($all_lessons),
    ];
}

function member_learning_service_complete_lesson(PDO $pdo, int $member_id, int $lesson_id, array $input): array
{
    $lesson = member_learning_repository_find_lesson_by_id($pdo, $lesson_id, $member_id);
    if ($lesson === null) {
        return ['ok' => false, 'message' => 'Lesson not found.'];
    }

    $answer = strtoupper(trim((string) ($input['selected_option'] ?? '')));
    if (!in_array($answer, ['A', 'B', 'C', 'D'], true)) {
        return ['ok' => false, 'message' => 'Please choose a quiz answer before submitting.'];
    }

    $reflection = trim((string) ($input['reflection_text'] ?? ''));
    $completed_at = date('Y-m-d H:i:s');
    member_learning_repository_upsert_lesson_progress($pdo, $member_id, $lesson_id, 'completed', $reflection, $completed_at);
    member_badges_service_award_lesson_badge($pdo, $member_id, $lesson);
    member_badges_service_sync_for_event($pdo, $member_id, (int) $lesson['question_id']);

    return [
        'ok' => true,
        'is_correct' => strtoupper((string) $lesson['correct_option']) === $answer,
        'correct_option' => strtoupper((string) $lesson['correct_option']),
        'explanation_text' => (string) $lesson['explanation_text'],
        'question_id' => (int) $lesson['question_id'],
        'lesson_title' => (string) $lesson['title'],
    ];
}
