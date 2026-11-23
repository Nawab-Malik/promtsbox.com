<p><a class="btn btn-primary" href="<?= e(base_url('admin/categories/create')) ?>">+ New category</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Featured</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= e($cat['name']) ?></td>
                <td><?= e($cat['slug']) ?></td>
                <td><?= !empty($cat['is_featured']) ? 'Yes' : 'No' ?></td>
                <td><?= e($cat['status']) ?></td>
                <td><a href="<?= e(base_url('admin/categories/edit/' . $cat['id'])) ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>