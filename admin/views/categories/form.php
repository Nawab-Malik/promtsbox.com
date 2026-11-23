<?php use App\Core\Csrf; $editing = $category !== null; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url($editing ? 'admin/categories/edit/' . $category['id'] : 'admin/categories/create')) ?>">
    <?= Csrf::field() ?>
    <label>Name *<input type="text" name="name" required value="<?= e($category['name'] ?? '') ?>"></label>
    <label>Slug<input type="text" name="slug" value="<?= e($category['slug'] ?? '') ?>"></label>
    <label>Description<textarea name="description" rows="3"><?= e($category['description'] ?? '') ?></textarea></label>
    <label>Sort order<input type="number" name="sort_order" value="<?= e((string)($category['sort_order'] ?? 0)) ?>"></label>
    <label><input type="checkbox" name="is_featured" value="1" <?= !empty($category['is_featured']) ? 'checked' : '' ?>> Featured on homepage</label>
    <label>Status
        <select name="status">
            <option value="published" <?= ($category['status'] ?? '') === 'published' ? 'selected' : '' ?>>published</option>
            <option value="draft" <?= ($category['status'] ?? '') === 'draft' ? 'selected' : '' ?>>draft</option>
        </select>
    </label>
    <h3>SEO</h3>
    <?php $item = $category ?? []; require ADMIN_VIEW_PATH . '/partials/seo-fields.php'; ?>
    <button class="btn btn-primary" type="submit">Save</button>
</form>