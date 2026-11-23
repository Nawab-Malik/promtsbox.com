<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\Tool;
use App\Services\SeoAuditService;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('dashboard/index', [
            'page_title' => 'Dashboard',
            'post_count' => Post::countAll(),
            'tools' => Tool::allAdmin(),
            'activity' => ActivityLog::recent(12),
            'seo_issues' => SeoAuditService::issues(),
        ]);
    }
}