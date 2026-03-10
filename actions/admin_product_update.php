<?php
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
if (!is_post()) {
    redirect('/public/admin.php');
}
if ($message = validate_required(['id', 'name', 'price', 'stock', 'category_id'])) {
    $_SESSION['flash_error'] = $message;
    redirect('/public/admin.php');
}
$stmt = db()->prepare('UPDATE products SET category_id=:category_id, name=:name, description=:description, price=:price, stock=:stock, featured=:featured, image_url=:image_url WHERE id=:id');
$stmt->execute([
    'id' => (int)$_POST['id'],
    'category_id' => (int)$_POST['category_id'],
    'name' => trim($_POST['name']),
    'description' => trim($_POST['description'] ?? ''),
    'price' => (float)$_POST['price'],
    'stock' => (int)$_POST['stock'],
    'featured' => isset($_POST['featured']) ? 1 : 0,
    'image_url' => trim($_POST['image_url'] ?? ''),
]);
$_SESSION['flash_success'] = 'Product updated.';
redirect('/public/admin.php');
