<?php
declare(strict_types=1);

$members_page_title = 'Linden — Prepare';
$members_page_description = 'Prepare for your selected life event.';
$members_hero_kicker = 'Prepare';
$members_hero_title = (string) ($event['question'] ?? 'Prepare');
$members_hero_subtitle = 'Save your questions, track readiness, and arrive with confidence.';

$suggested_questions = $prepare_payload['suggested_questions'] ?? [];
$saved_questions = $prepare_payload['saved_questions'] ?? [];
$checklist = $prepare_payload['checklist'] ?? [];
$checked_map = $prepare_payload['checked_map'] ?? [];
$notes_text = (string) ($prepare_payload['notes_text'] ?? '');
$is_ready = (bool) ($prepare_payload['is_ready'] ?? false);

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>">
                    <?= e((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <h3 class="font-serif text-2xl tracking-display mb-4">Suggested questions</h3>
                <div class="space-y-3">
                    <?php foreach ($suggested_questions as $question_text): ?>
                        <form method="post" action="index.php" class="border border-border p-4 flex flex-wrap items-start justify-between gap-3">
                            <input type="hidden" name="action" value="save-suggested-question">
                            <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
                            <input type="hidden" name="question_id" value="<?= e((string) $question_id) ?>">
                            <input type="hidden" name="saved_question_text" value="<?= e((string) $question_text) ?>">
                            <p class="text-sm text-muted-foreground max-w-2xl"><?= e((string) $question_text) ?></p>
                            <button type="submit" class="<?= e($members_btn_secondary) ?>">Bookmark</button>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <h3 class="font-serif text-2xl tracking-display mb-4">Saved questions</h3>
                <?php if (!empty($saved_questions)): ?>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <?php foreach ($saved_questions as $row): ?>
                            <li>• <?= e((string) ($row['saved_question_text'] ?? '')) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-sm text-muted-foreground">No questions saved yet.</p>
                <?php endif; ?>
                <form method="post" action="index.php" class="mt-6 space-y-3">
                    <input type="hidden" name="action" value="save-custom-question">
                    <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
                    <input type="hidden" name="question_id" value="<?= e((string) $question_id) ?>">
                    <label for="saved_question_text" class="<?= e($members_label) ?>">Add custom question</label>
                    <input id="saved_question_text" name="saved_question_text" type="text" class="<?= e($members_input) ?>" placeholder="Write your own question">
                    <button type="submit" class="<?= e($members_btn_secondary) ?>">Save question</button>
                </form>
            </div>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <h3 class="font-serif text-2xl tracking-display mb-4">Preparation checklist</h3>
                <form method="post" action="index.php" class="space-y-4">
                    <input type="hidden" name="action" value="save-prepare-progress">
                    <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
                    <input type="hidden" name="question_id" value="<?= e((string) $question_id) ?>">
                    <?php foreach ($checklist as $item): ?>
                        <label class="flex items-start gap-3 border border-border p-3">
                            <input type="checkbox" name="checklist_items[]" value="<?= e((string) $item) ?>" <?= !empty($checked_map[$item]) ? 'checked' : '' ?>>
                            <span class="text-sm text-muted-foreground"><?= e((string) $item) ?></span>
                        </label>
                    <?php endforeach; ?>
                    <div>
                        <label for="notes_text" class="<?= e($members_label) ?>">Things I still want clarified</label>
                        <textarea id="notes_text" name="notes_text" rows="4" class="<?= e($members_input) ?>"><?= e($notes_text) ?></textarea>
                    </div>
                    <button type="submit" class="<?= e($members_btn) ?>">Save preparation</button>
                </form>
            </div>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <h3 class="font-serif text-2xl tracking-display mb-4">Completion state</h3>
                <p class="text-sm text-muted-foreground mb-5">Current status: <?= $is_ready ? 'I feel ready' : 'In progress' ?></p>
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="mark-feel-ready">
                    <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
                    <input type="hidden" name="question_id" value="<?= e((string) $question_id) ?>">
                    <button type="submit" class="<?= e($members_btn_secondary) ?>">I feel ready</button>
                </form>
            </div>

            <div class="bg-card border border-accent/30 bg-accent/5 p-8 md:p-10">
                <p class="font-serif text-xl leading-relaxed text-pretty">
                    You do not need to know everything before the meeting. You only need to arrive prepared, curious, and clear on the questions that matter most to you.
                </p>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
