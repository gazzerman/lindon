<?php
declare(strict_types=1);

$breadcrumbs = $breadcrumbs ?? [];
if ($breadcrumbs === []) {
    return;
}
?>
<nav aria-label="Breadcrumb" class="mb-4 text-sm text-slate-600">
    <ol class="flex flex-wrap items-center gap-2">
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <?php
            $label = (string) ($crumb['label'] ?? '');
            $href = $crumb['href'] ?? null;
            ?>
            <?php if ($i > 0): ?>
                <li class="select-none text-slate-400" aria-hidden="true">/</li>
            <?php endif; ?>
            <li class="inline-flex items-center">
                <?php if ($href !== null && $href !== ''): ?>
                    <a class="text-blue-600 hover:underline" href="<?= e((string) $href) ?>"><?= e($label) ?></a>
                <?php else: ?>
                    <span class="font-medium text-slate-900"><?= e($label) ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
