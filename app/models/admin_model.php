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