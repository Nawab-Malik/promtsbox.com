<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Tool
{
    public static function findPublishedBySlug(string $slug): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM tools WHERE slug = :slug AND status = "published" LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        if ($row && isset($row['config_json']) && is_string($row['config_json'])) {
            $row['config'] = json_decode($row['config_json'], true) ?? [];
        }
        return $row ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM tools WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** @return list<array> */
    public static function published(int $limit = 50): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM tools WHERE status = "published" ORDER BY sort_order, name LIMIT :lim');
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return list<array> */
    public static function featured(int $limit = 4): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM tools WHERE status = "published" ORDER BY sort_order LIMIT :lim');
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return list<array> */
    public static function allAdmin(): array
    {
        return Database::connection()->query('SELECT * FROM tools ORDER BY sort_order, name')->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO tools (name, slug, description, long_content, tool_type, config_json, category_id, meta_title, meta_description,
             og_title, og_description, og_image, status, sort_order)
             VALUES (:name,:slug,:description,:long_content,:tool_type,:config_json,:category_id,:meta_title,:meta_description,
             :og_title,:og_description,:og_image,:status,:sort_order)'
        );
        $stmt->execute(self::bind($data));
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE tools SET name=:name, slug=:slug, description=:description, long_content=:long_content, tool_type=:tool_type,
             config_json=:config_json, category_id=:category_id, meta_title=:meta_title, meta_description=:meta_description,
             og_title=:og_title, og_description=:og_description, og_image=:og_image, status=:status, sort_order=:sort_order WHERE id=:id'
        );
        $stmt->execute(self::bind($data) + ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM tools WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /** @return list<array> */
    public static function forSitemap(): array
    {
        return Database::connection()->query('SELECT slug, updated_at FROM tools WHERE status = "published"')->fetchAll();
    }

    private static function bind(array $data): array
    {
        $config = $data['config_json'] ?? '{}';
        if (is_array($config)) {
            $config = json_encode($config) ?: '{}';
        }
        return [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'long_content' => $data['long_content'] ?? null,
            'tool_type' => $data['tool_type'],
            'config_json' => $config,
            'category_id' => $data['category_id'] ?: null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'og_title' => $data['og_title'] ?? null,
            'og_description' => $data['og_description'] ?? null,
            'og_image' => $data['og_image'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }
}