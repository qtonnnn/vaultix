<?php
include 'config/koneksi.php';
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

// Cek apakah session admin sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_admin = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pengaturan['nama_toko'] ?> - <?= $pengaturan['slogan'] ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: #1e293b !important;
            letter-spacing: -0.5px;
        }
        .nav-link {
            font-weight: 600;
            color: #475569 !important;
            padding: 8px 16px !important;
            transition: 0.3s;
        }
        .nav-link:hover {
            color: #4f46e5 !important;
        }
        .admin-badge {
            background: #e0e7ff;
            color: #4338ca;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
        }
        main {
            min-height: 80vh;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    <i class="bi bi-controller fs-3 me-2 text-primary"></i>
                    <?= $pengaturan['nama_toko'] ?>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="katalog.php">Katalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cara-order.php">Cara Order</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                        <?php if ($is_admin): ?>
                            <div class="dropdown">
                                <a class="btn admin-badge dropdown-toggle border-0" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> Admin (<?= $_SESSION['username'] ?>)
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li><a class="dropdown-item fw-bold text-primary" href="admin/index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="admin/list-game.php"><i class="bi bi-controller me-2"></i>Kelola Game</a></li>
                                    <li><a class="dropdown-item" href="admin/akun.php"><i class="bi bi-grid-fill me-2"></i>Kelola Akun</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="admin/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="admin/login.php" class="btn btn-outline-primary btn-sm px-4 rounded-pill fw-bold">
                                Login Admin
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="mt-4">