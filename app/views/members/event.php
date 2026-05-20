<?php
declare(strict_types=1);

$event = $payload['event'] ?? [];
$overview = $payload['overview'] ?? [];
$lessons = $payload['lessons'] ?? [];
$completed = (int) ($payload['completed'] ?? 0);
$total = (int) ($payload['total'] ?? 0);
$learn_points = $payload['what_you_will_learn'] ?? [];

$members_page_title = 'Linden — Event Journey';
$members_page_description = 'Event overview and lesson path.';
$members_hero_kicker = 'Learn';
$members_hero_title = (string) ($event['question'] ?? 'Event');
$members_hero_subtitle = 'Work through five concise lessons, each with one quiz and one reflection step.';

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>">
                    <?= e((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <p class="text-[10px] uppercase tracking-editorial text-accent mb-2">Event overview</p>
                <p class="text-sm text-muted-foreground leading-relaxed whitespace-pre-line"><?= e((string) ($overview['intro_copy'] ?? '')) ?></p>
                <div class="h-px bg-border my-6"></div>
                <p class="text-[10px] uppercase tracking-editorial text-muted-foreground mb-2">Progress</p>
                <p class="font-serif text-xl"><?= e((string) $completed) ?> of <?= e((string) $total) ?> lessons completed</p>
            </div>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <h3 class="font-serif text-2xl tracking-display mb-4">What you will learn</h3>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <?php foreach ($learn_points as $point): ?>
                        <li>• <?= e((string) $point) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="space-y-4">
                <?php foreach ($lessons as $lesson): ?>
                    <div class="bg-card border border-border p-6 md:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-editorial text-muted-foreground">Lesson <?= e((string) ($lesson['lesson_order'] ?? 0)) ?> of <?= e((string) max(1, $total)) ?></p>
                                <h4 class="font-serif text-2xl mt-1"><?= e((string) ($lesson['title'] ?? 'Lesson')) ?></h4>
                                <p class="text-sm text-muted-foreground mt-2"><?= e((string) ($lesson['short_description'] ?? '')) ?></p>
                                <p class="text-xs uppercase tracking-editorial mt-3 <?= (($lesson['progress_status'] ?? 'not_started') === 'completed') ? 'text-accent' : 'text-muted-foreground' ?>">
                                    <?= e(str_replace('_', ' ', (string) ($lesson['progress_status'] ?? 'not_started'))) ?>
                                </p>
                            </div>
                            <a href="index.php?action=lesson&lesson_id=<?= e((string) ($lesson['id'] ?? 0)) ?>" class="<?= e($members_btn) ?>">Open lesson</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
