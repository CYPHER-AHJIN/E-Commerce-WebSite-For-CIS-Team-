<?php
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
$title = 'Admin Dashboard';
$products = db()->query('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC')->fetchAll();
$orders = db()->query('SELECT o.*, u.email FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.id DESC LIMIT 20')->fetchAll();
$users = db()->query('SELECT id, name, email, role, created_at FROM users ORDER BY id DESC LIMIT 20')->fetchAll();
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Admin Dashboard</h1>
<section class="card">
<h2>Add Product</h2>
<form method="post" action="/actions/admin_product_save.php">
    <label>Name</label><input name="name">
    <label>Description</label><textarea name="description"></textarea>
    <label>Price</label><input type="number" step="0.01" name="price">
    <label>Stock</label><input type="number" name="stock">
    <label>Category</label>
    <select name="category_id">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= (int)$cat['id'] ?>"><?= esc($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Image URL</label><input name="image_url">
    <label><input type="checkbox" name="featured" value="1"> Featured</label>
    <button class="btn" type="submit">Save Product</button>
</form>
</section>

<section>
<h2>Products</h2>
<table><thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th></th></tr></thead><tbody>
<?php foreach ($products as $p): ?>
<tr>
<td><?= (int)$p['id'] ?></td><td><?= esc($p['name']) ?></td><td>$<?= number_format((float)$p['price'],2) ?></td><td><?= (int)$p['stock'] ?></td>
<td>
<form method="post" action="/actions/admin_product_delete.php" onsubmit="return confirm('Delete product?')">
<a class="btn secondary" href="/public/admin_product_edit.php?id=<?= (int)$p['id'] ?>">Edit</a> <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><button class="btn danger" type="submit">Delete</button>
</form>
</td></tr>
<?php endforeach; ?>
</tbody></table>
</section>

<section>
<h2>Recent Orders</h2>
<table><thead><tr><th>ID</th><th>User</th><th>Status</th><th>Total</th></tr></thead><tbody>
<?php foreach ($orders as $o): ?><tr><td>#<?= (int)$o['id'] ?></td><td><?= esc($o['email']) ?></td><td><?= esc($o['status']) ?></td><td>$<?= number_format((float)$o['total_amount'],2) ?></td></tr><?php endforeach; ?>
</tbody></table>
</section>

<section>
<h2>Users</h2>
<table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr></thead><tbody>
<?php foreach ($users as $u): ?><tr><td><?= (int)$u['id'] ?></td><td><?= esc($u['name']) ?></td><td><?= esc($u['email']) ?></td><td><?= esc($u['role']) ?></td></tr><?php endforeach; ?>
</tbody></table>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
