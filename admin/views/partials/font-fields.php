<?php
use App\Services\FontService;
/** @var array|null $item */
$fonts = FontService::presets();
$fontKey = $item['body_font'] ?? 'poppins';
?>
<label>Article font
    <select name="body_font" id="body_font">
        <?php foreach ($fonts as $key => $font): ?>
            <option value="<?= e($key) ?>" <?= $fontKey === $key ? 'selected' : '' ?>><?= e($font['label']) ?></option>
        <?php endforeach; ?>
    </select>
</label>
<label id="customFontWrap" style="<?= $fontKey === 'custom' ? '' : 'display:none' ?>">
    Custom Google Fonts CSS URL
    <input type="url" name="custom_font_url" placeholder="https://fonts.googleapis.com/css2?family=..." value="<?= e($item['custom_font_url'] ?? '') ?>">
    <span class="meta">Paste the full link from Google Fonts → Embed → &lt;link&gt; href.</span>
</label>
<script>
document.getElementById('body_font')?.addEventListener('change', function () {
  document.getElementById('customFontWrap').style.display = this.value === 'custom' ? '' : 'none';
});
</script>