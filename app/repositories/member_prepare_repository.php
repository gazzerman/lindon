<?php
declare(strict_types=1);

function member_prepare_repository_get_progress(PDO $pdo, int $member_id, int $question_id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT * FROM member_prepare_progress
         WHERE member_id = :member_id AND question_id = :question_id
         LIMIT 1'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function member_prepare_repository_upsert_progress(
    PDO $pdo,
    int $member_id,
    int $question_id,
    string $checklist_json,
    string $notes_text,
    bool $is_ready
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO member_prepare_progress (member_id, question_id, checklist_json, notes_text, is_ready, ready_at)
         VALUES (:member_id, :question_id, :checklist_json, :notes_text, :is_ready, :ready_at)
         ON DUPLICATE KEY UPDATE
            checklist_json = VALUES(checklist_json),
            notes_text = VALUES(notes_text),
            is_ready = VALUES(is_ready),
            ready_at = VALUES(ready_at)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->bindValue(':checklist_json', $checklist_json);
    $stmt->bindValue(':notes_text', $notes_text === '' ? null : $notes_text, $notes_text === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':is_ready', $is_ready ? 1 : 0, PDO::PARAM_INT);
    $stmt->bindValue(':ready_at', $is_ready ? date('Y-m-d H:i:s') : null, $is_ready ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();
}

function member_prepare_repository_save_question(
    PDO $pdo,
    int $member_id,
    int $question_id,
    ?int $lesson_module_id,
    string $saved_question_text
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO member_saved_questions (member_id, question_id, lesson_module_id, saved_question_text)
         VALUES (:member_id, :question_id, :lesson_module_id, :saved_question_text)
         ON DUPLICATE KEY UPDATE saved_question_text = VALUES(saved_question_text)'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->bindValue(':lesson_module_id', $lesson_module_id, $lesson_module_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':saved_question_text', $saved_question_text);
    $stmt->execute();
}

function member_prepare_repository_list_saved_questions(PDO $pdo, int $member_id, int $question_id): array
{
    $stmt = $pdo->prepare(
        'SELECT * FROM member_saved_questions
         WHERE member_id = :member_id AND question_id = :question_id
         ORDER BY created_at DESC, id DESC'
    );
    $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}
