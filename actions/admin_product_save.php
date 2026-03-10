<?php
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
if (!is_post()) {
    redirect('/public/admin.php');
}
if ($message = validate_required(['name', 'price', 'stock', 'category_id'])) {
    $_SESSION['flash_error'] = $message;
    redirect('/public/admin.php');
}
$stmt = db()->prepare('INSERT INTO products(category_id, name, description, price, stock, featured, image_url) VALUES(:category_id, :name, :description, :price, :stock, :featured, :image_url)');
$stmt->execute([
    'category_id' => (int)$_POST['category_id'],
    'name' => trim($_POST['name']),
    'description' => trim($_POST['description'] ?? ''),
    'price' => (float)$_POST['price'],
    'stock' => (int)$_POST['stock'],
    'featured' => isset($_POST['featured']) ? 1 : 0,
    'image_url' => trim($_POST['image_url'] ?? ''),
]);
$_SESSION['flash_success'] = 'Product saved.';
redirect('/public/admin.php');
