<?php
/** @var array $font from FontService::resolve */
if (!empty($font['href'])): ?>
<link rel="stylesheet" href="<?= e($font['href']) ?>">
<?php endif; ?>
<?php if (!empty($font['family'])): ?>
<style>.article-prose.<?= e($font['class'] ?? 'font-poppins') ?> { font-family: <?= $font['family'] ?>; }</style>
<?php endif; ?>