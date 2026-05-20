<?php
declare(strict_types=1);

$auth_page_description = 'Sign in to your private Linden workspace.';
$auth_hero_title = 'Welcome back.';
$auth_hero_subtitle = 'Your saved decks, preparations, and library await — private to you alone.';
$auth_errors = !empty($errors['login']) ? [$errors['login']] : [];

require __DIR__ . '/partials/auth_shell_start.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Sign in</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Enter your workspace</h2>

                <?php require __DIR__ . '/partials/auth_alerts.php'; ?>

                <form method="post" action="index.php" class="space-y-6">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">

                    <div>
                        <label for="identifier" class="<?= e($auth_label) ?>">Username or email</label>
                        <input
                            type="text"
                            id="identifier"
                            name="identifier"
                            required
                            autocomplete="username"
                            class="<?= e($auth_input) ?>"
                        >
                    </div>

                    <div>
                        <div class="flex justify-between items-baseline mb-2">
                            <label for="password" class="text-[10px] uppercase tracking-editorial text-muted-foreground">Password</label>
                            <a href="index.php?action=forgot-password" class="text-[10px] uppercase tracking-editorial text-accent hover:text-foreground transition-colors">
                                Forgot?
                            </a>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="<?= e($auth_input) ?>"
                        >
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input
                            type="checkbox"
                            value="1"
                            id="remember"
                            name="remember"
                            class="mt-0.5 size-4 shrink-0 border border-border bg-background accent-accent focus:outline-none focus:ring-1 focus:ring-accent"
                        >
                        <span class="text-sm text-muted-foreground leading-relaxed group-hover:text-foreground transition-colors">
                            Keep me signed in for 30 days
                        </span>
                    </label>

                    <button type="submit" class="<?= e($auth_btn) ?>">Sign in</button>
                </form>

                <div class="h-px bg-border my-8"></div>

                <p class="text-sm text-muted-foreground text-center">
                    No account yet?
                    <a href="index.php?action=register" class="text-accent hover:text-foreground transition-colors font-medium">Create one</a>
                </p>
            </div>

<?php require __DIR__ . '/partials/auth_shell_end.php'; ?>
