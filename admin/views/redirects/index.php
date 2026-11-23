<?php use App\Core\Csrf; ?>
<p><a class="btn btn-primary" href="<?= e(base_url('admin/redirects/create')) ?>">+ New 301 redirect</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>From</th><th>To</th><th>Code</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($redirects as $r): ?>
            <tr>
                <td><code><?= e($r['from_path']) ?></code></td>
                <td><?= e($r['to_path']) ?></td>
                <td><?= (int) $r['status_code'] ?></td>
                <td>
                    <a href="<?= e(base_url('admin/redirects/edit/' . $r['id'])) ?>">Edit</a>
                    <form method="post" action="<?= e(base_url('admin/redirects/delete/' . $r['id'])) ?>" style="display:inline;" onsubmit="return confirm('Delete redirect?');">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn btn-secondary" style="padding:0.2rem 0.5rem;">Del</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>