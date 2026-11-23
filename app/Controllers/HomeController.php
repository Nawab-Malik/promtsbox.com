<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tool;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index', [
            'title' => Setting::get('default_meta_title'),
            'meta_title' => Setting::get('default_meta_title'),
            'meta_description' => Setting::get('default_meta_description'),
            'tagline' => Setting::get('site_tagline', (string) app_config('tagline')),
            'featured_categories' => Category::featured(6),
            'featured_tools' => Tool::featured(4),
            'latest_posts' => Post::latestPublished(6),
            'sidebar_posts' => Post::latestPublished(8),
        ]);
    }
}