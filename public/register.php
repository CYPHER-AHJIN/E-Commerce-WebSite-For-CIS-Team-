<?php
require_once __DIR__ . '/../includes/helpers.php';
$title = 'Register';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Register</h1>
<form method="post" action="/actions/register.php">
    <label>Name</label>
    <input type="text" name="name">
    <label>Email</label>
    <input type="email" name="email">
    <label>Password</label>
    <input type="password" name="password">
    <button class="btn" type="submit">Register</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
