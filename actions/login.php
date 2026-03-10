<?php
require_once __DIR__ . '/../includes/helpers.php';
if (!is_post()) {
    redirect('/public/login.php');
}
if ($message = validate_required(['email', 'password'])) {
    $_SESSION['flash_error'] = $message;
    redirect('/public/login.php');
}

$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];
$stmt = db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['flash_error'] = 'Invalid credentials.';
    redirect('/public/login.php');
}

$_SESSION['user'] = [
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'role' => $user['role'],
];
$_SESSION['flash_success'] = 'Welcome back!';
redirect('/public/index.php');
