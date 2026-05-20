<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/member_learning_repository.php';
require_once __DIR__ . '/member_prepare_service.php';

function member_badges_service_sync_for_event(PDO $pdo, int $member_id, int $question_id): void
{
    $completed_lessons = member_learning_repository_count_completed_lessons($pdo, $member_id, $question_id);
    if ($completed_lessons > 0) {
        member_learning_repository_award_badge(
            $pdo,
            $member_id,
            'first-lesson-completed',
            'First Lesson Completed',
            'Completed your first lesson in the learning journey.'
        );
    }

    $prepare_ready = member_prepare_service_is_ready($pdo, $member_id, $question_id);
    $total_lessons = member_learning_repository_count_total_lessons($pdo, $question_id);
    if ($total_lessons > 0 && $completed_lessons >= $total_lessons && $prepare_ready) {
        member_learning_repository_award_badge(
            $pdo,
            $member_id,
            'annual-family-meeting-ready',
            'Annual Family Meeting Ready',
            'Completed all lessons and marked yourself ready for the meeting.'
        );
    }
}

function member_badges_service_award_lesson_badge(PDO $pdo, int $member_id, array $lesson): void
{
    $badge_name = trim((string) ($lesson['badge_name'] ?? ''));
    if ($badge_name === '') {
        return;
    }
    $badge_key = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $badge_name), '-'));
    member_learning_repository_award_badge(
        $pdo,
        $member_id,
        'lesson-' . $badge_key,
        $badge_name,
        'Completed lesson: ' . (string) ($lesson['title'] ?? 'Lesson')
    );
}
