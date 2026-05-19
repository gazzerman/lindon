<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/partials/subnav.php'; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Profile</h1>

                <?php if (!empty($flash)): ?>
                    <div class="alert alert-<?= e($flash['type'] === 'error' ? 'danger' : 'success') ?>" role="alert">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <dl class="row mb-0">
                    <dt class="col-sm-4">Username</dt>
                    <dd class="col-sm-8"><?= e((string) ($member['username'] ?? '')) ?></dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8"><?= e((string) ($member['email'] ?? '')) ?></dd>

                    <dt class="col-sm-4">First Name</dt>
                    <dd class="col-sm-8"><?= e((string) ($member['first_name'] ?? '')) ?></dd>

                    <dt class="col-sm-4">Last Name</dt>
                    <dd class="col-sm-8"><?= e((string) ($member['last_name'] ?? '')) ?></dd>

                    <dt class="col-sm-4">Phone</dt>
                    <dd class="col-sm-8"><?= e((string) ($member['phone'] ?? '')) ?></dd>

                    <dt class="col-sm-4">Date of Birth</dt>
                    <dd class="col-sm-8"><?= e((string) ($member['date_of_birth'] ?? '')) ?></dd>
                </dl>

                <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                    <a href="index.php?action=edit-profile" class="btn btn-primary">Edit Profile</a>
                    <form method="post" action="index.php" class="mb-0 ms-auto">
                        <input type="hidden" name="action" value="logout">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                        <button type="submit" class="btn btn-sm" style="color: #c75c5c; border-color: #e8a8a8; background: transparent;">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

