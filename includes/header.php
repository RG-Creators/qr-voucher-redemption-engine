<?php
require_once __DIR__ . '/../auth/auth_check.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{
            font-family:'Poppins',sans-serif;
            background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
        }

        .sidebar-link{
            display:flex;
            align-items:center;
            gap:10px;
            padding:10px 14px;
            border-radius:10px;
            color:#e2e8f0;
            transition:0.3s;
        }

        .sidebar-link:hover{
            background:rgba(255,255,255,0.08);
            color:#fff;
        }

        .sidebar{
            background:rgba(255,255,255,0.06);
            backdrop-filter:blur(12px);
            border-right:1px solid rgba(255,255,255,0.1);
        }

        .topbar{
            background:linear-gradient(135deg,#007cf0,#00d4ff);
            box-shadow:0 4px 20px rgba(0,0,0,0.3);
        }

        .logout-btn{
            background:#ef4444;
            padding:6px 12px;
            border-radius:8px;
            transition:0.3s;
        }

        .logout-btn:hover{
            background:#dc2626;
        }
    </style>
</head>

<body class="text-white">

<!-- Top Navbar -->
<nav class="topbar px-6 py-3 flex justify-between items-center">

    <div class="font-semibold text-lg flex items-center gap-2">
        <i class="fa-solid fa-qrcode"></i>
        QR Redemption Admin
    </div>

    <div class="flex items-center gap-4">

        <div class="hidden md:block">
            <i class="fa-solid fa-user-circle"></i>
            <?= htmlspecialchars($_SESSION['admin_username']) ?>
        </div>

        <a href="../auth/logout.php" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>

    </div>

</nav>

<div class="flex">

<!-- Sidebar -->
<!-- Sidebar -->
<aside class="w-64 min-h-screen bg-white shadow-xl p-6 hidden md:block">

    <nav class="space-y-4">

        <!-- Dashboard -->
        <a href="dashboard.php"
           class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition group">

            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa-solid fa-chart-pie"></i>
            </div>

            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Add Customer -->
        <a href="add_customer.php"
           class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition group">

            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-purple-100 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                <i class="fa-solid fa-user-plus"></i>
            </div>

            <span class="font-medium">Add Customer</span>
        </a>

        <!-- Scan QR -->
        <a href="scan.php"
           class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition group">

            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-100 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition">
                <i class="fa-solid fa-camera"></i>
            </div>

            <span class="font-medium">Scan & Redeem</span>
        </a>

        <!-- Customers -->
        <a href="customers.php"
           class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">

            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                <i class="fa-solid fa-address-book"></i>
            </div>

            <span class="font-medium">Customer Directory</span>
        </a>

    </nav>

</aside>

<!-- Main Content -->
<main class="flex-1 p-8">