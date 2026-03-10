<?php
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM products WHERE id = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();
if (!$product) {
    exit('Product not found');
}
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$title = 'Edit Product';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Edit Product</h1>
<form method="post" action="/actions/admin_product_update.php">
    <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
    <label>Name</label><input name="name" value="<?= esc($product['name']) ?>">
    <label>Description</label><textarea name="description"><?= esc($product['description']) ?></textarea>
    <label>Price</label><input type="number" step="0.01" name="price" value="<?= esc($product['price']) ?>">
    <label>Stock</label><input type="number" name="stock" value="<?= esc($product['stock']) ?>">
    <label>Category</label>
    <select name="category_id"><?php foreach ($categories as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= (int)$product['category_id']===(int)$cat['id']?'selected':'' ?>><?= esc($cat['name']) ?></option><?php endforeach; ?></select>
    <label>Image URL</label><input name="image_url" value="<?= esc($product['image_url']) ?>">
    <label><input type="checkbox" name="featured" value="1" <?= (int)$product['featured']===1?'checked':'' ?>> Featured</label>
    <button class="btn" type="submit">Update Product</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
