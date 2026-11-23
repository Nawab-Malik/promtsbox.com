<?php use App\Core\Csrf; $editing = $redirect !== null; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url($editing ? 'admin/redirects/edit/' . $redirect['id'] : 'admin/redirects/create')) ?>">
    <?= Csrf::field() ?>
    <label>From path *<input type="text" name="from_path" required placeholder="/blog/old-slug" value="<?= e($redirect['from_path'] ?? '') ?>"></label>
    <label>To URL or path *<input type="text" name="to_path" required value="<?= e($redirect['to_path'] ?? '') ?>"></label>
    <label>Status code<input type="number" name="status_code" value="<?= e((string)($redirect['status_code'] ?? 301)) ?>"></label>
    <button class="btn btn-primary" type="submit">Save redirect</button>
</form>