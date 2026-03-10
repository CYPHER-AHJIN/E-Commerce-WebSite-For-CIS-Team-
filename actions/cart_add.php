<?php
require_once __DIR__ . '/../includes/helpers.php';
if (!is_post()) {
    redirect('/public/products.php');
}
$productId = (int)($_POST['product_id'] ?? 0);
$qty = max((int)($_POST['quantity'] ?? 1), 1);
$stmt = db()->prepare('SELECT id, name, price, stock FROM products WHERE id = :id');
$stmt->execute(['id' => $productId]);
$product = $stmt->fetch();
if (!$product) {
    $_SESSION['flash_error'] = 'Product not found.';
    redirect('/public/products.php');
}
$qty = min($qty, (int)$product['stock']);
$cart = cart();
if (isset($cart[$productId])) {
    $cart[$productId]['quantity'] = min($cart[$productId]['quantity'] + $qty, (int)$product['stock']);
} else {
    $cart[$productId] = [
        'product_id' => $productId,
        'name' => $product['name'],
        'price' => (float)$product['price'],
        'quantity' => $qty,
    ];
}
set_cart($cart);
$_SESSION['flash_success'] = 'Item added to cart.';
redirect('/public/cart.php');
