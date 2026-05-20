<?php
declare(strict_types=1);

$current = (string) ($current_page ?? '');
$nav_link = static function (string $page, string $href, string $label) use ($current): string {
    $active = $current === $page;
    $class = $active
        ? 'text-accent border-accent'
        : 'text-muted-foreground border-transparent hover:text-foreground hover:border-border';

    return '<a href="' . e($href) . '" class="pb-3 border-b-2 ' . $class . ' transition-colors">' . e($label) . '</a>';
};
?>

<nav class="flex flex-wrap gap-x-8 gap-y-2 mb-6 text-[11px] uppercase tracking-editorial font-medium border-b border-border">
    <?= $nav_link('hello', 'index.php?action=hello', 'Members Home') ?>
    <?= $nav_link('profile', 'index.php?action=profile', 'Profile') ?>
</nav>

<?php if (!empty($breadcrumbs)): ?>
    <nav aria-label="Breadcrumb" class="mb-8 text-xs text-muted-foreground">
        <ol class="flex flex-wrap items-center gap-2">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>
                <?php if ($i > 0): ?>
                    <li aria-hidden="true" class="text-border">/</li>
                <?php endif; ?>
                <li>
                    <?php if (!empty($crumb['url'])): ?>
                        <a href="<?= e((string) $crumb['url']) ?>" class="hover:text-accent transition-colors"><?= e((string) $crumb['label']) ?></a>
                    <?php else: ?>
                        <span class="text-foreground"><?= e((string) $crumb['label']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>
