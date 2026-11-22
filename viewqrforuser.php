<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/config/database.php';

$customer = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $phone   = trim($_POST['phone'] ?? '');
    $aadhaar = trim($_POST['aadhaar'] ?? '');

    if ($phone && $aadhaar) {

        $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = ? AND aadhaar = ?");
        $stmt->execute([$phone, $aadhaar]);
        $customer = $stmt->fetch();

        if (!$customer) {
            $error = "Invalid Phone or Aadhaar number.";
        }

    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View QR</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-6">

<div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 border border-gray-100">

    <!-- Branding -->
    <div class="text-center mb-6">
        <!-- <h1 class="text-3xl font-bold text-indigo-600">RG</h1> -->
        <p class="text-gray-500 text-sm">QR Redemption System</p>
    </div>

<?php if (!$customer): ?>

    <h2 class="text-xl font-semibold text-gray-700 text-center mb-4">
        Customer Verification
    </h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-600 text-sm p-3 rounded-lg mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <input type="text" name="phone" placeholder="Enter Phone Number"
               class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-400 focus:outline-none">

        <input type="text" name="aadhaar" placeholder="Enter Aadhaar Number"
               class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-400 focus:outline-none">

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium transition">
            <i class="fa-solid fa-qrcode mr-2"></i>
            View My QR
        </button>

    </form>

<?php else: ?>


<?php
    $qrURL = "qr_codes/" . htmlspecialchars($customer['qr_image']);
    $isRedeemed = (int)$customer['is_redeemed'] === 1;
?>

<div class="text-center relative">

    <h2 class="text-xl font-semibold text-gray-700 mb-4">
        Your QR Code
    </h2>

    <!-- QR Wrapper -->
    <div class="flex justify-center mb-4 relative">

        <img src="<?= $qrURL ?>" class="w-52 rounded-2xl shadow-lg">

        <?php if ($isRedeemed): ?>
            <!-- USED Stamp -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-red-600 text-white text-xl font-bold px-6 py-2 rotate-[-20deg] opacity-90 shadow-2xl rounded-lg tracking-widest">
                    USED
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="text-gray-800 font-semibold">
        <?= htmlspecialchars($customer['name']) ?>
    </div>

    <div class="text-gray-500 text-sm mb-4">
        <?= htmlspecialchars($customer['phone']) ?>
    </div>

    <!-- Status Badge -->
    <?php if ($isRedeemed): ?>
        <div class="mb-6 inline-block bg-red-100 text-red-600 text-sm px-4 py-2 rounded-full font-medium">
            Redeemed Successfully
        </div>
    <?php else: ?>
        <div class="mb-6 inline-block bg-yellow-100 text-yellow-600 text-sm px-4 py-2 rounded-full font-medium">
            Not Redeemed Yet
        </div>
    <?php endif; ?>

    <div class="space-y-3">

        <?php if (!$isRedeemed): ?>
        <a href="<?= $qrURL ?>" download
           class="block w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-medium transition">
            <i class="fa-solid fa-download mr-2"></i>
            Download QR
        </a>
        <?php endif; ?>

        <a href="index.php"
           class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-medium transition">
            Back
        </a>

    </div>

</div>

<?php endif; ?>

    <!-- Footer Branding -->
    <div class="mt-8 text-center text-xm text-gray-400">
        Designed & Developed by 
        <a href="https://rgcreators.netlify.app" target="_blank"
           class="text-indigo-600 font-semibold hover:underline">
            RG
        </a>
    </div>

</div>

</body>
</html>