<?php
require_once __DIR__ . '/../includes/helpers.php';
if (is_post()) {
    $productId = (int)($_POST['product_id'] ?? 0);
    $cart = cart();
    unset($cart[$productId]);
    set_cart($cart);
    $_SESSION['flash_success'] = 'Item removed.';
}
redirect('/public/cart.php');
