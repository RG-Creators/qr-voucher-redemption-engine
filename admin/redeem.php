<?php
require_once __DIR__ . '/../auth/auth_check.php';
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';

$message = null;
$messageType = null;
$customerData = null;

// Handle manual token search or redemption
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = trim($_POST['token'] ?? '');

    if ($action === 'search') {
        if (!empty($token)) {
            $stmt = $pdo->prepare("SELECT * FROM customers WHERE qr_token = ? OR phone = ? LIMIT 1");
            $stmt->execute([$token, $token]);
            $customerData = $stmt->fetch();
            if (!$customerData) {
                $message = "Voucher/Customer not found for '$token'.";
                $messageType = "danger";
            }
        } else {
            $message = "Please enter a valid QR token or phone number.";
            $messageType = "warning";
        }
    } elseif ($action === 'confirm_redeem') {
        $customerId = (int)($_POST['customer_id'] ?? 0);
        if ($customerId > 0) {
            $pdo->beginTransaction();
            try {
                $check = $pdo->prepare("SELECT * FROM customers WHERE id = ? FOR UPDATE");
                $check->execute([$customerId]);
                $c = $check->fetch();

                if ($c && $c['is_redeemed'] == 0) {
                    $upd = $pdo->prepare("UPDATE customers SET is_redeemed = 1, redeemed_at = NOW(), redeemed_by = ? WHERE id = ?");
                    $upd->execute([$_SESSION['admin_id'], $customerId]);

                    $log = $pdo->prepare("INSERT INTO redemption_logs (customer_id, admin_id, ip_address) VALUES (?, ?, ?)");
                    $log->execute([$customerId, $_SESSION['admin_id'], $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1']);

                    $pdo->commit();
                    $message = "Voucher successfully redeemed for " . htmlspecialchars($c['name']) . "!";
                    $messageType = "success";
                    $c['is_redeemed'] = 1;
                    $c['redeemed_at'] = date('Y-m-d H:i:s');
                    $customerData = $c;
                } else {
                    $pdo->rollBack();
                    $message = "This voucher has already been redeemed.";
                    $messageType = "danger";
                    $customerData = $c;
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = "Redemption failed: " . $e->getMessage();
                $messageType = "danger";
            }
        }
    }
}

// Fetch recent redemptions
$recentStmt = $pdo->query("
    SELECT r.id, r.redeemed_at, c.name as customer_name, c.phone, c.qr_token, a.username as admin_name
    FROM redemption_logs r
    JOIN customers c ON r.customer_id = c.id
    LEFT JOIN admins a ON r.admin_id = a.id
    ORDER BY r.redeemed_at DESC
    LIMIT 8
");
$recentRedemptions = $recentStmt->fetchAll();

include '../includes/header.php';
?>

<div class="p-6 max-w-6xl mx-auto space-y-6">
    <!-- Header banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 text-white">
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-barcode text-indigo-400"></i> Manual Voucher Redemption
            </h1>
            <p class="text-sm text-gray-300 mt-1">Direct token lookup & cashier redemption gateway</p>
        </div>
        <div class="flex gap-3">
            <a href="scan.php" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition flex items-center gap-2 shadow-lg">
                <i class="fas fa-camera"></i> Live Camera Scanner
            </a>
            <a href="dashboard.php" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-xl transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="p-4 rounded-xl text-sm font-semibold flex items-center gap-3 <?= $messageType === 'success' ? 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-200' : ($messageType === 'warning' ? 'bg-amber-500/20 border border-amber-500/40 text-amber-200' : 'bg-rose-500/20 border border-rose-500/40 text-rose-200') ?>">
            <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Search & Verify Form -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 lg:col-span-1">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-search text-indigo-600"></i> Find Voucher
            </h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="search">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">QR Token / Phone</label>
                    <input type="text" name="token" required value="<?= htmlspecialchars($_POST['token'] ?? '') ?>" placeholder="e.g. 64-char token or phone" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono">
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Search Voucher
                </button>
            </form>
        </div>

        <!-- Verification Result & Action Card -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 lg:col-span-2">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-id-card text-indigo-600"></i> Voucher Details & Verification
            </h2>

            <?php if ($customerData): ?>
                <div class="space-y-4">
                    <div class="p-4 rounded-xl <?= $customerData['is_redeemed'] ? 'bg-rose-50 border border-rose-200' : 'bg-emerald-50 border border-emerald-200' ?> flex items-center justify-between">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider <?= $customerData['is_redeemed'] ? 'text-rose-600' : 'text-emerald-600' ?>">
                                <?= $customerData['is_redeemed'] ? 'ALREADY REDEEMED' : 'VALID & READY TO REDEEM' ?>
                            </span>
                            <h3 class="text-xl font-bold text-gray-900 mt-1"><?= htmlspecialchars($customerData['name']) ?></h3>
                            <p class="text-sm text-gray-600"><i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($customerData['phone']) ?></p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold <?= $customerData['is_redeemed'] ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' ?>">
                                <?= $customerData['is_redeemed'] ? 'Status: Claimed' : 'Status: Unused' ?>
                            </span>
                            <?php if ($customerData['is_redeemed'] && !empty($customerData['redeemed_at'])): ?>
                                <p class="text-xs text-gray-500 mt-1">Claimed: <?= htmlspecialchars($customerData['redeemed_at']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl text-xs space-y-1 font-mono text-gray-600 break-all">
                        <p><strong>QR Token:</strong> <?= htmlspecialchars($customerData['qr_token']) ?></p>
                        <p><strong>Issued On:</strong> <?= htmlspecialchars($customerData['created_at'] ?? 'N/A') ?></p>
                    </div>

                    <?php if (!$customerData['is_redeemed']): ?>
                        <form method="POST" onsubmit="return confirm('Confirm redemption for this voucher? This action is irreversible.');">
                            <input type="hidden" name="action" value="confirm_redeem">
                            <input type="hidden" name="customer_id" value="<?= (int)$customerData['id'] ?>">
                            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Confirm & Redeem Voucher Now
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="p-3 bg-gray-100 text-gray-500 rounded-xl text-center text-sm font-medium">
                            <i class="fas fa-ban mr-1"></i> This voucher has already been marked as redeemed and cannot be reused.
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="py-12 text-center text-gray-400">
                    <i class="fas fa-ticket-alt text-4xl mb-3 opacity-40"></i>
                    <p class="text-sm">Search a customer phone or enter a QR token to inspect voucher status.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Redemptions Table -->
    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-indigo-600"></i> Recent Redemptions Audit Log
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Phone</th>
                        <th class="py-3 px-4">Redeemed At</th>
                        <th class="py-3 px-4">Cashier / Admin</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (count($recentRedemptions) > 0): ?>
                        <?php foreach ($recentRedemptions as $r): ?>
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 px-4 font-semibold text-gray-900"><?= htmlspecialchars($r['customer_name']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($r['phone']) ?></td>
                                <td class="py-3 px-4 text-xs"><?= htmlspecialchars($r['redeemed_at']) ?></td>
                                <td class="py-3 px-4 font-mono text-xs"><?= htmlspecialchars($r['admin_name'] ?? 'System Admin') ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Redeemed
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400">No redemption logs recorded yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
