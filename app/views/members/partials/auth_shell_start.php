<?php
declare(strict_types=1);

$page_title = $auth_page_title ?? 'Linden — Members';
$page_description = $auth_page_description ?? 'A discreet companion for the next generation of family stewards.';
$auth_card_max = $auth_card_max ?? 'max-w-md';
$auth_hero_kicker = $auth_hero_kicker ?? 'Members Area';
$auth_hero_title = $auth_hero_title ?? 'Members';
$auth_hero_subtitle = $auth_hero_subtitle ?? '';

$auth_input = 'w-full border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground/50 focus:outline-none focus:border-accent transition-colors';
$auth_input_invalid = $auth_input . ' border-red-900/40';
$auth_label = 'block text-[10px] uppercase tracking-editorial text-muted-foreground mb-2';
$auth_btn = 'w-full px-8 py-4 bg-foreground text-background text-[11px] uppercase tracking-editorial hover:bg-accent hover:text-accent-foreground transition-colors duration-500';
$auth_btn_secondary = 'inline-block px-6 py-3 border border-border text-[11px] uppercase tracking-editorial text-foreground hover:border-accent hover:text-accent transition-colors';
$auth_field_error = 'text-xs text-red-900 mt-1.5';

require __DIR__ . '/../../layout/linden_head.php';
?>

<div class="min-h-screen bg-background text-foreground">
    <nav class="flex justify-between items-center px-6 md:px-10 py-6 border-b border-border">
        <a href="../index.php" class="text-base md:text-lg font-serif font-medium uppercase tracking-editorial text-foreground">Linden</a>
        <div class="hidden md:flex gap-10 text-[11px] uppercase tracking-editorial font-medium text-muted-foreground">
            <a href="../index.php" class="hover:text-foreground transition-colors">House</a>
            <span class="text-accent">Members</span>
        </div>
        <a href="../index.php" class="md:hidden text-[10px] uppercase tracking-editorial text-muted-foreground hover:text-foreground transition-colors">
            House
        </a>
    </nav>

    <header class="relative h-[38vh] min-h-[260px] w-full overflow-hidden border-b border-border">
        <img
            src="../assets/images/interior-room.jpg"
            alt="A heritage drawing room with arched windows"
            width="1600"
            height="1200"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-[oklch(0.18_0.03_50/0.65)] via-[oklch(0.18_0.03_50/0.45)] to-[oklch(0.18_0.03_50/0.78)]"></div>
        <div class="relative z-10 h-full max-w-6xl mx-auto px-6 md:px-10 flex flex-col justify-end pb-10 md:pb-14">
            <span class="text-[10px] uppercase tracking-editorial text-background/70 block mb-3"><?= e($auth_hero_kicker) ?></span>
            <h1 class="font-serif font-semibold text-3xl md:text-5xl tracking-display text-background text-pretty">
                <?= e($auth_hero_title) ?>
            </h1>
            <?php if ($auth_hero_subtitle !== ''): ?>
                <p class="mt-4 text-sm text-background/75 max-w-md leading-relaxed">
                    <?= e($auth_hero_subtitle) ?>
                </p>
            <?php endif; ?>
        </div>
    </header>

    <section class="bg-background">
        <div class="<?= e($auth_card_max) ?> mx-auto px-6 md:px-10 py-16 md:py-24">
