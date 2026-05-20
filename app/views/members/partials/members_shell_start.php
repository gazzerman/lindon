<?php
declare(strict_types=1);

$fn = trim((string) ($member['first_name'] ?? ''));
$ln = trim((string) ($member['last_name'] ?? ''));
if ($fn !== '' && $ln !== '') {
    $member_initials = strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1));
} elseif ($fn !== '') {
    $member_initials = strtoupper(substr($fn, 0, 2));
} else {
    $member_initials = strtoupper(substr((string) ($member['username'] ?? 'M'), 0, 2));
}

$page_title = $members_page_title ?? 'Linden — Members';
$page_description = $members_page_description ?? 'Your private Linden workspace.';
$members_hero_kicker = $members_hero_kicker ?? 'Members Area';
$members_hero_title = $members_hero_title ?? 'Members';
$members_hero_subtitle = $members_hero_subtitle ?? '';

$members_input = 'w-full border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground/50 focus:outline-none focus:border-accent transition-colors';
$members_input_invalid = $members_input . ' border-red-900/40';
$members_label = 'block text-[10px] uppercase tracking-editorial text-muted-foreground mb-2';
$members_btn = 'px-8 py-4 bg-foreground text-background text-[11px] uppercase tracking-editorial hover:bg-accent hover:text-accent-foreground transition-colors duration-500';
$members_btn_secondary = 'inline-block px-6 py-3 border border-border text-[11px] uppercase tracking-editorial text-foreground hover:border-accent hover:text-accent transition-colors';
$members_field_error = 'text-xs text-red-900 mt-1.5';

require __DIR__ . '/../../layout/linden_head.php';
?>

<div class="min-h-screen bg-background text-foreground">
    <nav class="flex justify-between items-center px-6 md:px-10 py-6 border-b border-border">
        <a href="../index.php" class="text-base md:text-lg font-serif font-medium uppercase tracking-editorial text-foreground">Linden</a>
        <div class="hidden md:flex gap-10 text-[11px] uppercase tracking-editorial font-medium text-muted-foreground">
            <a href="../index.php" class="hover:text-foreground transition-colors">House</a>
            <a href="index.php?action=hello" class="hover:text-foreground transition-colors">Members</a>
            <span class="text-accent">Account</span>
        </div>
        <a href="index.php?action=profile" class="size-9 rounded-full border border-border grid place-items-center text-[10px] font-serif italic tracking-wider hover:border-accent transition-colors" title="Profile"><?= e($member_initials) ?></a>
    </nav>

    <header class="relative h-[32vh] min-h-[220px] w-full overflow-hidden border-b border-border">
        <img
            src="../assets/images/interior-room.jpg"
            alt="A heritage drawing room with arched windows"
            width="1600"
            height="1200"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-[oklch(0.18_0.03_50/0.65)] via-[oklch(0.18_0.03_50/0.45)] to-[oklch(0.18_0.03_50/0.78)]"></div>
        <div class="relative z-10 h-full max-w-6xl mx-auto px-6 md:px-10 flex flex-col justify-end pb-8 md:pb-12">
            <span class="text-[10px] uppercase tracking-editorial text-background/70 block mb-3"><?= e($members_hero_kicker) ?></span>
            <h1 class="font-serif font-semibold text-3xl md:text-4xl tracking-display text-background text-pretty">
                <?= e($members_hero_title) ?>
            </h1>
            <?php if ($members_hero_subtitle !== ''): ?>
                <p class="mt-3 text-sm text-background/75 max-w-md leading-relaxed">
                    <?= e($members_hero_subtitle) ?>
                </p>
            <?php endif; ?>
        </div>
    </header>

    <section class="bg-background">
        <div class="max-w-3xl mx-auto px-6 md:px-10 py-12 md:py-20">
