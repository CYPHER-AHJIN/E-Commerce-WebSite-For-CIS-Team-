<?php
require_once __DIR__ . '/../includes/helpers.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$action = $payload['action'] ?? '';

if ($action === 'login') {
    $email = filter_var(trim($payload['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $payload['password'] ?? '';
    if (!$email || $password === '') {
        http_response_code(422);
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }
    $stmt = db()->prepare('SELECT * FROM users WHERE email=:email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']];
    echo json_encode(['message' => 'Logged in']);
    exit;
}

http_response_code(422);
echo json_encode(['error' => 'Unsupported action']);
