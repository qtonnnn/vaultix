<?php
include 'includes/header.php';

$id_pembelian = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : 0;
if ($id_pembelian === false) $id_pembelian = 0;

$pembelian = query_prepare("SELECT p.*, a.nama_akun, a.harga, g.nama_game FROM pembelian p JOIN akun a ON p.id_akun = a.id_akun JOIN game g ON a.id_game = g.id_game WHERE p.id_pembelian = ?", [$id_pembelian]);

$wa_admin_link = isset($_GET['wa']) ? $_GET['wa'] : '';

if (empty($pembelian)) {
    echo "<div class='container mt-5 text-center'><div class='alert alert-danger'>Transaksi tidak ditemukan.</div><a href='index.php' class='btn btn-primary'>Kembali</a></div>";
    include 'includes/footer.php';
    exit;
}
$p = $pembelian[0];
?>
<style>
    .success-container { max-width: 550px; margin: 0 auto; }
    .card-success { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; }
    .icon-success { font-size: 4rem; color: #22c55e; }
    .detail-line { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
    .detail-line:last-child { border-bottom: none; }
</style>
<div class="container py-5 success-container">
    <div class="card card-success p-4 p-md-5">
        <div class="icon-success mb-3"><i class="bi bi-check-circle-fill"></i></div>
        <h4 class="fw-bold mb-1">Pesanan Berhasil!</h4>
        <p class="text-muted small mb-4">Terima kasih, <?= htmlspecialchars($p['nama_pembeli']) ?>.</p>

        <div class="text-start bg-light rounded-3 p-3 mb-4">
            <div class="detail-line"><span class="text-muted">ID Transaksi</span><span class="fw-bold">#<?= $p['id_pembelian'] ?></span></div>
            <div class="detail-line"><span class="text-muted">Game</span><span><?= $p['nama_game'] ?></span></div>
            <div class="detail-line"><span class="text-muted">Akun</span><span><?= $p['nama_akun'] ?></span></div>
            <div class="detail-line"><span class="text-muted">Total</span><span class="fw-bold text-primary"><?= rupiah($p['harga']) ?></span></div>
            <div class="detail-line"><span class="text-muted">Status</span><span class="badge bg-warning text-dark">Menunggu Pembayaran</span></div>
        </div>

        <p class="small text-muted mb-3">Admin akan menghubungi kamu di <strong><?= htmlspecialchars($p['no_wa_pembeli']) ?></strong> untuk konfirmasi dan pengiriman akun.</p>

        <?php if ($wa_admin_link): ?>
            <a href="<?= htmlspecialchars($wa_admin_link, ENT_QUOTES) ?>" class="btn btn-success w-100 py-3 fw-bold rounded-3" target="_blank">
                <i class="bi bi-whatsapp me-2"></i> Chat Admin Sekarang
            </a>
        <?php endif; ?>

        <a href="katalog.php" class="btn btn-outline-secondary w-100 mt-2 rounded-3 py-2">
            <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
        </a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>