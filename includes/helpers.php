<?php
require_once __DIR__ . '/config.php';

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        $_SESSION['flash_error'] = 'Please login first.';
        header('Location: /public/login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (current_user()['role'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function flash(string $key): ?string
{
    if (!isset($_SESSION[$key])) {
        return null;
    }
    $message = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $message;
}

function cart(): array
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

function set_cart(array $cart): void
{
    $_SESSION['cart'] = $cart;
}

function cart_count(): int
{
    return array_sum(array_column(cart(), 'quantity'));
}

function cart_totals(): array
{
    $items = cart();
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $discountAmount = $_SESSION['applied_discount']['amount'] ?? 0;
    return [
        'subtotal' => $subtotal,
        'discount' => $discountAmount,
        'total' => max($subtotal - $discountAmount, 0),
    ];
}

function validate_required(array $fields): ?string
{
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            return 'All fields are required.';
        }
    }
    return null;
}
