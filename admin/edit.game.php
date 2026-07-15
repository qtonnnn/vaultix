<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil data game berdasarkan ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: list-game.php');
    exit;
}

$id_game = $_GET['id'];
$game = query("SELECT * FROM game WHERE id_game = $id_game");

if (empty($game)) {
    header('Location: list-game.php');
    exit;
}

$game = $game[0];

// Ambil pengaturan untuk brand
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

if (isset($_POST['update'])) {
    $nama_game = $_POST['nama_game'];
    
    if (empty($nama_game)) {
        $error = "Nama game tidak boleh kosong!";
    } else {
        $cek = query("SELECT * FROM game WHERE nama_game = '$nama_game' AND id_game != $id_game");
        if (!empty($cek)) {
            $error = "Game dengan nama tersebut sudah ada!";
        } else {
            $sql = "UPDATE game SET nama_game = '$nama_game' WHERE id_game = $id_game";
            if (execute($sql)) {
                echo "<script>alert('Game berhasil diupdate!'); window.location='list-game.php';</script>";
                exit;
            } else {
                $error = "Gagal mengupdate game!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; color: white; }
        .form-card { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .id-badge { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 15px; border-radius: 10px; color: #64748b; font-weight: 600; }
        .nav-link-admin { color: rgba(255,255,255,0.7); font-weight: 500; padding: 10px 15px !important; }
        .nav-link-admin:hover, .nav-link-admin.active { color: white; background: rgba(255,255,255,0.1); border-radius: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-admin navbar-dark mb-5 shadow-sm">
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="mb-4">
                <a href="list-game.php" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Game
                </a>
                <h3 class="fw-bold mt-2">Edit Nama Game</h3>
            </div>

            <div class="card form-card">
                <div class="card-body p-4 p-md-5">
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4">
                            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">ID Game</label>
                            <div class="id-badge">
                                <i class="bi bi-hash me-1"></i> <?= $game['id_game'] ?>
                                <span class="ms-2 small fw-normal opacity-50">(ID tidak dapat diubah)</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Nama Game</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-primary"><i class="bi bi-pencil-fill"></i></span>
                                <input type="text" name="nama_game" class="form-control border-start-0" 
                                       value="<?= htmlspecialchars($game['nama_game']) ?>" 
                                       required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update" class="btn btn-warning btn-lg rounded-pill fw-bold py-2 shadow-sm text-dark">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="list-game.php" class="btn btn-light rounded-pill py-2">Batal</a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>