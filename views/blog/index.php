<div class="page-hero container">
    <?php
    $breadcrumbs = [['name' => 'Home', 'url' => base_url()], ['name' => 'Guides']];
    require VIEW_PATH . '/partials/breadcrumbs.php';
    ?>
    <h1>Guides &amp; articles</h1>
    <p class="page-lead">Tutorials, prompt lists, and creator tips — fresh from PromptsBox.</p>
</div>
<section class="section-block">
    <div class="container layout-with-sidebar">
        <div class="layout-main">
        <?php if (empty($posts)): ?>
            <div class="empty-state"><p>No articles published yet.</p></div>
        <?php else: ?>
            <div class="post-grid">
                <?php foreach ($posts as $post): ?>
                    <?php require VIEW_PATH . '/partials/article-card.php'; ?>
                <?php endforeach; ?>
            </div>
            <?php if ($total_pages > 1): ?>
                <nav class="pagination" aria-label="Pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a class="<?= $i === (int)$page ? 'active' : '' ?>" href="<?= e(base_url('blog?page=' . $i)) ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
        </div>
        <?php require VIEW_PATH . '/partials/sidebar-recent.php'; ?>
    </div>
</section>