<?php
include 'includes/header.php';

// Query data akun
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$akun = query("SELECT a.*, g.nama_game FROM akun a JOIN game g ON a.id_game = g.id_game WHERE a.id_akun = '$id'");

if (empty($akun)) {
    echo "<div class='container mt-5 text-center'><div class='alert alert-danger'>Akun tidak ditemukan.</div><a href='katalog.php' class='btn btn-primary'>Kembali ke Katalog</a></div>";
    include 'includes/footer.php';
    exit;
}

$akun = $akun[0];
$galeri = query("SELECT * FROM galeri WHERE id_akun = '$id'");

// Membuat pesan WhatsApp otomatis (versi lebih menarik)
$message = "Halo min!%0A";
$message .= "Saya tertarik membeli akun game berikut:%0A%0A";
$message .= "Game       : " . $akun['nama_game'] . "%0A";
$message .= "Nama Akun  : " . $akun['nama_akun'] . "%0A";
$message .= "Harga      : " . rupiah($akun['harga']) . "%0A%0A";
$message .= "Apakah akun ini masih tersedia? 🙏";

$wa_link = "https://wa.me/" . formatWA($pengaturan['no_wa']) . "?text=" . $message;
?>

<style>
    /* Dasar & Desktop */
    .detail-container { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .img-preview { width: 100%; object-fit: cover; background: #f1f5f9; cursor: zoom-in; }
    .price-box { background: #f0f7ff; border-radius: 12px; padding: 15px; border: 1px solid #d0e2ff; }
    .spec-list { background: #fff; border-radius: 12px; padding: 15px; border: 1px dashed #cbd5e1; font-size: 0.95rem; }
    
    /* Gallery Styling */
    .gallery-container { display: flex; gap: 8px; overflow-x: auto; padding: 10px 15px; scroll-snap-type: x mandatory; }
    .gallery-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 10px; flex-shrink: 0; border: 2px solid transparent; cursor: pointer; scroll-snap-align: start; }
    .gallery-thumb.active { border-color: #4f46e5; }

    /* MOBILE OPTIMIZATION ( < 768px ) */
    @media (max-width: 767.98px) {
        .container.py-5 { padding-top: 0 !important; padding-left: 0; padding-right: 0; } /* Full width di HP */
        .breadcrumb { padding-left: 15px; }
        .detail-container { border-radius: 0; box-shadow: none; }
        .img-preview { height: 300px; } /* Tinggi gambar pas di HP */
        .detail-content { padding: 20px 15px !important; }
        .detail-title { font-size: 1.4rem !important; margin-bottom: 10px !important; }
        
        /* Floating Buy Button di Mobile */
        .mobile-action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 12px 20px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btn-buy-mobile {
            flex-grow: 1;
            padding: 12px;
            font-weight: 700;
            border-radius: 10px;
            font-size: 1rem;
        }
        .price-mobile-label { margin-right: 15px; }
        .price-mobile-label span { font-size: 0.75rem; color: #64748b; display: block; }
        .price-mobile-label strong { font-size: 1.1rem; color: #4f46e5; }
        
        body { padding-bottom: 80px; } /* Beri ruang agar konten tidak tertutup floating button */
    }

    /* DESKTOP ONLY ( > 992px ) */
    @media (min-width: 992px) {
        .img-preview { height: 500px; }
        .detail-content { padding: 40px !important; }
        .mobile-action-bar { display: none; } /* Sembunyikan floating bar di desktop */
    }
</style>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-3 d-none d-md-block">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="katalog.php">Katalog</a></li>
            <li class="breadcrumb-item active"><?= $akun['nama_akun'] ?></li>
        </ol>
    </nav>

    <div class="detail-container">
        <div class="row g-0">
            <div class="col-lg-7">
                <div class="position-relative">
                    <img id="mainImage" src="uploads/<?= !empty($akun['foto']) ? $akun['foto'] : $galeri[0]['foto'] ?>" class="img-preview" alt="Akun" onclick="openFullscreen(this)">
                </div>
                
                <div class="gallery-container">
                    <?php if ($akun['foto']): ?>
                        <img src="uploads/<?= $akun['foto'] ?>" class="gallery-thumb active" onclick="changeImage(this, 'uploads/<?= $akun['foto'] ?>')">
                    <?php endif; ?>
                    <?php foreach ($galeri as $g): ?>
                        <img src="uploads/<?= $g['foto'] ?>" class="gallery-thumb" onclick="changeImage(this, 'uploads/<?= $g['foto'] ?>')">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="detail-content border-start-lg">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge <?= $akun['status'] == 'tersedia' ? 'bg-success' : 'bg-danger' ?> rounded-pill px-3">
                            <?= strtoupper($akun['status']) ?>
                        </span>
                        <small class="text-muted">ID: #<?= $akun['id_akun'] ?></small>
                    </div>

                    <p class="text-primary fw-bold mb-1 small"><?= $akun['nama_game'] ?></p>
                    <h1 class="fw-bold detail-title"><?= $akun['nama_akun'] ?></h1>

                    <div class="price-box my-4 d-none d-md-block">
                        <small class="text-muted fw-bold">HARGA</small>
                        <h2 class="text-primary fw-bold m-0"><?= rupiah($akun['harga']) ?></h2>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-card-text me-2"></i>Informasi Akun</h6>
                        <div class="spec-list text-secondary">
                            <?= nl2br($akun['spesifikasi']) ?>
                        </div>
                    </div>

                    <div class="mt-5 d-none d-md-block">
                        <?php if ($akun['status'] == 'tersedia'): ?>
                            <a href="<?= $wa_link ?>" class="btn btn-success w-100 py-3 fw-bold shadow-sm rounded-3" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i> BELI SEKARANG
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mobile-action-bar d-md-none">
    <div class="price-mobile-label">
        <span>Harga Total</span>
        <strong><?= rupiah($akun['harga']) ?></strong>
    </div>
    <?php if ($akun['status'] == 'tersedia'): ?>
        <a href="<?= $wa_link ?>" class="btn btn-success btn-buy-mobile" target="_blank">
            <i class="bi bi-whatsapp"></i> Beli
        </a>
    <?php else: ?>
        <button disabled class="btn btn-secondary btn-buy-mobile text-white">Terjual</button>
    <?php endif; ?>
</div>

<script>
function changeImage(thumbnail, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(img => img.classList.remove('active'));
    thumbnail.classList.add('active');
}
// ... fungsi openFullscreen ...
</script>

<?php include 'includes/footer.php'; ?>

