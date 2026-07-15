<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Rate limiting — max 5 percobaan per 15 menit per IP
define('LOGIN_ATTEMPTS_FILE', __DIR__ . '/../config/login_attempts.json');
define('MAX_ATTEMPTS', 5);
define('LOCKOUT_MINUTES', 15);

function checkRateLimit() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $now = time();
    $data = [];
    if (file_exists(LOGIN_ATTEMPTS_FILE)) {
        $data = json_decode(file_get_contents(LOGIN_ATTEMPTS_FILE), true) ?: [];
    }
    // Bersihkan data expired
    $data = array_filter($data, function($entry) use ($now) {
        return ($now - $entry['time']) < LOCKOUT_MINUTES * 60;
    });
    // Hitung percobaan IP ini
    $count = 0;
    foreach ($data as $entry) {
        if ($entry['ip'] === $ip) $count++;
    }
    if ($count >= MAX_ATTEMPTS) {
        // Cari waktu percobaan tertua dari IP ini
        $oldest = $now;
        foreach ($data as $entry) {
            if ($entry['ip'] === $ip && $entry['time'] < $oldest) {
                $oldest = $entry['time'];
            }
        }
        $wait = LOCKOUT_MINUTES - floor(($now - $oldest) / 60);
        if ($wait < 0) $wait = 0;
        if ($wait === 0) return null; // sudah lewat waktu lockout, reset
        return 'Terlalu banyak percobaan login. Coba lagi dalam ' . $wait . ' menit.';
    }
    return null;
}

function recordAttempt() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $data = [];
    if (file_exists(LOGIN_ATTEMPTS_FILE)) {
        $data = json_decode(file_get_contents(LOGIN_ATTEMPTS_FILE), true) ?: [];
    }
    $data[] = ['ip' => $ip, 'time' => time()];
    // Simpan maks 100 entry terbaru
    if (count($data) > 100) {
        $data = array_slice($data, -100);
    }
    file_put_contents(LOGIN_ATTEMPTS_FILE, json_encode($data), LOCK_EX);
}

function clearAttempts() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $data = [];
    if (file_exists(LOGIN_ATTEMPTS_FILE)) {
        $data = json_decode(file_get_contents(LOGIN_ATTEMPTS_FILE), true) ?: [];
    }
    $data = array_filter($data, function($entry) use ($ip) {
        return $entry['ip'] !== $ip;
    });
    file_put_contents(LOGIN_ATTEMPTS_FILE, json_encode($data), LOCK_EX);
}

// Ambil pengaturan
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

if (isset($_POST['login'])) {
    // Cek rate limit
    $rateError = checkRateLimit();
    if ($rateError) {
        $error = $rateError;
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $admin = query_prepare("SELECT * FROM admin WHERE username = ? AND password = ?", [$username, $password]);
        
        if (!empty($admin)) {
            clearAttempts();
            $_SESSION['admin'] = $admin[0]['id_admin'];
            $_SESSION['username'] = $admin[0]['username'];
            header('Location: index.php');
            exit;
        } else {
            recordAttempt();
            $error = "Username atau password salah!";
        }
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