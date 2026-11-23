<?php
use App\Models\Setting;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title'] ?? 'PromptsBox') ?></title>
    <meta name="description" content="<?= e($seo['description'] ?? '') ?>">
    <meta name="robots" content="<?= e($seo['robots'] ?? 'index,follow') ?>">
    <meta name="theme-color" content="#1e3a8a">
    <link rel="icon" href="<?= e(base_url('favicon.png')) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= e(base_url('assets/images/logo.png')) ?>">
    <?php if (!empty($seo['canonical'])): ?>
        <link rel="canonical" href="<?= e($seo['canonical']) ?>">
    <?php endif; ?>
    <?php if (!empty($seo['verification'])): ?>
        <meta name="google-site-verification" content="<?= e($seo['verification']) ?>">
    <?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($seo['og_title'] ?? $seo['title']) ?>">
    <meta property="og:description" content="<?= e($seo['og_description'] ?? $seo['description']) ?>">
    <meta property="og:url" content="<?= e($seo['canonical'] ?? base_url()) ?>">
    <meta property="og:image" content="<?= e($seo['og_image'] ?? base_url('assets/images/logo.png')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="<?= e(base_url('site.webmanifest')) ?>">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css?v=4')) ?>">
    <link rel="alternate" type="application/rss+xml" title="<?= e(Setting::get('site_name', 'PromptsBox')) ?>" href="<?= e(base_url('feed.xml')) ?>">
    <script type="application/ld+json"><?= \App\Services\SeoService::organizationJsonLd() ?></script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?= e(Setting::get('site_name', 'PromptsBox')) ?>",
      "url": "<?= e(base_url()) ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= e(base_url('search?q={search_term_string}')) ?>",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <?php if (!empty($article_schema)): ?>
        <script type="application/ld+json"><?= $article_schema ?></script>
    <?php endif; ?>
    <?php if (!empty($breadcrumb_schema)): ?>
        <script type="application/ld+json"><?= $breadcrumb_schema ?></script>
    <?php endif; ?>
</head>
<body>
<?php require VIEW_PATH . '/partials/site-header.php'; ?>

<main class="site-main">
    <?= $content ?>
</main>

<?php require VIEW_PATH . '/partials/site-footer.php'; ?>

<?php if (Setting::get('cookie_consent_enabled') === '1'): ?>
<div id="cookieBanner" class="cookie-banner" role="dialog" aria-live="polite">
    <div class="container cookie-inner">
        <span>We use cookies for analytics and to improve your experience.</span>
        <button class="btn btn-primary" type="button" id="cookieAccept">Accept</button>
    </div>
</div>
<script>
(function(){
    var key = 'pb_cookie_ok';
    var banner = document.getElementById('cookieBanner');
    if (!localStorage.getItem(key)) { banner.classList.add('show'); }
    document.getElementById('cookieAccept').addEventListener('click', function(){
        localStorage.setItem(key, '1');
        banner.classList.remove('show');
    });
})();
</script>
<?php endif; ?>

<script src="<?= e(base_url('assets/js/site.js')) ?>" defer></script>

<?php if (!empty($seo['analytics_id'])): ?>
<script>
function pbLoadAnalytics(){
  if (<?= Setting::get('cookie_consent_enabled') === '1' ? 'localStorage.getItem("pb_cookie_ok")' : 'true' ?>) {
    var s=document.createElement('script'); s.async=true;
    s.src='https://www.googletagmanager.com/gtag/js?id=<?= e($seo['analytics_id']) ?>';
    document.head.appendChild(s);
    window.dataLayer=window.dataLayer||[];
    function gtag(){dataLayer.push(arguments);} window.gtag=gtag;
    gtag('js', new Date());
    gtag('config', '<?= e($seo['analytics_id']) ?>');
  }
}
pbLoadAnalytics();
document.getElementById('cookieAccept')?.addEventListener('click', pbLoadAnalytics);
</script>
<?php endif; ?>
<?php if (!empty($seo['adsense_enabled']) && !empty($seo['adsense_client'])): ?>
<script>
(function(){
  var ok = <?= Setting::get('cookie_consent_enabled') === '1' ? 'localStorage.getItem("pb_cookie_ok")' : 'true' ?>;
  if (!ok) return;
  var s=document.createElement('script');
  s.async=true; s.crossOrigin='anonymous';
  s.src='https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?= e($seo['adsense_client']) ?>';
  document.head.appendChild(s);
})();
</script>
<?php endif; ?>
</body>
</html>