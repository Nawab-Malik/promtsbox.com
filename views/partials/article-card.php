<?php
/** @var array $post */
$img = $post['featured_image'] ?? '';
if ($img !== '' && !str_starts_with($img, 'http')) {
    $img = base_url(ltrim($img, '/'));
}
$mins = read_time_minutes($post['content'] ?? $post['excerpt'] ?? '');
?>
<article class="post-card">
    <a class="post-card-media" href="<?= e(base_url('blog/' . $post['slug'])) ?>">
        <?php if ($img): ?>
            <img src="<?= e($img) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
        <?php else: ?>
            <div class="post-card-placeholder" aria-hidden="true"></div>
        <?php endif; ?>
        <?php if (!empty($post['category_name'])): ?>
            <span class="post-card-badge"><?= e($post['category_name']) ?></span>
        <?php endif; ?>
    </a>
    <div class="post-card-body">
        <h3 class="post-card-title"><a href="<?= e(base_url('blog/' . $post['slug'])) ?>"><?= e($post['title']) ?></a></h3>
        <p class="post-card-excerpt"><?= e(truncate($post['excerpt'] ?? $post['content'] ?? '', 130)) ?></p>
        <p class="post-card-meta">
            <?= e($post['author_name'] ?? 'Editor') ?> · <?= (int) $mins ?> min read
            <?php if (!empty($post['published_at'])): ?> · <?= e(date('M j, Y', strtotime($post['published_at']))) ?><?php endif; ?>
        </p>
    </div>
</article>