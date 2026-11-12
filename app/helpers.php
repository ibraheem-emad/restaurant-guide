<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url($path)
{
    return BASE_URL . $path;
}

function view($name, $data = [])
{
    extract($data);
    require __DIR__ . '/views/header.php';
    require __DIR__ . '/views/' . $name . '.php';
    require __DIR__ . '/views/footer.php';
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}

function not_found()
{
    http_response_code(404);
    view('404', ['title' => 'الصفحة غير موجودة']);
}

function json_response($data)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function day_name($day)
{
    $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
    return $days[$day];
}

function price_label($price_range)
{
    $labels = ['low' => '₪', 'medium' => '₪₪', 'high' => '₪₪₪'];
    return $labels[$price_range] ?? '';
}
