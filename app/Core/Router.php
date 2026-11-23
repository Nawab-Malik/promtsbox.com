<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<int, array{method: string, pattern: string, regex: string, handler: string, params: list<string>}> */
    private array $routes = [];

    public function get(string $pattern, string $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, string $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    private function add(string $method, string $pattern, string $handler): void
    {
        $paramNames = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_]+)\}/', static function (array $m) use (&$paramNames): string {
            $paramNames[] = $m[1];
            return '([^/]+)';
        }, $pattern);

        if ($regex === null) {
            $regex = $pattern;
        }

        $regex = '#^' . $regex . '$#';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'regex' => $regex,
            'handler' => $handler,
            'params' => $paramNames,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $base = parse_url((string) app_config('url'), PHP_URL_PATH) ?: '';
        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }
        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['params'] as $i => $name) {
                $params[$name] = $matches[$i + 1] ?? '';
            }

            $this->invoke($route['handler'], $params);
            return;
        }

        http_response_code(404);
        View::render('errors/404', [
            'meta_robots' => 'noindex, nofollow',
        ], 'main');
    }

    /** @param array<string, string> $params */
    private function invoke(string $handler, array $params): void
    {
        if (!str_contains($handler, '@')) {
            throw new \InvalidArgumentException('Invalid route handler');
        }
        [$class, $method] = explode('@', $handler, 2);
        $class = 'App\\Controllers\\' . str_replace('.', '\\', $class);
        if (!class_exists($class)) {
            throw new \RuntimeException("Controller not found: {$class}");
        }
        $controller = new $class();
        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Method not found: {$class}::{$method}");
        }
        $controller->{$method}(...array_values($params));
    }
}