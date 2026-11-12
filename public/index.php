<?php

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/open_now.php';
require __DIR__ . '/../app/models/restaurant_model.php';
require __DIR__ . '/../app/controllers/restaurant_controller.php';
require __DIR__ . '/../app/models/user_model.php';
require __DIR__ . '/../app/controllers/auth_controller.php';

session_start();
date_default_timezone_set('Asia/Hebron');

define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = substr($path, strlen(BASE_URL));
$path = rtrim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];

if ($path == '' || $path == '/restaurants') {
    restaurants_page($pdo);
} elseif (preg_match('#^/restaurant/(\d+)$#', $path, $match)) {
    restaurant_page($pdo, (int) $match[1]);
} elseif ($path == '/register' && $method == 'GET') {
    register_page();
} elseif ($path == '/register' && $method == 'POST') {
    register_submit($pdo);
} elseif ($path == '/login' && $method == 'GET') {
    login_page();
} elseif ($path == '/login' && $method == 'POST') {
    login_submit($pdo);
} elseif ($path == '/logout') {
    logout();
} else {
    not_found();
}