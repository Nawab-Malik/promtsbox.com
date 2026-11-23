<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Services\SeoService;

final class CategoryController extends Controller
{
    public function show(string $slug): void
    {
        $category = Category::findBySlug($slug);
        if ($category === null) {
            http_response_code(404);
            $this->render('errors/404', ['meta_robots' => 'noindex, nofollow']);
            return;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) app_config('posts_per_page', 12);
        $result = Post::paginateCategory((int) $category['id'], $page, $perPage);
        $totalPages = max(1, (int) ceil($result['total'] / $perPage));

        $breadcrumbs = [
            ['name' => 'Home', 'url' => base_url()],
            ['name' => $category['name']],
        ];
        $this->render('category/show', [
            'title' => $category['meta_title'] ?: $category['name'],
            'meta_title' => $category['meta_title'] ?: $category['name'],
            'meta_description' => $category['meta_description'] ?: truncate($category['description'] ?? '', 155),
            'category' => $category,
            'posts' => $result['items'],
            'page' => $page,
            'total_pages' => $totalPages,
            'canonical' => base_url('category/' . $category['slug']) . ($page > 1 ? '?page=' . $page : ''),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumb_schema' => SeoService::breadcrumbJsonLd($breadcrumbs),
            'sidebar_posts' => Post::latestPublished(8),
        ]);
    }
}