<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil pengaturan
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

// Ambil statistik
$total_akun = query("SELECT COUNT(*) as total FROM akun")[0]['total'];
$tersedia = query("SELECT COUNT(*) as total FROM akun WHERE status = 'tersedia'")[0]['total'];
$terjual = query("SELECT COUNT(*) as total FROM akun WHERE status = 'terjual'")[0]['total'];
$total_game = query("SELECT COUNT(*) as total FROM game")[0]['total'];
// Asumsi tabel pembelian ada, jika tidak, hapus bagian ini
$total_pembelian = query("SELECT COUNT(*) as total FROM pembelian")[0]['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; color: white; }
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-shape {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .nav-link-admin {
            color: rgba(255,255,255,0.7);
            font-weight: 500;
            padding: 10px 15px !important;
        }
        .nav-link-admin:hover, .nav-link-admin.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .quick-action-btn {
            border-radius: 12px;
            padding: 15px;
            text-align: left;
            transition: 0.3s;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-admin mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="index.php">
            <i class="bi bi-speedometer2 me-2"></i> ADMIN PANEL
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link nav-link-admin active" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin" href="list-game.php">Kelola Game</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin" href="akun.php">Kelola Akun</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin" href="pembelian.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin" href="pengaturan.php">Pengaturan</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="text-white-50 me-3 small">Halo, <?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Ringkasan Toko</h3>
            <p class="text-muted small">Update terakhir: <?= date('d M Y, H:i') ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="../index.php" target="_blank" class="btn btn-light shadow-sm border rounded-pill">
                <i class="bi bi-globe me-1"></i> Lihat Website
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <p class="mb-0 opacity-75 small fw-bold text-uppercase">Total Akun</p>
                        <h3 class="mb-0 fw-bold"><?= $total_akun ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <p class="mb-0 opacity-75 small fw-bold text-uppercase">Tersedia</p>
                        <h3 class="mb-0 fw-bold"><?= $tersedia ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3 bg-dark-subtle"><i class="bi bi-cart-dash"></i></div>
                    <div>
                        <p class="mb-0 opacity-75 small fw-bold text-uppercase text-dark">Terjual</p>
                        <h3 class="mb-0 fw-bold"><?= $terjual ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3"><i class="bi bi-controller"></i></div>
                    <div>
                        <p class="mb-0 opacity-75 small fw-bold text-uppercase text-white">Total Game</p>
                        <h3 class="mb-0 fw-bold"><?= $total_game ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Aksi Cepat</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="akun-tambah.php" class="btn btn-white border quick-action-btn w-100 shadow-sm d-flex align-items-center">
                <div class="bg-primary-subtle text-primary p-3 rounded-3 me-3"><i class="bi bi-plus-lg fs-4"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold">Tambah Akun</h6>
                    <small class="text-muted">Input data akun baru ke katalog</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="list-game.php" class="btn btn-white border quick-action-btn w-100 shadow-sm d-flex align-items-center">
                <div class="bg-info-subtle text-info p-3 rounded-3 me-3"><i class="bi bi-controller fs-4"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold">Kelola Game</h6>
                    <small class="text-muted">Tambah atau hapus list game</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="pengaturan.php" class="btn btn-white border quick-action-btn w-100 shadow-sm d-flex align-items-center">
                <div class="bg-danger-subtle text-danger p-3 rounded-3 me-3"><i class="bi bi-gear fs-4"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold">Pengaturan</h6>
                    <small class="text-muted">Ubah nama toko & nomor WhatsApp</small>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>