<?php
declare(strict_types=1);

$auth_page_description = 'Create your private Linden workspace.';
$auth_hero_title = 'Join the Members Area.';
$auth_hero_subtitle = 'A quiet workspace for your preparations — visible only to you.';
$auth_card_max = 'max-w-lg';
$auth_errors = !empty($errors['form']) ? [$errors['form']] : [];

require __DIR__ . '/partials/auth_shell_start.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Create account</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Begin your membership</h2>

                <?php require __DIR__ . '/partials/auth_alerts.php'; ?>

                <form method="post" action="index.php" id="register-form" class="space-y-6">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <input type="hidden" name="g-recaptcha-response" value="">

                    <div>
                        <label for="username" class="<?= e($auth_label) ?>">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?= e((string) ($old['username'] ?? '')) ?>"
                            required
                            autocomplete="username"
                            class="<?= e(!empty($errors['username']) ? $auth_input_invalid : $auth_input) ?>"
                        >
                        <div id="username-feedback" class="text-xs mt-1.5"></div>
                        <?php if (!empty($errors['username'])): ?>
                            <p class="<?= e($auth_field_error) ?>"><?= e($errors['username']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="email" class="<?= e($auth_label) ?>">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= e((string) ($old['email'] ?? '')) ?>"
                            required
                            autocomplete="email"
                            class="<?= e(!empty($errors['email']) ? $auth_input_invalid : $auth_input) ?>"
                        >
                        <div id="email-feedback" class="text-xs mt-1.5"></div>
                        <?php if (!empty($errors['email'])): ?>
                            <p class="<?= e($auth_field_error) ?>"><?= e($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password" class="<?= e($auth_label) ?>">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="<?= e(!empty($errors['password']) ? $auth_input_invalid : $auth_input) ?>"
                        >
                        <?php if (!empty($errors['password'])): ?>
                            <p class="<?= e($auth_field_error) ?>"><?= e($errors['password']) ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($captcha['turnstile_site_key'])): ?>
                        <div>
                            <div class="cf-turnstile" data-sitekey="<?= e((string) $captcha['turnstile_site_key']) ?>"></div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="<?= e($auth_btn) ?>">Create account</button>
                </form>

                <div class="h-px bg-border my-8"></div>

                <p class="text-sm text-muted-foreground text-center">
                    Already a member?
                    <a href="index.php?action=login" class="text-accent hover:text-foreground transition-colors font-medium">Sign in</a>
                </p>
            </div>

<script>
(() => {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const usernameFeedback = document.getElementById('username-feedback');
    const emailFeedback = document.getElementById('email-feedback');

    const postCheck = async (action, field, value) => {
        const body = new URLSearchParams();
        body.append('action', action);
        body.append(field, value);
        const response = await fetch('index.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: body.toString()
        });
        return response.json();
    };

    let usernameTimer = null;
    let emailTimer = null;

    usernameInput.addEventListener('input', () => {
        clearTimeout(usernameTimer);
        usernameTimer = setTimeout(async () => {
            const value = usernameInput.value.trim();
            if (value.length < 3) {
                usernameFeedback.textContent = '';
                return;
            }
            const data = await postCheck('check-username', 'username', value);
            if (data.reserved) {
                usernameFeedback.textContent = 'This username is not allowed.';
                usernameFeedback.className = 'text-xs text-red-900 mt-1.5';
                return;
            }
            usernameFeedback.textContent = data.exists ? 'Username is already taken.' : 'Username is available.';
            usernameFeedback.className = data.exists ? 'text-xs text-red-900 mt-1.5' : 'text-xs text-accent mt-1.5';
        }, 300);
    });

    emailInput.addEventListener('input', () => {
        clearTimeout(emailTimer);
        emailTimer = setTimeout(async () => {
            const value = emailInput.value.trim();
            if (value.length < 5) {
                emailFeedback.textContent = '';
                return;
            }
            const data = await postCheck('check-email', 'email', value);
            emailFeedback.textContent = data.exists ? 'Email already exists.' : 'Email looks good.';
            emailFeedback.className = data.exists ? 'text-xs text-red-900 mt-1.5' : 'text-xs text-accent mt-1.5';
        }, 300);
    });
})();
</script>

<?php if (!empty($captcha['turnstile_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>'; ?>
<?php endif; ?>
<?php if (!empty($captcha['recaptcha_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://www.google.com/recaptcha/api.js?render=' . e((string) $captcha['recaptcha_site_key']) . '"></script>'; ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script>(function(){var form=document.getElementById("register-form");if(!form||typeof grecaptcha==="undefined"){return;}form.addEventListener("submit",function(event){event.preventDefault();grecaptcha.ready(function(){grecaptcha.execute("' . e((string) $captcha['recaptcha_site_key']) . '",{action:"members_register"}).then(function(token){var input=form.querySelector(\'input[name="g-recaptcha-response"]\');if(input){input.value=token;}form.submit();});});});})();</script>'; ?>
<?php endif; ?>

<?php require __DIR__ . '/partials/auth_shell_end.php'; ?>
