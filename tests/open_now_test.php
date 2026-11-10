<?php

require __DIR__ . '/../app/open_now.php';

$normal = [
    ['day_of_week' => 0, 'open_time' => '09:00:00', 'close_time' => '22:00:00'],
];
$late = [
    ['day_of_week' => 5, 'open_time' => '18:00:00', 'close_time' => '01:00:00'],
];

$tests = [
    ['Sunday 12:00, open 09:00-22:00',          is_open_now($normal, 0, '12:00:00'), true],
    ['Sunday 23:00, open 09:00-22:00',          is_open_now($normal, 0, '23:00:00'), false],
    ['Monday 12:00, no hours on Monday',        is_open_now($normal, 1, '12:00:00'), false],
    ['Friday 23:30, open 18:00-01:00',          is_open_now($late, 5, '23:30:00'), true],
    ['Saturday 00:30, still open from Friday',  is_open_now($late, 6, '00:30:00'), true],
    ['Saturday 02:00, closed',                  is_open_now($late, 6, '02:00:00'), false],
];

foreach ($tests as $test) {
    $result = $test[1] === $test[2] ? 'PASS' : 'FAIL';
    echo $result . '  ' . $test[0] . "\n";
}
