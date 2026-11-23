<?php

declare(strict_types=1);

function app_config(?string $key = null, mixed $default = null): mixed
{
    static $config;
    if ($config === null) {
        $config = require CONFIG_PATH . '/app.php';
    }
    if ($key === null) {
        return $config;
    }
    return $config[$key] ?? $default;
}

function base_url(string $path = ''): string
{
    $base = rtrim((string) app_config('url'), '/');
    $path = ltrim($path, '/');
    return $path === '' ? $base : $base . '/' . $path;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function author_initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= mb_strtoupper(mb_substr($p, 0, 1));
    }
    return $initials !== '' ? $initials : 'PB';
}

function post_image_url(?string $path): string
{
    if ($path === null || $path === '') {
        return '';
    }
    return str_starts_with($path, 'http') ? $path : base_url(ltrim($path, '/'));
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}

function redirect(string $url, int $code = 302): never
{
    header('Location: ' . $url, true, $code);
    exit;
}

function json_response(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit;
}

function sanitize_html(string $html): string
{
    $allowed = '<p><br><strong><em><u><h2><h3><h4><ul><ol><li><a><blockquote><code><pre><img><table><thead><tbody><tr><th><td>';
    return strip_tags($html, $allowed);
}

function read_time_minutes(string $html): int
{
    $text = strip_tags($html);
    $words = str_word_count($text);
    return max(1, (int) ceil($words / 200));
}

function request_canonical(): string
{
    $base = rtrim((string) app_config('url'), '/');
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $appPath = parse_url($base, PHP_URL_PATH) ?: '';
    if ($appPath !== '' && $appPath !== '/' && str_starts_with($path, $appPath)) {
        $path = substr($path, strlen($appPath)) ?: '/';
    }
    $path = '/' . trim($path, '/');
    if ($path !== '/') {
        $path = rtrim($path, '/');
    }
    return $path === '/' ? $base . '/' : $base . $path;
}

function truncate(string $text, int $length = 160): string
{
  $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length - 3) . '...';
}