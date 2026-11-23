<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;

final class BlogController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) app_config('posts_per_page', 12);
        $result = Post::paginateAll($page, $perPage);
        $totalPages = max(1, (int) ceil($result['total'] / $perPage));

        $this->render('blog/index', [
            'title' => 'Guides & Articles',
            'meta_title' => 'Guides & AI Prompt Articles',
            'meta_description' => 'Browse tutorials, prompt ideas, and creator guides on PromptsBox.',
            'posts' => $result['items'],
            'page' => $page,
            'total_pages' => $totalPages,
            'sidebar_posts' => Post::latestPublished(8),
        ]);
    }
}