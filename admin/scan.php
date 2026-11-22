<?php include '../includes/header.php'; ?>

<style>
body{
    background:#f4f7fb;
    font-family:'Poppins',sans-serif;
    color:#000;
}

/* Page Title */
.page-title{
    font-size:26px;
    font-weight:600;
    margin-bottom:20px;
    color:#000;
}

/* Card */
.scan-card{
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    max-width:600px;
    width:100%;
}

/* Scanner Box */
#reader{
    width:100%;
    border-radius:12px;
    overflow:hidden;
}

/* ===============================
   FIX html5-qrcode INTERNAL UI
================================ */

/* Permission text */
#reader * {
    color:#000 !important;
}

/* Camera select dropdown */
#reader select{
    color:#000 !important;
    background:#fff !important;
}

/* Permission button */
#reader button{
    background:#000 !important;
    color:#fff !important;
    border:none !important;
    padding:6px 12px !important;
    border-radius:6px !important;
}

/* Result Area */
#result{
    margin-top:20px;
    font-size:14px;
    color:#000;
}

/* Status Boxes */
.result-box{
    padding:15px;
    border-radius:12px;
    margin-top:10px;
}

.result-success{
    background:#dcfce7;
    color:#000;
}

.result-error{
    background:#fee2e2;
    color:#000;
}

.result-warning{
    background:#fef9c3;
    color:#000;
}

/* Buttons */
.btn{
    padding:8px 14px;
    border-radius:8px;
    border:none;
    font-size:14px;
    font-weight:500;
    cursor:pointer;
    transition:0.2s;
}

.btn-primary{
    background:#4f46e5;
    color:#fff;
}

.btn-primary:hover{
    background:#4338ca;
}

.btn-dark{
    background:#000;
    color:#fff;
}

.btn-dark:hover{
    background:#333;
}

/* Responsive */
@media(max-width:768px){
    .scan-card{
        padding:18px;
    }
}
</style>

<h1 class="page-title">Scan QR Code</h1>

<div class="scan-card">

    <div id="reader"></div>

    <div id="result"></div>

    <button onclick="startScanner()" class="btn btn-dark" style="margin-top:15px;">
        Restart Scanner
    </button>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

let html5QrcodeScanner;

/* ==============================
   START SCANNER
============================== */
function startScanner() {

    document.getElementById("result").innerHTML = "";

    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            rememberLastUsedCamera: true,
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_CAMERA,
                Html5QrcodeScanType.SCAN_TYPE_FILE
            ]
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}

/* ==============================
   SCAN SUCCESS
============================== */
function onScanSuccess(decodedText) {

    html5QrcodeScanner.clear();

    document.getElementById("result").innerHTML =
        "<div class='result-box result-warning'>Checking QR...</div>";

    fetch("../api/scan_verify.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: decodedText })
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "not_found") {
            showError("Invalid QR Code");
        }

        else if (data.status === "already_used") {
            document.getElementById("result").innerHTML = `
                <div class="result-box result-error">
                    <h3 style="font-weight:600;margin-bottom:8px;">Already Redeemed</h3>
                    <p><b>Name:</b> ${data.customer.name}</p>
                    <p><b>Phone:</b> ${data.customer.phone}</p>
                    <p><b>Redeemed:</b> ${data.customer.redeemed_at}</p>
                </div>
            `;
        }

        else if (data.status === "valid") {
            document.getElementById("result").innerHTML = `
                <div class="result-box result-success">
                    <h3 style="font-weight:600;margin-bottom:8px;">Valid QR</h3>
                    <p><b>Name:</b> ${data.customer.name}</p>
                    <p><b>Phone:</b> ${data.customer.phone}</p>

                    <button onclick="redeem(${data.customer.id})"
                        class="btn btn-primary" style="margin-top:10px;">
                        Confirm Redeem
                    </button>
                </div>
            `;
        }

    })
    .catch(() => showError("Server error"));
}

/* ==============================
   SCAN FAILURE
============================== */
function onScanFailure(error) {}

/* ==============================
   SHOW ERROR
============================== */
function showError(msg) {
    document.getElementById("result").innerHTML =
        `<div class="result-box result-error">${msg}</div>`;
}

/* ==============================
   REDEEM CUSTOMER
============================== */
function redeem(customerId) {

    if (!confirm("Confirm redeem this customer?")) return;

    fetch("../api/redeem_customer.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ customer_id: customerId })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            document.getElementById("result").innerHTML =
                "<div class='result-box result-success' style='font-weight:600;'>Redeemed Successfully</div>";
        } else {
            showError("Redemption failed or already used");
        }
    });
}

/* ==============================
   START ON PAGE LOAD
============================== */
startScanner();

</script>

<?php include '../includes/footer.php'; ?>