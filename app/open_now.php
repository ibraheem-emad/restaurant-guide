<?php

function is_open_now($hours, $today, $time)
{
    $today = (int) $today;
    $yesterday = $today == 0 ? 6 : $today - 1;

    foreach ($hours as $row) {
        $day = (int) $row['day_of_week'];
        $open = $row['open_time'];
        $close = $row['close_time'];

        if ($close > $open) {
            if ($day == $today && $time >= $open && $time < $close) {
                return true;
            }
        } else {
            // closes after midnight, for example 18:00 to 01:00
            if ($day == $today && $time >= $open) {
                return true;
            }
            if ($day == $yesterday && $time < $close) {
                return true;
            }
        }
    }

    return false;
}
