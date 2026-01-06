<?php
require_once 'includes/auth.php';
session_start();

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$page_title = "Login";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Kasir - Login</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/img/logo.png" sizes="32x32">
</head>
<body class="hold-transition">
    <div class="d-flex align-items-center justify-content-center" style="min-height:100vh; padding:2rem; background: linear-gradient(135deg, #f3f6ff 0%, #f6fbff 100%);">
        <div class="card" style="width:380px;">
            <div class="card-header text-center" style="background: linear-gradient(90deg,#3b82f6,#2563eb); color: #fff;">
                <h3 class="mb-0"><i class="fas fa-cash-register"></i> Sistem Kasir</h3>
            </div>
            <div class="card-body">
            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>