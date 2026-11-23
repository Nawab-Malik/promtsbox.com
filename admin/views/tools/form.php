<?php use App\Core\Csrf; $editing = $tool !== null; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url($editing ? 'admin/tools/edit/' . $tool['id'] : 'admin/tools/create')) ?>">
    <?= Csrf::field() ?>
    <label>Name *<input type="text" name="name" required value="<?= e($tool['name'] ?? '') ?>"></label>
    <label>Slug<input type="text" name="slug" value="<?= e($tool['slug'] ?? '') ?>"></label>
    <label>Tool type
        <select name="tool_type">
            <?php foreach (['bio_generator','prompt_builder','caption_generator','hashtag_helper','meta_generator'] as $t): ?>
                <option value="<?= $t ?>" <?= ($tool['tool_type'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Category<select name="category_id">
        <option value="0">—</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= (int)$cat['id'] ?>" <?= ((int)($tool['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
        <?php endforeach; ?>
    </select></label>
    <label>Short description<textarea name="description" rows="2"><?= e($tool['description'] ?? '') ?></textarea></label>
    <label>SEO body below tool<textarea class="rich-editor" name="long_content" rows="8"><?= e($tool['long_content'] ?? '') ?></textarea></label>
    <label>Config JSON<textarea name="config_json" rows="3"><?= e($tool['config_json'] ?? '{}') ?></textarea></label>
    <label>Sort order<input type="number" name="sort_order" value="<?= e((string)($tool['sort_order'] ?? 0)) ?>"></label>
    <label>Status
        <select name="status">
            <option value="published" <?= ($tool['status'] ?? '') === 'published' ? 'selected' : '' ?>>published</option>
            <option value="draft" <?= ($tool['status'] ?? '') === 'draft' ? 'selected' : '' ?>>draft</option>
        </select>
    </label>
    <h3>SEO</h3>
    <?php $item = $tool ?? []; require ADMIN_VIEW_PATH . '/partials/seo-fields.php'; ?>
    <button class="btn btn-primary" type="submit">Save</button>
</form>