<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        $token = Session::get(self::KEY);
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::set(self::KEY, $token);
        }
        return $token;
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function validate(?string $token): bool
    {
        $session = Session::get(self::KEY);
        return is_string($session) && is_string($token) && hash_equals($session, $token);
    }

    public static function abortIfInvalid(): void
    {
        $token = $_POST['_token'] ?? null;
        if (!self::validate(is_string($token) ? $token : null)) {
            http_response_code(419);
            echo 'Invalid security token. Go back and try again.';
            exit;
        }
    }
}