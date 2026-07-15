<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Ambil pengaturan
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Catatan: Sebaiknya gunakan password_verify di masa depan untuk keamanan ekstra
    $admin = query("SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
    
    if (!empty($admin)) {
        $_SESSION['admin'] = $admin[0]['id_admin'];
        $_SESSION['username'] = $admin[0]['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.2);
            border-color: #764ba2;
        }
        .btn-login {
            background: #764ba2;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
            color: white;
        }
        .btn-login:hover {
            background: #5a397a;
            transform: translateY(-2px);
        }
        .brand-icon {
            font-size: 3rem;
            color: #764ba2;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="login-card text-center">
            <div class="brand-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h2>Admin Panel</h2>
            <p class="text-muted mb-4 small"><?= $pengaturan['nama_toko'] ?></p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger d-flex align-items-center small py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="text-start">
                    <label class="form-label small fw-bold text-secondary">Username</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0 rounded-start-10"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control mb-0 border-start-0" placeholder="Masukkan username" required>
                    </div>

                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-light border-end-0 rounded-start-10"><i class="bi bi-key"></i></span>
                        <input type="password" name="password" class="form-control mb-0 border-start-0" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-login shadow-sm">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-4">
                <a href="../index.php" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Toko
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>