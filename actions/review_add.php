<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
if (!is_post()) {
    redirect('/public/products.php');
}
$productId = (int)($_POST['product_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
if ($rating < 1 || $rating > 5) {
    $_SESSION['flash_error'] = 'Rating must be between 1 and 5.';
    redirect('/public/product.php?id=' . $productId);
}
$stmt = db()->prepare('INSERT INTO reviews(user_id, product_id, rating, comment) VALUES(:user_id, :product_id, :rating, :comment)');
$stmt->execute([
    'user_id' => current_user()['id'],
    'product_id' => $productId,
    'rating' => $rating,
    'comment' => $comment,
]);
$_SESSION['flash_success'] = 'Review added.';
redirect('/public/product.php?id=' . $productId);
