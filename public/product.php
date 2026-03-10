<?php
require_once __DIR__ . '/../includes/helpers.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    exit('Product not found');
}
$title = $product['name'];
$reviewsStmt = db()->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON u.id = r.user_id WHERE product_id = :pid ORDER BY r.created_at DESC');
$reviewsStmt->execute(['pid' => $id]);
$reviews = $reviewsStmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<article class="card">
    <img src="<?= esc($product['image_url'] ?: 'https://via.placeholder.com/600') ?>" alt="<?= esc($product['name']) ?>">
    <h1><?= esc($product['name']) ?></h1>
    <p><?= esc($product['category_name'] ?? 'General') ?></p>
    <p><?= esc($product['description']) ?></p>
    <h3>$<?= number_format((float)$product['price'], 2) ?></h3>
    <p>Stock: <?= (int)$product['stock'] ?></p>
    <form method="post" action="/actions/cart_add.php">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <label>Quantity</label>
        <input type="number" name="quantity" value="1" min="1" max="<?= (int)$product['stock'] ?>">
        <button class="btn" type="submit">Add to Cart</button>
    </form>
    <?php if (current_user()): ?>
        <form method="post" action="/actions/wishlist_toggle.php" style="margin-top:10px;">
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <button class="btn secondary" type="submit">Toggle Wishlist</button>
        </form>
    <?php endif; ?>
</article>

<section>
    <h2>Reviews</h2>
    <?php if (current_user()): ?>
        <form method="post" action="/actions/review_add.php">
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <label>Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" required>
            <label>Comment</label>
            <textarea name="comment"></textarea>
            <button class="btn" type="submit">Submit Review</button>
        </form>
    <?php endif; ?>
    <?php foreach ($reviews as $review): ?>
        <div class="card">
            <strong><?= esc($review['name']) ?></strong> · ⭐ <?= (int)$review['rating'] ?>
            <p><?= esc($review['comment']) ?></p>
        </div>
    <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
