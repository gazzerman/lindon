<?php
declare(strict_types=1);

$members_page_title = 'Linden — Edit Profile';
$members_page_description = 'Update your Linden member profile.';
$members_hero_kicker = 'Your account';
$members_hero_title = 'Edit profile';
$members_hero_subtitle = 'Keep your details current for a composed presence at the table.';

require __DIR__ . '/partials/members_shell_start.php';
require __DIR__ . '/partials/subnav.php';
?>

            <div class="bg-card border border-border p-8 md:p-10">
                <?php if (!empty($flash)): ?>
                    <div
                        class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>"
                        role="alert"
                    >
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <span class="text-[10px] uppercase tracking-editorial text-accent block mb-2">Member details</span>
                <h2 class="font-serif text-2xl md:text-3xl tracking-display mb-8">Update your information</h2>

                <form method="post" action="index.php" class="space-y-6">
                    <input type="hidden" name="action" value="update-profile">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">

                    <div>
                        <label for="first_name" class="<?= e($members_label) ?>">First name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="<?= e((string) ($member['first_name'] ?? '')) ?>"
                            required
                            autocomplete="given-name"
                            class="<?= e(!empty($errors['first_name']) ? $members_input_invalid : $members_input) ?>"
                        >
                        <?php if (!empty($errors['first_name'])): ?>
                            <p class="<?= e($members_field_error) ?>"><?= e((string) $errors['first_name']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="last_name" class="<?= e($members_label) ?>">Last name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="<?= e((string) ($member['last_name'] ?? '')) ?>"
                            required
                            autocomplete="family-name"
                            class="<?= e(!empty($errors['last_name']) ? $members_input_invalid : $members_input) ?>"
                        >
                        <?php if (!empty($errors['last_name'])): ?>
                            <p class="<?= e($members_field_error) ?>"><?= e((string) $errors['last_name']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="phone" class="<?= e($members_label) ?>">Phone</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="<?= e((string) ($member['phone'] ?? '')) ?>"
                            maxlength="30"
                            autocomplete="tel"
                            class="<?= e(!empty($errors['phone']) ? $members_input_invalid : $members_input) ?>"
                        >
                        <?php if (!empty($errors['phone'])): ?>
                            <p class="<?= e($members_field_error) ?>"><?= e((string) $errors['phone']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="date_of_birth" class="<?= e($members_label) ?>">Date of birth</label>
                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="<?= e((string) ($member['date_of_birth'] ?? '')) ?>"
                            required
                            class="<?= e(!empty($errors['date_of_birth']) ? $members_input_invalid : $members_input) ?>"
                        >
                        <?php if (!empty($errors['date_of_birth'])): ?>
                            <p class="<?= e($members_field_error) ?>"><?= e((string) $errors['date_of_birth']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="<?= e($members_btn) ?>">Save changes</button>
                        <a href="index.php?action=profile" class="<?= e($members_btn_secondary) ?> text-center">Cancel</a>
                    </div>
                </form>
            </div>

<?php require __DIR__ . '/partials/members_shell_end.php'; ?>
