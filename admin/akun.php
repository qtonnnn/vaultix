<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$akun = query("SELECT a.*, g.nama_game 
              FROM akun a 
              JOIN game g ON a.id_game = g.id_game 
              ORDER BY a.id_akun DESC");

$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; color: white; }
        .card-table { border: none; border-radius: 15px; overflow: hidden; }
        .table thead { background-color: #f8fafc; }
        .table th { font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; color: #64748b; padding: 15px; }
        .thumb-preview { width: 80px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px; }
        .nav-link-admin { color: rgba(255,255,255,0.7); font-weight: 500; padding: 10px 15px !important; }
        .nav-link-admin:hover, .nav-link-admin.active { color: white; background: rgba(255,255,255,0.1); border-radius: 8px; }
        .btn-action { padding: 5px 10px; font-size: 0.85rem; }
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
                <li class="nav-item"><a class="nav-link nav-link-admin" href="list-game.php">Kelola Game</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin active" href="akun.php">Kelola Akun</a></li>
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

<div class="container-fluid px-4 pb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold m-0"><i class="bi bi-person-badge me-2 text-primary"></i>Manajemen Akun</h3>
            <p class="text-muted small mb-0">Total: <?= count($akun) ?> akun terdaftar di sistem.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="akun-tambah.php" class="btn btn-primary rounded-pill shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Akun Baru
            </a>
        </div>
    </div>

    <div class="card card-table shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($akun)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-light"></i>
                    <p class="text-muted mt-3">Belum ada akun yang dijual.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Foto</th>
                                <th>Game & Nama Akun</th>
                                <th>Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($akun as $a): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if ($a['foto']): ?>
                                        <img src="../uploads/<?= $a['foto'] ?>" class="thumb-preview shadow-sm">
                                    <?php else: ?>
                                        <div class="thumb-preview bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-0"><?= $a['nama_akun'] ?></div>
                                    <div class="text-primary small fw-semibold" style="font-size: 0.75rem;">
                                        <i class="bi bi-controller me-1"></i><?= $a['nama_game'] ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-secondary"><?= rupiah($a['harga']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($a['status'] == 'tersedia'): ?>
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                            <span class="status-dot bg-success"></span>Tersedia
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                            <span class="status-dot bg-danger"></span>Terjual
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="akun-edit.php?id=<?= $a['id_akun'] ?>" class="btn btn-white btn-action border" title="Edit">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </a>
                                        <a href="../detail.php?id=<?= $a['id_akun'] ?>" target="_blank" class="btn btn-white btn-action border" title="Lihat di Web">
                                            <i class="bi bi-eye text-info"></i>
                                        </a>
                                        <a href="akun-hapus.php?id=<?= $a['id_akun'] ?>" 
                                           class="btn btn-white btn-action border text-danger" 
                                           onclick="return confirm('Hapus akun ini?')">
                                            <i class="bi bi-trash3"></i>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>