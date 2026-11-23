<div class="page-hero container">
    <?php
    $breadcrumbs = [['name' => 'Home', 'url' => base_url()], ['name' => 'Categories']];
    require VIEW_PATH . '/partials/breadcrumbs.php';
    ?>
    <h1>All categories</h1>
    <p class="page-lead">Pick a topic — each category has its own archive of guides and prompts.</p>
</div>
<section class="section-block">
    <div class="container category-grid category-grid--full">
        <?php foreach ($categories as $cat): ?>
            <a class="category-tile" href="<?= e(base_url('category/' . $cat['slug'])) ?>">
                <span class="category-tile-icon" aria-hidden="true">◆</span>
                <h3><?= e($cat['name']) ?></h3>
                <p><?= e(truncate($cat['description'] ?? '', 120)) ?></p>
                <?php $c = $post_counts[$cat['id']] ?? 0; ?>
                <span class="category-count"><?= (int) $c ?> article<?= $c === 1 ? '' : 's' ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>