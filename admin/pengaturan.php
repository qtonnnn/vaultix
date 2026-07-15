<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

if (isset($_POST['simpan'])) {
    $nama_toko = mysqli_real_escape_string($koneksi, $_POST['nama_toko']);
    $slogan = mysqli_real_escape_string($koneksi, $_POST['slogan']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    $sql = "UPDATE pengaturan SET 
            nama_toko = '$nama_toko',
            slogan = '$slogan',
            no_wa = '$no_wa',
            email = '$email'
            WHERE id_pengaturan = 1";
    
    if (execute($sql)) {
        $success = "Pengaturan berhasil diperbarui!";
        $pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];
    } else {
        $error = "Gagal menyimpan pengaturan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .navbar-admin { background: #1e293b; }
        .settings-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-group-text { border: none; background-color: #f8fafc; color: #64748b; }
        .form-control { border: 1px solid #e2e8f0; padding: 12px; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); border-color: #4f46e5; }
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
                <li class="nav-item"><a class="nav-link nav-link-admin" href="list-game.php">Kelola Game</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin" href="akun.php">Kelola Akun</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin" href="pembelian.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin active" href="pengaturan.php">Pengaturan</a></li>
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
        <div class="col-lg-7">
            
            <div class="mb-4">
                <h3 class="fw-bold m-0 text-dark">Pengaturan Umum</h3>
                <p class="text-muted">Kelola identitas toko dan kontak bantuan pelanggan.</p>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= $success ?>
                </div>
            <?php endif; ?>

            <div class="card settings-card">
                <div class="card-body p-4 p-md-5">
                    <form method="POST">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label">Nama Toko</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shop"></i></span>
                                    <input type="text" name="nama_toko" class="form-control" value="<?= $pengaturan['nama_toko'] ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Slogan Website</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-megaphone"></i></span>
                                    <input type="text" name="slogan" class="form-control" value="<?= $pengaturan['slogan'] ?>" placeholder="Contoh: Jual Akun Game Terpercaya" required>
                                </div>
                                <div class="form-text small">Slogan akan muncul di bawah judul pada halaman utama.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="no_wa" class="form-control" value="<?= $pengaturan['no_wa'] ?>" placeholder="628123456789" required>
                                </div>
                                <div class="form-text small">Gunakan format 62 (Tanpa +).</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Admin</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="<?= $pengaturan['email'] ?>" required>
                                </div>
                            </div>

                            <div class="col-12 pt-3">
                                <hr class="my-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small italic">Pastikan data sudah benar sebelum menyimpan.</span>
                                    <button type="submit" name="simpan" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 border-0 bg-primary-subtle shadow-sm">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="fs-1 me-3 text-primary"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Keamanan Data</h6>
                        <p class="mb-0 small text-primary-emphasis">Hanya admin terotorisasi yang dapat mengubah data konfigurasi ini.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>