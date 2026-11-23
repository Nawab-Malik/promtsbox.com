<p><a class="btn btn-primary" href="<?= e(base_url('admin/users/create')) ?>">+ New user</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= e($u['display_name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['role']) ?></td>
                <td><?= e($u['status']) ?></td>
                <td><a href="<?= e(base_url('admin/users/edit/' . $u['id'])) ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>