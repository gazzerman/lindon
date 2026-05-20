<?php
declare(strict_types=1);

$members_page_title = 'Linden — Preferences';
$members_page_description = 'Set your learning preferences.';
$members_hero_kicker = 'Preferences';
$members_hero_title = 'Learning Preferences';
$members_hero_subtitle = 'Personalize your default experience with a calm, thoughtful pace.';

$style = (string) ($preferences['preferred_learning_style'] ?? 'reading');
$confidence = (string) ($preferences['confidence_level'] ?? 'beginner');
$selected_question_id = (int) ($preferences['current_selected_question_id'] ?? 0);

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>">
                    <?= e((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="bg-card border border-border p-8 md:p-10">
                <form method="post" action="index.php" class="space-y-6">
                    <input type="hidden" name="action" value="save-learning-preferences">
                    <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">

                    <div>
                        <label for="preferred_learning_style" class="<?= e($members_label) ?>">Preferred learning style</label>
                        <select id="preferred_learning_style" name="preferred_learning_style" class="<?= e($members_input) ?>">
                            <?php foreach (['reading', 'visual', 'audio', 'quiz-first', 'scenario-based', 'reflection-based'] as $option): ?>
                                <option value="<?= e($option) ?>" <?= $style === $option ? 'selected' : '' ?>><?= e(ucwords(str_replace('-', ' ', $option))) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="confidence_level" class="<?= e($members_label) ?>">Confidence level</label>
                        <select id="confidence_level" name="confidence_level" class="<?= e($members_input) ?>">
                            <?php foreach (['beginner', 'familiar', 'confident'] as $option): ?>
                                <option value="<?= e($option) ?>" <?= $confidence === $option ? 'selected' : '' ?>><?= e(ucfirst($option)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="current_selected_question_id" class="<?= e($members_label) ?>">Current selected life event</label>
                        <select id="current_selected_question_id" name="current_selected_question_id" class="<?= e($members_input) ?>">
                            <option value="">No selection</option>
                            <?php foreach (($events ?? []) as $event): ?>
                                <?php $event_id = (int) ($event['id'] ?? 0); ?>
                                <option value="<?= e((string) $event_id) ?>" <?= $selected_question_id === $event_id ? 'selected' : '' ?>>
                                    <?= e((string) ($event['question'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="<?= e($members_btn) ?>">Save preferences</button>
                </form>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
