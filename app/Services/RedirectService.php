<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Redirect;

final class RedirectService
{
    public static function onSlugChange(string $routePrefix, string $oldSlug, string $newSlug): void
    {
        if ($oldSlug === '' || $newSlug === '' || $oldSlug === $newSlug) {
            return;
        }
        $from = Redirect::normalizePath($routePrefix . '/' . $oldSlug);
        $to = rtrim((string) app_config('url'), '/') . $routePrefix . '/' . $newSlug;
        Redirect::create($from, $to, 301);
    }
}