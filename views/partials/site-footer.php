<?php
use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;

$footerPages = Page::footerLinks();
$navCategories = Category::published();
$siteName = Setting::get('site_name', 'PromptsBox');
$tagline = Setting::get('site_tagline', '');
$logo = base_url('assets/images/logo.png');
?>
<footer class="site-footer">
    <div class="container footer-top">
        <div class="footer-brand">
            <div class="footer-logo-card">
                <img src="<?= e($logo) ?>" alt="<?= e($siteName) ?>" width="180" height="64" loading="lazy">
            </div>
            <p class="footer-site-name"><?= e($siteName) ?></p>
            <p><?= e($tagline) ?></p>
            <p class="footer-tagline">Discover · Create · Explore</p>
        </div>
        <div class="footer-col">
            <h4>Explore</h4>
            <a href="<?= e(base_url()) ?>">Home</a>
            <a href="<?= e(base_url('tools')) ?>">Free tools</a>
            <a href="<?= e(base_url('blog')) ?>">All guides</a>
            <a href="<?= e(base_url('categories')) ?>">Categories</a>
        </div>
        <div class="footer-col">
            <h4>Popular topics</h4>
            <?php foreach (array_slice($navCategories, 0, 5) as $cat): ?>
                <a href="<?= e(base_url('category/' . $cat['slug'])) ?>"><?= e($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <div class="footer-col">
            <h4>Legal &amp; trust</h4>
            <?php foreach ($footerPages as $pg): ?>
                <a href="<?= e(base_url('page/' . $pg['slug'])) ?>"><?= e($pg['title']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>&copy; <?= date('Y') ?> <?= e($siteName) ?> · promptsbox.com</span>
        <a href="<?= e(base_url('feed.xml')) ?>">RSS</a>
        <a href="<?= e(base_url('sitemap.xml')) ?>">Sitemap</a>
    </div>
</footer>