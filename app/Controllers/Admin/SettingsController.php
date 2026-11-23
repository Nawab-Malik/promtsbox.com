<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\ActivityLog;
use App\Core\Session;
use App\Models\Setting;

final class SettingsController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('superadmin', 'admin');
        $this->renderAdmin('settings/index', [
            'page_title' => 'Site & SEO Settings',
            'settings' => Setting::all(),
        ]);
    }

    public function save(): void
    {
        Auth::requireRole('superadmin');
        Csrf::abortIfInvalid();
        $keys = [
            'site_name', 'site_tagline', 'default_meta_title', 'default_meta_description',
            'google_site_verification', 'analytics_id', 'adsense_client_id', 'adsense_enabled',
            'contact_email', 'organization_logo', 'social_twitter', 'social_facebook', 'social_instagram',
            'robots_extra', 'maintenance_mode', 'cookie_consent_enabled',
        ];
        $pairs = [];
        foreach ($keys as $key) {
            if ($key === 'adsense_enabled' || $key === 'maintenance_mode' || $key === 'cookie_consent_enabled') {
                $pairs[$key] = isset($_POST[$key]) ? '1' : '0';
            } else {
                $pairs[$key] = trim($_POST[$key] ?? '');
            }
        }
        Setting::setMany($pairs);
        ActivityLog::write('update', 'settings');
        Session::flash('toast_success', 'Settings saved.');
        redirect(base_url('admin/settings'));
    }
}