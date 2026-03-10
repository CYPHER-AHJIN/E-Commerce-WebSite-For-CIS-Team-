<?php
require_once __DIR__ . '/../includes/helpers.php';
if (!is_post()) {
    redirect('/public/cart.php');
}
$code = trim($_POST['code'] ?? '');
if ($code === '') {
    $_SESSION['flash_error'] = 'All fields are required.';
    redirect('/public/cart.php');
}
$totals = cart_totals();
$stmt = db()->prepare('SELECT * FROM discounts WHERE code = :code AND is_active = 1 AND (expires_at IS NULL OR expires_at > NOW()) LIMIT 1');
$stmt->execute(['code' => $code]);
$discount = $stmt->fetch();
if (!$discount || $totals['subtotal'] < (float)$discount['min_order_amount']) {
    $_SESSION['flash_error'] = 'Invalid discount code.';
    redirect('/public/cart.php');
}
$amount = $discount['type'] === 'percentage'
    ? round($totals['subtotal'] * ((float)$discount['value'] / 100), 2)
    : min((float)$discount['value'], $totals['subtotal']);
$_SESSION['applied_discount'] = ['id' => $discount['id'], 'code' => $discount['code'], 'amount' => $amount];
$_SESSION['flash_success'] = 'Discount applied.';
redirect('/public/cart.php');
