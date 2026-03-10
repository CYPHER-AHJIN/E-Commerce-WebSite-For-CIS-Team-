<?php
require_once __DIR__ . '/../includes/helpers.php';
$title = 'Cart';
$cart = cart();
$totals = cart_totals();
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Shopping Cart</h1>
<?php if (!$cart): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
<table>
    <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($cart as $item): ?>
        <tr>
            <td><?= esc($item['name']) ?></td>
            <td><?= (int)$item['quantity'] ?></td>
            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            <td>
                <form method="post" action="/actions/cart_remove.php">
                    <input type="hidden" name="product_id" value="<?= (int)$item['product_id'] ?>">
                    <button class="btn danger" type="submit">Remove</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="card">
    <p>Subtotal: $<?= number_format($totals['subtotal'], 2) ?></p>
    <p>Discount: $<?= number_format($totals['discount'], 2) ?></p>
    <h3>Total: $<?= number_format($totals['total'], 2) ?></h3>
    <form method="post" action="/actions/apply_discount.php">
        <label>Discount code</label><input name="code" type="text">
        <button class="btn secondary" type="submit">Apply</button>
    </form>
    <a class="btn" href="/public/checkout.php">Proceed to Checkout</a>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
