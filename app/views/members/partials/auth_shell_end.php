<?php
declare(strict_types=1);

$auth_footnote = $auth_footnote ?? 'End-to-end encrypted · No third-party sharing';
?>
            <?php if ($auth_footnote !== ''): ?>
                <p class="mt-10 text-center text-[10px] uppercase tracking-editorial text-muted-foreground leading-loose">
                    <?= e($auth_footnote) ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <footer class="border-t border-border bg-background">
        <div class="max-w-6xl mx-auto px-6 md:px-10 py-10 flex flex-col sm:flex-row justify-between items-center gap-3 text-[10px] uppercase tracking-editorial text-muted-foreground">
            <span>© 2026 Linden &amp; Co.</span>
            <a href="../index.php" class="hover:text-accent transition-colors">Return to the House</a>
        </div>
    </footer>
</div>

<?php require __DIR__ . '/../../layout/linden_foot.php'; ?>
