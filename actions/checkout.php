<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
if (!is_post()) {
    redirect('/public/checkout.php');
}
if ($message = validate_required(['shipping_address', 'payment_method'])) {
    $_SESSION['flash_error'] = $message;
    redirect('/public/checkout.php');
}
$cart = cart();
if (!$cart) {
    $_SESSION['flash_error'] = 'Cart is empty.';
    redirect('/public/cart.php');
}
$totals = cart_totals();
$paymentMethod = $_POST['payment_method'];
$shippingAddress = trim($_POST['shipping_address']);

$pdo = db();
$pdo->beginTransaction();
try {
    foreach ($cart as $item) {
        $stockStmt = $pdo->prepare('SELECT stock FROM products WHERE id = :id FOR UPDATE');
        $stockStmt->execute(['id' => $item['product_id']]);
        $stock = (int)$stockStmt->fetchColumn();
        if ($stock < $item['quantity']) {
            throw new RuntimeException('Insufficient stock for ' . $item['name']);
        }
    }

    $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, status, payment_method, subtotal, discount_amount, total_amount, discount_id, shipping_address) VALUES (:user_id, :status, :payment_method, :subtotal, :discount_amount, :total_amount, :discount_id, :shipping_address)');
    $orderStmt->execute([
        'user_id' => current_user()['id'],
        'status' => 'paid',
        'payment_method' => $paymentMethod,
        'subtotal' => $totals['subtotal'],
        'discount_amount' => $totals['discount'],
        'total_amount' => $totals['total'],
        'discount_id' => $_SESSION['applied_discount']['id'] ?? null,
        'shipping_address' => $shippingAddress,
    ]);
    $orderId = (int)$pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items(order_id, product_id, quantity, unit_price) VALUES(:order_id, :product_id, :quantity, :unit_price)');
    $stockUpdateStmt = $pdo->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id');
    foreach ($cart as $item) {
        $itemStmt->execute([
            'order_id' => $orderId,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['price'],
        ]);
        $stockUpdateStmt->execute(['qty' => $item['quantity'], 'id' => $item['product_id']]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    unset($_SESSION['applied_discount']);
    $_SESSION['flash_success'] = 'Order placed successfully!';
    redirect('/public/orders.php');
} catch (Throwable $e) {
    $pdo->rollBack();
    $_SESSION['flash_error'] = 'Checkout failed: ' . $e->getMessage();
    redirect('/public/checkout.php');
}
