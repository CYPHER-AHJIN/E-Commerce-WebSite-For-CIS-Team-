<?php
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
if (is_post()) {
    $id = (int)($_POST['product_id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $_SESSION['flash_success'] = 'Product deleted.';
}
redirect('/public/admin.php');
