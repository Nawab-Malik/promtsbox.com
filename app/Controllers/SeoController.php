<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tool;

final class SeoController
{
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        $base = rtrim((string) app_config('url'), '/');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $this->url($base . '/', date('c'));
        $this->url($base . '/tools', date('c'));
        $this->url($base . '/blog', date('c'));
        $this->url($base . '/categories', date('c'));

        foreach (Category::published() as $c) {
            $this->url($base . '/category/' . $c['slug'], $c['updated_at'] ?? date('c'));
        }
        foreach (Post::forSitemap() as $p) {
            $this->url($base . '/blog/' . $p['slug'], $p['updated_at'] ?? $p['published_at'] ?? date('c'));
        }
        foreach (Tool::forSitemap() as $t) {
            $this->url($base . '/tools/' . $t['slug'], $t['updated_at'] ?? date('c'));
        }
        foreach (Page::forSitemap() as $pg) {
            $this->url($base . '/page/' . $pg['slug'], $pg['updated_at'] ?? date('c'));
        }
        echo '</urlset>';
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $base = rtrim((string) app_config('url'), '/');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /install.php\n";
        $extra = Setting::get('robots_extra');
        if ($extra !== '') {
            echo $extra . "\n";
        }
        echo 'Sitemap: ' . $base . "/sitemap.xml\n";
    }

    public function rss(): void
    {
        header('Content-Type: application/rss+xml; charset=utf-8');
        $site = Setting::get('site_name', 'PromptsBox');
        $base = rtrim((string) app_config('url'), '/');
        $posts = Post::latestPublished(30);
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<rss version="2.0"><channel>';
        echo '<title>' . htmlspecialchars($site, ENT_XML1) . '</title>';
        echo '<link>' . htmlspecialchars($base, ENT_XML1) . '</link>';
        echo '<description>' . htmlspecialchars(Setting::get('site_tagline'), ENT_XML1) . '</description>';
        foreach ($posts as $post) {
            $link = $base . '/blog/' . $post['slug'];
            $pub = $post['published_at'] ?? $post['created_at'] ?? date('c');
            echo '<item>';
            echo '<title>' . htmlspecialchars($post['title'], ENT_XML1) . '</title>';
            echo '<link>' . htmlspecialchars($link, ENT_XML1) . '</link>';
            echo '<guid>' . htmlspecialchars($link, ENT_XML1) . '</guid>';
            echo '<pubDate>' . htmlspecialchars(gmdate('D, d M Y H:i:s T', strtotime($pub)), ENT_XML1) . '</pubDate>';
            echo '<description>' . htmlspecialchars(truncate(strip_tags($post['excerpt'] ?? $post['content'] ?? ''), 300), ENT_XML1) . '</description>';
            echo '</item>';
        }
        echo '</channel></rss>';
    }

    private function url(string $loc, string $lastmod): void
    {
        echo '<url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . htmlspecialchars(substr($lastmod, 0, 10), ENT_XML1) . '</lastmod></url>';
    }
}