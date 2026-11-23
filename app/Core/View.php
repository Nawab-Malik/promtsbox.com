<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\SeoService;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $data['seo'] = SeoService::forPage($data);
        extract($data, EXTR_SKIP);
        ob_start();
        require VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        $content = ob_get_clean() ?: '';
        require VIEW_PATH . '/layouts/' . $layout . '.php';
    }

    public static function renderAdmin(string $view, array $data = [], string $layout = 'admin'): void
    {
        $data['page_title'] = $data['page_title'] ?? 'Admin';
        extract($data, EXTR_SKIP);
        ob_start();
        require ADMIN_VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        $content = ob_get_clean() ?: '';
        require ADMIN_VIEW_PATH . '/layouts/' . $layout . '.php';
    }
}