<?php
declare(strict_types=1);
?>
<div class="bg-card border border-border p-8 md:p-10 mb-8">
    <p class="text-[10px] uppercase tracking-editorial text-accent mb-2">Quick quiz</p>
    <p class="font-serif text-xl mb-6"><?= e((string) ($lesson['question_text'] ?? '')) ?></p>
    <form method="post" action="index.php" class="space-y-4">
        <input type="hidden" name="action" value="complete-lesson">
        <input type="hidden" name="csrf_token" value="<?= e((string) $csrf_token) ?>">
        <input type="hidden" name="lesson_module_id" value="<?= e((string) ($lesson['id'] ?? 0)) ?>">
        <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
            <label class="flex gap-3 border border-border p-3 cursor-pointer">
                <input type="radio" name="selected_option" value="<?= e($option) ?>" required>
                <span class="text-sm">
                    <?= e($option) ?>.
                    <?= e((string) ($lesson['option_' . strtolower($option)] ?? '')) ?>
                </span>
            </label>
        <?php endforeach; ?>

        <div>
            <label for="reflection_text" class="<?= e($members_label) ?>">Reflection</label>
            <textarea id="reflection_text" name="reflection_text" rows="4" class="<?= e($members_input) ?>"><?= e((string) ($lesson['reflection_text'] ?? '')) ?></textarea>
        </div>

        <div>
            <label for="saved_question_text" class="<?= e($members_label) ?>">Optional question to save</label>
            <input id="saved_question_text" name="saved_question_text" type="text" class="<?= e($members_input) ?>" placeholder="Write one practical question to bring to the meeting">
        </div>

        <button type="submit" class="<?= e($members_btn) ?>">Submit lesson</button>
    </form>
</div>
