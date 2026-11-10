<?php

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/open_now.php';
require __DIR__ . '/../app/models/restaurant_model.php';
require __DIR__ . '/../app/controllers/restaurant_controller.php';

date_default_timezone_set('Asia/Hebron');

// the folder the site runs in, for example /resturant-guide/public
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = substr($path, strlen(BASE_URL));
$path = rtrim($path, '/');

if ($path == '' || $path == '/restaurants') {
    restaurants_page($pdo);
} elseif (preg_match('#^/restaurant/(\d+)$#', $path, $match)) {
    restaurant_page($pdo, (int) $match[1]);
} else {
    not_found();
}
