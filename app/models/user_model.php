<?php

function find_user_by_email($pdo, $email)
{
    $stmt = $pdo->prepare("SELECT * FROM app_user WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function create_user($pdo, $username, $email, $password)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO app_user (username, email, user_password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hash]);
}