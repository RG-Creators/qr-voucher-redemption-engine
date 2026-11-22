<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? '';

if (!$token) {
    echo json_encode(["status" => "not_found"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM customers WHERE qr_token = ?");
$stmt->execute([$token]);
$customer = $stmt->fetch();

if (!$customer) {
    echo json_encode(["status" => "not_found"]);
    exit;
}

if ($customer['is_redeemed']) {
    echo json_encode([
        "status" => "already_used",
        "customer" => [
            "name" => $customer['name'],
            "phone" => $customer['phone'],
            "redeemed_at" => $customer['redeemed_at']
        ]
    ]);
    exit;
}

echo json_encode([
    "status" => "valid",
    "customer" => [
        "id" => $customer['id'],
        "name" => $customer['name'],
        "phone" => $customer['phone']
    ]
]);