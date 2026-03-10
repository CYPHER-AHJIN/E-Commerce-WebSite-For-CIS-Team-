<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
if (!is_post()) {
    redirect('/public/products.php');
}
$productId = (int)($_POST['product_id'] ?? 0);
$check = db()->prepare('SELECT id FROM wishlists WHERE user_id = :uid AND product_id = :pid');
$check->execute(['uid' => current_user()['id'], 'pid' => $productId]);
if ($check->fetch()) {
    $del = db()->prepare('DELETE FROM wishlists WHERE user_id = :uid AND product_id = :pid');
    $del->execute(['uid' => current_user()['id'], 'pid' => $productId]);
    $_SESSION['flash_success'] = 'Removed from wishlist.';
} else {
    $ins = db()->prepare('INSERT INTO wishlists(user_id, product_id) VALUES(:uid, :pid)');
    $ins->execute(['uid' => current_user()['id'], 'pid' => $productId]);
    $_SESSION['flash_success'] = 'Added to wishlist.';
}
redirect('/public/product.php?id=' . $productId);
