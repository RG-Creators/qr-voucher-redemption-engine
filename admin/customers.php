<?php
include '../includes/header.php';

define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';

/*
-----------------------------------------
Search functionality
-----------------------------------------
*/
$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $pdo->prepare("
        SELECT * FROM customers
        WHERE name LIKE ? OR phone LIKE ? OR aadhaar LIKE ?
        ORDER BY id DESC
    ");
    $like = "%$search%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM customers ORDER BY id DESC");
}

$customers = $stmt->fetchAll();
?>

<style>

/* 🔥 Global Fix */
*,
*::before,
*::after{
    box-sizing:border-box;
}

html, body{
    margin:0;
    padding:0;
    width:100%;
    max-width:100%;
    overflow-x:hidden;
    background:#f4f7fb;
    font-family:'Poppins',sans-serif;
}

/* Page Content Wrapper (IMPORTANT) */
.page-wrapper{
    padding:-15px;
    width:100%;
    max-width:100%;
}

/* Page Title */
.page-title{
    font-size:24px;
    font-weight:600;
    color:#1e293b;
    margin-bottom:20px;
}

/* Search Box */
.search-box{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:20px;
}

.search-input{
    flex:1;
    min-width:0;
    padding:12px 15px;
    border-radius:12px;
    border:1px solid #e2e8f0;
    font-size:14px;
    width:100%;
    transition:0.3s;
}

.search-input:focus{
    outline:none;
    border-color:#6366f1;
    box-shadow:0 0 0 3px rgba(99,102,241,0.15);
}

.search-btn{
    padding:12px 18px;
    border-radius:12px;
    background:linear-gradient(135deg,#6366f1,#4f46e5);
    color:white;
    border:none;
    font-weight:500;
    transition:0.3s;
}

.search-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(99,102,241,0.3);
}

/* Table Card */
.table-card{
    width:100%;
    max-width:100%;
    background:white;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    overflow-x:auto;
}

/* Table */
table{
    width:100%;
    max-width:100%;
    border-collapse:collapse;
    font-size:14px;
}

thead{
    background:#f8fafc;
}

th{
    padding:12px;
    text-align:left;
    font-weight:600;
    color:#334155;
    white-space:nowrap;
}

td{
    padding:12px;
    color:#475569;
    word-break:break-word;
    white-space:normal;
}

tr:not(:last-child){
    border-bottom:1px solid #f1f5f9;
}

tr:hover{
    background:#f9fafb;
}

/* Status Badges */
.badge{
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:500;
    display:inline-block;
}

.badge-success{
    background:#dcfce7;
    color:#15803d;
}

.badge-warning{
    background:#fef9c3;
    color:#a16207;
}

/* Action Buttons */
.action-btn{
    padding:6px 10px;
    border-radius:8px;
    font-size:12px;
    font-weight:500;
    border:none;
    cursor:pointer;
    text-decoration:none;
    transition:0.25s;
    display:inline-block;
    text-align:center;
}

.btn-view{
    background:#3b82f6;
    color:white;
}

.btn-view:hover{
    background:#2563eb;
}

.btn-share{
    background:#22c55e;
    color:white;
}

.btn-share:hover{
    background:#16a34a;
}

/* Action Cell */
.action-cell{
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}

/* ✅ Mobile Optimization */
@media(max-width:768px){

    .page-wrapper{
        padding:12px;  /* padding moved here instead of body */
    }

    .page-title{
        font-size:20px;
    }

    table{
        font-size:13px;
    }

    th, td{
        padding:10px 8px;
    }

    .search-box{
        flex-direction:column;
    }

    .search-btn{
        width:100%;
    }

    .action-cell{
        flex-direction:column;
        align-items:stretch;
    }

    .action-btn{
        width:100%;
    }
}

</style>

<h1 class="page-title">
    <i class="fa-solid fa-users"></i> All Customers
</h1>

<!-- Search -->
<form method="GET" class="search-box">
    <input name="search"
           value="<?= htmlspecialchars($search) ?>"
           placeholder="Search name / phone / aadhaar"
           class="search-input">

    <button class="search-btn">
        <i class="fa-solid fa-magnifying-glass"></i> Search
    </button>
</form>

<!-- Table -->
<div class="table-card">

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Aadhaar</th>
    <th>Status</th>
    <th>Created</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php if (!$customers): ?>
<tr>
    <td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">
        No customers found
    </td>
</tr>
<?php endif; ?>

<?php foreach ($customers as $c): ?>

<tr>

    <td><?= $c['id'] ?></td>

    <td style="font-weight:600;">
        <?= htmlspecialchars($c['name']) ?>
    </td>

    <td><?= htmlspecialchars($c['phone']) ?></td>

    <td><?= htmlspecialchars($c['aadhaar']) ?></td>

    <td>
        <?php if ($c['is_redeemed']): ?>
            <span class="badge badge-success">Redeemed</span>
        <?php else: ?>
            <span class="badge badge-warning">Not Used</span>
        <?php endif; ?>
    </td>

    <td>
        <?= date('d M Y', strtotime($c['created_at'])) ?>
    </td>

    <td class="action-cell">

        <a href="qr_view.php?id=<?= $c['id'] ?>"
           class="action-btn btn-view">
           View QR
        </a>

        <!-- <button onclick="shareQR('<?= $c['qr_image'] ?>')"
           class="action-btn btn-share">
           Share
        </button> -->

    </td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>

<script>
function shareQR(fileName) {

    const url = window.location.origin + "/beer_redemption/qr_codes/" + fileName;

    if (navigator.share) {
        navigator.share({
            title: 'Customer QR',
            text: 'Show this QR at event',
            url: url
        });
    } else {
        prompt("Copy QR link:", url);
    }
}
</script>

<?php include '../includes/footer.php'; ?>