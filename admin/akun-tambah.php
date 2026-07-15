<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$game = query("SELECT * FROM game ORDER BY nama_game");
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

if (isset($_POST['simpan'])) {
    $id_game = (int)$_POST['id_game'];
    $nama_akun = mysqli_real_escape_string($koneksi, $_POST['nama_akun']);
    $spesifikasi = mysqli_real_escape_string($koneksi, $_POST['spesifikasi']);
    $harga = (int)$_POST['harga'];
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    // Upload foto utama
    $foto = '';
    if ($_FILES['foto']['name'] != '') {
        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);
    }
    
    $sql = "INSERT INTO akun (id_game, nama_akun, spesifikasi, harga, foto, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    if (execute_prepare($sql, [$id_game, $nama_akun, $spesifikasi, $harga, $foto, $status])) {
        $id_akun = mysqli_insert_id($koneksi);
        
        // Upload foto galeri
        if (!empty($_FILES['galeri']['name'][0])) {
            foreach ($_FILES['galeri']['name'] as $key => $nama_file) {
                if ($_FILES['galeri']['name'][$key] != '') {
                    $nama_galeri = time() . '_' . $key . '_' . $_FILES['galeri']['name'][$key];
                    move_uploaded_file($_FILES['galeri']['tmp_name'][$key], '../uploads/' . $nama_galeri);
                    execute_prepare("INSERT INTO galeri (id_akun, foto) VALUES (?, ?)", [$id_akun, $nama_galeri]);
                }
            }
        }
        echo "<script>alert('Akun berhasil ditambahkan!'); window.location='akun.php';</script>";
        exit;
    } else {
        $error = "Gagal menambah akun!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; }
        .form-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .section-title { font-size: 0.85rem; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: block; border-bottom: 2px solid #eef2ff; padding-bottom: 5px; }
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

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold m-0">Tambah Akun</h3>
                    <p class="text-muted small">Lengkapi detail akun untuk dipajang di katalog.</p>
                </div>
                <a href="akun.php" class="btn btn-light border rounded-pill btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger shadow-sm"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="card form-card mb-4">
                    <div class="card-body p-4">
                        <span class="section-title">Informasi Dasar</span>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Pilih Game</label>
                                <select name="id_game" class="form-select" required>
                                    <option value="">- Pilih Game -</option>
                                    <?php foreach ($game as $g): ?>
                                        <option value="<?= $g['id_game'] ?>"><?= $g['nama_game'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Harga (IDR)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="harga" class="form-control" placeholder="Contoh: 150000" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Judul / Nama Akun</label>
                                <input type="text" name="nama_akun" class="form-control" placeholder="Contoh: Akun ML Mythic Glory Skin 200+" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Spesifikasi Lengkap</label>
                                <textarea name="spesifikasi" rows="5" class="form-control" placeholder="Jelaskan detail skin, rank, hero, dll secara detail..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card form-card mb-4">
                    <div class="card-body p-4">
                        <span class="section-title">Media & Status</span>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Foto Utama (Thumbnail)</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Foto Galeri (Bisa pilih banyak)</label>
                                <input type="file" name="galeri[]" class="form-control" multiple accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status Ketersediaan</label>
                                <select name="status" class="form-select">
                                    <option value="tersedia">Tersedia</option>
                                    <option value="terjual">Terjual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card form-card">
                    <div class="card-body p-3 d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light rounded-pill px-4">Reset</button>
                        <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-5 fw-bold shadow">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Simpan Ke Katalog
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>