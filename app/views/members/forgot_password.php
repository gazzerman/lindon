<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Forgot Password</h1>

                <?php if (!empty($flash)): ?>
                    <div class="alert alert-<?= e($flash['type'] === 'error' ? 'danger' : 'success') ?>" role="alert">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors['form'])): ?>
                    <div class="alert alert-danger" role="alert"><?= e($errors['form']) ?></div>
                <?php endif; ?>

                <form method="post" action="index.php" id="forgot-password-form">
                    <input type="hidden" name="action" value="forgot-password">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <input type="hidden" name="g-recaptcha-response" value="">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e((string) ($old['email'] ?? '')) ?>" required>
                    </div>
                    <?php if (!empty($captcha['turnstile_site_key'])): ?>
                        <div class="mb-3">
                            <div class="cf-turnstile" data-sitekey="<?= e((string) $captcha['turnstile_site_key']) ?>"></div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($captcha['turnstile_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>'; ?>
<?php endif; ?>
<?php if (!empty($captcha['recaptcha_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://www.google.com/recaptcha/api.js?render=' . e((string) $captcha['recaptcha_site_key']) . '"></script>'; ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script>(function(){var form=document.getElementById("forgot-password-form");if(!form||typeof grecaptcha==="undefined"){return;}form.addEventListener("submit",function(event){event.preventDefault();grecaptcha.ready(function(){grecaptcha.execute("' . e((string) $captcha['recaptcha_site_key']) . '",{action:"members_forgot_password"}).then(function(token){var input=form.querySelector(\'input[name="g-recaptcha-response"]\');if(input){input.value=token;}form.submit();});});});})();</script>'; ?>
<?php endif; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>

