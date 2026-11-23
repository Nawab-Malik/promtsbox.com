<div class="page-hero container">
    <?php require VIEW_PATH . '/partials/breadcrumbs.php'; ?>
    <h1><?= e($category['name']) ?></h1>
    <?php if (!empty($category['description'])): ?>
        <p class="page-lead"><?= e($category['description']) ?></p>
    <?php endif; ?>
</div>
<section class="section-block">
    <div class="container layout-with-sidebar">
        <div class="layout-main">
        <?php if (empty($posts)): ?>
            <div class="empty-state"><p>No articles in this category yet. Check back soon or browse <a href="<?= e(base_url('categories')) ?>">other topics</a>.</p></div>
        <?php else: ?>
            <div class="post-grid">
                <?php foreach ($posts as $post): ?>
                    <?php require VIEW_PATH . '/partials/article-card.php'; ?>
                <?php endforeach; ?>
            </div>
            <?php if ($total_pages > 1): ?>
                <nav class="pagination" aria-label="Pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a class="<?= $i === (int)$page ? 'active' : '' ?>" href="<?= e(base_url('category/' . $category['slug'] . '?page=' . $i)) ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
        </div>
        <?php require VIEW_PATH . '/partials/sidebar-recent.php'; ?>
    </div>
</section>