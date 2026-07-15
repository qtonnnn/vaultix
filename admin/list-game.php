<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil data game
$game = query("SELECT * FROM game ORDER BY id_game DESC");

// Ambil pengaturan untuk brand
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Game - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; color: white; }
        .card-table { border: none; border-radius: 15px; overflow: hidden; }
        .table thead { background-color: #f8fafc; }
        .table th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b; }
        .nav-link-admin { color: rgba(255,255,255,0.7); font-weight: 500; padding: 10px 15px !important; }
        .nav-link-admin:hover, .nav-link-admin.active { color: white; background: rgba(255,255,255,0.1); border-radius: 8px; }
        .badge-count { font-size: 0.85rem; padding: 5px 12px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-admin navbar-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="index.php">
            <i class="bi bi-speedometer2 me-2"></i> ADMIN PANEL
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link nav-link-admin" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin active" href="list-game.php">Kelola Game</a></li>
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
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Admin</a></li>
                    <li class="breadcrumb-item active">Daftar Game</li>
                </ol>
            </nav>
            <h3 class="fw-bold m-0 text-dark">Manajemen Game</h3>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="tambah-game.php" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Game Baru
            </a>
        </div>
    </div>

    <div class="card card-table shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($game)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-controller display-1 text-light"></i>
                    <p class="text-muted mt-3">Belum ada game yang terdaftar.</p>
                    <a href="tambah-game.php" class="btn btn-outline-primary btn-sm">Mulai Tambah Game</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 text-center" width="80">ID</th>
                                <th>Nama Game</th>
                                <th class="text-center">Stok Akun</th>
                                <th class="text-end pe-4" width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($game as $g): ?>
                            <?php 
                                $id_g = $g['id_game'];
                                $jumlah_akun = query("SELECT COUNT(*) as total FROM akun WHERE id_game = $id_g")[0]['total'];
                            ?>
                            <tr>
                                <td class="ps-4 text-center text-muted fw-bold small"><?= $g['id_game'] ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= $g['nama_game'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border badge-count">
                                        <i class="bi bi-database me-1 text-primary"></i> <?= $jumlah_akun ?> Akun
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm shadow-sm" role="group">
                                        <a href="edit.game.php?id=<?= $g['id_game'] ?>" class="btn btn-white border" title="Edit">
                                            <i class="bi bi-pencil-square text-warning"></i> Edit
                                        </a>
                                        <a href="hapus-game.php?id=<?= $g['id_game'] ?>" 
                                           class="btn btn-white border" 
                                           onclick="return confirm('PERINGATAN! Menghapus game ini akan menghapus semua akun yang terkait. Lanjutkan?')"
                                           title="Hapus">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-4 text-center text-muted small">
        <i class="bi bi-info-circle me-1"></i> Tips: Pastikan stok akun selalu terupdate agar pelanggan tidak kecewa.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>