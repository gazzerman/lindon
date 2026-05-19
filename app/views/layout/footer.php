<?php
declare(strict_types=1);
?>
    </main>
    <hr>
    <footer>
        <small>&copy; <?= e(date('Y')) ?> My Sample Site</small>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($extra_scripts)): ?>
<?= $extra_scripts ?>
<?php endif; ?>
</body>
</html>

