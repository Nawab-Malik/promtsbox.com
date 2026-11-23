<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Page
{
    public static function findPublishedBySlug(string $slug): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM pages WHERE slug = :slug AND status = "published" LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM pages WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** @return list<array> */
    public static function footerLinks(): array
    {
        return Database::connection()->query(
            'SELECT title, slug FROM pages WHERE status = "published" AND show_in_footer = 1 ORDER BY sort_order'
        )->fetchAll();
    }

    /** @return list<array> */
    public static function headerLinks(): array
    {
        return Database::connection()->query(
            'SELECT title, slug FROM pages WHERE status = "published" AND show_in_header = 1 ORDER BY sort_order'
        )->fetchAll();
    }

    /** @return list<array> */
    public static function allAdmin(): array
    {
        return Database::connection()->query('SELECT * FROM pages ORDER BY sort_order')->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO pages (title, slug, content, body_font, custom_font_url, template, status, meta_title, meta_description, meta_robots, show_in_footer, show_in_header, sort_order)
             VALUES (:title,:slug,:content,:body_font,:custom_font_url,:template,:status,:meta_title,:meta_description,:meta_robots,:show_in_footer,:show_in_header,:sort_order)'
        );
        $stmt->execute(self::bind($data));
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE pages SET title=:title, slug=:slug, content=:content, body_font=:body_font, custom_font_url=:custom_font_url, template=:template, status=:status,
             meta_title=:meta_title, meta_description=:meta_description, meta_robots=:meta_robots,
             show_in_footer=:show_in_footer, show_in_header=:show_in_header, sort_order=:sort_order WHERE id=:id'
        );
        $stmt->execute(self::bind($data) + ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM pages WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /** @return list<array> */
    public static function forSitemap(): array
    {
        return Database::connection()->query('SELECT slug, updated_at FROM pages WHERE status = "published"')->fetchAll();
    }

    private static function bind(array $data): array
    {
        return [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'] ?? '',
            'body_font' => $data['body_font'] ?? 'poppins',
            'custom_font_url' => $data['custom_font_url'] ?? null,
            'template' => $data['template'] ?? 'default',
            'status' => $data['status'] ?? 'draft',
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_robots' => $data['meta_robots'] ?? 'index,follow',
            'show_in_footer' => !empty($data['show_in_footer']) ? 1 : 0,
            'show_in_header' => !empty($data['show_in_header']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }
}