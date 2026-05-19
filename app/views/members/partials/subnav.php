<?php
declare(strict_types=1);
?>
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <nav class="nav nav-pills">
            <a class="nav-link <?= ($current_page ?? '') === 'hello' ? 'active' : '' ?>" href="index.php?action=hello">Members Home</a>
            <a class="nav-link <?= ($current_page ?? '') === 'profile' ? 'active' : '' ?>" href="index.php?action=profile">Profile</a>
        </nav>
    </div>
</div>

<?php if (!empty($breadcrumbs)): ?>
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumbs as $crumb): ?>
                <?php if (!empty($crumb['url'])): ?>
                    <li class="breadcrumb-item"><a href="<?= e((string) $crumb['url']) ?>"><?= e((string) $crumb['label']) ?></a></li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= e((string) $crumb['label']) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>

