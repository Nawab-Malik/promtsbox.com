<div class="admin-card">
    <p>Total posts: <strong><?= (int) $post_count ?></strong></p>
    <p>Live tools: <strong><?= count(array_filter($tools, fn($t) => ($t['status'] ?? '') === 'published')) ?></strong></p>
    <p><a href="<?= e(base_url('feed.xml')) ?>" target="_blank" rel="noopener">RSS feed</a> · <a href="<?= e(base_url('sitemap.xml')) ?>" target="_blank" rel="noopener">Sitemap</a></p>
</div>

<?php if (!empty($seo_issues)): ?>
<div class="admin-card" style="margin-top:1rem;">
    <h2>SEO checklist</h2>
    <ul>
        <?php foreach ($seo_issues as $issue): ?>
            <li style="margin:0.35rem 0;color:<?= $issue['level'] === 'warn' ? '#fbbf24' : '#94a3b8' ?>;">
                <?= e($issue['message']) ?>
                <?php if (!empty($issue['link'])): ?> — <a href="<?= e($issue['link']) ?>">Fix</a><?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="admin-card" style="margin-top:1rem;">
    <h2>Recent activity</h2>
    <table class="admin-table">
        <thead><tr><th>When</th><th>User</th><th>Action</th><th>Entity</th></tr></thead>
        <tbody>
        <?php foreach ($activity as $row): ?>
            <tr>
                <td><?= e($row['created_at']) ?></td>
                <td><?= e($row['email'] ?? '—') ?></td>
                <td><?= e($row['action']) ?></td>
                <td><?= e(trim(($row['entity'] ?? '') . ' #' . ($row['entity_id'] ?? ''))) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>