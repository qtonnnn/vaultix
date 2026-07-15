<?php
include 'includes/header.php';

// Filter berdasarkan game
$id_game = isset($_GET['game']) ? $_GET['game'] : '';
$where = "WHERE a.status = 'tersedia'";
if ($id_game != '') {
    $where .= " AND a.id_game = '$id_game'";
}

$semua_akun = query("SELECT a.*, g.nama_game 
                    FROM akun a 
                    JOIN game g ON a.id_game = g.id_game 
                    $where 
                    ORDER BY a.harga ASC");

// Ambil semua game untuk filter
$semua_game = query("SELECT * FROM game ORDER BY nama_game");
?>

<style>
    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .account-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
        background: white;
    }
    .account-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .price-tag {
        font-size: 1.25rem;
        font-weight: 800;
        color: #4f46e5;
    }
    .game-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .spec-text {
        font-size: 0.9rem;
        color: #64748b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.7rem;
    }
    .card-img-container {
        height: 180px;
        overflow: hidden;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        background: #f1f5f9;
    }
    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #94a3b8;
    }
</style>

<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold m-0"><i class="bi bi-grid-3x3-gap me-2"></i>Katalog Akun</h2>
            <p class="text-muted small">Temukan akun impianmu dengan harga terbaik</p>
        </div>
        
        <div class="col-md-6">
            <div class="filter-card border">
                <form method="GET" action="" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="fw-bold small text-uppercase">Filter Game:</label>
                    </div>
                    <div class="col">
                        <select name="game" onchange="this.form.submit()" class="form-select border-0 bg-light">
                            <option value="">Semua Game</option>
                            <?php foreach ($semua_game as $game): ?>
                                <option value="<?= $game['id_game'] ?>" <?= ($id_game == $game['id_game']) ? 'selected' : '' ?>>
                                    <?= $game['nama_game'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (empty($semua_akun)): ?>
        <div class="text-center py-5">
            <i class="bi bi-emoji-frown display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Saat ini akun pada game ini tidak tersedia</h4>
            <p>Coba pilih filter game lain atau hubungi admin.</p>
            <a href="katalog.php" class="btn btn-primary rounded-pill px-4">Reset Filter</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($semua_akun as $akun): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 account-card shadow-sm">
                        <!-- Foto Utama Akun -->
                        <div class="card-img-container">
                            <?php if (!empty($akun['foto'])): ?>
                                <img src="uploads/<?= $akun['foto'] ?>" alt="<?= $akun['nama_akun'] ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="bi bi-image display-4"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body p-4">
                            <span class="game-badge fw-bold">
                                <i class="bi bi-controller"></i> <?= $akun['nama_game'] ?>
                            </span>
                            
                            <h5 class="card-title fw-bold mb-2 text-dark">
                                <?= $akun['nama_akun'] ?>
                            </h5>
                            
                            <p class="spec-text mb-3">
                                <?= $akun['spesifikasi'] ?>
                            </p>
                            
                            <div class="price-tag mb-3">
                                <?= rupiah($akun['harga']) ?>
                            </div>
                            
                            <a href="detail.php?id=<?= $akun['id_akun'] ?>" class="btn btn-outline-primary w-100 fw-bold py-2 rounded-3">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-5 p-3 bg-light rounded-3 text-center border">
            <span class="text-muted">Menampilkan <strong><?= count($semua_akun) ?></strong> akun yang siap diangkut! 🚀</span>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>