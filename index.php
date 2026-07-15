<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/header.php';

// Ambil semua game
$semua_game = query("SELECT * FROM game ORDER BY nama_game");

// Hitung jumlah akun tersedia per game
$jumlah_akun = [];
foreach ($semua_game as $game) {
    $hitung = query("SELECT COUNT(*) as total FROM akun WHERE id_game = {$game['id_game']} AND status = 'tersedia'");
    $jumlah_akun[$game['id_game']] = $hitung[0]['total'];
}
?>

<style>
    body { background-color: #f4f7f6; }
    .hero-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 60px 0;
        border-radius: 0 0 50px 50px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .game-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
        background: #fff;
    }
    .game-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .game-icon {
        width: 70px;
        height: 70px;
        background: #e2e8f0;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
    }
    .badge-stock {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 50px;
    }
    .btn-view {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Selamat Datang di <?= $pengaturan['nama_toko'] ?></h1>
        <p class="lead opacity-75"><?= $pengaturan['slogan'] ?></p>
    </div>
</div>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Pilih Game Favoritmu</h3>
        <a href="katalog.php" class="text-primary fw-semibold text-decoration-none">
            Lihat Semua Akun <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <?php if (empty($semua_game)): ?>
        <div class="alert alert-info text-center py-5 border-0 shadow-sm">
            <h5 class="m-0">Belum ada game yang tersedia saat ini.</h5>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($semua_game as $game): 
                $total = $jumlah_akun[$game['id_game']];
            ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 game-card shadow-sm text-center p-4">
                        <div class="game-icon">
                            🎮
                        </div>
                        <h5 class="card-title fw-bold mb-3"><?= $game['nama_game'] ?></h5>
                        
                        <div class="mb-4">
                            <?php if ($total > 0): ?>
                                <span class="badge-stock bg-success-subtle text-success">
                                    <span class="me-1">●</span> <?= $total ?> Akun Tersedia
                                </span>
                            <?php else: ?>
                                <span class="badge-stock bg-danger-subtle text-danger">
                                    Stok Kosong
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($total > 0): ?>
                            <a href="katalog.php?game=<?= $game['id_game'] ?>" class="btn btn-primary btn-view w-100">
                                Lihat Katalog
                            </a>
                        <?php else: ?>
                            <button disabled class="btn btn-secondary btn-view w-100">
                                Habis
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-5">
        <p class="text-muted">Bingung cari game apa? 
            <a href="katalog.php" class="fw-bold text-dark">Eksplor semua akun &raquo;</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
