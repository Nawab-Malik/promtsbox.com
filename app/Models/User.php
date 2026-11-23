<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => strtolower(trim($email))]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function touchLogin(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /** @return list<array> */
    public static function all(): array
    {
        return Database::connection()->query('SELECT id, email, display_name, role, status, last_login_at, created_at FROM users ORDER BY id DESC')->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO users (email, password_hash, display_name, role, status) VALUES (:email, :hash, :name, :role, :status)'
        );
        $stmt->execute([
            'email' => strtolower(trim($data['email'])),
            'hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'name' => $data['display_name'],
            'role' => $data['role'],
            'status' => $data['status'] ?? 'active',
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $fields = ['display_name' => $data['display_name'], 'role' => $data['role'], 'status' => $data['status']];
        $sql = 'UPDATE users SET display_name = :display_name, role = :role, status = :status';
        $params = $fields;
        if (!empty($data['password'])) {
            $sql .= ', password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id = :id';
        $params['id'] = $id;
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
    }
}