<?php if (!empty($breadcrumbs)): ?>
<nav class="breadcrumbs" aria-label="Breadcrumb">
    <?php foreach ($breadcrumbs as $i => $crumb): ?>
        <?php if ($i > 0): ?> / <?php endif; ?>
        <?php if (!empty($crumb['url'])): ?>
            <a href="<?= e($crumb['url']) ?>"><?= e($crumb['name']) ?></a>
        <?php else: ?>
            <span><?= e($crumb['name']) ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
<?php endif; ?>