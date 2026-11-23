<?php
/** @var list<array> $more_like_posts */
/** @var array $more_like_category */
if (empty($more_like_posts)) {
    return;
}
$catName = $more_like_category['name'] ?? 'Guides';
$catSlug = $more_like_category['slug'] ?? '';
?>
<section class="more-like-band" aria-labelledby="more-like-heading">
    <div class="container">
        <div class="more-like-head">
            <h2 id="more-like-heading">More posts you might like</h2>
            <?php if ($catSlug !== ''): ?>
                <a href="<?= e(base_url('category/' . $catSlug)) ?>">More in <?= e($catName) ?> →</a>
            <?php endif; ?>
        </div>
        <div class="more-like-grid">
            <?php foreach ($more_like_posts as $mp): ?>
                <?php
                $img = post_image_url($mp['featured_image'] ?? '');
                $mins = read_time_minutes($mp['content'] ?? $mp['excerpt'] ?? '');
                $author = $mp['author_name'] ?? 'Editor';
                ?>
                <article class="more-like-card">
                    <a href="<?= e(base_url('blog/' . $mp['slug'])) ?>" class="more-like-media">
                        <?php if ($img): ?>
                            <img src="<?= e($img) ?>" alt="<?= e($mp['title']) ?>" loading="lazy">
                        <?php else: ?>
                            <span class="more-like-media-fallback" aria-hidden="true"></span>
                        <?php endif; ?>
                    </a>
                    <div class="more-like-body">
                        <a class="more-like-cat" href="<?= e(base_url('category/' . ($mp['category_slug'] ?? ''))) ?>"><?= e($mp['category_name'] ?? '') ?></a>
                        <h3><a href="<?= e(base_url('blog/' . $mp['slug'])) ?>"><?= e($mp['title']) ?></a></h3>
                        <p><?= e(truncate($mp['excerpt'] ?? $mp['content'] ?? '', 110)) ?></p>
                        <footer class="more-like-footer">
                            <span class="author-chip">
                                <span class="author-avatar" aria-hidden="true"><?= e(author_initials($author)) ?></span>
                                <?= e($author) ?>
                            </span>
                            <span><?= (int) $mins ?> min</span>
                            <span><?= (int) ($mp['view_count'] ?? 0) ?> views</span>
                        </footer>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>