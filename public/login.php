<?php
require_once __DIR__ . '/../includes/helpers.php';
$title = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Login</h1>
<form method="post" action="/actions/login.php">
    <label>Email</label>
    <input type="email" name="email">
    <label>Password</label>
    <input type="password" name="password">
    <button class="btn" type="submit">Login</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
