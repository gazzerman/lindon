<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Login</h1>

                <?php if (!empty($flash)): ?>
                    <div class="alert alert-<?= e($flash['type'] === 'error' ? 'danger' : 'success') ?>" role="alert">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['login'])): ?>
                    <div class="alert alert-danger" role="alert"><?= e($errors['login']) ?></div>
                <?php endif; ?>

                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">

                    <div class="mb-3">
                        <label for="identifier" class="form-label">Username or Email</label>
                        <input type="text" class="form-control" id="identifier" name="identifier" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Keep me logged in for 30 days</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <hr>
                <a href="index.php?action=register">Create Account</a>
                <span class="mx-2">|</span>
                <a href="index.php?action=forgot-password">Forgot Password</a>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

