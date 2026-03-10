<?php
require_once __DIR__ . '/../includes/helpers.php';
if (!is_post()) {
    redirect('/public/register.php');
}
if ($message = validate_required(['name', 'email', 'password'])) {
    $_SESSION['flash_error'] = $message;
    redirect('/public/register.php');
}

$name = trim($_POST['name']);
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];
if (!$email) {
    $_SESSION['flash_error'] = 'Invalid email.';
    redirect('/public/register.php');
}

$exists = db()->prepare('SELECT id FROM users WHERE email = :email');
$exists->execute(['email' => $email]);
if ($exists->fetch()) {
    $_SESSION['flash_error'] = 'Email already exists.';
    redirect('/public/register.php');
}

$stmt = db()->prepare('INSERT INTO users(name, email, password_hash) VALUES(:name, :email, :password_hash)');
$stmt->execute([
    'name' => $name,
    'email' => $email,
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
]);
$_SESSION['flash_success'] = 'Registration complete. Please login.';
redirect('/public/login.php');
