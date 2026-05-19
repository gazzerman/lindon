<?php
declare(strict_types=1);

/** @var array<int, array<string, mixed>> $members */
/** @var string $csrf_token */
/** @var string $users_sort_dir */
/** @var int $current_admin_member_id */
/** @var array{type: string, message: string}|null $users_flash */

$next_sort_dir = $users_sort_dir === 'desc' ? 'asc' : 'desc';
$sort_link_href = 'index.php?action=users&dir=' . rawurlencode($next_sort_dir);
$sort_caption = $users_sort_dir === 'desc' ? 'Newest first' : 'Oldest first';

require __DIR__ . '/partials/shell_start.php';
require __DIR__ . '/partials/breadcrumb.php';
?>

<div class="mb-4">
    <a class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50" href="index.php">Back</a>
</div>

<?php if ($users_flash !== null): ?>
    <?php
    $flash_ok = $users_flash['type'] === 'ok';
    $box = $flash_ok
        ? 'border-emerald-200 bg-emerald-50 text-emerald-900'
        : 'border-amber-200 bg-amber-50 text-amber-950';
    ?>
    <div class="mb-4 rounded-lg border px-4 py-3 text-sm <?= $box ?>">
        <?= e($users_flash['message']) ?>
    </div>
<?php endif; ?>

<div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-4 py-3">
        <h1 class="text-xl font-bold text-slate-900">Users</h1>
        <p class="text-sm text-slate-600">Search by name, username, or email (three or more characters). Use the checkboxes to update verification, admin, or ban status.</p>
        <div class="mt-3 max-w-md">
            <label for="user-filter-q" class="mb-1 block text-xs font-medium text-slate-600">Filter</label>
            <input
                id="user-filter-q"
                type="search"
                autocomplete="off"
                placeholder="Name, username, or email..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
            <p id="user-filter-hint" class="mt-1 hidden text-xs text-slate-500">Type at least 3 characters to filter.</p>
            <p id="user-filter-empty" class="mt-1 hidden text-xs text-amber-800">No rows match that filter.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">
                        <a href="<?= e($sort_link_href) ?>" class="text-blue-700 hover:underline">Joined</a>
                        <span class="block text-xs font-normal text-slate-500"><?= e($sort_caption) ?></span>
                    </th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">Username</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">Email</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">First name</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">Last name</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">Verified</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">Admin</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-slate-700">Banned</th>
                </tr>
            </thead>
            <tbody id="user-table-body" class="divide-y divide-slate-100 bg-white">
                <?php foreach ($members as $row): ?>
                    <?php
                    $mid = (int) ($row['id'] ?? 0);
                    $is_self = $mid === $current_admin_member_id;
                    $verified = (int) ($row['is_verified'] ?? 0) === 1;
                    $admin = (int) ($row['is_admin'] ?? 0) === 1;
                    $banned = (int) ($row['is_banned'] ?? 0) === 1;
                    $created_raw = (string) ($row['created_at'] ?? '');
                    $created_ts = strtotime($created_raw);
                    $created_label = $created_ts !== false ? date('M j, Y g:i A', $created_ts) : ($created_raw !== '' ? $created_raw : '-');
                    $u = strtolower((string) ($row['username'] ?? ''));
                    $em = strtolower((string) ($row['email'] ?? ''));
                    $fn = strtolower((string) ($row['first_name'] ?? ''));
                    $ln = strtolower((string) ($row['last_name'] ?? ''));
                    $filter_blob = trim($u . ' ' . $em . ' ' . $fn . ' ' . $ln);
                    ?>
                    <tr class="hover:bg-slate-50" data-user-filter="<?= e($filter_blob) ?>">
                        <td class="whitespace-nowrap px-4 py-3 text-slate-700"><?= e($created_label) ?></td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-900"><?= e((string) ($row['username'] ?? '')) ?></td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-700"><?= e((string) ($row['email'] ?? '')) ?></td>
                        <td class="px-4 py-3 text-slate-700"><?= e((string) ($row['first_name'] ?? '')) ?></td>
                        <td class="px-4 py-3 text-slate-700"><?= e((string) ($row['last_name'] ?? '')) ?></td>
                        <td class="px-4 py-3">
                            <?php
                            $member_id = $mid;
                            $flag = 'verified';
                            $is_on = $verified;
                            $disabled = false;
                            require __DIR__ . '/partials/user_flag_toggle.php';
                            ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php
                            $member_id = $mid;
                            $flag = 'admin';
                            $is_on = $admin;
                            $disabled = $is_self && $admin;
                            require __DIR__ . '/partials/user_flag_toggle.php';
                            ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php
                            $member_id = $mid;
                            $flag = 'banned';
                            $is_on = $banned;
                            $disabled = $is_self;
                            require __DIR__ . '/partials/user_flag_toggle.php';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($members === []): ?>
        <p class="px-4 py-6 text-center text-sm text-slate-500">No members yet.</p>
    <?php endif; ?>
</div>

<script>
(function () {
    var input = document.getElementById('user-filter-q');
    var tbody = document.getElementById('user-table-body');
    if (!input || !tbody) return;
    var rows = tbody.querySelectorAll('tr[data-user-filter]');
    var hint = document.getElementById('user-filter-hint');
    var empty = document.getElementById('user-filter-empty');

    function apply() {
        var q = input.value.trim().toLowerCase();
        if (q.length > 0 && q.length < 3) {
            if (hint) hint.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            for (var i = 0; i < rows.length; i++) rows[i].style.display = '';
            return;
        }
        if (hint) hint.classList.add('hidden');
        if (q.length < 3) {
            if (empty) empty.classList.add('hidden');
            for (var j = 0; j < rows.length; j++) rows[j].style.display = '';
            return;
        }
        var any = false;
        for (var k = 0; k < rows.length; k++) {
            var tr = rows[k];
            var hay = (tr.getAttribute('data-user-filter') || '').toLowerCase();
            var show = hay.indexOf(q) !== -1;
            tr.style.display = show ? '' : 'none';
            if (show) any = true;
        }
        if (empty) empty.classList.toggle('hidden', any || rows.length === 0);
    }

    input.addEventListener('input', apply);
    input.addEventListener('search', apply);
})();
</script>

<?php require __DIR__ . '/partials/shell_end.php'; ?>
