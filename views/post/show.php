<?php $font = $content_font ?? ['class' => 'font-poppins', 'family' => "'Poppins', sans-serif", 'href' => null]; ?>
<?php require VIEW_PATH . '/partials/content-font.php'; ?>

<div class="container layout-with-sidebar layout-with-sidebar--article">
    <div class="layout-main">
        <?php require VIEW_PATH . '/partials/breadcrumbs.php'; ?>
        <article class="article-shell">
            <header class="article-header">
                <p class="eyebrow"><?= e($post['category_name']) ?></p>
                <h1><?= e($post['title']) ?></h1>
                <p class="post-card-meta">
                    <?= e($post['author_name']) ?> · <?= e($read_minutes) ?> min read
                    <?php if (!empty($post['published_at'])): ?> · <?= e(date('M j, Y', strtotime($post['published_at']))) ?><?php endif; ?>
                    · <?= (int) ($post['view_count'] ?? 0) ?> views
                </p>
            </header>
            <?php if (!empty($post['featured_image'])): ?>
                <figure class="article-figure">
                    <img src="<?= e(post_image_url($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy" width="1200" height="630">
                </figure>
            <?php endif; ?>
            <nav id="postToc" class="article-toc" aria-label="Table of contents"></nav>
            <div class="content-body article-prose <?= e($font['class'] ?? 'font-poppins') ?>" id="postContent">
                <?= $post['content'] ?>
            </div>
        </article>
    </div>
    <?php require VIEW_PATH . '/partials/sidebar-recent.php'; ?>
</div>

<?php require VIEW_PATH . '/partials/more-like.php'; ?>

<script src="<?= e(base_url('assets/js/post-toc.js')) ?>" defer></script>