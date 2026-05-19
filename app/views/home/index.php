<?php
declare(strict_types=1);

$life_events = [
    ['kicker' => 'Perspective', 'title' => 'An annual family meeting is coming up', 'body' => 'Arrive prepared. Navigate the agenda with a voice that is informed, measured, and respectful.'],
    ['kicker' => 'Initiation', 'title' => 'Joining my first family wealth conversation', 'body' => 'Understand the foundational terms, the unspoken etiquette, and your seat at the table.'],
    ['kicker' => 'Roles', 'title' => 'Understanding what is expected of me', 'body' => "Clarify your responsibilities  and your latitude  within the family's structure."],
    ['kicker' => 'Sensitivity', 'title' => 'Asking about inheritance without sounding greedy', 'body' => 'Frameworks for discussing the future from a place of curiosity rather than expectation.'],
    ['kicker' => 'Enterprise', 'title' => 'Learning about our family business', 'body' => "Bridge the distance between your perspective and the operations you'll one day shape."],
    ['kicker' => 'Purpose', 'title' => "Our family's values and purpose", 'body' => 'Articulate what holds the family together beyond its assets.'],
    ['kicker' => 'Impact', 'title' => 'I want to talk about philanthropy', 'body' => "Align shared resources with the causes that will define your generation's contribution."],
    ['kicker' => 'Structures', 'title' => 'Preparing for a trust distribution conversation', 'body' => 'De-mystify the mechanics of long-term financial structures before the meeting begins.'],
    ['kicker' => 'Governance', 'title' => 'How decisions are made in our family', 'body' => 'Understand the voting, the deference, and the quiet conventions that shape outcomes.'],
    ['kicker' => 'Dialogue', 'title' => 'Preparing for a difficult conversation', 'body' => 'Approach parents, siblings, or advisors with composure on subjects that matter most.'],
];

$marquee_items = ['Trust Counsel', 'Family Office', 'Governance', 'Philanthropy', 'Succession', 'Heritage'];

$preparation_steps = [
    ['n' => 'I', 't' => 'Choose your moment', 'b' => "Select an upcoming event or a conversation you'd like to handle with more confidence."],
    ['n' => 'II', 't' => 'Review curated prompts', 'b' => 'Each question is paired with quiet context  the why behind the wording.'],
    ['n' => 'III', 't' => 'Arrive composed', 'b' => 'Save your selections to a private deck. Practice. Or simply read once before you walk in.'],
];

require __DIR__ . '/../layout/linden_head.php';
?>

<div class="min-h-screen bg-background text-foreground selection:bg-accent/30">
    <nav class="absolute top-0 left-0 right-0 z-20 flex justify-between items-center px-6 md:px-10 py-6">
        <div class="text-base md:text-lg font-serif font-medium uppercase tracking-editorial text-ivory">Linden</div>
        <div class="hidden md:flex gap-10 text-[11px] uppercase tracking-editorial font-medium text-background/80">
            <a href="#approach" class="hover:text-background transition-colors">Approach</a>
            <a href="#journeys" class="hover:text-background transition-colors">Journeys</a>
            <a href="#privacy" class="hover:text-background transition-colors">Privacy</a>
            <a href="members/index.php" class="text-background hover:text-accent transition-colors">Members</a>
        </div>
        <a href="members/index.php" class="md:hidden text-[10px] uppercase tracking-editorial text-background border border-background/60 px-3 py-1.5">
            Members
        </a>
    </nav>

    <header class="relative h-[95vh] min-h-[640px] w-full overflow-hidden">
        <img
            src="assets/images/hero-meeting.jpg"
            alt="A composed young woman seated at a heritage family meeting table in a sunlit library"
            width="1920"
            height="1080"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-[oklch(0.18_0.03_50/0.55)] via-[oklch(0.20_0.03_50/0.35)] to-[oklch(0.18_0.03_50/0.75)]"></div>
        <div class="relative z-10 h-full max-w-6xl mx-auto px-6 md:px-10 flex flex-col justify-end pb-16 md:pb-24">
            <span class="text-[10px] md:text-[11px] uppercase tracking-editorial text-background/80 block mb-6">
                For the next generation of family stewards
            </span>
            <h1 class="font-serif font-semibold text-[2.5rem] md:text-7xl leading-[1.05] tracking-display max-w-3xl text-background text-balance">
                The most important questions are often the hardest to ask.
            </h1>
            <p class="mt-6 md:mt-8 text-base md:text-xl text-background/80 font-light max-w-xl leading-relaxed text-pretty">
                Linden is the discreet companion that prepares you for the meetings, milestones, and quiet inheritances that shape a family's future.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 sm:items-center">
                <a href="members/index.php" class="inline-block px-8 py-4 bg-background text-foreground text-[11px] uppercase tracking-editorial hover:bg-accent hover:text-accent-foreground transition-colors duration-500 text-center">
                    Enter the Members Area
                </a>
                <a href="#approach" class="text-[11px] uppercase tracking-editorial text-background/80 hover:text-background transition-colors text-center">
                    Discover the approach →
                </a>
            </div>
        </div>
    </header>

    <div class="bg-foreground text-background border-y border-foreground">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-5 flex flex-wrap justify-center md:justify-between gap-x-8 gap-y-2 text-[10px] uppercase tracking-editorial text-background/70">
            <?php foreach ($marquee_items as $item): ?>
                <span><?= e($item) ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <section id="approach" class="bg-background">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-24 md:py-36 grid md:grid-cols-12 gap-12 md:gap-16">
            <div class="md:col-span-5 md:sticky md:top-12 self-start">
                <span class="text-[11px] uppercase tracking-editorial text-accent">The Approach</span>
                <div class="mt-10 aspect-[3/4] overflow-hidden">
                    <img
                        src="assets/images/still-ledger.jpg"
                        alt="An aged leather family ledger and antique brass key on a walnut desk"
                        width="1080"
                        height="1600"
                        loading="lazy"
                        class="h-full w-full object-cover"
                    >
                </div>
            </div>
            <div class="md:col-span-7 md:pt-2">
                <p class="font-serif font-semibold text-3xl md:text-5xl leading-[1.15] tracking-display text-pretty">
                    A private companion for the meetings, milestones, and quiet inheritances that arrive without instructions.
                </p>
                <div class="h-px bg-border mt-12 mb-10"></div>
                <p class="text-base text-muted-foreground leading-relaxed max-w-xl">
                    Each prompt is composed with the same care a family office brings to a memorandum: precise, respectful, and shaped by people who understand that the right question, asked well, changes everything.
                </p>
                <p class="mt-6 text-base text-muted-foreground leading-relaxed max-w-xl">
                    Linden does not advise. It readies you  so that when the moment arrives, you arrive composed, articulate, and unmistakably present.
                </p>
            </div>
        </div>
    </section>

    <section id="journeys" class="border-t border-border bg-secondary/60">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-24 md:py-32">
            <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-14 pb-6 border-b border-border">
                <div>
                    <span class="text-[11px] uppercase tracking-editorial text-accent block mb-3">Guided Journeys</span>
                    <h2 class="font-serif font-semibold text-3xl md:text-5xl tracking-display text-pretty">Prepare for the moment.</h2>
                </div>
                <p class="text-muted-foreground text-sm italic">Ten quiet preparations.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-border border border-border">
                <?php foreach ($life_events as $i => $event): ?>
                    <div class="group bg-background p-8 md:p-10 flex flex-col">
                        <div class="flex justify-between items-baseline mb-8">
                            <span class="text-[10px] uppercase tracking-editorial text-muted-foreground"><?= e($event['kicker']) ?></span>
                            <span class="text-[10px] font-serif italic text-accent"><?= e(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        </div>
                        <h3 class="font-serif text-xl md:text-2xl leading-snug mb-4 group-hover:text-accent transition-colors">
                            <?= e($event['title']) ?>
                        </h3>
                        <p class="text-sm text-muted-foreground leading-relaxed"><?= e($event['body']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="relative bg-foreground text-background">
        <div class="relative h-[70vh] min-h-[480px] w-full overflow-hidden">
            <img
                src="assets/images/hands-legacy.jpg"
                alt="One generation handing a leather portfolio to the next across a walnut table"
                width="1920"
                height="1080"
                loading="lazy"
                class="absolute inset-0 h-full w-full object-cover opacity-80"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-[oklch(0.18_0.03_50/0.85)] via-[oklch(0.18_0.03_50/0.35)] to-transparent"></div>
            <div class="relative z-10 h-full max-w-6xl mx-auto px-6 md:px-10 flex items-center">
                <div class="max-w-xl">
                    <span class="text-[11px] uppercase tracking-editorial text-accent block mb-6">Between Generations</span>
                    <p class="font-serif font-semibold text-3xl md:text-5xl leading-[1.15] tracking-display text-background text-pretty">
                        Inheritance is rarely about what is given. It is about how it is received.
                    </p>
                    <p class="mt-8 text-background/75 leading-relaxed max-w-md">
                        Linden was built with senior trustees, estate counsel, and next-generation members who learned the etiquette of legacy the slow way  and wished they had not.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-background">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-24 md:py-32">
            <div class="max-w-2xl mb-16">
                <span class="text-[11px] uppercase tracking-editorial text-accent">The Preparation</span>
                <h2 class="mt-4 font-serif font-semibold text-3xl md:text-5xl tracking-display text-pretty">
                    How a family meeting becomes a conversation you helped shape.
                </h2>
            </div>
            <div class="grid md:grid-cols-3 gap-px bg-border border border-border">
                <?php foreach ($preparation_steps as $step): ?>
                    <div class="bg-background p-10 md:p-12">
                        <div class="font-serif italic text-accent text-2xl mb-10"><?= e($step['n']) ?></div>
                        <h3 class="font-serif text-2xl leading-snug mb-4"><?= e($step['t']) ?></h3>
                        <p class="text-sm text-muted-foreground leading-relaxed"><?= e($step['b']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="border-t border-border bg-secondary/60">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-24 md:py-32 grid md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-6 order-2 md:order-1">
                <span class="text-[11px] uppercase tracking-editorial text-accent">The Members Area</span>
                <h2 class="mt-4 font-serif font-semibold text-3xl md:text-5xl tracking-display text-pretty">
                    A quiet, private workspace  yours alone.
                </h2>
                <p class="mt-6 text-muted-foreground leading-relaxed max-w-md">
                    Saved decks of prompts. Upcoming preparations. A library of sample questions drawn from sessions with governance counsel and senior trustees. Visible only to you.
                </p>
                <a href="members/index.php" class="mt-10 inline-block px-8 py-4 bg-foreground text-background text-[11px] uppercase tracking-editorial hover:bg-accent hover:text-accent-foreground transition-colors duration-500">
                    Preview the Members Area
                </a>
            </div>
            <div class="md:col-span-6 order-1 md:order-2">
                <div class="aspect-[4/5] overflow-hidden">
                    <img
                        src="assets/images/interior-room.jpg"
                        alt="A heritage drawing room with tall arched windows and a single armchair"
                        width="1600"
                        height="1200"
                        loading="lazy"
                        class="h-full w-full object-cover"
                    >
                </div>
            </div>
        </div>
    </section>

    <section id="privacy" class="bg-background border-t border-border">
        <div class="max-w-3xl mx-auto px-6 md:px-10 py-24 md:py-32 text-center">
            <div class="mx-auto w-px h-16 bg-border mb-10"></div>
            <span class="text-[11px] uppercase tracking-editorial text-accent">Discretion, by design</span>
            <h2 class="mt-6 font-serif font-semibold text-3xl md:text-4xl tracking-display text-pretty">
                What is shared with Linden remains with you.
            </h2>
            <p class="mt-8 text-muted-foreground leading-relaxed text-pretty">
                Encryption is our standard. Discretion is our culture. Notes and prompt selections are visible only to you  never to your advisors, never to your family, never to us.
            </p>
            <div class="mt-12 flex flex-wrap justify-center gap-x-10 gap-y-3 text-[10px] uppercase tracking-editorial text-muted-foreground">
                <span>End-to-end encrypted</span>
                <span>·</span>
                <span>No third-party sharing</span>
                <span>·</span>
                <span>Delete everything, instantly</span>
            </div>
        </div>
    </section>

    <footer class="bg-foreground text-background">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-16 md:py-20 flex flex-col md:flex-row justify-between gap-12">
            <div class="max-w-sm">
                <div class="text-xl font-serif font-medium uppercase tracking-editorial text-ivory mb-6">Linden</div>
                <p class="text-xs text-background/60 leading-loose">
                    A discreet companion for the conversations that shape a family's future.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-16">
                <div class="space-y-4">
                    <span class="text-[10px] uppercase tracking-editorial font-medium text-background/80">Program</span>
                    <nav class="flex flex-col gap-2 text-sm text-background/60">
                        <a href="#journeys" class="hover:text-accent">Guided Journeys</a>
                        <a href="members/index.php" class="hover:text-accent">Members Area</a>
                        <a href="#" class="hover:text-accent">Member Workshops</a>
                    </nav>
                </div>
                <div class="space-y-4">
                    <span class="text-[10px] uppercase tracking-editorial font-medium text-background/80">House</span>
                    <nav class="flex flex-col gap-2 text-sm text-background/60">
                        <a href="#approach" class="hover:text-accent">Principles</a>
                        <a href="#privacy" class="hover:text-accent">Security</a>
                        <a href="#" class="hover:text-accent">Contact</a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="border-t border-background/15">
            <div class="max-w-6xl mx-auto px-6 md:px-10 py-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-[10px] uppercase tracking-editorial text-background/50">
                <span>© 2026 Linden &amp; Co.</span>
                <span>A discreet digital advisor</span>
            </div>
        </div>
    </footer>
</div>

<?php require __DIR__ . '/../layout/linden_foot.php'; ?>
