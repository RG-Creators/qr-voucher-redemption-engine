<?php include '../includes/header.php'; ?>

<style>

/* Light Professional Background */
body{
    background:#f4f7fb;
}

/* Title */
.dashboard-title{
    font-size:30px;
    font-weight:600;
    color:#1e293b;
    margin-bottom:35px;
}

/* Card */
.dashboard-card{
    background:white;
    padding:30px;
    border-radius:20px;
    transition:0.35s ease;
    box-shadow:0 10px 30px rgba(0,0,0,0.06);
    position:relative;
    overflow:hidden;
}

.dashboard-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 50px rgba(0,0,0,0.12);
}

/* Gradient Icon Box */
.card-icon{
    width:60px;
    height:60px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:16px;
    font-size:24px;
    margin-bottom:18px;
    color:white;
}

/* Different beautiful gradients */
.icon-add{
    background:linear-gradient(135deg,#667eea,#764ba2);
}

.icon-scan{
    background:linear-gradient(135deg,#f7971e,#ffd200);
}

.icon-list{
    background:linear-gradient(135deg,#11998e,#38ef7d);
}

/* Title */
.card-title{
    font-size:20px;
    font-weight:600;
    color:#0f172a;
    margin-bottom:8px;
}

/* Description */
.card-desc{
    font-size:14px;
    color:#64748b;
}

/* Subtle top border accent */
.dashboard-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:5px;
    background:linear-gradient(90deg,#667eea,#764ba2);
    opacity:0;
    transition:0.3s;
}

.dashboard-card:hover::before{
    opacity:1;
}

@media(max-width:768px){
    .dashboard-title{
        text-align:center;
    }
}

</style>

<div>
<!-- 
<h1 class="dashboard-title">
    Welcome Back 👋
</h1> -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">

    <a href="add_customer.php" class="dashboard-card">
        <div class="card-icon icon-add">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <div class="card-title">Add Customer</div>
        <div class="card-desc">
            Register a new customer and generate a secure QR code.
        </div>
    </a>

    <a href="scan.php" class="dashboard-card">
        <div class="card-icon icon-scan">
            <i class="fa-solid fa-camera"></i>
        </div>
        <div class="card-title">Scan & Redeem</div>
        <div class="card-desc">
            Scan customer QR codes and redeem rewards instantly.
        </div>
    </a>

    <a href="customers.php" class="dashboard-card">
        <div class="card-icon icon-list">
            <i class="fa-solid fa-address-book"></i>
        </div>
        <div class="card-title">Customer Directory</div>
        <div class="card-desc">
            View and manage all registered customers easily.
        </div>
    </a>

</div>

</div>

<?php include '../includes/footer.php'; ?>