<section class="hero-banner">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">PromptsBox · promptsbox.com</p>
            <h1>AI prompts &amp; tools that turn ideas into scroll-stopping content</h1>
            <p class="hero-lead">Copy-ready prompts, free generators, and guides for Instagram, TikTok, and image AI — organized like a real library.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= e(base_url('category/ai-prompts')) ?>">Browse prompts</a>
                <a class="btn btn-ghost" href="<?= e(base_url('tools')) ?>">Try free tools</a>
            </div>
        </div>
        <div class="hero-visual">
            <img src="<?= e(base_url('assets/images/logo.png')) ?>" alt="PromptsBox" width="480" height="480" class="hero-logo" loading="eager">
        </div>
    </div>
</section>

<section class="section-block section-articles">
    <div class="container layout-with-sidebar">
        <div class="layout-main">
            <div class="section-head">
                <h2>Latest guides &amp; articles</h2>
                <a href="<?= e(base_url('blog')) ?>" class="section-link">See all articles</a>
            </div>
            <?php if (empty($latest_posts)): ?>
                <div class="empty-state">
                    <p>Your first articles will appear here. Add posts from the admin panel.</p>
                </div>
            <?php else: ?>
                <div class="post-grid post-grid--main">
                    <?php foreach ($latest_posts as $post): ?>
                        <?php require VIEW_PATH . '/partials/article-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php require VIEW_PATH . '/partials/sidebar-recent.php'; ?>
    </div>
</section>

<section class="section-block section-muted">
    <div class="container">
        <div class="section-head">
            <h2>Free creator tools</h2>
            <a href="<?= e(base_url('tools')) ?>" class="section-link">View all tools</a>
        </div>
        <div class="tool-scroll">
            <?php foreach ($featured_tools as $tool): ?>
                <a class="tool-chip" href="<?= e(base_url('tools/' . $tool['slug'])) ?>">
                    <strong><?= e($tool['name']) ?></strong>
                    <span><?= e(truncate($tool['description'] ?? '', 72)) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-head">
            <h2>Explore by topic</h2>
            <a href="<?= e(base_url('categories')) ?>" class="section-link">All categories</a>
        </div>
        <div class="category-grid">
            <?php foreach ($featured_categories as $cat): ?>
                <a class="category-tile" href="<?= e(base_url('category/' . $cat['slug'])) ?>">
                    <span class="category-tile-icon" aria-hidden="true">◆</span>
                    <h3><?= e($cat['name']) ?></h3>
                    <p><?= e(truncate($cat['description'] ?? '', 100)) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>