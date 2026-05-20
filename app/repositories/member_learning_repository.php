<?php
declare(strict_types=1);

function member_learning_repository_list_events(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id, question FROM questions ORDER BY id ASC');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}

function member_learning_repository_find_event(PDO $pdo, int $question_id): ?array
{
    $stmt = $pdo->prepare('SELECT id, question FROM questions WHERE id = :id LIMIT 1');
    $stmt->bindValue(':id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function member_learning_repository_get_preferences(PDO $pdo, int $member_id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM member_learning_preferences WHERE member_id = :member_id LIMIT 1');
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function member_learning_repository_upsert_preferences(PDO $pdo, int $member_id, string $style, string $confidence, ?int $question_id): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO member_learning_preferences (member_id, preferred_learning_style, confidence_level, current_selected_question_id)
         VALUES (:member_id, :style, :confidence, :question_id)
         ON DUPLICATE KEY UPDATE
             preferred_learning_style = VALUES(preferred_learning_style),
             confidence_level = VALUES(confidence_level),
             current_selected_question_id = VALUES(current_selected_question_id)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':style', $style);
    $stmt->bindValue(':confidence', $confidence);
    $stmt->bindValue(':question_id', $question_id, $question_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->execute();
}

function member_learning_repository_select_event(PDO $pdo, int $member_id, int $question_id): void
{
    $current = member_learning_repository_get_preferences($pdo, $member_id);
    $style = (string) ($current['preferred_learning_style'] ?? 'reading');
    $confidence = (string) ($current['confidence_level'] ?? 'beginner');
    member_learning_repository_upsert_preferences($pdo, $member_id, $style, $confidence, $question_id);
}

function member_learning_repository_get_event_overview(PDO $pdo, int $question_id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM learning_event_overviews WHERE question_id = :question_id LIMIT 1');
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function member_learning_repository_list_lessons_with_progress(PDO $pdo, int $question_id, int $member_id): array
{
    $stmt = $pdo->prepare(
        'SELECT lm.*, COALESCE(mlp.status, "not_started") AS progress_status, mlp.completed_at
         FROM lesson_modules lm
         LEFT JOIN member_lesson_progress mlp
           ON mlp.lesson_module_id = lm.id AND mlp.member_id = :member_id
         WHERE lm.question_id = :question_id
         ORDER BY lm.lesson_order ASC'
    );
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}

function member_learning_repository_find_lesson_by_id(PDO $pdo, int $lesson_module_id, int $member_id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT lm.*,
                q.question AS event_question,
                COALESCE(mlp.status, "not_started") AS progress_status,
                mlp.reflection_text,
                quiz.id AS quiz_id,
                quiz.question_text,
                quiz.option_a,
                quiz.option_b,
                quiz.option_c,
                quiz.option_d,
                quiz.correct_option,
                quiz.explanation_text
         FROM lesson_modules lm
         INNER JOIN questions q ON q.id = lm.question_id
         INNER JOIN lesson_quizzes quiz ON quiz.lesson_module_id = lm.id
         LEFT JOIN member_lesson_progress mlp
           ON mlp.lesson_module_id = lm.id AND mlp.member_id = :member_id
         WHERE lm.id = :lesson_module_id
         LIMIT 1'
    );
    $stmt->bindValue(':lesson_module_id', $lesson_module_id, PDO::PARAM_INT);
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function member_learning_repository_find_lesson_by_slug(PDO $pdo, int $question_id, string $slug, int $member_id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT lm.id
         FROM lesson_modules lm
         WHERE lm.question_id = :question_id AND lm.slug = :slug
         LIMIT 1'
    );
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->bindValue(':slug', $slug);
    $stmt->execute();
    $lesson_id = $stmt->fetchColumn();
    if ($lesson_id === false) {
        return null;
    }
    return member_learning_repository_find_lesson_by_id($pdo, (int) $lesson_id, $member_id);
}

function member_learning_repository_upsert_lesson_progress(
    PDO $pdo,
    int $member_id,
    int $lesson_module_id,
    string $status,
    string $reflection_text,
    ?string $completed_at
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO member_lesson_progress (member_id, lesson_module_id, status, reflection_text, completed_at)
         VALUES (:member_id, :lesson_module_id, :status, :reflection_text, :completed_at)
         ON DUPLICATE KEY UPDATE
             status = VALUES(status),
             reflection_text = VALUES(reflection_text),
             completed_at = VALUES(completed_at)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':lesson_module_id', $lesson_module_id, PDO::PARAM_INT);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':reflection_text', $reflection_text === '' ? null : $reflection_text, $reflection_text === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':completed_at', $completed_at, $completed_at === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();
}

function member_learning_repository_count_completed_lessons(PDO $pdo, int $member_id, int $question_id): int
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*)
         FROM member_lesson_progress mlp
         INNER JOIN lesson_modules lm ON lm.id = mlp.lesson_module_id
         WHERE mlp.member_id = :member_id
           AND lm.question_id = :question_id
           AND mlp.status = "completed"'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

function member_learning_repository_count_total_lessons(PDO $pdo, int $question_id): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM lesson_modules WHERE question_id = :question_id');
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

function member_learning_repository_list_badges(PDO $pdo, int $member_id): array
{
    $stmt = $pdo->prepare('SELECT * FROM member_badges WHERE member_id = :member_id ORDER BY earned_at DESC, id DESC');
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}

function member_learning_repository_has_badge(PDO $pdo, int $member_id, string $badge_key): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM member_badges WHERE member_id = :member_id AND badge_key = :badge_key');
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':badge_key', $badge_key);
    $stmt->execute();
    return (int) $stmt->fetchColumn() > 0;
}

function member_learning_repository_award_badge(
    PDO $pdo,
    int $member_id,
    string $badge_key,
    string $badge_name,
    string $badge_description
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO member_badges (member_id, badge_key, badge_name, badge_description, earned_at)
         VALUES (:member_id, :badge_key, :badge_name, :badge_description, NOW())
         ON DUPLICATE KEY UPDATE
            badge_name = VALUES(badge_name),
            badge_description = VALUES(badge_description)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':badge_key', $badge_key);
    $stmt->bindValue(':badge_name', $badge_name);
    $stmt->bindValue(':badge_description', $badge_description);
    $stmt->execute();
}
