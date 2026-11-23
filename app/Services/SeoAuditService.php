<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class SeoAuditService
{
    /** @return list<array{level:string,message:string,link?:string}> */
    public static function issues(): array
    {
        $pdo = Database::connection();
        $issues = [];

        $posts = $pdo->query(
            "SELECT id, title, slug FROM posts WHERE status = 'published' AND (meta_title IS NULL OR meta_title = '' OR meta_description IS NULL OR meta_description = '') LIMIT 30"
        )->fetchAll();
        foreach ($posts as $p) {
            $issues[] = [
                'level' => 'warn',
                'message' => 'Post missing SEO meta: ' . $p['title'],
                'link' => base_url('admin/posts/edit/' . $p['id']),
            ];
        }

        $noExcerpt = $pdo->query(
            "SELECT id, title FROM posts WHERE status = 'published' AND (excerpt IS NULL OR excerpt = '') LIMIT 15"
        )->fetchAll();
        foreach ($noExcerpt as $p) {
            $issues[] = [
                'level' => 'info',
                'message' => 'Post missing excerpt: ' . $p['title'],
                'link' => base_url('admin/posts/edit/' . $p['id']),
            ];
        }

        $pages = $pdo->query(
            "SELECT id, title FROM pages WHERE status = 'published' AND slug IN ('privacy-policy','about','contact') AND (content IS NULL OR LENGTH(content) < 200)"
        )->fetchAll();
        foreach ($pages as $p) {
            $issues[] = [
                'level' => 'warn',
                'message' => 'Trust page content too short: ' . $p['title'],
                'link' => base_url('admin/pages/edit/' . $p['id']),
            ];
        }

        $tools = $pdo->query(
            "SELECT id, name FROM tools WHERE status = 'published' AND (long_content IS NULL OR LENGTH(long_content) < 100) LIMIT 10"
        )->fetchAll();
        foreach ($tools as $t) {
            $issues[] = [
                'level' => 'info',
                'message' => 'Tool needs more SEO text: ' . $t['name'],
                'link' => base_url('admin/tools/edit/' . $t['id']),
            ];
        }

        $media = $pdo->query(
            'SELECT COUNT(*) AS c FROM media WHERE alt_text = "" OR alt_text IS NULL'
        )->fetch();
        if (!empty($media['c'])) {
            $issues[] = [
                'level' => 'warn',
                'message' => 'Media without alt text: ' . (int) $media['c'],
                'link' => base_url('admin/media'),
            ];
        }

        return $issues;
    }
}