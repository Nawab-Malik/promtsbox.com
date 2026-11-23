<?php
use App\Models\Category;
use App\Models\Page;

$navCategories = Category::published();
$headerPages = Page::headerLinks();
$assetLogo = base_url('assets/images/logo.png');
$siteName = \App\Models\Setting::get('site_name', 'PromptsBox');
?>
<header class="site-header">
    <div class="container header-bar">
        <a class="brand" href="<?= e(base_url()) ?>">
            <img class="brand-logo" src="<?= e($assetLogo) ?>" alt="<?= e($siteName) ?>" width="220" height="80">
        </a>
        <button type="button" class="nav-toggle" aria-label="Open menu" aria-expanded="false" id="navToggle">
            <span></span><span></span><span></span>
        </button>
        <ul class="site-nav" id="siteNav">
            <li><a href="<?= e(base_url()) ?>">Home</a></li>
            <li><a href="<?= e(base_url('tools')) ?>">Free Tools</a></li>
            <li class="has-dropdown">
                <button type="button" class="nav-drop-trigger" aria-expanded="false">Categories</button>
                <div class="nav-dropdown">
                    <a href="<?= e(base_url('categories')) ?>" class="nav-dropdown-all">All categories →</a>
                    <ul class="nav-dropdown-grid">
                        <?php foreach ($navCategories as $cat): ?>
                            <li><a href="<?= e(base_url('category/' . $cat['slug'])) ?>"><?= e($cat['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
            <li><a href="<?= e(base_url('blog')) ?>">Guides</a></li>
            <?php foreach ($headerPages as $pg): ?>
                <?php if (in_array($pg['slug'], ['about', 'contact'], true)): ?>
                    <li><a href="<?= e(base_url('page/' . $pg['slug'])) ?>"><?= e($pg['title']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <form class="header-search" action="<?= e(base_url('search')) ?>" method="get" role="search">
            <input type="search" name="q" placeholder="Search prompts..." aria-label="Search">
            <button type="submit" aria-label="Search submit">⌕</button>
        </form>
    </div>
</header>