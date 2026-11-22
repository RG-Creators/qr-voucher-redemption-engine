<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $aadhaar = trim($_POST['aadhaar']);

    if (!$name || !$phone || !$aadhaar) {
        $error = "All fields are required.";
    } else {

        $token = bin2hex(random_bytes(32));
        $qrFile = 'qr_' . $token . '.png';
        $qrPath = '../qr_codes/' . $qrFile;

        QRcode::png($token, $qrPath, QR_ECLEVEL_H, 8);

        $stmt = $pdo->prepare("
            INSERT INTO customers (name, phone, aadhaar, qr_token, qr_image)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $phone,
            $aadhaar,
            $token,
            $qrFile
        ]);

        $customer_id = $pdo->lastInsertId();

        header("Location: qr_view.php?id=" . $customer_id);
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<style>
body{
    background:#f4f7fb;
}

.page-title{
    font-size:28px;
    font-weight:600;
    color:#1e293b;
    margin-bottom:30px;
}

.form-card{
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.06);
    max-width:500px;
}

.input-group{
    margin-bottom:20px;
}

.input-label{
    font-size:14px;
    font-weight:500;
    color:#334155;
    margin-bottom:6px;
    display:block;
}

.input-field{
    width:100%;
    padding:12px 14px 12px 45px;
    border-radius:12px;
    border:1px solid #e2e8f0;
    font-size:14px;
    transition:0.3s;
}

.input-field:focus{
    outline:none;
    border-color:#6366f1;
    box-shadow:0 0 0 3px rgba(99,102,241,0.15);
}

.input-wrapper{
    position:relative;
}

.input-icon{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
}

.submit-btn{
    width:100%;
    padding:13px;
    border-radius:12px;
    border:none;
    font-weight:500;
    font-size:15px;
    cursor:pointer;
    color:white;
    background:linear-gradient(135deg,#667eea,#764ba2);
    transition:0.3s;
}

.submit-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(102,126,234,0.4);
}

.error-box{
    background:#fee2e2;
    color:#b91c1c;
    padding:12px;
    border-radius:12px;
    margin-bottom:20px;
}

.input-field{
    color:#1e293b; /* dark text */
    background:white;
}

.input-field::placeholder{
    color:#94a3b8;
}

</style>

<h1 class="page-title">
    <i class="fa-solid fa-user-plus text-indigo-600"></i> Add Customer
</h1>

<?php if ($error): ?>
<div class="error-box">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST" class="form-card">

    <div class="input-group">
        <label class="input-label">Customer Name</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-user input-icon"></i>
            <input name="name" class="input-field" placeholder="Enter full name" required>
        </div>
    </div>

    <div class="input-group">
        <label class="input-label">Phone Number</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-phone input-icon"></i>
            <input name="phone" class="input-field" placeholder="Enter phone number" required>
        </div>
    </div>

    <div class="input-group">
        <label class="input-label">Aadhaar Number</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-id-card input-icon"></i>
            <input name="aadhaar" class="input-field" placeholder="Enter Aadhaar number" required>
        </div>
    </div>

    <button class="submit-btn">
        <i class="fa-solid fa-qrcode"></i> Generate QR Code
    </button>

</form>

<?php include '../includes/footer.php'; ?>