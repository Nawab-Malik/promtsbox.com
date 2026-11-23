<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function renderAdmin(string $view, array $data = [], string $layout = 'admin'): void
    {
        View::renderAdmin($view, $data, $layout);
    }
}