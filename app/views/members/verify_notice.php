<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Verify Your Account</h1>

                <?php if (!empty($flash)): ?>
                    <div class="alert alert-<?= e($flash['type'] === 'error' ? 'danger' : 'success') ?>" role="alert">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <p><?= e((string) $message) ?></p>

                <?php if (!empty($member_id)): ?>
                    <form method="post" action="index.php" class="d-inline">
                        <input type="hidden" name="action" value="resend-verification">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                        <input type="hidden" name="member_id" value="<?= e((string) $member_id) ?>">
                        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                    </form>
                <?php endif; ?>

                <a href="index.php?action=login" class="btn btn-outline-secondary ms-2">Back to Login</a>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

