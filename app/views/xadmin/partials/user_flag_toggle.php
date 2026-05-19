<?php
declare(strict_types=1);

/**
 * @var string $csrf_token
 * @var int $member_id
 * @var string $users_sort_dir
 * @var string $flag verified|admin|banned
 * @var bool $is_on
 * @var bool $disabled
 */
?>
<form method="post" class="inline-flex items-center" action="index.php?action=users">
    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
    <input type="hidden" name="form" value="member_flag_toggle">
    <input type="hidden" name="member_id" value="<?= (int) $member_id ?>">
    <input type="hidden" name="flag" value="<?= e($flag) ?>">
    <input type="hidden" name="redirect_dir" value="<?= e($users_sort_dir) ?>">
    <input type="hidden" name="value" value="<?= $is_on ? '1' : '0' ?>" data-user-flag-value>
    <label class="inline-flex cursor-pointer items-center gap-2 <?= $disabled ? 'cursor-not-allowed opacity-60' : '' ?>">
        <span class="sr-only"><?= e($flag) ?></span>
        <input
            type="checkbox"
            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed"
            <?= $is_on ? 'checked' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
            onchange="const h=this.form.querySelector('[data-user-flag-value]');if(h){h.value=this.checked?'1':'0';}this.form.requestSubmit();"
        >
    </label>
</form>
