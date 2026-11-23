<div class="container page-hero">
    <?php require VIEW_PATH . '/partials/breadcrumbs.php'; ?>
    <h1><?= e($tool['name']) ?></h1>
    <p class="page-lead"><?= e($tool['description'] ?? '') ?></p>
</div>
<div class="container section">
    <?php require VIEW_PATH . '/partials/tool-form.php'; ?>
    <div class="article-content">
        <?= $tool['long_content'] ?? '' ?>
    </div>
</div>