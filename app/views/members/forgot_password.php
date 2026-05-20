<?php
declare(strict_types=1);

$auth_page_description = 'Request a password reset link for your Linden account.';
$auth_hero_title = 'Restore access.';
$auth_hero_subtitle = 'We will send a discreet reset link to the email on your account.';
$auth_errors = !empty($errors['form']) ? [$errors['form']] : [];

require __DIR__ . '/partials/auth_shell_start.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Password</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Forgot your password?</h2>

                <?php require __DIR__ . '/partials/auth_alerts.php'; ?>

                <form method="post" action="index.php" id="forgot-password-form" class="space-y-6">
                    <input type="hidden" name="action" value="forgot-password">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <input type="hidden" name="g-recaptcha-response" value="">

                    <div>
                        <label for="email" class="<?= e($auth_label) ?>">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= e((string) ($old['email'] ?? '')) ?>"
                            required
                            autocomplete="email"
                            class="<?= e($auth_input) ?>"
                        >
                    </div>

                    <?php if (!empty($captcha['turnstile_site_key'])): ?>
                        <div>
                            <div class="cf-turnstile" data-sitekey="<?= e((string) $captcha['turnstile_site_key']) ?>"></div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="<?= e($auth_btn) ?>">Send reset link</button>
                </form>

                <div class="h-px bg-border my-8"></div>

                <p class="text-sm text-muted-foreground text-center">
                    Remember your password?
                    <a href="index.php?action=login" class="text-accent hover:text-foreground transition-colors font-medium">Sign in</a>
                </p>
            </div>

<?php if (!empty($captcha['turnstile_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>'; ?>
<?php endif; ?>
<?php if (!empty($captcha['recaptcha_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://www.google.com/recaptcha/api.js?render=' . e((string) $captcha['recaptcha_site_key']) . '"></script>'; ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script>(function(){var form=document.getElementById("forgot-password-form");if(!form||typeof grecaptcha==="undefined"){return;}form.addEventListener("submit",function(event){event.preventDefault();grecaptcha.ready(function(){grecaptcha.execute("' . e((string) $captcha['recaptcha_site_key']) . '",{action:"members_forgot_password"}).then(function(token){var input=form.querySelector(\'input[name="g-recaptcha-response"]\');if(input){input.value=token;}form.submit();});});});})();</script>'; ?>
<?php endif; ?>

<?php require __DIR__ . '/partials/auth_shell_end.php'; ?>
