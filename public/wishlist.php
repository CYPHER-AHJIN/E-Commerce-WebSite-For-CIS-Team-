<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
$title = 'Wishlist';
$stmt = db()->prepare('SELECT p.* FROM wishlists w JOIN products p ON p.id = w.product_id WHERE w.user_id = :uid ORDER BY w.created_at DESC');
$stmt->execute(['uid' => current_user()['id']]);
$items = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Wishlist</h1>
<div class="grid">
<?php foreach ($items as $item): ?>
    <article class="card">
        <img src="<?= esc($item['image_url'] ?: 'https://via.placeholder.com/400') ?>" alt="<?= esc($item['name']) ?>">
        <h3><?= esc($item['name']) ?></h3>
        <p>$<?= number_format((float)$item['price'], 2) ?></p>
        <a class="btn" href="/public/product.php?id=<?= (int)$item['id'] ?>">View Product</a>
    </article>
<?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
