<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Create Account</h1>

                <?php if (!empty($flash)): ?>
                    <div class="alert alert-<?= e($flash['type'] === 'error' ? 'danger' : 'success') ?>" role="alert">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors['form'])): ?>
                    <div class="alert alert-danger" role="alert"><?= e($errors['form']) ?></div>
                <?php endif; ?>

                <form method="post" action="index.php" id="register-form">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <input type="hidden" name="g-recaptcha-response" value="">

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control<?= !empty($errors['username']) ? ' is-invalid' : '' ?>" id="username" name="username" value="<?= e((string) ($old['username'] ?? '')) ?>" required>
                        <div id="username-feedback" class="form-text"></div>
                        <?php if (!empty($errors['username'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['username']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control<?= !empty($errors['email']) ? ' is-invalid' : '' ?>" id="email" name="email" value="<?= e((string) ($old['email'] ?? '')) ?>" required>
                        <div id="email-feedback" class="form-text"></div>
                        <?php if (!empty($errors['email'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control<?= !empty($errors['password']) ? ' is-invalid' : '' ?>" id="password" name="password" required>
                        <?php if (!empty($errors['password'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($captcha['turnstile_site_key'])): ?>
                        <div class="mb-3">
                            <div class="cf-turnstile" data-sitekey="<?= e((string) $captcha['turnstile_site_key']) ?>"></div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
            </div>
        </div>
    </div>
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
                usernameFeedback.className = 'text-danger small';
                return;
            }
            usernameFeedback.textContent = data.exists ? 'Username is already taken.' : 'Username is available.';
            usernameFeedback.className = data.exists ? 'text-danger small' : 'text-success small';
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
            emailFeedback.className = data.exists ? 'text-danger small' : 'text-success small';
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
<?php require __DIR__ . '/../layout/footer.php'; ?>

