<?php
/** @var list<array> $sidebar_posts */
if (empty($sidebar_posts)) {
    return;
}
?>
<aside class="sidebar-panel" aria-label="Newest posts">
    <h2 class="sidebar-title">Newest posts</h2>
    <ul class="sidebar-list">
        <?php foreach ($sidebar_posts as $sp): ?>
            <?php
            $thumb = post_image_url($sp['featured_image'] ?? '');
            $mins = read_time_minutes($sp['content'] ?? $sp['excerpt'] ?? '');
            ?>
            <li class="sidebar-item">
                <a href="<?= e(base_url('blog/' . $sp['slug'])) ?>" class="sidebar-item-link">
                    <?php if ($thumb): ?>
                        <img class="sidebar-thumb" src="<?= e($thumb) ?>" alt="" loading="lazy" width="72" height="72">
                    <?php else: ?>
                        <span class="sidebar-thumb sidebar-thumb--empty" aria-hidden="true"></span>
                    <?php endif; ?>
                    <span class="sidebar-item-text">
                        <span class="sidebar-item-cat"><?= e($sp['category_name'] ?? '') ?></span>
                        <span class="sidebar-item-title"><?= e(truncate($sp['title'], 58)) ?></span>
                        <span class="sidebar-item-meta"><?= (int) $mins ?> min · <?= (int) ($sp['view_count'] ?? 0) ?> views</span>
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a class="sidebar-all" href="<?= e(base_url('blog')) ?>">View all guides →</a>
</aside>