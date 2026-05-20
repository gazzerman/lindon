<?php
declare(strict_types=1);

$auth_page_description = 'Verify your Linden account to access the Members Area.';
$auth_hero_title = 'Verify your account.';
$auth_hero_subtitle = 'A confirmation link was sent when you registered. Check your inbox to continue.';
$auth_footnote = '';

require __DIR__ . '/partials/auth_shell_start.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Verification</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Confirm your email</h2>

                <?php require __DIR__ . '/partials/auth_alerts.php'; ?>

                <p class="text-sm text-muted-foreground leading-relaxed mb-8"><?= e((string) $message) ?></p>

                <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                    <?php if (!empty($member_id)): ?>
                        <form method="post" action="index.php">
                            <input type="hidden" name="action" value="resend-verification">
                            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                            <input type="hidden" name="member_id" value="<?= e((string) $member_id) ?>">
                            <button type="submit" class="<?= e($auth_btn) ?> sm:w-auto">Resend verification email</button>
                        </form>
                    <?php endif; ?>
                    <a href="index.php?action=login" class="<?= e($auth_btn_secondary) ?> text-center">Back to sign in</a>
                </div>
            </div>

<?php require __DIR__ . '/partials/auth_shell_end.php'; ?>
