<?php

function admin_page()
{
    require_role('admin');

    view('admin_home', ['title' => 'لوحة التحكم']);
}

function add_restaurant_page($pdo)
{
    require_role('admin');

    view('admin_add_restaurant', [
        'title' => 'إضافة مطعم',
        'error' => '',
        'categories' => get_categories($pdo),
        'attributes' => get_attributes($pdo),
    ]);
}

function add_restaurant_submit($pdo)
{
    require_role('admin');

    $name = trim($_POST['place_name']);
    $phone = trim($_POST['phone_number']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $price_range = $_POST['price_range'];
    $status = $_POST['status'];

    $error = '';

    if ($name == '') {
        $error = 'اسم المطعم مطلوب.';
    } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
        $error = 'خط العرض وخط الطول يجب أن يكونا أرقاماً.';
    }

    if ($error != '') {
        view('admin_add_restaurant', [
            'title' => 'إضافة مطعم',
            'error' => $error,
            'categories' => get_categories($pdo),
            'attributes' => get_attributes($pdo),
        ]);
        return;
    }

    $place_id = create_restaurant($pdo, $name, $phone, $latitude, $longitude, $price_range, $status);

    if (isset($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id) {
            add_restaurant_category($pdo, $place_id, $category_id);
        }
    }

    if (isset($_POST['attributes'])) {
        foreach ($_POST['attributes'] as $attribute_id) {
            add_restaurant_attribute($pdo, $place_id, $attribute_id);
        }
    }

    redirect('/admin?saved=1');
}

function admin_restaurants_page($pdo)
{
    require_role('admin');

    $status = '';
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
    }

    view('admin_restaurants', [
        'title' => 'إدارة المطاعم',
        'status' => $status,
        'restaurants' => get_admin_restaurants($pdo, $status),
    ]);
}

function show_edit_form($pdo, $restaurant, $error)
{
    $id = $restaurant['place_id'];

    view('admin_edit_restaurant', [
        'title' => 'تعديل مطعم',
        'error' => $error,
        'restaurant' => $restaurant,
        'categories' => get_categories($pdo),
        'attributes' => get_attributes($pdo),
        'checked_categories' => get_restaurant_category_ids($pdo, $id),
        'checked_attributes' => get_restaurant_attribute_ids($pdo, $id),
        'hours' => get_hours($pdo, $id),
    ]);
}

function edit_restaurant_page($pdo, $id)
{
    require_role('admin');

    $restaurant = get_restaurant_for_edit($pdo, $id);
    if (!$restaurant) {
        not_found();
        return;
    }

    show_edit_form($pdo, $restaurant, '');
}

function edit_restaurant_submit($pdo, $id)
{
    require_role('admin');

    $restaurant = get_restaurant_for_edit($pdo, $id);
    if (!$restaurant) {
        not_found();
        return;
    }

    $name = trim($_POST['place_name']);
    $phone = trim($_POST['phone_number']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $price_range = $_POST['price_range'];
    $status = $_POST['status'];

    $error = '';

    if ($name == '') {
        $error = 'اسم المطعم مطلوب.';
    } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
        $error = 'خط العرض وخط الطول يجب أن يكونا أرقاماً.';
    }

    if ($error != '') {
        show_edit_form($pdo, $restaurant, $error);
        return;
    }

    update_restaurant($pdo, $id, $name, $phone, $latitude, $longitude, $price_range, $status);

    clear_restaurant_categories($pdo, $id);
    if (isset($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id) {
            add_restaurant_category($pdo, $id, $category_id);
        }
    }

    clear_restaurant_attributes($pdo, $id);
    if (isset($_POST['attributes'])) {
        foreach ($_POST['attributes'] as $attribute_id) {
            add_restaurant_attribute($pdo, $id, $attribute_id);
        }
    }

    clear_restaurant_hours($pdo, $id);
    for ($day = 0; $day <= 6; $day++) {
        $open_time = $_POST['open_time'][$day];
        $close_time = $_POST['close_time'][$day];

        if ($open_time != '' && $close_time != '') {
            add_restaurant_hours($pdo, $id, $day, $open_time, $close_time);
        }
    }

    redirect('/admin/restaurants?saved=1');
}

function show_menu_page($pdo, $restaurant, $error)
{
    view('admin_menu', [
        'title' => 'المنيو',
        'error' => $error,
        'restaurant' => $restaurant,
        'meals' => get_meals($pdo, $restaurant['place_id']),
        'categories' => get_categories($pdo),
    ]);
}

function menu_page($pdo, $id)
{
    require_role('admin');

    $restaurant = get_restaurant_for_edit($pdo, $id);
    if (!$restaurant) {
        not_found();
        return;
    }

    show_menu_page($pdo, $restaurant, '');
}

function add_meal_submit($pdo, $id)
{
    require_role('admin');

    $restaurant = get_restaurant_for_edit($pdo, $id);
    if (!$restaurant) {
        not_found();
        return;
    }

    $name = trim($_POST['meal_name']);
    $price = trim($_POST['price']);
    $category_id = $_POST['category_id'];

    $error = '';

    if ($name == '') {
        $error = 'اسم الوجبة مطلوب.';
    } elseif (!is_numeric($price) || $price < 0 || $price > 999) {
        $error = 'السعر يجب أن يكون رقماً بين 0 و 999.';
    }

    if ($error != '') {
        show_menu_page($pdo, $restaurant, $error);
        return;
    }

    $meal_id = add_meal($pdo, $id, $name, $price);

    if ($category_id != '') {
        add_meal_category($pdo, $meal_id, $category_id);
    }

    redirect('/admin/menu/' . $id . '?saved=1');
}

function delete_meal_submit($pdo, $meal_id)
{
    require_role('admin');

    $meal = get_meal($pdo, $meal_id);
    if (!$meal) {
        not_found();
        return;
    }

    delete_meal($pdo, $meal_id);

    redirect('/admin/menu/' . $meal['place_id'] . '?deleted=1');
}