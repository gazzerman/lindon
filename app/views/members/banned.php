<?php
declare(strict_types=1);

$auth_page_description = 'Your Linden account has been restricted.';
$auth_hero_kicker = 'Account status';
$auth_hero_title = 'Access restricted.';
$auth_hero_subtitle = 'This account is currently unable to sign in to the Members Area.';
$auth_footnote = '';

require __DIR__ . '/partials/auth_shell_start.php';
?>

            <div class="bg-card border border-red-900/25 p-8 md:p-10">
                <span class="text-[10px] uppercase tracking-editorial text-red-900 block mb-2">Restricted</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-6 text-red-950">Account restricted</h2>
                <p class="text-sm text-muted-foreground leading-relaxed">
                    Your account is currently marked as banned. Please contact customer service for assistance.
                </p>
                <div class="mt-8">
                    <a href="../index.php" class="<?= e($auth_btn_secondary) ?>">Return to the House</a>
                </div>
            </div>

<?php require __DIR__ . '/partials/auth_shell_end.php'; ?>
