<div class="page-hero container">
    <?php
    $breadcrumbs = [['name' => 'Home', 'url' => base_url()], ['name' => 'Free tools']];
    require VIEW_PATH . '/partials/breadcrumbs.php';
    ?>
    <h1>Free creator tools</h1>
    <p class="page-lead">Generate bios, prompts, captions, and SEO meta in one click — no signup required.</p>
</div>
<section class="section-block">
    <div class="container tools-grid-page">
        <?php foreach ($tools as $tool): ?>
            <a class="category-tile tool-tile" href="<?= e(base_url('tools/' . $tool['slug'])) ?>">
                <h3><?= e($tool['name']) ?></h3>
                <p><?= e($tool['description'] ?? '') ?></p>
                <span class="section-link">Open tool →</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>