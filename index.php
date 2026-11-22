<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RG QR Redemption</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
@keyframes float {
    0%,100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
.floating {
    animation: float 4s ease-in-out infinite;
}
</style>
</head>

<body class="bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-600 flex items-center justify-center px-6 py-10 sm:min-h-screen relative overflow-x-hidden">

<!-- Background Blur Circles -->
<div class="absolute w-96 h-96 bg-pink-400 opacity-30 rounded-full blur-3xl -top-20 -left-20"></div>
<div class="absolute w-96 h-96 bg-blue-400 opacity-30 rounded-full blur-3xl -bottom-20 -right-20"></div>

<div class="relative z-10 backdrop-blur-xl bg-white/20 border border-white/30 shadow-2xl rounded-3xl p-10 w-full max-w-md text-center text-white">

    <!-- Branding -->
    <div class="mb-8 floating">
        <h1 class="text-4xl font-extrabold tracking-wide">
            RG
        </h1>
        <p class="text-sm opacity-90 mt-2">
            Premium QR Redemption System
        </p>
    </div>

    <!-- Description -->
    <div class="mb-8 text-sm opacity-80">
        Smart. Secure. Seamless.  
        Manage and redeem event QR codes with professionalism.
    </div>

    <!-- Buttons -->
    <div class="space-y-4">

        <a href="auth/login.php"
           class="w-full block bg-white text-indigo-700 py-3 rounded-xl font-semibold shadow-lg hover:scale-105 transition transform duration-300">
            <i class="fa-solid fa-user-shield mr-2"></i>
            Admin Login
        </a>

        <a href="viewqrforuser.php"
           class="w-full block bg-indigo-500 hover:bg-indigo-400 py-3 rounded-xl font-semibold shadow-lg hover:scale-105 transition transform duration-300">
            <i class="fa-solid fa-user mr-2"></i>
            User Access
        </a>

    </div>

    <!-- Divider -->
    <div class="my-8 border-t border-white/30"></div>

    <!-- Footer Branding -->
    <div class="text-xs opacity-80">
        Designed & Developed by
        <a href="https://rgcreators.netlify.app" target="_blank"
           class="font-semibold underline hover:text-yellow-300 transition">
            RG Creators
        </a>
    </div>

</div>

</body>
</html>