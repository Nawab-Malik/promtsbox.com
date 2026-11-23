<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Services\FontService;
use App\Services\SeoService;

final class PostController extends Controller
{
    public function show(string $slug): void
    {
        $post = Post::findPublishedBySlug($slug);
        if ($post === null) {
            http_response_code(404);
            $this->render('errors/404', ['meta_robots' => 'noindex, nofollow']);
            return;
        }

        Post::incrementViews((int) $post['id']);
        $post = Post::findPublishedBySlug($slug) ?? $post;

        $url = base_url('blog/' . $post['slug']);
        $moreLike = Post::moreInCategory((int) $post['category_id'], (int) $post['id'], 4);
        if (count($moreLike) < 4) {
            $moreLike = array_values(array_filter(
                Post::latestPublished(8),
                static fn ($p) => (int) $p['id'] !== (int) $post['id']
            ));
            $moreLike = array_slice($moreLike, 0, 4);
        }
        $breadcrumbs = [
            ['name' => 'Home', 'url' => base_url()],
            ['name' => $post['category_name'], 'url' => base_url('category/' . $post['category_slug'])],
            ['name' => $post['title']],
        ];

        $this->render('post/show', [
            'title' => $post['meta_title'] ?: $post['title'],
            'meta_title' => $post['meta_title'] ?: $post['title'],
            'meta_description' => $post['meta_description'],
            'meta_robots' => $post['meta_robots'],
            'canonical_url' => $post['canonical_url'] ?: $url,
            'og_title' => $post['og_title'],
            'og_description' => $post['og_description'],
            'og_image' => $post['og_image'] ?: $post['featured_image'],
            'schema_type' => $post['schema_type'],
            'post' => $post,
            'sidebar_posts' => Post::latestPublished(8),
            'more_like_posts' => $moreLike,
            'more_like_category' => ['name' => $post['category_name'], 'slug' => $post['category_slug']],
            'article_schema' => SeoService::articleJsonLd($post, $url),
            'read_minutes' => read_time_minutes($post['content']),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumb_schema' => SeoService::breadcrumbJsonLd($breadcrumbs),
            'content_font' => FontService::resolve(
                (string) ($post['body_font'] ?? 'poppins'),
                $post['custom_font_url'] ?? null
            ),
        ]);
    }
}