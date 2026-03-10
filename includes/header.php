<?php require_once __DIR__ . '/helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Modern PHP e-commerce website demo">
    <title><?= esc($title ?? 'E-Commerce Store') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <nav>
        <a href="/public/index.php" class="brand">CIS Store</a>
        <a href="/public/products.php">Products</a>
        <?php if (current_user()): ?>
            <a href="/public/orders.php">My Orders</a>
            <a href="/public/wishlist.php">Wishlist</a>
            <?php if (current_user()['role'] === 'admin'): ?>
                <a href="/public/admin.php">Admin</a>
            <?php endif; ?>
            <a href="/actions/logout.php">Logout</a>
        <?php else: ?>
            <a href="/public/login.php">Login</a>
            <a href="/public/register.php">Register</a>
        <?php endif; ?>
        <a href="/public/cart.php">Cart (<?= cart_count() ?>)</a>
    </nav>
</header>
<main class="container">
    <?php if ($error = flash('flash_error')): ?>
        <p class="alert error"><?= esc($error) ?></p>
    <?php endif; ?>
    <?php if ($success = flash('flash_success')): ?>
        <p class="alert success"><?= esc($success) ?></p>
    <?php endif; ?>
