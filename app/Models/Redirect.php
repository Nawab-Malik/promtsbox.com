<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Redirect
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM redirects WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByFromPath(string $path): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM redirects WHERE from_path = :p LIMIT 1');
        $stmt->execute(['p' => $path]);
        return $stmt->fetch() ?: null;
    }

    /** @return list<array> */
    public static function all(): array
    {
        return Database::connection()->query('SELECT * FROM redirects ORDER BY id DESC')->fetchAll();
    }

    public static function create(string $from, string $to, int $code = 301): int
    {
        $from = self::normalizePath($from);
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO redirects (from_path, to_path, status_code) VALUES (:f,:t,:c)
             ON DUPLICATE KEY UPDATE to_path = VALUES(to_path), status_code = VALUES(status_code)'
        );
        $stmt->execute(['f' => $from, 't' => $to, 'c' => $code]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, string $from, string $to, int $code): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE redirects SET from_path = :f, to_path = :t, status_code = :c WHERE id = :id'
        );
        $stmt->execute([
            'f' => self::normalizePath($from),
            't' => $to,
            'c' => $code,
            'id' => $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM redirects WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function normalizePath(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: $path;
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}