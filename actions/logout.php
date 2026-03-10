<?php
require_once __DIR__ . '/../includes/helpers.php';
session_destroy();
session_start();
$_SESSION['flash_success'] = 'Logged out successfully.';
redirect('/public/login.php');
