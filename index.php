<?php
declare(strict_types=1);

// Let PHP's built-in server serve real files (css, js, images) directly.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

define('ROOT_PATH', dirname(__DIR__));
// Folder the site lives in, e.g. "" or "/restaurant-guide/public" under XAMPP.
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

spl_autoload_register(function (string $class): void {
    // App\Core\Router -> app/Core/Router.php
    if (str_starts_with($class, 'App\\')) {
        $path = ROOT_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file($path)) {
            require $path;
        }
    }
});

require ROOT_PATH . '/app/helpers.php';

$config = require ROOT_PATH . '/config/config.php';
$GLOBALS['config'] = $config;
date_default_timezone_set($config['timezone']);

ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting(E_ALL);

session_start();

use App\Core\Router;
use App\Core\Response;

$router = new Router();
require ROOT_PATH . '/app/routes.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
if (BASE_URL !== '' && str_starts_with($path, BASE_URL)) {
    $path = substr($path, strlen(BASE_URL));
}

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $path);
} catch (Throwable $e) {
    http_response_code(500);
    $msg = $config['debug'] ? $e->getMessage() : 'حدث خطأ غير متوقع';
    if (str_starts_with($path, '/api/')) {
        Response::json(['error' => $msg], 500);
    }
    echo '<h1>خطأ</h1><p dir="auto">' . e($msg) . '</p>';
}
