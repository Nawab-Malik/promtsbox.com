<?php

declare(strict_types=1);

$root = dirname(__FILE__);
define('BASE_PATH', $root);
define('APP_PATH', $root . '/app');
define('CONFIG_PATH', $root . '/config');
define('VIEW_PATH', $root . '/views');
define('ADMIN_VIEW_PATH', $root . '/admin/views');
define('STORAGE_PATH', $root . '/storage');

$config = require CONFIG_PATH . '/app.php';
date_default_timezone_set($config['timezone']);

if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = APP_PATH . '/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require APP_PATH . '/Core/Helpers.php';