<?php
declare(strict_types=1);

if (!empty($flash)): ?>
    <div
        class="mb-6 border px-4 py-3 text-sm leading-relaxed <?= $flash['type'] === 'error' ? 'border-red-900/20 bg-red-50 text-red-950' : 'border-accent/30 bg-accent/10 text-foreground' ?>"
        role="alert"
    >
        <?= e($flash['message']) ?>
    </div>
<?php endif;

foreach ($auth_errors ?? [] as $message) {
    if ($message === '' || $message === null) {
        continue;
    }
    ?>
    <div class="mb-6 border border-red-900/20 bg-red-50 px-4 py-3 text-sm text-red-950 leading-relaxed" role="alert">
        <?= e((string) $message) ?>
    </div>
    <?php
}
