<?php
declare(strict_types=1);

$page_title = $page_title ?? 'Admin';
$xadmin_action = $xadmin_action ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="mx-auto w-full max-w-6xl px-4 py-4">
    <nav class="mb-4 rounded-lg border border-slate-200 bg-white p-3" aria-label="Admin navigation">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <span class="text-lg font-semibold text-slate-800">Admin</span>
            <div class="flex flex-wrap items-center gap-1 text-sm">
                <a class="rounded px-2 py-1 hover:bg-slate-100 text-slate-700" href="../">Homepage</a>
                <a class="rounded px-2 py-1 hover:bg-slate-100 text-slate-700" href="../members/">Members</a>
                <a class="rounded px-2 py-1 hover:bg-slate-100 <?= $xadmin_action === 'home' ? 'font-semibold text-blue-600' : 'text-slate-700' ?>" href="index.php">Admin home</a>
                <a class="rounded px-2 py-1 hover:bg-slate-100 <?= $xadmin_action === 'users' ? 'font-semibold text-blue-600' : 'text-slate-700' ?>" href="index.php?action=users">Users</a>
            </div>
        </div>
    </nav>
    <main>
