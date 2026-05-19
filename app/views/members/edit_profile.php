<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/partials/subnav.php'; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Edit Profile</h1>

                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="update-profile">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">

                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control<?= !empty($errors['first_name']) ? ' is-invalid' : '' ?>" id="first_name" name="first_name" value="<?= e((string) ($member['first_name'] ?? '')) ?>" required>
                        <?php if (!empty($errors['first_name'])): ?>
                            <div class="invalid-feedback d-block"><?= e((string) $errors['first_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control<?= !empty($errors['last_name']) ? ' is-invalid' : '' ?>" id="last_name" name="last_name" value="<?= e((string) ($member['last_name'] ?? '')) ?>" required>
                        <?php if (!empty($errors['last_name'])): ?>
                            <div class="invalid-feedback d-block"><?= e((string) $errors['last_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control<?= !empty($errors['phone']) ? ' is-invalid' : '' ?>" id="phone" name="phone" value="<?= e((string) ($member['phone'] ?? '')) ?>" maxlength="30">
                        <?php if (!empty($errors['phone'])): ?>
                            <div class="invalid-feedback d-block"><?= e((string) $errors['phone']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control<?= !empty($errors['date_of_birth']) ? ' is-invalid' : '' ?>" id="date_of_birth" name="date_of_birth" value="<?= e((string) ($member['date_of_birth'] ?? '')) ?>" required>
                        <?php if (!empty($errors['date_of_birth'])): ?>
                            <div class="invalid-feedback d-block"><?= e((string) $errors['date_of_birth']) ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="index.php?action=profile" class="btn btn-outline-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

