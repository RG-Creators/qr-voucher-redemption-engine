<?php
session_start();

define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['customer_id'] ?? null;

if (!$id || !isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false]);
    exit;
}

/* prevent double redeem */
$stmt = $pdo->prepare("
    UPDATE customers
    SET is_redeemed = 1,
        redeemed_at = NOW(),
        redeemed_by = ?
    WHERE id = ? AND is_redeemed = 0
");

$stmt->execute([$_SESSION['admin_id'], $id]);

if ($stmt->rowCount()) {

    /* log redemption */
    $pdo->prepare("
        INSERT INTO redemption_logs (customer_id, admin_id, ip_address)
        VALUES (?, ?, ?)
    ")->execute([
        $id,
        $_SESSION['admin_id'],
        $_SERVER['REMOTE_ADDR'] ?? null
    ]);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}