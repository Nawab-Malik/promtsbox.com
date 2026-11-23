<?php
use App\Core\Auth;
use App\Core\Session;

$user = Auth::user();
$toastSuccess = Session::flash('toast_success');
$toastError = Session::flash('toast_error');
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$base = parse_url((string) app_config('url'), PHP_URL_PATH) ?: '';
if ($base && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base)) ?: '/';
}
function navActive(string $path, string $needle): string {
    return str_contains($path, $needle) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title ?? 'Admin') ?> — PromptsBox</title>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>">
    <meta name="robots" content="noindex,nofollow">
</head>
<body class="admin-body" data-media-json="<?= e(base_url('admin/media/json')) ?>" data-editor-upload="<?= e(base_url('admin/editor/upload-image')) ?>">
<div class="admin-wrap">
    <aside class="admin-sidebar">
        <a class="logo" href="<?= e(base_url('admin')) ?>" style="display:block;margin-bottom:1rem;">PromptsBox Admin</a>
        <a class="<?= navActive($path, '/admin') && !str_contains($path, '/admin/') ? 'active' : '' ?>" href="<?= e(base_url('admin')) ?>">Dashboard</a>
        <a class="<?= navActive($path, '/admin/posts') ?>" href="<?= e(base_url('admin/posts')) ?>">Posts</a>
        <a class="<?= navActive($path, '/admin/categories') ?>" href="<?= e(base_url('admin/categories')) ?>">Categories</a>
        <a class="<?= navActive($path, '/admin/pages') ?>" href="<?= e(base_url('admin/pages')) ?>">Pages</a>
        <a class="<?= navActive($path, '/admin/tools') ?>" href="<?= e(base_url('admin/tools')) ?>">Tools</a>
        <a class="<?= navActive($path, '/admin/media') ?>" href="<?= e(base_url('admin/media')) ?>">Media</a>
        <a class="<?= navActive($path, '/admin/redirects') ?>" href="<?= e(base_url('admin/redirects')) ?>">Redirects</a>
        <a class="<?= navActive($path, '/admin/profile') ?>" href="<?= e(base_url('admin/profile')) ?>">My profile</a>
        <?php if (Auth::canManageUsers()): ?>
            <a class="<?= navActive($path, '/admin/users') ?>" href="<?= e(base_url('admin/users')) ?>">Users</a>
            <a class="<?= navActive($path, '/admin/settings') ?>" href="<?= e(base_url('admin/settings')) ?>">Settings & SEO</a>
        <?php endif; ?>
        <form method="post" action="<?= e(base_url('admin/logout')) ?>" style="margin-top:1rem;">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" class="btn btn-secondary" style="width:100%;">Logout (<?= e($user['display_name'] ?? '') ?>)</button>
        </form>
        <p style="margin-top:1rem;font-size:0.85rem;color:#94a3b8;"><a href="<?= e(base_url()) ?>" target="_blank" rel="noopener">View site</a></p>
    </aside>
    <div class="admin-main">
        <h1><?= e($page_title ?? 'Admin') ?></h1>
        <?= $content ?>
    </div>
</div>
<?php if ($toastSuccess || $toastError): ?>
<div id="adminToast" class="admin-toast show"><?= e($toastSuccess ?: $toastError) ?></div>
<?php else: ?>
<div id="adminToast" class="admin-toast"></div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="<?= e(base_url('assets/js/admin.js')) ?>"></script>
</body>
</html>