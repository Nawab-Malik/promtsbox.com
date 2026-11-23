<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Post
{
    public static function findPublishedBySlug(string $slug): ?array
    {
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.display_name AS author_name
                FROM posts p
                JOIN categories c ON c.id = p.category_id
                JOIN users u ON u.id = p.author_id
                WHERE p.slug = :slug AND p.status = "published"
                  AND (p.published_at IS NULL OR p.published_at <= NOW())
                LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** @return list<array> */
    public static function latestPublished(int $limit = 6, ?int $categoryId = null): array
    {
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.display_name AS author_name
                FROM posts p
                JOIN categories c ON c.id = p.category_id
                LEFT JOIN users u ON u.id = p.author_id
                WHERE p.status = "published" AND (p.published_at IS NULL OR p.published_at <= NOW())';
        if ($categoryId) {
            $sql .= ' AND p.category_id = :cid';
        }
        $sql .= ' ORDER BY COALESCE(p.published_at, p.created_at) DESC LIMIT :lim';
        $stmt = Database::connection()->prepare($sql);
        if ($categoryId) {
            $stmt->bindValue('cid', $categoryId, PDO::PARAM_INT);
        }
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return list<array> */
    public static function moreInCategory(int $categoryId, int $excludePostId, int $limit = 4): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.display_name AS author_name
             FROM posts p
             JOIN categories c ON c.id = p.category_id
             LEFT JOIN users u ON u.id = p.author_id
             WHERE p.status = "published" AND (p.published_at IS NULL OR p.published_at <= NOW())
               AND p.category_id = :cid AND p.id != :ex
             ORDER BY COALESCE(p.published_at, p.created_at) DESC
             LIMIT :lim'
        );
        $stmt->bindValue('cid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('ex', $excludePostId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function incrementViews(int $postId): void
    {
        try {
            $stmt = Database::connection()->prepare('UPDATE posts SET view_count = view_count + 1 WHERE id = :id');
            $stmt->execute(['id' => $postId]);
        } catch (\Throwable) {
            // column may be missing on old DB
        }
    }

    /** @return array{items: list<array>, total: int} */
    public static function paginateAll(int $page, int $perPage): array
    {
        $pdo = Database::connection();
        $total = (int) $pdo->query(
            'SELECT COUNT(*) FROM posts WHERE status = "published" AND (published_at IS NULL OR published_at <= NOW())'
        )->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = $pdo->prepare(
            'SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.display_name AS author_name
             FROM posts p
             JOIN categories c ON c.id = p.category_id
             JOIN users u ON u.id = p.author_id
             WHERE p.status = "published" AND (p.published_at IS NULL OR p.published_at <= NOW())
             ORDER BY COALESCE(p.published_at, p.created_at) DESC
             LIMIT :lim OFFSET :off'
        );
        $stmt->bindValue('lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    /** @return array{items: list<array>, total: int} */
    public static function paginateCategory(int $categoryId, int $page, int $perPage): array
    {
        $pdo = Database::connection();
        $countStmt = $pdo->prepare(
            'SELECT COUNT(*) FROM posts WHERE category_id = :cid AND status = "published" AND (published_at IS NULL OR published_at <= NOW())'
        );
        $countStmt->execute(['cid' => $categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $stmt = $pdo->prepare(
            'SELECT p.*, u.display_name AS author_name FROM posts p
             JOIN users u ON u.id = p.author_id
             WHERE p.category_id = :cid AND p.status = "published" AND (p.published_at IS NULL OR p.published_at <= NOW())
             ORDER BY COALESCE(p.published_at, p.created_at) DESC LIMIT :lim OFFSET :off'
        );
        $stmt->bindValue('cid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    /** @return list<array> */
    public static function adminList(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = Database::connection()->prepare(
            'SELECT p.id, p.title, p.slug, p.status, p.published_at, p.updated_at, c.name AS category_name
             FROM posts p LEFT JOIN categories c ON c.id = p.category_id
             ORDER BY p.updated_at DESC LIMIT :lim OFFSET :off'
        );
        $stmt->bindValue('lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countAll(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM posts')->fetchColumn();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(self::insertSql());
        $stmt->execute(self::bind($data));
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = Database::connection()->prepare(self::updateSql());
        $stmt->execute(self::bind($data) + ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /** @return list<array> */
    public static function forSitemap(): array
    {
        return Database::connection()->query(
            'SELECT slug, updated_at, published_at FROM posts WHERE status = "published" AND (published_at IS NULL OR published_at <= NOW())'
        )->fetchAll();
    }

    /** @return list<array> */
    public static function search(string $q, int $limit = 20): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.title, p.slug, p.excerpt FROM posts p
             WHERE p.status = "published" AND (p.title LIKE :q OR p.content LIKE :q2)
             ORDER BY p.published_at DESC LIMIT :lim'
        );
        $like = '%' . $q . '%';
        $stmt->bindValue('q', $like);
        $stmt->bindValue('q2', $like);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private static function insertSql(): string
    {
        return 'INSERT INTO posts (' . self::columns() . ') VALUES (' . self::placeholders() . ')';
    }

    private static function updateSql(): string
    {
        $cols = array_map(static fn ($c) => "$c = :$c", self::columnList());
        return 'UPDATE posts SET ' . implode(', ', $cols) . ' WHERE id = :id';
    }

    private static function columns(): string
    {
        return implode(', ', self::columnList());
    }

    private static function placeholders(): string
    {
        return implode(', ', array_map(static fn ($c) => ':' . $c, self::columnList()));
    }

    /** @return list<string> */
    private static function columnList(): array
    {
        return [
            'category_id', 'author_id', 'title', 'slug', 'excerpt', 'content', 'body_font', 'custom_font_url', 'featured_image',
            'post_type', 'tool_id', 'status', 'published_at', 'meta_title', 'meta_description',
            'meta_robots', 'canonical_url', 'og_title', 'og_description', 'og_image',
            'schema_type', 'focus_keyword',
        ];
    }

    private static function bind(array $data): array
    {
        return [
            'category_id' => (int) $data['category_id'],
            'author_id' => (int) $data['author_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? '',
            'body_font' => $data['body_font'] ?? 'poppins',
            'custom_font_url' => $data['custom_font_url'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'post_type' => $data['post_type'] ?? 'article',
            'tool_id' => $data['tool_id'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'published_at' => $data['published_at'] ?: null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_robots' => $data['meta_robots'] ?? 'index,follow',
            'canonical_url' => $data['canonical_url'] ?? null,
            'og_title' => $data['og_title'] ?? null,
            'og_description' => $data['og_description'] ?? null,
            'og_image' => $data['og_image'] ?? null,
            'schema_type' => $data['schema_type'] ?? 'Article',
            'focus_keyword' => $data['focus_keyword'] ?? null,
        ];
    }
}