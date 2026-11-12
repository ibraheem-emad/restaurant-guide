<?php

function register_page()
{
    view('register', ['title' => 'إنشاء حساب', 'error' => '']);
}

function register_submit($pdo)
{
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $error = '';

    if ($username == '') {
        $error = 'الاسم مطلوب.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'البريد الإلكتروني غير صحيح.';
    } elseif (strlen($password) < 8) {
        $error = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.';
    } elseif (find_user_by_email($pdo, $email)) {
        $error = 'هذا البريد مستخدم من قبل.';
    }

    if ($error != '') {
        view('register', ['title' => 'إنشاء حساب', 'error' => $error]);
        return;
    }

    create_user($pdo, $username, $email, $password);
    redirect('/login');
}

function login_page()
{
    view('login', ['title' => 'تسجيل الدخول', 'error' => '']);
}

function login_submit($pdo)
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $user = find_user_by_email($pdo, $email);

    if (!$user || !password_verify($password, $user['user_password'])) {
        view('login', ['title' => 'تسجيل الدخول', 'error' => 'البريد أو كلمة المرور غير صحيحة.']);
        return;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    redirect('/');
}

function logout()
{
    session_destroy();
    redirect('/');
}