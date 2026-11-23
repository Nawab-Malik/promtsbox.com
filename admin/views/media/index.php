<?php use App\Core\Csrf; use App\Services\ImageUploadService; ?>
<div class="admin-card" style="margin-bottom:1rem;">
    <h2>Upload</h2>
    <form class="admin-form" method="post" action="<?= e(base_url('admin/media/upload')) ?>" enctype="multipart/form-data">
        <?= Csrf::field() ?>
        <label>Image file<input type="file" name="file" accept="image/*" required></label>
        <label>Alt text (SEO)<input type="text" name="alt_text" required placeholder="Describe the image"></label>
        <button class="btn btn-primary" type="submit">Upload</button>
    </form>
</div>
<div class="grid-3">
    <?php foreach ($media as $m): ?>
        <article class="admin-card">
            <img src="<?= e(ImageUploadService::publicUrl($m['path'])) ?>" alt="<?= e($m['alt_text']) ?>" style="max-width:100%;border-radius:8px;">
            <p class="meta"><?= e($m['filename']) ?> · <?= (int) round($m['size'] / 1024) ?> KB</p>
            <form method="post" action="<?= e(base_url('admin/media/alt/' . $m['id'])) ?>" class="admin-form">
                <?= Csrf::field() ?>
                <label>Alt<input type="text" name="alt_text" value="<?= e($m['alt_text']) ?>" required></label>
                <button class="btn btn-secondary" type="submit">Save alt</button>
            </form>
            <p><code style="font-size:0.8rem;"><?= e($m['path']) ?></code></p>
            <form method="post" action="<?= e(base_url('admin/media/delete/' . $m['id'])) ?>" onsubmit="return confirm('Delete?');">
                <?= Csrf::field() ?>
                <button class="btn btn-secondary" type="submit">Delete</button>
            </form>
        </article>
    <?php endforeach; ?>
</div>