<?php

function get_restaurants($pdo)
{
    $sql = "SELECT p.place_id, p.place_name, p.rating, p.price_range,
                   GROUP_CONCAT(c.category_name SEPARATOR '، ') AS categories
            FROM place p
            LEFT JOIN place_category pc ON pc.place_id = p.place_id
            LEFT JOIN category c ON c.category_id = pc.category_id
            GROUP BY p.place_id
            ORDER BY p.rating DESC";
    $restaurants = $pdo->query($sql)->fetchAll();

    foreach ($restaurants as $i => $restaurant) {
        $hours = get_hours($pdo, $restaurant['place_id']);
        $restaurants[$i]['is_open'] = is_open_now($hours, date('w'), date('H:i:s'));
    }

    return $restaurants;
}

function get_restaurant($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM place WHERE place_id = ?");
    $stmt->execute([$id]);
    $restaurant = $stmt->fetch();

    if (!$restaurant) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT c.category_name
                           FROM place_category pc
                           JOIN category c ON c.category_id = pc.category_id
                           WHERE pc.place_id = ?");
    $stmt->execute([$id]);
    $restaurant['categories'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $pdo->prepare("SELECT a.attribute_name
                           FROM place_attribute pa
                           JOIN attribute a ON a.attribute_id = pa.attribute_id
                           WHERE pa.place_id = ?");
    $stmt->execute([$id]);
    $restaurant['attributes'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $restaurant['hours'] = get_hours($pdo, $id);
    $restaurant['is_open'] = is_open_now($restaurant['hours'], date('w'), date('H:i:s'));
    $restaurant['menu'] = get_menu($pdo, $id);

    return $restaurant;
}

function get_hours($pdo, $place_id)
{
    $stmt = $pdo->prepare("SELECT * FROM place_hours WHERE place_id = ?");
    $stmt->execute([$place_id]);

    $hours = [];
    foreach ($stmt->fetchAll() as $row) {
        $hours[$row['day_of_week']] = $row;
    }

    return $hours;
}

function get_menu($pdo, $place_id)
{
    $stmt = $pdo->prepare("SELECT c.category_name, m.meal_name, m.price
                           FROM meal m
                           LEFT JOIN meal_category mc ON mc.meal_id = m.meal_id
                           LEFT JOIN category c ON c.category_id = mc.category_id
                           WHERE m.place_id = ?
                           ORDER BY c.category_name, m.meal_name");
    $stmt->execute([$place_id]);

    $menu = [];
    foreach ($stmt->fetchAll() as $row) {
        $section = $row['category_name'] ?? 'أخرى';
        $menu[$section][] = $row;
    }

    return $menu;
}
