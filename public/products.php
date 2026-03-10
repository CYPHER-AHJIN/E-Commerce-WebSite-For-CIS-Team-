<?php
require_once __DIR__ . '/../includes/helpers.php';
$title = 'Products';
$params = [];
$query = 'SELECT p.*, c.name AS category_name, COALESCE(AVG(r.rating),0) AS avg_rating FROM products p LEFT JOIN categories c ON c.id = p.category_id LEFT JOIN reviews r ON r.product_id = p.id WHERE 1=1';

$search = trim($_GET['search'] ?? '');
$category = (int)($_GET['category'] ?? 0);
if ($search !== '') {
    $query .= ' AND (p.name LIKE :search OR p.description LIKE :search)';
    $params['search'] = '%' . $search . '%';
}
if ($category > 0) {
    $query .= ' AND p.category_id = :category';
    $params['category'] = $category;
}
$query .= ' GROUP BY p.id ORDER BY p.created_at DESC';

$stmt = db()->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Products</h1>
<form method="get" class="flex">
    <input name="search" placeholder="Search products" value="<?= esc($search) ?>">
    <select name="category">
        <option value="0">All categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= (int)$cat['id'] ?>" <?= $category === (int)$cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Filter</button>
</form>
<div class="grid">
    <?php foreach ($products as $product): ?>
        <article class="card">
            <img src="<?= esc($product['image_url'] ?: 'https://via.placeholder.com/400') ?>" alt="<?= esc($product['name']) ?>">
            <h3><?= esc($product['name']) ?></h3>
            <p><?= esc($product['category_name'] ?? 'General') ?> · ⭐ <?= number_format((float)$product['avg_rating'], 1) ?></p>
            <p>$<?= number_format((float)$product['price'], 2) ?></p>
            <a class="btn" href="/public/product.php?id=<?= (int)$product['id'] ?>">Details</a>
        </article>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
