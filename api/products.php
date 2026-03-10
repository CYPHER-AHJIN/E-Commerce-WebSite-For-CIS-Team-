<?php
require_once __DIR__ . '/../includes/helpers.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $q = trim($_GET['q'] ?? '');
    $sql = 'SELECT id, name, description, price, stock, image_url FROM products';
    $params = [];
    if ($q !== '') {
        $sql .= ' WHERE name LIKE :q OR description LIKE :q';
        $params['q'] = '%' . $q . '%';
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['data' => $stmt->fetchAll()]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
