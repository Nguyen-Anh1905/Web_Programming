<section class="card">
    <h1><?= htmlspecialchars($title ?? 'CRM System', ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($message ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <p class="hint">Front controller, router, autoload PSR-4 and 404 page are configured.</p>
</section>
