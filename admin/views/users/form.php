<?php use App\Core\Csrf; $editing = $user !== null; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url($editing ? 'admin/users/edit/' . $user['id'] : 'admin/users/create')) ?>">
    <?= Csrf::field() ?>
    <label>Display name<input type="text" name="display_name" required value="<?= e($user['display_name'] ?? '') ?>"></label>
    <label>Email<input type="email" name="email" required value="<?= e($user['email'] ?? '') ?>" <?= $editing ? 'readonly' : '' ?>></label>
    <label>Password<?= $editing ? ' (leave blank to keep)' : ' (10+ chars)' ?><input type="password" name="password" <?= $editing ? '' : 'required' ?>></label>
    <label>Role
        <select name="role">
            <?php foreach (['superadmin','admin','editor'] as $role): ?>
                <option value="<?= $role ?>" <?= ($user['role'] ?? 'editor') === $role ? 'selected' : '' ?>><?= $role ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Status
        <select name="status">
            <option value="active" <?= ($user['status'] ?? '') === 'active' ? 'selected' : '' ?>>active</option>
            <option value="disabled" <?= ($user['status'] ?? '') === 'disabled' ? 'selected' : '' ?>>disabled</option>
        </select>
    </label>
    <button class="btn btn-primary" type="submit">Save user</button>
</form>