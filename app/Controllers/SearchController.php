<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;

final class SearchController extends Controller
{
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $results = $q !== '' ? Post::search($q) : [];
        $this->render('search/index', [
            'title' => $q ? 'Search: ' . $q : 'Search',
            'meta_title' => 'Search PromptsBox',
            'meta_description' => 'Search articles and guides on PromptsBox.',
            'meta_robots' => 'noindex, follow',
            'q' => $q,
            'results' => $results,
        ]);
    }
}