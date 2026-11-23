<?php
use App\Core\Csrf;
use App\Services\ImageUploadService;
$editing = $post !== null;
$preview = '';
if (!empty($post['featured_image'])) {
    $preview = str_starts_with($post['featured_image'], 'http')
        ? $post['featured_image']
        : ImageUploadService::publicUrl($post['featured_image']);
}
?>
<form class="admin-form admin-card" method="post" enctype="multipart/form-data" action="<?= e(base_url($editing ? 'admin/posts/edit/' . $post['id'] : 'admin/posts/create')) ?>">
    <?= Csrf::field() ?>
    <label>Title *<input type="text" name="title" required value="<?= e($post['title'] ?? '') ?>"></label>
    <label>Slug<input type="text" name="slug" value="<?= e($post['slug'] ?? '') ?>" placeholder="auto from title"></label>
    <label>Category *
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) $cat['id'] ?>" <?= ((int)($post['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Excerpt<textarea name="excerpt" rows="2"><?= e($post['excerpt'] ?? '') ?></textarea></label>

    <?php $item = $post ?? []; require ADMIN_VIEW_PATH . '/partials/font-fields.php'; ?>

    <label>Content
        <textarea class="rich-editor" name="content" rows="16"><?= e($post['content'] ?? '') ?></textarea>
    </label>
    <p class="meta">Tip: Use the image button in the editor to upload from your computer (saved in Media library).</p>

    <fieldset style="border:1px solid #334155;border-radius:8px;padding:1rem;margin:1rem 0;">
        <legend>Featured image (file upload — preferred)</legend>
        <?php if ($preview): ?>
            <p><img src="<?= e($preview) ?>" alt="" style="max-width:280px;border-radius:8px;"></p>
        <?php endif; ?>
        <label>Upload file<input type="file" name="featured_file" accept="image/*"></label>
        <label>Alt text for SEO<input type="text" name="featured_alt" value="<?= e($post['title'] ?? '') ?>"></label>
        <label>Or existing path / URL<input type="text" name="featured_image" id="featured_image" value="<?= e($post['featured_image'] ?? '') ?>"></label>
        <button type="button" class="btn btn-secondary" onclick="openMediaPicker('featured_image')">Pick from library</button>
    </fieldset>

    <label>Status
        <select name="status">
            <?php foreach (['draft', 'published', 'scheduled'] as $st): ?>
                <option value="<?= $st ?>" <?= ($post['status'] ?? 'draft') === $st ? 'selected' : '' ?>><?= $st ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Publish date (YYYY-MM-DD HH:MM:SS)<input type="text" name="published_at" value="<?= e($post['published_at'] ?? '') ?>"></label>

    <h3>SEO</h3>
    <?php
    $item = $post ?? ['canonical_url' => '', 'schema_type' => 'Article', 'focus_keyword' => ''];
    $show_canonical = true;
    $show_schema = true;
    $show_focus_keyword = true;
    require ADMIN_VIEW_PATH . '/partials/seo-fields.php';
    ?>

    <button class="btn btn-primary" type="submit">Save post</button>
    <?php if ($editing): ?>
        <a href="<?= e(base_url('blog/' . $post['slug'])) ?>" target="_blank" rel="noopener">Preview live</a>
    <?php endif; ?>
</form>