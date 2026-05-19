<?php
declare(strict_types=1);

$script_name = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
$is_members_area = strpos($script_name, '/members/') !== false;
$root_prefix = $is_members_area ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sample Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
<div class="container py-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border rounded px-3">
        <div class="container-fluid p-0">
            <span class="navbar-brand mb-0 h1">My Sample Site</span>
            <div>
                <a class="nav-link d-inline-block px-2 <?= str_ends_with($script_name, '/index.php') && !$is_members_area ? 'fw-semibold text-primary' : '' ?>" href="<?= e($root_prefix) ?>index.php">Homepage</a>
                <a class="nav-link d-inline-block px-2 <?= str_ends_with($script_name, '/about.php') ? 'fw-semibold text-primary' : '' ?>" href="<?= e($root_prefix) ?>about.php">About Us</a>
                <a class="nav-link d-inline-block px-2 <?= str_ends_with($script_name, '/contact.php') ? 'fw-semibold text-primary' : '' ?>" href="<?= e($root_prefix) ?>contact.php">Contact Us</a>
                <a class="nav-link d-inline-block px-2 <?= $is_members_area ? 'fw-semibold text-primary' : '' ?>" href="<?= e($root_prefix) ?>members/index.php">Members</a>
            </div>
        </div>
    </nav>

    <main class="mt-4">

