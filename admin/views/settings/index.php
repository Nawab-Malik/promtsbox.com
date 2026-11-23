<?php use App\Core\Csrf; $s = $settings; ?>
<form class="admin-form admin-card" method="post" action="<?= e(base_url('admin/settings')) ?>">
    <?= Csrf::field() ?>
    <h3>Site</h3>
    <label>Site name<input type="text" name="site_name" value="<?= e($s['site_name'] ?? '') ?>"></label>
    <label>Tagline<input type="text" name="site_tagline" value="<?= e($s['site_tagline'] ?? '') ?>"></label>
    <label>Contact email<input type="email" name="contact_email" value="<?= e($s['contact_email'] ?? '') ?>"></label>
    <label>Organization logo URL (schema)<input type="text" name="organization_logo" value="<?= e($s['organization_logo'] ?? '') ?>"></label>
    <h3>SEO defaults</h3>
    <label>Default meta title<input type="text" name="default_meta_title" value="<?= e($s['default_meta_title'] ?? '') ?>"></label>
    <label>Default meta description<textarea name="default_meta_description" rows="2"><?= e($s['default_meta_description'] ?? '') ?></textarea></label>
    <label>Google site verification<input type="text" name="google_site_verification" value="<?= e($s['google_site_verification'] ?? '') ?>"></label>
    <label>Extra robots.txt lines<textarea name="robots_extra" rows="2"><?= e($s['robots_extra'] ?? '') ?></textarea></label>
    <h3>Integrations</h3>
    <label>Google Analytics ID<input type="text" name="analytics_id" value="<?= e($s['analytics_id'] ?? '') ?>" placeholder="G-XXXXXXXX"></label>
    <label>AdSense client ID<input type="text" name="adsense_client_id" value="<?= e($s['adsense_client_id'] ?? '') ?>"></label>
    <label><input type="checkbox" name="adsense_enabled" value="1" <?= ($s['adsense_enabled'] ?? '') === '1' ? 'checked' : '' ?>> Enable AdSense script (after approval only)</label>
    <label><input type="checkbox" name="cookie_consent_enabled" value="1" <?= ($s['cookie_consent_enabled'] ?? '1') === '1' ? 'checked' : '' ?>> Cookie consent banner</label>
    <label><input type="checkbox" name="maintenance_mode" value="1" <?= ($s['maintenance_mode'] ?? '') === '1' ? 'checked' : '' ?>> Maintenance mode (public site)</label>
    <h3>Social</h3>
    <label>Twitter/X URL<input type="text" name="social_twitter" value="<?= e($s['social_twitter'] ?? '') ?>"></label>
    <label>Facebook URL<input type="text" name="social_facebook" value="<?= e($s['social_facebook'] ?? '') ?>"></label>
    <label>Instagram URL<input type="text" name="social_instagram" value="<?= e($s['social_instagram'] ?? '') ?>"></label>
    <button class="btn btn-primary" type="submit">Save settings</button>
</form>