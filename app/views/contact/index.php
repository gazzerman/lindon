<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Contact Us</h1>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        Your message was received. Thank you for contacting us.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['csrf'])): ?>
                    <div class="alert alert-danger" role="alert"><?= e($errors['csrf']) ?></div>
                <?php endif; ?>
                <?php if (!empty($errors['form'])): ?>
                    <div class="alert alert-danger" role="alert"><?= e($errors['form']) ?></div>
                <?php endif; ?>

                <form action="contact.php" method="post" id="contact-form">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First name</label>
                        <input
                            id="first_name"
                            name="first_name"
                            type="text"
                            class="form-control<?= !empty($errors['first_name']) ? ' is-invalid' : '' ?>"
                            value="<?= e($old['first_name'] ?? '') ?>"
                            autocomplete="given-name"
                        >
                        <?php if (!empty($errors['first_name'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['first_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-control<?= !empty($errors['email']) ? ' is-invalid' : '' ?>"
                            value="<?= e($old['email'] ?? '') ?>"
                            autocomplete="email"
                        >
                        <?php if (!empty($errors['email'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="query" class="form-label">Query</label>
                        <textarea
                            id="query"
                            name="query"
                            class="form-control<?= !empty($errors['query']) ? ' is-invalid' : '' ?>"
                            rows="5"
                        ><?= e($old['query'] ?? '') ?></textarea>
                        <?php if (!empty($errors['query'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['query']) ?></div>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <input type="hidden" name="g-recaptcha-response" value="">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Send</button>
                        <a class="btn btn-outline-secondary" href="index.php">Cancel</a>
                    </div>
                    <?php if (!empty($captcha['turnstile_site_key'])): ?>
                        <div class="mt-3">
                            <div class="cf-turnstile" data-sitekey="<?= e((string) $captcha['turnstile_site_key']) ?>"></div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h4 mb-3">What to include</h2>
                <ul class="mb-0">
                    <li class="mb-2">Your question or comment in the `query` field.</li>
                    <li class="mb-2">A valid email address so we can reply.</li>
                    <li class="mb-0">We store enquiries in the `contact` table.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($captcha['turnstile_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>'; ?>
<?php endif; ?>
<?php if (!empty($captcha['recaptcha_site_key'])): ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script src="https://www.google.com/recaptcha/api.js?render=' . e((string) $captcha['recaptcha_site_key']) . '"></script>'; ?>
    <?php $extra_scripts = ($extra_scripts ?? '') . '<script>(function(){var form=document.getElementById("contact-form");if(!form||typeof grecaptcha==="undefined"){return;}form.addEventListener("submit",function(event){event.preventDefault();grecaptcha.ready(function(){grecaptcha.execute("' . e((string) $captcha['recaptcha_site_key']) . '",{action:"contact_submit"}).then(function(token){var input=form.querySelector(\'input[name="g-recaptcha-response"]\');if(input){input.value=token;}form.submit();});});});})();</script>'; ?>
<?php endif; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>

