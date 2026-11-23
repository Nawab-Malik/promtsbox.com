<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Post;

final class CategoriesController extends Controller
{
    public function index(): void
    {
        $categories = Category::published();
        $counts = [];
        foreach ($categories as $cat) {
            $counts[$cat['id']] = count(Post::latestPublished(100, (int) $cat['id']));
        }
        $this->render('categories/index', [
            'title' => 'Categories',
            'meta_title' => 'Browse categories',
            'meta_description' => 'Explore AI prompts, social tools, and seasonal guides by topic.',
            'categories' => $categories,
            'post_counts' => $counts,
        ]);
    }
}