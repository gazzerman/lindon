<?php
declare(strict_types=1);
?>
<?php require __DIR__ . '/partials/shell_start.php'; ?>
<?php require __DIR__ . '/partials/breadcrumb.php'; ?>

<div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-4 py-3">
        <h1 class="text-xl font-bold text-slate-900">Admin Dashboard</h1>
        <p class="text-sm text-slate-600">Simple admin area.</p>
    </div>
    <div class="px-4 py-4">
        <a class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50" href="index.php?action=users">Manage Users</a>
    </div>
</div>

<?php require __DIR__ . '/partials/shell_end.php'; ?>
