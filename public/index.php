<?php

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/open_now.php';
require __DIR__ . '/../app/models/restaurant_model.php';
require __DIR__ . '/../app/controllers/restaurant_controller.php';
require __DIR__ . '/../app/models/user_model.php';
require __DIR__ . '/../app/controllers/auth_controller.php';
require __DIR__ . '/../app/controllers/admin_controller.php';
require __DIR__ . '/../app/models/admin_model.php';

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
} elseif ($path == '/admin') {
    admin_page();
} elseif ($path == '/admin/restaurants') {
    admin_restaurants_page($pdo);
} elseif ($path == '/admin/add-restaurant' && $method == 'GET') {
    add_restaurant_page($pdo);
} elseif ($path == '/admin/add-restaurant' && $method == 'POST') {
    add_restaurant_submit($pdo);
} elseif (preg_match('#^/admin/edit-restaurant/(\d+)$#', $path, $match) && $method == 'GET') {
    edit_restaurant_page($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/edit-restaurant/(\d+)$#', $path, $match) && $method == 'POST') {
    edit_restaurant_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/menu/(\d+)$#', $path, $match) && $method == 'GET') {
    menu_page($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/menu/(\d+)$#', $path, $match) && $method == 'POST') {
    add_meal_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/delete-meal/(\d+)$#', $path, $match) && $method == 'POST') {
    delete_meal_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/edit-meal/(\d+)$#', $path, $match) && $method == 'GET') {
    edit_meal_page($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/edit-meal/(\d+)$#', $path, $match) && $method == 'POST') {
    edit_meal_submit($pdo, (int) $match[1]);
} elseif ($path == '/admin/categories' && $method == 'GET') {
    categories_page($pdo);
} elseif ($path == '/admin/categories' && $method == 'POST') {
    add_category_submit($pdo);
} elseif (preg_match('#^/admin/edit-category/(\d+)$#', $path, $match) && $method == 'POST') {
    edit_category_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/delete-category/(\d+)$#', $path, $match) && $method == 'POST') {
    delete_category_submit($pdo, (int) $match[1]);
} elseif ($path == '/admin/attributes' && $method == 'GET') {
    attributes_page($pdo);
} elseif ($path == '/admin/attributes' && $method == 'POST') {
    add_attribute_submit($pdo);
} elseif (preg_match('#^/admin/edit-attribute/(\d+)$#', $path, $match) && $method == 'POST') {
    edit_attribute_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/delete-attribute/(\d+)$#', $path, $match) && $method == 'POST') {
    delete_attribute_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/photos/(\d+)$#', $path, $match) && $method == 'GET') {
    photos_page($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/photos/(\d+)$#', $path, $match) && $method == 'POST') {
    upload_photo_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/photo-cover/(\d+)$#', $path, $match) && $method == 'POST') {
    set_cover_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/photo-up/(\d+)$#', $path, $match) && $method == 'POST') {
    move_photo_submit($pdo, (int) $match[1], 'up');
} elseif (preg_match('#^/admin/photo-down/(\d+)$#', $path, $match) && $method == 'POST') {
    move_photo_submit($pdo, (int) $match[1], 'down');
} elseif (preg_match('#^/admin/delete-photo/(\d+)$#', $path, $match) && $method == 'POST') {
    delete_photo_submit($pdo, (int) $match[1]);
} elseif ($path == '/admin/users' && $method == 'GET') {
    users_page($pdo);
} elseif ($path == '/admin/users' && $method == 'POST') {
    create_owner_submit($pdo);
} elseif (preg_match('#^/admin/user-role/(\d+)$#', $path, $match) && $method == 'POST') {
    change_role_submit($pdo, (int) $match[1]);
} elseif (preg_match('#^/admin/block-user/(\d+)$#', $path, $match) && $method == 'POST') {
    block_user_submit($pdo, (int) $match[1]);
} else {
    not_found();
}