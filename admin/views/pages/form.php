<?php use App\Core\Csrf; $editing = $page !== null; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url($editing ? 'admin/pages/edit/' . $page['id'] : 'admin/pages/create')) ?>">
    <?= Csrf::field() ?>
    <label>Title *<input type="text" name="title" required value="<?= e($page['title'] ?? '') ?>"></label>
    <label>Slug<input type="text" name="slug" value="<?= e($page['slug'] ?? '') ?>"></label>
    <label>Template
        <select name="template">
            <option value="default" <?= ($page['template'] ?? '') === 'default' ? 'selected' : '' ?>>Default</option>
            <option value="contact" <?= ($page['template'] ?? '') === 'contact' ? 'selected' : '' ?>>Contact (with form)</option>
        </select>
    </label>
    <?php $item = $page ?? []; require ADMIN_VIEW_PATH . '/partials/font-fields.php'; ?>
    <label>Content<textarea class="rich-editor" name="content" rows="12"><?= e($page['content'] ?? '') ?></textarea></label>
    <label>Status
        <select name="status">
            <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>published</option>
            <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>draft</option>
        </select>
    </label>
    <label><input type="checkbox" name="show_in_footer" value="1" <?= !empty($page['show_in_footer']) ? 'checked' : '' ?>> Show in footer</label>
    <label><input type="checkbox" name="show_in_header" value="1" <?= !empty($page['show_in_header']) ? 'checked' : '' ?>> Show in header</label>
    <label>Sort order<input type="number" name="sort_order" value="<?= e((string)($page['sort_order'] ?? 0)) ?>"></label>
    <h3>SEO</h3>
    <?php $item = $page ?? []; require ADMIN_VIEW_PATH . '/partials/seo-fields.php'; ?>
    <button class="btn btn-primary" type="submit">Save</button>
</form>