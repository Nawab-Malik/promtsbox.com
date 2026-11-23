<?php
/** @var array|null $item */
$item = $item ?? [];
?>
<div class="admin-card" style="margin-bottom:1rem;background:#0f172a;">
    <strong>Google preview</strong>
    <div class="serp-preview" style="margin-top:0.5rem;padding:0.75rem;background:#111827;border-radius:8px;">
        <div id="serpTitle" style="color:#8ab4f8;font-size:1.05rem;"><?= e($item['meta_title'] ?? ($item['title'] ?? 'Page title')) ?></div>
        <div style="color:#9aa0a6;font-size:0.82rem;"><?= e(parse_url((string) app_config('url'), PHP_URL_HOST) ?: 'promptsbox.com') ?></div>
        <div id="serpDesc" style="color:#bdc1c6;font-size:0.9rem;margin-top:0.25rem;"><?= e($item['meta_description'] ?? 'Meta description preview...') ?></div>
    </div>
</div>
<div class="seo-grid">
    <label>Meta title (≤70)<input type="text" name="meta_title" maxlength="70" data-counter data-max="70" value="<?= e($item['meta_title'] ?? '') ?>"></label>
    <label>Meta robots<input type="text" name="meta_robots" value="<?= e($item['meta_robots'] ?? 'index,follow') ?>"></label>
    <label>Meta description (≤160)<textarea name="meta_description" maxlength="160" rows="2" data-counter data-max="160"><?= e($item['meta_description'] ?? '') ?></textarea></label>
    <?php if (array_key_exists('focus_keyword', $item) || ($show_focus_keyword ?? false)): ?>
        <label>Focus keyword (internal)<input type="text" name="focus_keyword" value="<?= e($item['focus_keyword'] ?? '') ?>"></label>
    <?php endif; ?>
    <label>OG title<input type="text" name="og_title" value="<?= e($item['og_title'] ?? '') ?>"></label>
    <label>OG description<textarea name="og_description" rows="2"><?= e($item['og_description'] ?? '') ?></textarea></label>
    <label>OG image URL<input type="text" name="og_image" value="<?= e($item['og_image'] ?? '') ?>"></label>
    <?php if ($show_canonical ?? isset($item['canonical_url'])): ?>
        <label>Canonical URL<input type="text" name="canonical_url" value="<?= e($item['canonical_url'] ?? '') ?>"></label>
    <?php endif; ?>
    <?php if ($show_schema ?? isset($item['schema_type'])): ?>
        <label>Schema type
            <select name="schema_type">
                <?php foreach (['Article', 'HowTo', 'FAQPage', 'BlogPosting'] as $schema): ?>
                    <option value="<?= e($schema) ?>" <?= ($item['schema_type'] ?? 'Article') === $schema ? 'selected' : '' ?>><?= e($schema) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php endif; ?>
</div>