<?php
declare(strict_types=1);

$members_page_title = 'Linden — Members';
$members_page_description = 'Your private learning and preparation workspace.';
$members_hero_kicker = 'Members Area';
$members_hero_title = 'My Learning Journey';
$members_hero_subtitle = 'A calm, private space to learn, prepare, and arrive at important conversations with clarity.';

$selected_question_id = (int) ($preferences['current_selected_question_id'] ?? 0);
$selected_event = null;
foreach (($events ?? []) as $event_item) {
    if ((int) ($event_item['id'] ?? 0) === $selected_question_id) {
        $selected_event = $event_item;
        break;
    }
}
$selected_progress = $progress_map[$selected_question_id] ?? ['completed' => 0, 'total' => 0];

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>">
                    <?= e((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Continue your journey</span>
                <?php if ($selected_event !== null): ?>
                    <h2 class="font-serif text-2xl md:text-3xl tracking-display"><?= e((string) $selected_event['question']) ?></h2>
                    <p class="mt-3 text-sm text-muted-foreground">
                        <?= e((string) ($selected_progress['completed'] ?? 0)) ?> of <?= e((string) ($selected_progress['total'] ?? 0)) ?> lessons completed.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="index.php?action=event&question_id=<?= e((string) $selected_question_id) ?>" class="<?= e($members_btn) ?>">Resume learning</a>
                        <a href="index.php?action=prepare&question_id=<?= e((string) $selected_question_id) ?>" class="<?= e($members_btn_secondary) ?>">Open prepare</a>
                    </div>
                <?php else: ?>
                    <h2 class="font-serif text-2xl md:text-3xl tracking-display">Choose your first life event</h2>
                    <p class="mt-3 text-sm text-muted-foreground">Select an event below to begin your members-only learning path.</p>
                    <a href="index.php?action=journey" class="<?= e($members_btn) ?> inline-block mt-6">Start journey</a>
                <?php endif; ?>
            </div>

            <div class="bg-card border border-border p-8 md:p-10 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <h3 class="font-serif text-2xl tracking-display">Available life events</h3>
                    <a href="index.php?action=journey" class="text-[11px] uppercase tracking-editorial text-accent">View details</a>
                </div>
                <div class="space-y-3">
                    <?php foreach (($events ?? []) as $event): ?>
                        <?php
                        $event_id = (int) ($event['id'] ?? 0);
                        $is_current = $event_id === $selected_question_id;
                        ?>
                        <form method="post" action="index.php" class="border <?= $is_current ? 'border-accent bg-accent/5' : 'border-border' ?> p-4 flex flex-wrap items-center justify-between gap-3">
                            <input type="hidden" name="action" value="select-learning-event">
                            <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
                            <input type="hidden" name="question_id" value="<?= e((string) $event_id) ?>">
                            <div>
                                <p class="font-serif text-lg"><?= e((string) ($event['question'] ?? 'Life Event')) ?></p>
                                <?php if ($is_current): ?>
                                    <p class="text-[10px] uppercase tracking-editorial text-accent mt-1">Current selected event</p>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="<?= e($members_btn_secondary) ?>">Select</button>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-card border border-border p-8 md:p-10">
                <h3 class="font-serif text-2xl tracking-display mb-5">Badges earned</h3>
                <?php if (!empty($badges)): ?>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <?php foreach ($badges as $badge): ?>
                            <div class="border border-border p-4">
                                <p class="text-[10px] uppercase tracking-editorial text-accent mb-1"><?= e((string) ($badge['badge_key'] ?? 'badge')) ?></p>
                                <p class="font-serif text-lg"><?= e((string) ($badge['badge_name'] ?? 'Badge')) ?></p>
                                <p class="text-xs text-muted-foreground mt-1"><?= e((string) ($badge['badge_description'] ?? '')) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-muted-foreground">Complete lessons to earn your first badge.</p>
                <?php endif; ?>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
