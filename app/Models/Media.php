<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Media
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM media WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** @return list<array> */
    public static function list(int $page = 1, int $perPage = 24): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = Database::connection()->prepare(
            'SELECT * FROM media ORDER BY id DESC LIMIT :lim OFFSET :off'
        );
        $stmt->bindValue('lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO media (filename, path, mime, size, alt_text, uploaded_by) VALUES (:filename,:path,:mime,:size,:alt,:uid)'
        );
        $stmt->execute([
            'filename' => $data['filename'],
            'path' => $data['path'],
            'mime' => $data['mime'],
            'size' => (int) $data['size'],
            'alt' => $data['alt_text'] ?? '',
            'uid' => $data['uploaded_by'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function updateAlt(int $id, string $alt): void
    {
        $stmt = Database::connection()->prepare('UPDATE media SET alt_text = :alt WHERE id = :id');
        $stmt->execute(['alt' => $alt, 'id' => $id]);
    }

    public static function delete(int $id): ?array
    {
        $row = self::find($id);
        if (!$row) {
            return null;
        }
        $stmt = Database::connection()->prepare('DELETE FROM media WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $row;
    }

    /** @return list<array> */
    public static function missingAlt(int $limit = 50): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, filename, path FROM media WHERE alt_text = "" OR alt_text IS NULL LIMIT :lim'
        );
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}