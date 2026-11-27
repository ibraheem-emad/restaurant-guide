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

function show_edit_meal_form($pdo, $meal, $error)
{
    view('admin_edit_meal', [
        'title' => 'تعديل وجبة',
        'error' => $error,
        'meal' => $meal,
        'categories' => get_categories($pdo),
    ]);
}

function edit_meal_page($pdo, $meal_id)
{
    require_role('admin');

    $meal = get_meal($pdo, $meal_id);
    if (!$meal) {
        not_found();
        return;
    }

    show_edit_meal_form($pdo, $meal, '');
}

function edit_meal_submit($pdo, $meal_id)
{
    require_role('admin');

    $meal = get_meal($pdo, $meal_id);
    if (!$meal) {
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
        show_edit_meal_form($pdo, $meal, $error);
        return;
    }

    update_meal($pdo, $meal_id, $name, $price);

    clear_meal_category($pdo, $meal_id);
    if ($category_id != '') {
        add_meal_category($pdo, $meal_id, $category_id);
    }

    redirect('/admin/menu/' . $meal['place_id'] . '?saved=1');
}

function show_categories_page($pdo, $error)
{
    view('admin_categories', [
        'title' => 'التصنيفات',
        'error' => $error,
        'categories' => get_categories($pdo),
    ]);
}

function categories_page($pdo)
{
    require_role('admin');

    show_categories_page($pdo, '');
}

function add_category_submit($pdo)
{
    require_role('admin');

    $name = trim($_POST['category_name']);

    $error = '';

    if ($name == '') {
        $error = 'الاسم مطلوب.';
    } elseif (category_name_taken($pdo, $name, 0)) {
        $error = 'هذا التصنيف موجود من قبل.';
    }

    if ($error != '') {
        show_categories_page($pdo, $error);
        return;
    }

    add_category($pdo, $name);

    redirect('/admin/categories?saved=1');
}

function edit_category_submit($pdo, $id)
{
    require_role('admin');

    $name = trim($_POST['category_name']);

    $error = '';

    if ($name == '') {
        $error = 'الاسم مطلوب.';
    } elseif (category_name_taken($pdo, $name, $id)) {
        $error = 'هذا التصنيف موجود من قبل.';
    }

    if ($error != '') {
        show_categories_page($pdo, $error);
        return;
    }

    update_category($pdo, $id, $name);

    redirect('/admin/categories?saved=1');
}

function delete_category_submit($pdo, $id)
{
    require_role('admin');

    delete_category($pdo, $id);

    redirect('/admin/categories?deleted=1');
}

function show_attributes_page($pdo, $error)
{
    view('admin_attributes', [
        'title' => 'المميزات',
        'error' => $error,
        'attributes' => get_attributes($pdo),
    ]);
}

function attributes_page($pdo)
{
    require_role('admin');

    show_attributes_page($pdo, '');
}

function add_attribute_submit($pdo)
{
    require_role('admin');

    $name = trim($_POST['attribute_name']);

    $error = '';

    if ($name == '') {
        $error = 'الاسم مطلوب.';
    } elseif (attribute_name_taken($pdo, $name, 0)) {
        $error = 'هذه الميزة موجودة من قبل.';
    }

    if ($error != '') {
        show_attributes_page($pdo, $error);
        return;
    }

    add_attribute($pdo, $name);

    redirect('/admin/attributes?saved=1');
}

function edit_attribute_submit($pdo, $id)
{
    require_role('admin');

    $name = trim($_POST['attribute_name']);

    $error = '';

    if ($name == '') {
        $error = 'الاسم مطلوب.';
    } elseif (attribute_name_taken($pdo, $name, $id)) {
        $error = 'هذه الميزة موجودة من قبل.';
    }

    if ($error != '') {
        show_attributes_page($pdo, $error);
        return;
    }

    update_attribute($pdo, $id, $name);

    redirect('/admin/attributes?saved=1');
}

function delete_attribute_submit($pdo, $id)
{
    require_role('admin');

    delete_attribute($pdo, $id);

    redirect('/admin/attributes?deleted=1');
}

function show_photos_page($pdo, $restaurant, $error)
{
    view('admin_photos', [
        'title' => 'الصور',
        'error' => $error,
        'restaurant' => $restaurant,
        'photos' => get_photos($pdo, $restaurant['place_id']),
    ]);
}

function photos_page($pdo, $id)
{
    require_role('admin');

    $restaurant = get_restaurant_for_edit($pdo, $id);
    if (!$restaurant) {
        not_found();
        return;
    }

    show_photos_page($pdo, $restaurant, '');
}

function upload_photo_submit($pdo, $id)
{
    require_role('admin');

    $restaurant = get_restaurant_for_edit($pdo, $id);
    if (!$restaurant) {
        not_found();
        return;
    }

    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    $error = '';

    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != UPLOAD_ERR_OK || $_FILES['photo']['size'] == 0) {
        $error = 'اختر صورة أولاً (حتى 2 ميغابايت).';
    } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
        $error = 'حجم الصورة يجب ألا يزيد عن 2 ميغابايت.';
    } else {
        $info = getimagesize($_FILES['photo']['tmp_name']);
        if (!$info || !isset($extensions[$info['mime']])) {
            $error = 'نوع الصورة يجب أن يكون JPG أو PNG أو WEBP.';
        }
    }

    if ($error != '') {
        show_photos_page($pdo, $restaurant, $error);
        return;
    }

    $folder = __DIR__ . '/../../public/uploads/';
    if (!is_dir($folder)) {
        mkdir($folder);
    }

    $file_name = $id . '_' . uniqid() . '.' . $extensions[$info['mime']];
    move_uploaded_file($_FILES['photo']['tmp_name'], $folder . $file_name);

    $photos = get_photos($pdo, $id);

    $is_cover = 0;
    if (!$photos) {
        $is_cover = 1;
    }

    $sort_order = 1;
    foreach ($photos as $photo) {
        $sort_order = $photo['sort_order'] + 1;
    }

    add_photo($pdo, $id, $file_name, $is_cover, $sort_order);

    redirect('/admin/photos/' . $id . '?saved=1');
}

function set_cover_submit($pdo, $photo_id)
{
    require_role('admin');

    $photo = get_photo($pdo, $photo_id);
    if (!$photo) {
        not_found();
        return;
    }

    set_cover($pdo, $photo['place_id'], $photo_id);

    redirect('/admin/photos/' . $photo['place_id']);
}

function move_photo_submit($pdo, $photo_id, $direction)
{
    require_role('admin');

    $photo = get_photo($pdo, $photo_id);
    if (!$photo) {
        not_found();
        return;
    }

    $photos = get_photos($pdo, $photo['place_id']);

    for ($i = 0; $i < count($photos); $i++) {
        if ($photos[$i]['photo_id'] == $photo_id) {
            $position = $i;
        }
    }

    if ($direction == 'up') {
        $other = $position - 1;
    } else {
        $other = $position + 1;
    }

    if (isset($photos[$other])) {
        set_photo_order($pdo, $photo_id, $photos[$other]['sort_order']);
        set_photo_order($pdo, $photos[$other]['photo_id'], $photo['sort_order']);
    }

    redirect('/admin/photos/' . $photo['place_id']);
}

function delete_photo_submit($pdo, $photo_id)
{
    require_role('admin');

    $photo = get_photo($pdo, $photo_id);
    if (!$photo) {
        not_found();
        return;
    }

    delete_photo($pdo, $photo_id);

    $file = __DIR__ . '/../../public/uploads/' . $photo['file_name'];
    if (file_exists($file)) {
        unlink($file);
    }

    if ($photo['is_cover']) {
        $photos = get_photos($pdo, $photo['place_id']);
        if ($photos) {
            set_cover($pdo, $photo['place_id'], $photos[0]['photo_id']);
        }
    }

    redirect('/admin/photos/' . $photo['place_id'] . '?deleted=1');
}

function show_users_page($pdo, $error)
{
    view('admin_users', [
        'title' => 'المستخدمون',
        'error' => $error,
        'users' => get_users($pdo),
        'restaurants' => get_admin_restaurants($pdo, ''),
    ]);
}

function users_page($pdo)
{
    require_role('admin');

    show_users_page($pdo, '');
}

function change_role_submit($pdo, $id)
{
    require_role('admin');

    $role = $_POST['role'];

    $error = '';

    if ($id == $_SESSION['user_id']) {
        $error = 'لا يمكنك تغيير دور حسابك.';
    } elseif (!in_array($role, ['user', 'owner', 'admin'])) {
        $error = 'الدور غير صحيح.';
    }

    if ($error != '') {
        show_users_page($pdo, $error);
        return;
    }

    set_user_role($pdo, $id, $role);

    redirect('/admin/users?saved=1');
}

function block_user_submit($pdo, $id)
{
    require_role('admin');

    $user = get_user($pdo, $id);
    if (!$user) {
        not_found();
        return;
    }

    if ($id == $_SESSION['user_id']) {
        show_users_page($pdo, 'لا يمكنك حظر حسابك.');
        return;
    }

    if ($user['is_blocked']) {
        set_user_blocked($pdo, $id, 0);
    } else {
        set_user_blocked($pdo, $id, 1);
    }

    redirect('/admin/users?saved=1');
}

function create_owner_submit($pdo)
{
    require_role('admin');

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $place_id = $_POST['place_id'];

    $error = '';

    if ($username == '') {
        $error = 'الاسم مطلوب.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'البريد الإلكتروني غير صحيح.';
    } elseif (strlen($password) < 8) {
        $error = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.';
    } elseif (find_user_by_email($pdo, $email)) {
        $error = 'هذا البريد مستخدم من قبل.';
    } elseif ($place_id == '') {
        $error = 'اختر المطعم.';
    }

    if ($error != '') {
        show_users_page($pdo, $error);
        return;
    }

    $user_id = create_owner($pdo, $username, $email, $password);
    set_restaurant_owner($pdo, $place_id, $user_id);

    redirect('/admin/users?saved=1');
}