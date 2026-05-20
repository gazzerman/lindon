<?php
declare(strict_types=1);

$auth_page_description = 'Set a new password for your Linden account.';
$auth_hero_title = 'Choose a new password.';
$auth_hero_subtitle = 'Your reset link is valid for a limited time. Choose something you will remember.';
$auth_errors = !empty($errors['password']) ? [$errors['password']] : [];

require __DIR__ . '/partials/auth_shell_start.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Password</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Set a new password</h2>

                <?php require __DIR__ . '/partials/auth_alerts.php'; ?>

                <form method="post" action="index.php" class="space-y-6">
                    <input type="hidden" name="action" value="reset-password">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <input type="hidden" name="token" value="<?= e((string) $token) ?>">

                    <div>
                        <label for="password" class="<?= e($auth_label) ?>">New password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="<?= e($auth_input) ?>"
                        >
                    </div>

                    <button type="submit" class="<?= e($auth_btn) ?>">Set new password</button>
                </form>

                <div class="h-px bg-border my-8"></div>

                <p class="text-sm text-muted-foreground text-center">
                    <a href="index.php?action=login" class="text-accent hover:text-foreground transition-colors font-medium">Back to sign in</a>
                </p>
            </div>

<?php require __DIR__ . '/partials/auth_shell_end.php'; ?>
