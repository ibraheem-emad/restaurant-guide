<?php

function restaurants_page($pdo)
{
    $restaurants = get_restaurants($pdo);

    view('results', [
        'title' => 'المطاعم',
        'restaurants' => $restaurants,
    ]);
}

function restaurant_page($pdo, $id)
{
    $restaurant = get_restaurant($pdo, $id);

    if (!$restaurant) {
        not_found();
        return;
    }

    view('restaurant', [
        'title' => $restaurant['place_name'],
        'restaurant' => $restaurant,
    ]);
}
