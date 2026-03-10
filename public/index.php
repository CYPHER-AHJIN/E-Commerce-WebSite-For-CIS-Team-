<?php
require_once __DIR__ . '/../includes/helpers.php';
$title = 'Home';
$stmt = db()->query('SELECT * FROM products WHERE featured = 1 ORDER BY created_at DESC LIMIT 8');
$featuredProducts = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Modern E-Commerce Demo</h1>
    <p>Browse featured products and shop instantly.</p>
</section>
<section>
    <h2>Featured Products</h2>
    <div class="grid">
        <?php foreach ($featuredProducts as $product): ?>
            <article class="card">
                <img src="<?= esc($product['image_url'] ?: 'https://via.placeholder.com/400') ?>" alt="<?= esc($product['name']) ?>">
                <h3><?= esc($product['name']) ?></h3>
                <p>$<?= number_format((float)$product['price'], 2) ?></p>
                <a class="btn" href="/public/product.php?id=<?= (int)$product['id'] ?>">View</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
