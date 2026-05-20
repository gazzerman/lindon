<?php
declare(strict_types=1);

$members_page_title = 'Linden — Learning Journey';
$members_page_description = 'Choose and continue your learning journey.';
$members_hero_kicker = 'Learn';
$members_hero_title = 'My Learning Journey';
$members_hero_subtitle = 'Select a life event and progress through focused modules at your own pace.';

$selected_question_id = (int) ($preferences['current_selected_question_id'] ?? 0);

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>">
                    <?= e((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <?php foreach (($events ?? []) as $event): ?>
                    <?php
                    $event_id = (int) ($event['id'] ?? 0);
                    $is_current = $event_id === $selected_question_id;
                    $progress = $progress_map[$event_id] ?? ['completed' => 0, 'total' => 0];
                    ?>
                    <div class="bg-card border <?= $is_current ? 'border-accent' : 'border-border' ?> p-6 md:p-8">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="font-serif text-2xl text-pretty"><?= e((string) ($event['question'] ?? 'Life Event')) ?></p>
                                <p class="text-sm text-muted-foreground mt-2">
                                    <?= e((string) ($progress['completed'] ?? 0)) ?> of <?= e((string) ($progress['total'] ?? 0)) ?> lessons complete
                                </p>
                                <?php if ($is_current): ?>
                                    <p class="text-[10px] uppercase tracking-editorial text-accent mt-2">Current selected event</p>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <form method="post" action="index.php">
                                    <input type="hidden" name="action" value="select-learning-event">
                                    <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
                                    <input type="hidden" name="question_id" value="<?= e((string) $event_id) ?>">
                                    <button type="submit" class="<?= e($members_btn_secondary) ?>">Select</button>
                                </form>
                                <a href="index.php?action=event&question_id=<?= e((string) $event_id) ?>" class="<?= e($members_btn) ?>">Open event</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-card border border-border p-8 md:p-10 mt-8">
                <h3 class="font-serif text-2xl tracking-display mb-4">Badges earned</h3>
                <?php if (!empty($badges)): ?>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <?php foreach ($badges as $badge): ?>
                            <div class="border border-border p-4">
                                <p class="font-serif text-lg"><?= e((string) ($badge['badge_name'] ?? 'Badge')) ?></p>
                                <p class="text-xs text-muted-foreground mt-1"><?= e((string) ($badge['badge_description'] ?? '')) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-muted-foreground">No badges yet. Start a lesson to earn your first one.</p>
                <?php endif; ?>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
