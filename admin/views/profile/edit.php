<?php use App\Core\Csrf; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url('admin/profile')) ?>">
    <?= Csrf::field() ?>
    <label>Display name<input type="text" name="display_name" value="<?= e($user['display_name']) ?>" required></label>
    <label>Email<p class="meta"><?= e($user['email']) ?> (contact superadmin to change)</p></label>
    <label>New password (10+ chars, leave blank to keep)<input type="password" name="password" autocomplete="new-password"></label>
    <button class="btn btn-primary" type="submit">Update profile</button>
</form>