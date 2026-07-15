<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil pengaturan untuk brand
$pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];

// Logika Backend (Simpan, Hapus, Lunas) tetap sama seperti kode Anda
if (isset($_POST['simpan'])) {
    $id_akun = $_POST['id_akun'];
    $nama_pembeli = mysqli_real_escape_string($koneksi, $_POST['nama_pembeli']);
    $no_wa_pembeli = mysqli_real_escape_string($koneksi, $_POST['no_wa_pembeli']);
    $status_bayar = $_POST['status_bayar'];
    
    $sql = "INSERT INTO pembelian (id_akun, nama_pembeli, no_wa_pembeli, tanggal_beli, status_bayar) 
            VALUES ('$id_akun', '$nama_pembeli', '$no_wa_pembeli', NOW(), '$status_bayar')";
    execute($sql);
    
    if ($status_bayar == 'sudah') {
        execute("UPDATE akun SET status = 'terjual' WHERE id_akun = '$id_akun'");
    }
    echo "<script>alert('Transaksi berhasil dicatat!'); location.href='pembelian.php';</script>";
}

// ... (Proses Hapus dan Lunas tetap menggunakan logika Anda) ...

$pembelian = query("SELECT p.*, a.nama_akun, a.harga, g.nama_game 
                   FROM pembelian p
                   JOIN akun a ON p.id_akun = a.id_akun
                   JOIN game g ON a.id_game = g.id_game
                   ORDER BY p.tanggal_beli DESC");

$akun_tersedia = query("SELECT a.id_akun, a.nama_akun, a.harga, g.nama_game 
                        FROM akun a 
                        JOIN game g ON a.id_game = g.id_game 
                        WHERE a.status = 'tersedia' 
                        ORDER BY g.nama_game, a.nama_akun");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - <?= $pengaturan['nama_toko'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .navbar-admin { background: #1e293b; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .table thead th { background: #f1f5f9; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b; border: none; }
        .status-badge { font-size: 0.75rem; padding: 4px 12px; border-radius: 50px; font-weight: 600; }
        .bg-pending { background-color: #fef3c7; color: #92400e; }
        .bg-success-subtle { background-color: #dcfce7; color: #166534; }
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
                <li class="nav-item"><a class="nav-link nav-link-admin" href="akun.php">Kelola Akun</a></li>
                <li class="nav-item"><a class="nav-link nav-link-admin active" href="pembelian.php">Transaksi</a></li>
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
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Input Transaksi</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Akun</label>
                            <select name="id_akun" class="form-select border-0 bg-light" required>
                                <option value="">-- Pilih Akun --</option>
                                <?php foreach ($akun_tersedia as $akun): ?>
                                    <option value="<?= $akun['id_akun'] ?>">
                                        <?= $akun['nama_game'] ?> - <?= $akun['nama_akun'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Pembeli</label>
                            <input type="text" name="nama_pembeli" class="form-control border-0 bg-light" placeholder="Nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">No. WhatsApp</label>
                            <input type="text" name="no_wa_pembeli" class="form-control border-0 bg-light" placeholder="08xxx" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Status Pembayaran</label>
                            <select name="status_bayar" class="form-select border-0 bg-light" required>
                                <option value="belum">Belum Bayar</option>
                                <option value="sudah">Sudah Bayar</option>
                            </select>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary w-100 rounded-pill fw-bold py-2">
                            <i class="bi bi-save me-2"></i>Catat Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0 text-dark">Riwayat Penjualan</h5>
                        <span class="badge bg-light text-dark border fw-normal"><?= count($pembelian) ?> Transaksi</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Info Akun</th>
                                    <th>Pembeli</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pembelian)): ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi keluar.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($pembelian as $p): ?>
                                    <tr>
                                        <td class="ps-4 small text-muted">
                                            <?= date('d/m/y', strtotime($p['tanggal_beli'])) ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold small text-dark"><?= $p['nama_akun'] ?></div>
                                            <div class="text-muted small" style="font-size: 0.7rem;"><?= $p['nama_game'] ?></div>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold"><?= $p['nama_pembeli'] ?></div>
<a href="https://wa.me/<?= formatWA($p['no_wa_pembeli']) ?>" target="_blank" class="text-success text-decoration-none small" style="font-size: 0.75rem;">
                                                <i class="bi bi-whatsapp"></i> Chat
                                            </a>
                                        </td>
                                        <td class="small fw-bold text-dark"><?= rupiah($p['harga']) ?></td>
                                        <td>
                                            <?php if ($p['status_bayar'] == 'sudah'): ?>
                                                <span class="status-badge bg-success-subtle">LUNAS</span>
                                            <?php else: ?>
                                                <span class="status-badge bg-pending">PENDING</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($p['status_bayar'] != 'sudah'): ?>
                                                    <a href="?lunas=<?= $p['id_pembelian'] ?>" class="btn btn-outline-success border-0" onclick="return confirm('Konfirmasi lunas?')">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="?hapus=<?= $p['id_pembelian'] ?>" class="btn btn-outline-danger border-0" onclick="return confirm('Hapus data?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>