<p><a class="btn btn-primary" href="<?= e(base_url('admin/tools/create')) ?>">+ New tool</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Type</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($tools as $tool): ?>
            <tr>
                <td><?= e($tool['name']) ?></td>
                <td><?= e($tool['tool_type']) ?></td>
                <td><?= e($tool['status']) ?></td>
                <td><a href="<?= e(base_url('admin/tools/edit/' . $tool['id'])) ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>