<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;
use App\Core\Router;
use App\Core\Session;
use App\Models\Redirect;
use App\Models\Setting;

Session::start();

try {
    $pdo = Database::connection();
} catch (Throwable $e) {
    if (str_contains($_SERVER['REQUEST_URI'] ?? '', 'install.php')) {
        // allow install
    } else {
        header('Location: ' . rtrim((string) app_config('url'), '/') . '/install.php');
        exit;
    }
}

if (isset($pdo) && Setting::get('maintenance_mode') === '1' && !str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin')) {
    http_response_code(503);
    echo '<h1>Maintenance</h1><p>We will be back soon.</p>';
    exit;
}

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = Redirect::normalizePath(parse_url($uri, PHP_URL_PATH) ?: '/');
$basePath = parse_url((string) app_config('url'), PHP_URL_PATH) ?: '';
if ($basePath !== '' && $basePath !== '/' && str_starts_with($path, $basePath)) {
    $path = Redirect::normalizePath(substr($path, strlen($basePath)) ?: '/');
}
if (isset($pdo)) {
    $stmt = $pdo->prepare('SELECT to_path, status_code FROM redirects WHERE from_path = :p LIMIT 1');
    $stmt->execute(['p' => $path]);
    $redir = $stmt->fetch();
    if ($redir) {
        header('Location: ' . $redir['to_path'], true, (int) $redir['status_code']);
        exit;
    }
}

$router = new Router();
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/admin.php';
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri);