<p><a class="btn btn-primary" href="<?= e(base_url('admin/pages/create')) ?>">+ New page</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Footer</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($pages as $pg): ?>
            <tr>
                <td><?= e($pg['title']) ?></td>
                <td><?= e($pg['slug']) ?></td>
                <td><?= e($pg['status']) ?></td>
                <td><?= !empty($pg['show_in_footer']) ? 'Yes' : 'No' ?></td>
                <td><a href="<?= e(base_url('admin/pages/edit/' . $pg['id'])) ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>