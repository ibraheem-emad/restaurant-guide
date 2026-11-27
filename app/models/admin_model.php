<?php

function get_categories($pdo)
{
    return $pdo->query("SELECT * FROM category ORDER BY category_name")->fetchAll();
}

function get_attributes($pdo)
{
    return $pdo->query("SELECT * FROM attribute ORDER BY attribute_name")->fetchAll();
}

function create_restaurant($pdo, $name, $phone, $latitude, $longitude, $price_range, $status)
{
    $stmt = $pdo->prepare("INSERT INTO place (place_name, phone_number, latitude, longitude, price_range, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $latitude, $longitude, $price_range, $status]);

    return $pdo->lastInsertId();
}

function add_restaurant_category($pdo, $place_id, $category_id)
{
    $stmt = $pdo->prepare("INSERT INTO place_category (place_id, category_id) VALUES (?, ?)");
    $stmt->execute([$place_id, $category_id]);
}

function add_restaurant_attribute($pdo, $place_id, $attribute_id)
{
    $stmt = $pdo->prepare("INSERT INTO place_attribute (place_id, attribute_id) VALUES (?, ?)");
    $stmt->execute([$place_id, $attribute_id]);
}

function get_admin_restaurants($pdo, $status)
{
    if ($status == '') {
        return $pdo->query("SELECT place_id, place_name, price_range, status FROM place ORDER BY place_name")->fetchAll();
    }

    $stmt = $pdo->prepare("SELECT place_id, place_name, price_range, status FROM place WHERE status = ? ORDER BY place_name");
    $stmt->execute([$status]);
    return $stmt->fetchAll();
}

function get_restaurant_for_edit($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM place WHERE place_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_restaurant_category_ids($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT category_id FROM place_category WHERE place_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function get_restaurant_attribute_ids($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT attribute_id FROM place_attribute WHERE place_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function update_restaurant($pdo, $id, $name, $phone, $latitude, $longitude, $price_range, $status)
{
    $stmt = $pdo->prepare("UPDATE place SET place_name = ?, phone_number = ?, latitude = ?, longitude = ?, price_range = ?, status = ? WHERE place_id = ?");
    $stmt->execute([$name, $phone, $latitude, $longitude, $price_range, $status, $id]);
}

function clear_restaurant_categories($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM place_category WHERE place_id = ?");
    $stmt->execute([$id]);
}

function clear_restaurant_attributes($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM place_attribute WHERE place_id = ?");
    $stmt->execute([$id]);
}

function clear_restaurant_hours($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM place_hours WHERE place_id = ?");
    $stmt->execute([$id]);
}

function add_restaurant_hours($pdo, $id, $day, $open_time, $close_time)
{
    $stmt = $pdo->prepare("INSERT INTO place_hours (place_id, day_of_week, open_time, close_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $day, $open_time, $close_time]);
}

function get_meals($pdo, $place_id)
{
    $stmt = $pdo->prepare("SELECT m.meal_id, m.meal_name, m.price, c.category_name
                           FROM meal m
                           LEFT JOIN meal_category mc ON mc.meal_id = m.meal_id
                           LEFT JOIN category c ON c.category_id = mc.category_id
                           WHERE m.place_id = ?
                           ORDER BY m.meal_name");
    $stmt->execute([$place_id]);
    return $stmt->fetchAll();
}

function add_meal($pdo, $place_id, $name, $price)
{
    $stmt = $pdo->prepare("INSERT INTO meal (meal_name, place_id, price) VALUES (?, ?, ?)");
    $stmt->execute([$name, $place_id, $price]);

    return $pdo->lastInsertId();
}

function add_meal_category($pdo, $meal_id, $category_id)
{
    $stmt = $pdo->prepare("INSERT INTO meal_category (meal_id, category_id) VALUES (?, ?)");
    $stmt->execute([$meal_id, $category_id]);
}

function get_meal($pdo, $meal_id)
{
    $stmt = $pdo->prepare("SELECT m.*, mc.category_id
                           FROM meal m
                           LEFT JOIN meal_category mc ON mc.meal_id = m.meal_id
                           WHERE m.meal_id = ?");
    $stmt->execute([$meal_id]);
    return $stmt->fetch();
}

function delete_meal($pdo, $meal_id)
{
    $stmt = $pdo->prepare("DELETE FROM meal WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
}

function update_meal($pdo, $meal_id, $name, $price)
{
    $stmt = $pdo->prepare("UPDATE meal SET meal_name = ?, price = ? WHERE meal_id = ?");
    $stmt->execute([$name, $price, $meal_id]);
}

function clear_meal_category($pdo, $meal_id)
{
    $stmt = $pdo->prepare("DELETE FROM meal_category WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
}

function add_category($pdo, $name)
{
    $stmt = $pdo->prepare("INSERT INTO category (category_name) VALUES (?)");
    $stmt->execute([$name]);
}

function update_category($pdo, $id, $name)
{
    $stmt = $pdo->prepare("UPDATE category SET category_name = ? WHERE category_id = ?");
    $stmt->execute([$name, $id]);
}

function delete_category($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM category WHERE category_id = ?");
    $stmt->execute([$id]);
}

function category_name_taken($pdo, $name, $id)
{
    $stmt = $pdo->prepare("SELECT category_id FROM category WHERE category_name = ? AND category_id != ?");
    $stmt->execute([$name, $id]);
    return $stmt->fetch();
}

function add_attribute($pdo, $name)
{
    $stmt = $pdo->prepare("INSERT INTO attribute (attribute_name) VALUES (?)");
    $stmt->execute([$name]);
}

function update_attribute($pdo, $id, $name)
{
    $stmt = $pdo->prepare("UPDATE attribute SET attribute_name = ? WHERE attribute_id = ?");
    $stmt->execute([$name, $id]);
}

function delete_attribute($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM attribute WHERE attribute_id = ?");
    $stmt->execute([$id]);
}

function attribute_name_taken($pdo, $name, $id)
{
    $stmt = $pdo->prepare("SELECT attribute_id FROM attribute WHERE attribute_name = ? AND attribute_id != ?");
    $stmt->execute([$name, $id]);
    return $stmt->fetch();
}

function get_photos($pdo, $place_id)
{
    $stmt = $pdo->prepare("SELECT * FROM photo WHERE place_id = ? ORDER BY sort_order");
    $stmt->execute([$place_id]);
    return $stmt->fetchAll();
}

function get_photo($pdo, $photo_id)
{
    $stmt = $pdo->prepare("SELECT * FROM photo WHERE photo_id = ?");
    $stmt->execute([$photo_id]);
    return $stmt->fetch();
}

function add_photo($pdo, $place_id, $file_name, $is_cover, $sort_order)
{
    $stmt = $pdo->prepare("INSERT INTO photo (place_id, file_name, is_cover, sort_order) VALUES (?, ?, ?, ?)");
    $stmt->execute([$place_id, $file_name, $is_cover, $sort_order]);
}

function set_cover($pdo, $place_id, $photo_id)
{
    $stmt = $pdo->prepare("UPDATE photo SET is_cover = 0 WHERE place_id = ?");
    $stmt->execute([$place_id]);

    $stmt = $pdo->prepare("UPDATE photo SET is_cover = 1 WHERE photo_id = ?");
    $stmt->execute([$photo_id]);
}

function set_photo_order($pdo, $photo_id, $sort_order)
{
    $stmt = $pdo->prepare("UPDATE photo SET sort_order = ? WHERE photo_id = ?");
    $stmt->execute([$sort_order, $photo_id]);
}

function delete_photo($pdo, $photo_id)
{
    $stmt = $pdo->prepare("DELETE FROM photo WHERE photo_id = ?");
    $stmt->execute([$photo_id]);
}

function get_users($pdo)
{
    return $pdo->query("SELECT u.user_id, u.username, u.email, u.role, u.is_blocked,
                               GROUP_CONCAT(p.place_name SEPARATOR '، ') AS restaurants
                        FROM app_user u
                        LEFT JOIN place p ON p.owner_id = u.user_id
                        GROUP BY u.user_id
                        ORDER BY u.user_id")->fetchAll();
}

function get_user($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM app_user WHERE user_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function set_user_role($pdo, $id, $role)
{
    $stmt = $pdo->prepare("UPDATE app_user SET role = ? WHERE user_id = ?");
    $stmt->execute([$role, $id]);
}

function set_user_blocked($pdo, $id, $is_blocked)
{
    $stmt = $pdo->prepare("UPDATE app_user SET is_blocked = ? WHERE user_id = ?");
    $stmt->execute([$is_blocked, $id]);
}

function create_owner($pdo, $username, $email, $password)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO app_user (username, email, user_password, role) VALUES (?, ?, ?, 'owner')");
    $stmt->execute([$username, $email, $hash]);

    return $pdo->lastInsertId();
}

function set_restaurant_owner($pdo, $place_id, $user_id)
{
    $stmt = $pdo->prepare("UPDATE place SET owner_id = ? WHERE place_id = ?");
    $stmt->execute([$user_id, $place_id]);
}