<?php
/** @var array $tool */
/** @var string|null $output */
use App\Core\Csrf;

$type = $tool['tool_type'];
?>
<div class="tool-panel" id="result">
    <form method="post" action="<?= e(base_url('tools/' . $tool['slug'] . '/generate')) ?>">
        <?= Csrf::field() ?>
        <?php if ($type === 'bio_generator'): ?>
            <label>Name<input type="text" name="name" placeholder="Your name or brand"></label>
            <label>Niche<input type="text" name="niche" placeholder="e.g. fitness, travel"></label>
            <label>Vibe
                <select name="vibe">
                    <option value="friendly">Friendly</option>
                    <option value="professional">Professional</option>
                    <option value="bold">Bold</option>
                </select>
            </label>
            <label><input type="checkbox" name="emoji" value="1" checked> Add emoji</label>
        <?php elseif ($type === 'prompt_builder'): ?>
            <label>Subject<input type="text" name="subject" placeholder="Portrait of..."></label>
            <label>Style<input type="text" name="style" value="cinematic"></label>
            <label>Lighting<input type="text" name="lighting" value="soft golden hour"></label>
            <label>Camera<input type="text" name="camera" value="85mm lens, shallow depth of field"></label>
        <?php elseif ($type === 'caption_generator'): ?>
            <label>Occasion<input type="text" name="occasion" placeholder="weekend trip"></label>
            <label>Tone<input type="text" name="tone" value="casual"></label>
        <?php elseif ($type === 'meta_generator'): ?>
            <label>Page topic<input type="text" name="topic" placeholder="AI prompts for Instagram"></label>
        <?php else: ?>
            <label>Topic<input type="text" name="topic" placeholder="photography"></label>
        <?php endif; ?>
        <button class="btn btn-primary" type="submit">Generate</button>
    </form>
    <?php if (!empty($output)): ?>
        <h3>Your result</h3>
        <div class="tool-output"><?= e($output) ?></div>
        <p><button type="button" class="btn btn-secondary" onclick="navigator.clipboard.writeText(document.querySelector('.tool-output').innerText)">Copy</button></p>
    <?php endif; ?>
</div>