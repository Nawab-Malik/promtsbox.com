<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    private const SESSION_USER = 'auth_user_id';

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if ($user === null || ($user['status'] ?? '') !== 'active') {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        Session::regenerate();
        Session::set(self::SESSION_USER, (int) $user['id']);
        User::touchLogin((int) $user['id']);
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_USER]);
        Session::regenerate();
    }

    public static function id(): ?int
    {
        $id = Session::get(self::SESSION_USER);
        return is_int($id) ? $id : (is_numeric($id) ? (int) $id : null);
    }

    public static function user(): ?array
    {
        $id = self::id();
        return $id ? User::find($id) : null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect(base_url('admin/login'));
        }
    }

    public static function requireRole(string ...$roles): void
    {
        self::requireLogin();
        $user = self::user();
        if ($user === null || !in_array($user['role'], $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    public static function canManageUsers(): bool
    {
        $user = self::user();
        return $user !== null && $user['role'] === 'superadmin';
    }
}