<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$akun = query("SELECT * FROM akun WHERE id_akun = '$id'");
if (empty($akun)) {
    header('Location: akun.php');
    exit;
}
$akun = $akun[0];

$game = query("SELECT * FROM game ORDER BY nama_game");
$galeri = query("SELECT * FROM galeri WHERE id_akun = '$id'");
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

// Handle hapus foto galeri
if (isset($_GET['hapus_galeri'])) {
    $id_galeri = $_GET['hapus_galeri'];
    $foto_galeri = query("SELECT foto FROM galeri WHERE id_galeri = '$id_galeri'");
    if (!empty($foto_galeri)) {
        $nama_foto = $foto_galeri[0]['foto'];
        if (file_exists('../uploads/' . $nama_foto)) {
            unlink('../uploads/' . $nama_foto);
        }
        execute("DELETE FROM galeri WHERE id_galeri = '$id_galeri'");
    }
    echo "<script>window.location='akun-edit.php?id=$id';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $id_game = $_POST['id_game'];
    $nama_akun = mysqli_real_escape_string($koneksi, $_POST['nama_akun']);
    $spesifikasi = mysqli_real_escape_string($koneksi, $_POST['spesifikasi']);
    $harga = $_POST['harga'];
    $status = $_POST['status'];
    
    $foto = $akun['foto'];
    if ($_FILES['foto']['name'] != '') {
        if ($foto != '' && file_exists('../uploads/' . $foto)) {
            unlink('../uploads/' . $foto);
        }
        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);
    }
    
    $sql = "UPDATE akun SET 
            id_game = '$id_game',
            nama_akun = '$nama_akun',
            spesifikasi = '$spesifikasi',
            harga = '$harga',
            foto = '$foto',
            status = '$status'
            WHERE id_akun = '$id'";
    
    if (execute($sql)) {
        if (!empty($_FILES['galeri']['name'][0])) {
            foreach ($_FILES['galeri']['name'] as $key => $nama_file) {
                if ($_FILES['galeri']['name'][$key] != '') {
                    $nama_galeri = time() . '_' . $key . '_' . $_FILES['galeri']['name'][$key];
                    move_uploaded_file($_FILES['galeri']['tmp_name'][$key], '../uploads/' . $nama_galeri);
                    execute("INSERT INTO galeri (id_akun, foto) VALUES ('$id', '$nama_galeri')");
                }
            }
        }
        echo "<script>alert('Akun berhasil diperbarui!'); window.location='akun.php';</script>";
        exit;
    } else {
        $error = "Gagal mengupdate akun!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; }
        .form-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .section-title { font-size: 0.85rem; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: block; border-bottom: 2px solid #eef2ff; padding-bottom: 5px; }
        .preview-img { width: 100%; max-width: 200px; border-radius: 10px; margin-bottom: 10px; border: 2px solid #e2e8f0; }
        .gallery-item { position: relative; display: inline-block; margin-right: 10px; margin-bottom: 10px; }
        .gallery-item img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6; }
        .btn-delete-img { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: none; cursor: pointer; }
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
                    <h3 class="fw-bold m-0 text-dark">Edit Detail Akun</h3>
                    <p class="text-muted small">Update spesifikasi, harga, atau foto akun.</p>
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
                        <span class="section-title">Detail Akun</span>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Pilih Game</label>
                                <select name="id_game" class="form-select" required>
                                    <?php foreach ($game as $g): ?>
                                        <option value="<?= $g['id_game'] ?>" <?= ($g['id_game'] == $akun['id_game']) ? 'selected' : '' ?>>
                                            <?= $g['nama_game'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Harga (IDR)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="harga" class="form-control" value="<?= $akun['harga'] ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Nama / Judul Akun</label>
                                <input type="text" name="nama_akun" class="form-control" value="<?= htmlspecialchars($akun['nama_akun']) ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Spesifikasi</label>
                                <textarea name="spesifikasi" rows="5" class="form-control" required><?= htmlspecialchars($akun['spesifikasi']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card form-card mb-4">
                    <div class="card-body p-4">
                        <span class="section-title">Manajemen Gambar</span>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Foto Utama</label><br>
                            <?php if ($akun['foto']): ?>
                                <img src="../uploads/<?= $akun['foto'] ?>" class="preview-img d-block shadow-sm">
                            <?php endif; ?>
                            <input type="file" name="foto" class="form-control">
                            <div class="form-text small text-info"><i class="bi bi-info-circle me-1"></i>Pilih file baru jika ingin mengganti foto utama.</div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Galeri Foto Saat Ini</label><br>
                            <?php if (!empty($galeri)): ?>
                                <div class="mb-3">
                                    <?php foreach ($galeri as $g): ?>
                                        <div class="gallery-item">
                                            <img src="../uploads/<?= $g['foto'] ?>" alt="Gallery">
                                            <a href="?id=<?= $id ?>&hapus_galeri=<?= $g['id_galeri'] ?>" 
                                               class="btn-delete-img" 
                                               onclick="return confirm('Hapus foto dari galeri?')">
                                                <i class="bi bi-x"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small italic">Tidak ada foto galeri.</p>
                            <?php endif; ?>
                            
                            <label class="form-label fw-semibold small mt-2">Tambah Foto Galeri Baru</label>
                            <input type="file" name="galeri[]" class="form-control" multiple>
                        </div>
                    </div>
                </div>

                <div class="card form-card">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-semibold small text-uppercase">Status Akun</label>
                                <select name="status" class="form-select border-primary text-primary fw-bold">
                                    <option value="tersedia" <?= ($akun['status'] == 'tersedia') ? 'selected' : '' ?>>TERSEDIA</option>
                                    <option value="terjual" <?= ($akun['status'] == 'terjual') ? 'selected' : '' ?>>TERJUAL</option>
                                </select>
                            </div>
                            <div class="col-md-6 text-md-end d-flex gap-2 justify-content-md-end">
                                <button type="submit" name="update" class="btn btn-primary rounded-pill px-5 fw-bold shadow">
                                    <i class="bi bi-save me-2"></i> Update Akun
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>