<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
$title = 'My Orders';
$stmt = db()->prepare('SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC');
$stmt->execute(['uid' => current_user()['id']]);
$orders = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Order History</h1>
<table>
    <thead><tr><th>ID</th><th>Status</th><th>Payment</th><th>Total</th><th>Date</th></tr></thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= (int)$order['id'] ?></td>
                <td><?= esc($order['status']) ?></td>
                <td><?= esc($order['payment_method']) ?></td>
                <td>$<?= number_format((float)$order['total_amount'], 2) ?></td>
                <td><?= esc($order['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
