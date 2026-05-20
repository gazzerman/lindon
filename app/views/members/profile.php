<?php
declare(strict_types=1);

$members_page_title = 'Linden — Profile';
$members_page_description = 'View your Linden member profile.';
$members_hero_kicker = 'Your account';
$members_hero_title = 'Profile';
$members_hero_subtitle = 'Account details visible only to you.';

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <?php if (!empty($flash)): ?>
                    <div
                        class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>"
                        role="alert"
                    >
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Member details</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Your information</h2>

                <dl class="space-y-6 border-t border-border pt-8">
                    <?php
                    $fields = [
                        'Username' => (string) ($member['username'] ?? ''),
                        'Email' => (string) ($member['email'] ?? ''),
                        'First name' => (string) ($member['first_name'] ?? ''),
                        'Last name' => (string) ($member['last_name'] ?? ''),
                        'Phone' => (string) ($member['phone'] ?? ''),
                        'Date of birth' => (string) ($member['date_of_birth'] ?? ''),
                    ];
                    foreach ($fields as $label => $value):
                        $display = $value !== '' ? $value : '—';
                    ?>
                        <div>
                            <dt class="text-[10px] uppercase tracking-editorial text-muted-foreground"><?= e($label) ?></dt>
                            <dd class="mt-1.5 font-serif text-lg text-foreground"><?= e($display) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <div class="h-px bg-border my-10"></div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <a href="index.php?action=edit-profile" class="<?= e($members_btn) ?> text-center">Edit profile</a>
                    <form method="post" action="index.php" class="sm:text-right">
                        <input type="hidden" name="action" value="logout">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                        <button
                            type="submit"
                            class="text-[11px] uppercase tracking-editorial text-red-900/80 border border-red-900/25 px-6 py-3 hover:border-red-900/50 hover:text-red-950 transition-colors w-full sm:w-auto"
                        >
                            Sign out
                        </button>
                    </form>
                </div>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
