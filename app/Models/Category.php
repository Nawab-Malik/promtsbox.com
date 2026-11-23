<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Category
{
    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM categories WHERE slug = :slug AND status = "published" LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** @return list<array> */
    public static function featured(int $limit = 6): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM categories WHERE status = "published" AND is_featured = 1 ORDER BY sort_order LIMIT :lim');
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return list<array> */
    public static function allAdmin(): array
    {
        return Database::connection()->query('SELECT * FROM categories ORDER BY sort_order, name')->fetchAll();
    }

    /** @return list<array> */
    public static function published(): array
    {
        return Database::connection()->query('SELECT * FROM categories WHERE status = "published" ORDER BY sort_order')->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO categories (name, slug, description, meta_title, meta_description, sort_order, is_featured, status)
             VALUES (:name,:slug,:description,:meta_title,:meta_description,:sort_order,:is_featured,:status)'
        );
        $stmt->execute(self::bind($data));
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = Database::connection()->prepare(
            'UPDATE categories SET name=:name, slug=:slug, description=:description, meta_title=:meta_title,
             meta_description=:meta_description, sort_order=:sort_order, is_featured=:is_featured, status=:status WHERE id=:id'
        );
        $stmt->execute(self::bind($data) + ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private static function bind(array $data): array
    {
        return [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_featured' => !empty($data['is_featured']) ? 1 : 0,
            'status' => $data['status'] ?? 'published',
        ];
    }
}