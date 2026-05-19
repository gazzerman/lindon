<?php
declare(strict_types=1);

$sample_questions = [
    ['kicker' => 'On values', 'text' => 'What were the original intentions behind establishing this trust, and how have they evolved?'],
    ['kicker' => 'On contribution', 'text' => 'Where in the family portfolio would you suggest I begin to develop a genuine understanding?'],
    ['kicker' => 'On succession', 'text' => 'How do you imagine my role in the family changing over the next decade?'],
    ['kicker' => 'On philanthropy', 'text' => 'Which of our giving commitments are you most proud of, and why?'],
    ['kicker' => 'On governance', 'text' => 'Which decisions are mine to make today, and which will become mine in time?'],
    ['kicker' => 'On dialogue', 'text' => 'Is there a conversation between us that you wish we had begun sooner?'],
];

$first_name = trim((string) ($member['first_name'] ?? ''));
$display_name = $first_name !== '' ? $first_name : (string) ($member['username'] ?? 'Member');

$fn = trim((string) ($member['first_name'] ?? ''));
$ln = trim((string) ($member['last_name'] ?? ''));
if ($fn !== '' && $ln !== '') {
    $initials = strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1));
} elseif ($fn !== '') {
    $initials = strtoupper(substr($fn, 0, 2));
} else {
    $initials = strtoupper(substr((string) ($member['username'] ?? 'M'), 0, 2));
}

$page_title = 'Linden  Members';
require __DIR__ . '/../layout/linden_head.php';
?>

<div class="min-h-screen bg-background text-foreground">
    <nav class="flex justify-between items-center px-6 md:px-10 py-6 border-b border-border">
        <a href="../index.php" class="text-base md:text-lg font-serif font-medium uppercase tracking-editorial text-foreground">Linden</a>
        <div class="hidden md:flex gap-10 text-[11px] uppercase tracking-editorial font-medium text-muted-foreground">
            <a href="../index.php" class="hover:text-foreground transition-colors">House</a>
            <span class="text-accent">Members</span>
        </div>
        <a href="index.php?action=profile" class="size-9 rounded-full border border-border grid place-items-center text-[10px] font-serif italic tracking-wider hover:border-accent transition-colors" title="Profile"><?= e($initials) ?></a>
    </nav>

    <header class="relative h-[40vh] min-h-[280px] w-full overflow-hidden border-b border-border">
        <img
            src="../assets/images/interior-room.jpg"
            alt="A heritage drawing room with arched windows"
            width="1600"
            height="1200"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-[oklch(0.18_0.03_50/0.65)] via-[oklch(0.18_0.03_50/0.45)] to-[oklch(0.18_0.03_50/0.75)]"></div>
        <div class="relative z-10 h-full max-w-6xl mx-auto px-6 md:px-10 flex flex-col justify-end pb-10 md:pb-14">
            <span class="text-[10px] uppercase tracking-editorial text-background/70 block mb-3">Members Area</span>
            <h1 class="font-serif font-semibold text-3xl md:text-5xl tracking-display text-background text-pretty">
                Good afternoon, <?= e($display_name) ?>.
            </h1>
        </div>
    </header>

    <section class="bg-background">
        <div class="max-w-5xl mx-auto px-6 md:px-10 py-16 md:py-24">
            <div class="bg-card border border-border p-8 md:p-12">
                <div class="flex justify-between items-end mb-10 pb-8 border-b border-border">
                    <div>
                        <span class="text-[10px] uppercase tracking-editorial text-muted-foreground">Currently preparing</span>
                        <h2 class="font-serif text-3xl mt-3">Annual Trustee Review</h2>
                        <p class="text-xs text-muted-foreground mt-2">Friday, 12 December · 10:00 AM · Library, Belgrave House</p>
                    </div>
                    <span class="text-[10px] uppercase tracking-editorial text-accent hidden sm:block">75% complete</span>
                </div>
                <div class="grid sm:grid-cols-2 gap-px bg-border border border-border">
                    <div class="bg-card p-8">
                        <span class="text-[10px] uppercase tracking-editorial text-muted-foreground block mb-4">Saved Deck</span>
                        <h3 class="font-serif text-xl mb-2">The Language of Fiduciary Duty</h3>
                        <p class="text-sm text-muted-foreground">7 questions selected for rehearsal</p>
                        <a href="#library" class="mt-6 inline-block text-[10px] uppercase tracking-editorial text-accent">Review deck →</a>
                    </div>
                    <div class="bg-card p-8">
                        <span class="text-[10px] uppercase tracking-editorial text-muted-foreground block mb-4">Next Suggested</span>
                        <h3 class="font-serif text-xl mb-2">Onboarding the Family Council</h3>
                        <p class="text-sm text-muted-foreground">A short reading before your meeting.</p>
                        <a href="#library" class="mt-6 inline-block text-[10px] uppercase tracking-editorial text-accent">Open guide →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="library" class="bg-foreground text-background py-24 md:py-32">
        <div class="max-w-6xl mx-auto px-6 md:px-10 grid md:grid-cols-12 gap-12 md:gap-20 items-start">
            <div class="md:col-span-5 md:sticky md:top-12">
                <span class="text-accent text-[11px] uppercase tracking-editorial block mb-6">From the Library</span>
                <h2 class="font-serif font-semibold text-3xl md:text-5xl leading-tight tracking-display text-pretty">
                    Refined inquiry, drawn from quiet rooms.
                </h2>
                <p class="mt-8 text-background/60 leading-relaxed max-w-md">
                    A sample from your curated reading  composed with family governance counsel, trustees, and next-generation members. Every question is a starting point, not a script.
                </p>
                <div class="h-px bg-background/15 mt-12"></div>
                <p class="mt-6 text-[10px] uppercase tracking-editorial text-background/50">Six of 412 in your library</p>
            </div>
            <div class="md:col-span-7 space-y-px bg-background/10">
                <?php foreach ($sample_questions as $i => $question): ?>
                    <article class="p-8 md:p-10 bg-foreground">
                        <div class="flex justify-between items-baseline mb-5">
                            <span class="text-[10px] uppercase tracking-editorial text-accent"><?= e($question['kicker']) ?></span>
                            <span class="text-[10px] font-serif italic text-background/40"><?= e(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        </div>
                        <p class="font-serif text-xl md:text-2xl italic leading-snug text-background/90 text-pretty">
                            “<?= e($question['text']) ?>”
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="border-t border-border bg-background">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-10 flex flex-col sm:flex-row justify-between items-center gap-3 text-[10px] uppercase tracking-editorial text-muted-foreground">
            <span>© 2026 Linden &amp; Co.</span>
            <a href="../index.php" class="hover:text-accent">Return to the House</a>
        </div>
    </footer>
</div>

<?php require __DIR__ . '/../layout/linden_foot.php'; ?>
