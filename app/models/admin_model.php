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