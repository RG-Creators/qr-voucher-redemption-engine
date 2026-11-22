<?php
session_start();

define('APP_RUNNING', true);
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = "All fields required";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?")
                ->execute([$admin['id']]);

            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            $error = "Invalid login credentials";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<!-- Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    margin:0;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
}

/* CARD */
.login-card{
    width:100%;
    max-width:400px;
    padding:40px 32px;
    border-radius:18px;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.12);
    box-shadow:0 25px 60px rgba(0,0,0,0.5);
}

/* TITLE */
.title{
    text-align:center;
    color:#fff;
    font-size:26px;
    font-weight:600;
    margin-bottom:30px;
    letter-spacing:.5px;
}

/* INPUT GROUP */
.input-group{
    position:relative;
    margin-bottom:18px;
}

.input-field{
    width:100%;
    padding:13px 45px;
    border-radius:10px;
    border:1px solid rgba(255,255,255,0.15);
    background:rgba(255,255,255,0.06);
    color:white;
    font-size:14px;
    outline:none;
    transition:.3s;
}

.input-field::placeholder{
    color:rgba(255,255,255,0.6);
}

.input-field:focus{
    border-color:#00d4ff;
    box-shadow:0 0 0 2px rgba(0,212,255,0.25);
}

/* LEFT ICON */
.input-icon{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:rgba(255,255,255,0.7);
}

/* PASSWORD TOGGLE */
.toggle-password{
    position:absolute;
    right:14px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:rgba(255,255,255,0.7);
    transition:.2s;
}
.toggle-password:hover{
    color:#00d4ff;
}

/* BUTTON */
.login-btn{
    width:100%;
    padding:13px;
    border-radius:10px;
    border:none;
    font-weight:500;
    font-size:15px;
    cursor:pointer;
    color:white;
    background:linear-gradient(135deg,#00d4ff,#007cf0);
    transition:.3s;
}

.login-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 25px rgba(0,212,255,0.45);
}

/* ERROR */
.error-box{
    background:rgba(255,80,80,0.15);
    border:1px solid rgba(255,80,80,0.4);
    color:#ffdede;
    padding:10px;
    border-radius:8px;
    font-size:13px;
    text-align:center;
    margin-bottom:15px;
}

/* MOBILE */
@media(max-width:420px){
    .login-card{
        margin:20px;
        padding:30px 22px;
    }
}
</style>
</head>

<body>

<form method="POST" class="login-card">

<div class="title">
<i class="fa-solid fa-shield-halved"></i> Admin Login
</div>

<?php if ($error): ?>
<div class="error-box">
<?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="input-group">
<i class="fa fa-user input-icon"></i>
<input name="username" placeholder="Username" class="input-field" required>
</div>

<div class="input-group">
<i class="fa fa-lock input-icon"></i>
<input id="password" name="password" type="password" placeholder="Password" class="input-field" required>
<i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
</div>

<button class="login-btn">
<i class="fa fa-right-to-bracket"></i> Login
</button>

</form>

<script>
function togglePassword(){
    const pass = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if(pass.type === "password"){
        pass.type = "text";
        icon.classList.replace("fa-eye","fa-eye-slash");
    }else{
        pass.type = "password";
        icon.classList.replace("fa-eye-slash","fa-eye");
    }
}
</script>

</body>
</html>