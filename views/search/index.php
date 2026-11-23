<div class="page-hero container">
    <?php $breadcrumbs = [['name' => 'Home', 'url' => base_url()], ['name' => 'Search']]; require VIEW_PATH . '/partials/breadcrumbs.php'; ?>
    <h1>Search PromptsBox</h1>
    <form class="header-search header-search--large" action="<?= e(base_url('search')) ?>" method="get">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Keywords, topics, tools..." autofocus>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
<section class="section-block">
    <div class="container">
        <?php if ($q === ''): ?>
            <div class="empty-state"><p>Type a keyword to find guides and prompts.</p></div>
        <?php elseif (empty($results)): ?>
            <div class="empty-state"><p>No results for “<?= e($q) ?>”.</p></div>
        <?php else: ?>
            <div class="post-grid">
                <?php foreach ($results as $post): ?>
                    <?php require VIEW_PATH . '/partials/article-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>