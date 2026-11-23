<?php use App\Core\Csrf; ?>
<p><a class="btn btn-primary" href="<?= e(base_url('admin/posts/create')) ?>">+ New post</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= e($post['title']) ?></td>
                <td><?= e($post['category_name'] ?? '—') ?></td>
                <td><?= e($post['status']) ?></td>
                <td><?= e($post['updated_at']) ?></td>
                <td>
                    <a href="<?= e(base_url('admin/posts/edit/' . $post['id'])) ?>">Edit</a>
                    <form method="post" action="<?= e(base_url('admin/posts/duplicate/' . $post['id'])) ?>" style="display:inline;">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn btn-secondary" style="padding:0.2rem 0.5rem;">Dup</button>
                    </form>
                    <form method="post" action="<?= e(base_url('admin/posts/delete/' . $post['id'])) ?>" style="display:inline;" onsubmit="return confirm('Delete post?');">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn btn-secondary" style="padding:0.2rem 0.5rem;">Del</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>