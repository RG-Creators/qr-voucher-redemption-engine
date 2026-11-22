<?php
include '../includes/header.php';

define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "Invalid request";
    include '../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$id]);
$customer = $stmt->fetch();

if (!$customer) {
    echo "Customer not found";
    include '../includes/footer.php';
    exit;
}

$qrURL = "../qr_codes/" . htmlspecialchars($customer['qr_image']);
?>

<style>
body{
    background:#f4f7fb;
}

/* Title */
.page-title{
    font-size:28px;
    font-weight:600;
    color:#1e293b;
    margin-bottom:30px;
}

/* Card */
.qr-card{
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.06);
    max-width:480px;
    margin:auto;
    text-align:center;
}

/* QR wrapper */
.qr-wrapper{
    position:relative;
    width:220px;
    margin:auto;
    margin-bottom:20px;
}

/* QR Image */
.qr-image{
    width:100%;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* RG Center Logo */
.qr-center-logo{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    background:white;
    padding:6px 14px;
    border-radius:10px;
    font-weight:700;
    font-size:18px;
    color:#4f46e5;
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

/* Customer Info */
.customer-name{
    font-size:20px;
    font-weight:600;
    color:#0f172a;
}

.customer-phone{
    color:#64748b;
    margin-bottom:25px;
}

/* Buttons */
.action-buttons{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.action-btn{
    width:100%;
    padding:12px;
    border-radius:12px;
    font-weight:500;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    transition:0.3s;
    border:none;
    cursor:pointer;
    text-decoration:none;
}

/* Download */
.btn-download{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
}
.btn-download:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(34,197,94,0.4);
}

/* Share */
.btn-share{
    background:linear-gradient(135deg,#6366f1,#4f46e5);
    color:white;
}
.btn-share:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(99,102,241,0.4);
}

/* Back */
.btn-back{
    background:#e2e8f0;
    color:#1e293b;
}
.btn-back:hover{
    background:#cbd5e1;
}

/* Footer Branding */
.qr-footer{
    margin-top:30px;
    font-size:13px;
    color:#64748b;
}

.qr-footer a{
    color:#6366f1;
    text-decoration:none;
}

.qr-footer a:hover{
    text-decoration:underline;
}
</style>

<h1 class="page-title">
    <i class="fa-solid fa-circle-check text-green-500"></i>
    QR Generated Successfully
</h1>

<div class="qr-card">

    <!-- QR With RG Center -->
    <div class="qr-wrapper">
        <img src="<?= $qrURL ?>" class="qr-image" alt="QR Code">
        <div class="qr-center-logo">RG</div>
    </div>

    <div class="customer-name">
        <?= htmlspecialchars($customer['name']) ?>
    </div>

    <div class="customer-phone">
        <?= htmlspecialchars($customer['phone']) ?>
    </div>

    <div class="action-buttons">

        <a href="<?= $qrURL ?>" download class="action-btn btn-download">
            <i class="fa-solid fa-download"></i>
            Download QR
        </a>

        <!-- <button onclick="shareQR()" class="action-btn btn-share">
            <i class="fa-solid fa-share-nodes"></i>
            Share QR
        </button> -->

        <a href="dashboard.php" class="action-btn btn-back">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>

    </div>

    <!-- Footer Branding -->
    <div class="qr-footer">
        System created and designed by <strong>RG</strong><br>
        <!-- <a href="https://rgcreators.netlify.app" target="_blank">
            https://rgcreators.netlify.app
        </a> -->
    </div>

</div>

<script>
function goBack() {
    if (document.referrer) {
        window.history.back();
    } else {
        window.location.href = "dashboard.php";
    }
}

function shareQR() {

    const url = window.location.origin + "/scanner_reedmption/qr_codes/<?= $customer['qr_image'] ?>";

    if (navigator.share) {
        navigator.share({
            title: 'QR Code',
            text: 'Show this QR at the event.',
            url: url
        }).catch(() => {});
    } else {
        const shareBox = document.getElementById("shareLinks");
        shareBox.style.display = shareBox.style.display === "block" ? "none" : "block";

        document.getElementById("whatsappShare").href =
            "https://wa.me/?text=" + encodeURIComponent("Here is your QR: " + url);

        document.getElementById("telegramShare").href =
            "https://t.me/share/url?url=" + encodeURIComponent(url);

        document.getElementById("emailShare").href =
            "mailto:?subject=Your QR Code&body=" + encodeURIComponent("Here is your QR: " + url);
    }
}

function copyLink() {
    const url = window.location.origin + "/scanner_reedmption/qr_codes/<?= $customer['qr_image'] ?>";

    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            alert("Link copied to clipboard!");
        });
    } else {
        prompt("Copy this link:", url);
    }
}
</script>

<?php include '../includes/footer.php'; ?>