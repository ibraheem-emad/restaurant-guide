<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
</head>
<body>
<nav class="navbar bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= url('/') ?>">دليل المطاعم</a>

        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                أهلاً <?= e($_SESSION['username']) ?>
                <a class="btn btn-sm btn-outline-secondary ms-2" href="<?= url('/logout') ?>">خروج</a>
            <?php else: ?>
                <a class="btn btn-sm btn-outline-primary" href="<?= url('/login') ?>">دخول</a>
                <a class="btn btn-sm btn-primary" href="<?= url('/register') ?>">حساب جديد</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="container pb-5">