<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
$title = 'Checkout';
$totals = cart_totals();
if (!cart()) {
    $_SESSION['flash_error'] = 'Cart is empty.';
    redirect('/public/cart.php');
}
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Checkout</h1>
<form method="post" action="/actions/checkout.php">
    <label>Shipping Address</label>
    <textarea name="shipping_address" required></textarea>
    <label>Payment Method</label>
    <select name="payment_method">
        <option value="stripe_simulation">Stripe Simulation</option>
        <option value="paypal_simulation">PayPal Simulation</option>
        <option value="cash_on_delivery">Cash On Delivery</option>
    </select>
    <p>Total to pay: <strong>$<?= number_format($totals['total'], 2) ?></strong></p>
    <button class="btn" type="submit">Place Order</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
