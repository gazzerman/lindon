<?php
declare(strict_types=1);

$lesson = $payload['lesson'] ?? [];
$preferences = $payload['preferences'] ?? [];
$lesson_count = (int) ($payload['lesson_count'] ?? 1);
$lesson_order = (int) ($lesson['lesson_order'] ?? 1);
$learning_style = (string) ($preferences['preferred_learning_style'] ?? 'reading');

$members_page_title = 'Linden — Lesson';
$members_page_description = 'Lesson module with quiz and reflection.';
$members_hero_kicker = 'Learn';
$members_hero_title = (string) ($lesson['title'] ?? 'Lesson');
$members_hero_subtitle = 'Lesson ' . $lesson_order . ' of ' . max(1, $lesson_count);

$show_quiz_first = $learning_style === 'quiz-first';
$emphasize_reflection = in_array($learning_style, ['scenario-based', 'reflection-based'], true);

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>">
                    <?= e((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <?php if (($lesson['progress_status'] ?? 'not_started') === 'completed'): ?>
                <div class="mb-6 border border-accent/30 bg-accent/10 px-4 py-3 text-sm text-foreground">
                    Badge earned progress updated for this lesson.
                </div>
            <?php endif; ?>

            <?php if ($show_quiz_first): ?>
                <?php require __DIR__ . '/partials/lesson_quiz_form.php'; ?>
            <?php endif; ?>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <p class="text-[10px] uppercase tracking-editorial text-accent mb-2">Lesson content</p>
                <p class="text-sm leading-relaxed text-muted-foreground whitespace-pre-line"><?= e((string) ($lesson['lesson_content'] ?? '')) ?></p>
                <div class="mt-6 border border-border p-4 <?= $learning_style === 'visual' ? 'bg-accent/5 border-accent/30' : '' ?>">
                    <p class="text-[10px] uppercase tracking-editorial text-muted-foreground mb-1">Key takeaway</p>
                    <p class="font-serif text-lg"><?= e((string) ($lesson['key_takeaway'] ?? '')) ?></p>
                </div>
                <?php if ($learning_style === 'audio'): ?>
                    <button type="button" id="tts-btn" class="<?= e($members_btn_secondary) ?> mt-4">Listen to lesson</button>
                    <script>
                        document.getElementById('tts-btn')?.addEventListener('click', function () {
                            if (!window.speechSynthesis) return;
                            const msg = new SpeechSynthesisUtterance(<?= json_encode((string) ($lesson['lesson_content'] ?? '')) ?>);
                            window.speechSynthesis.cancel();
                            window.speechSynthesis.speak(msg);
                        });
                    </script>
                <?php endif; ?>
            </div>

            <?php if (!$show_quiz_first): ?>
                <?php require __DIR__ . '/partials/lesson_quiz_form.php'; ?>
            <?php endif; ?>

            <div class="bg-card border <?= $emphasize_reflection ? 'border-accent' : 'border-border' ?> p-8 md:p-10">
                <p class="text-[10px] uppercase tracking-editorial text-accent mb-2">Apply and reflect</p>
                <p class="text-sm text-muted-foreground"><?= e((string) ($lesson['reflection_prompt'] ?? '')) ?></p>
                <p class="text-xs text-muted-foreground mt-3">
                    Want to continue? Visit <a class="text-accent" href="index.php?action=prepare&question_id=<?= e((string) ($lesson['question_id'] ?? 0)) ?>">Prepare</a>.
                </p>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
