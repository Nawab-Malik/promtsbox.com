<?php use App\Core\Csrf; ?>
<div class="admin-card" style="max-width:420px;">
    <h2>Admin login</h2>
    <?php if (!empty($error)): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form class="admin-form" method="post" action="<?= e(base_url('admin/login')) ?>">
        <?= Csrf::field() ?>
        <label>Email<input type="email" name="email" required autocomplete="username"></label>
        <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
        <button class="btn btn-primary" type="submit">Login</button>
    </form>
</div>