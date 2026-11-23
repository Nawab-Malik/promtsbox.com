<?php
use App\Core\Csrf;
$isContact = ($page['template'] ?? '') === 'contact' || $page['slug'] === 'contact';
$font = $content_font ?? ['class' => 'font-poppins', 'family' => "'Poppins', sans-serif", 'href' => null];
$breadcrumbs = [
    ['name' => 'Home', 'url' => base_url()],
    ['name' => $page['title']],
];
?>
<?php require VIEW_PATH . '/partials/content-font.php'; ?>
<div class="page-hero container">
    <?php require VIEW_PATH . '/partials/breadcrumbs.php'; ?>
    <h1><?= e($page['title']) ?></h1>
</div>
<div class="container section">
    <article class="article-shell article-prose <?= e($font['class'] ?? 'font-poppins') ?>">
        <?= $page['content'] ?>
    </article>

    <?php if ($isContact): ?>
        <?php if (!empty($sent)): ?><p class="meta" style="color:#047857;">Thanks — your message was received.</p><?php endif; ?>
        <?php if (!empty($_GET['error'])): ?><p class="meta" style="color:#b91c1c;">Please fill required fields.</p><?php endif; ?>
        <form class="tool-panel" method="post" action="<?= e(base_url('page/contact')) ?>">
            <?= Csrf::field() ?>
            <label>Name *<input type="text" name="name" required></label>
            <label>Email *<input type="email" name="email" required></label>
            <label>Subject<input type="text" name="subject"></label>
            <label>Message *<textarea name="message" rows="6" required></textarea></label>
            <button class="btn btn-primary" type="submit">Send message</button>
        </form>
    <?php endif; ?>
</div>