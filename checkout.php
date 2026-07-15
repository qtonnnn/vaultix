<?php
include 'includes/header.php';

$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : 0;
if ($id === false) $id = 0;

$akun = query_prepare("SELECT a.*, g.nama_game FROM akun a JOIN game g ON a.id_game = g.id_game WHERE a.id_akun = ?", [$id]);

if (empty($akun) || $akun[0]['status'] !== 'tersedia') {
    echo "<div class='container mt-5 text-center'><div class='alert alert-warning'>Akun tidak tersedia atau sudah terjual.</div><a href='katalog.php' class='btn btn-primary'>Kembali ke Katalog</a></div>";
    include 'includes/footer.php';
    exit;
}
$akun = $akun[0];

$error = '';
if (isset($_POST['checkout'])) {
    $nama = trim($_POST['nama_pembeli']);
    $wa = trim($_POST['no_wa']);

    if ($nama === '') $error = 'Nama wajib diisi.';
    elseif ($wa === '') $error = 'No. WhatsApp wajib diisi.';

    if (!$error) {
        // Insert pembelian
        execute_prepare("INSERT INTO pembelian (id_akun, nama_pembeli, no_wa_pembeli, tanggal_beli, status_bayar) VALUES (?, ?, ?, NOW(), 'belum')", [$id, $nama, $wa]);

        $id_pembelian = mysqli_insert_id($koneksi);
        // Update status akun
        execute_prepare("UPDATE akun SET status = 'terjual' WHERE id_akun = ?", [$id]);

        // Redirect WA ke admin
        $message = "Pembelian Baru!%0A%0A";
        $message .= "Akun : " . $akun['nama_akun'] . " (" . $akun['nama_game'] . ")%0A";
        $message .= "Harga: " . rupiah($akun['harga']) . "%0A";
        $message .= "Pembeli: " . $nama . "%0A";
        $message .= "WA: " . $wa . "%0A%0A";
        $message .= "ID Transaksi: #" . $id_pembelian;

        $wa_admin_link = "https://wa.me/" . formatWA($pengaturan['no_wa']) . "?text=" . $message;

        header("Location: sukses.php?id=" . $id_pembelian . "&wa=" . urlencode($wa_admin_link));
        exit;
    }
}
?>
<style>
    .checkout-container { max-width: 600px; margin: 0 auto; }
    .card-checkout { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .product-summary { background: #f8fafc; border-radius: 12px; padding: 15px; }
    .product-summary .game-badge { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; background: #e2e8f0; color: #475569; padding: 3px 10px; border-radius: 6px; display: inline-block; }
</style>
<div class="container py-5 checkout-container">
    <div class="card card-checkout p-4 p-md-5">
        <h4 class="fw-bold mb-4"><i class="bi bi-cart-check me-2 text-primary"></i>Checkout</h4>

        <div class="product-summary mb-4 d-flex justify-content-between align-items-center">
            <div>
                <span class="game-badge fw-bold"><?= $akun['nama_game'] ?></span>
                <h5 class="fw-bold mt-2 mb-1"><?= $akun['nama_akun'] ?></h5>
                <span class="text-muted small"><?= $akun['spesifikasi'] ?></span>
            </div>
            <div class="text-end">
                <small class="text-muted">Harga</small>
                <div class="fw-bold text-primary fs-5"><?= rupiah($akun['harga']) ?></div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="nama_pembeli" class="form-control bg-light border-0" placeholder="Masukkan nama kamu" required value="<?= isset($_POST['nama_pembeli']) ? htmlspecialchars($_POST['nama_pembeli']) : '' ?>">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">No. WhatsApp Aktif</label>
                <input type="text" name="no_wa" class="form-control bg-light border-0" placeholder="08xxxxxxxxxx" required value="<?= isset($_POST['no_wa']) ? htmlspecialchars($_POST['no_wa']) : '' ?>">
                <div class="form-text">Nomor ini akan digunakan admin untuk mengirim detail akun.</div>
            </div>
            <button type="submit" name="checkout" class="btn btn-success w-100 py-3 fw-bold rounded-3">
                <i class="bi bi-whatsapp me-2"></i> Konfirmasi & Beli
            </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            <i class="bi bi-shield-check me-1"></i> Transaksi aman. Admin akan menghubungi kamu via WhatsApp setelah pembelian.
        </p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>