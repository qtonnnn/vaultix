<?php
include 'includes/header.php';

// Pagination
$per_page = 12;
$page = isset($_GET['halaman']) ? filter_var($_GET['halaman'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : 1;
if ($page === false) $page = 1;
$offset = ($page - 1) * $per_page;

// Filter game
$id_game = isset($_GET['game']) ? filter_var($_GET['game'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : 0;
if ($id_game === false) $id_game = 0;

// Pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// Bangun WHERE dinamis
$where = "WHERE 1=1";
$params = [];

if ($id_game > 0) {
    $where .= " AND a.id_game = ?";
    $params[] = $id_game;
}
if ($cari !== '') {
    $where .= " AND a.nama_akun LIKE ?";
    $params[] = "%$cari%";
}

// Hitung total
$total = query_prepare("SELECT COUNT(*) as total FROM akun a $where", $params);
$total_akun = $total ? (int)$total[0]['total'] : 0;
$total_halaman = max(1, ceil($total_akun / $per_page));

// Ambil data halaman ini
$semua_akun = query_prepare("SELECT a.*, g.nama_game 
                    FROM akun a 
                    JOIN game g ON a.id_game = g.id_game 
                    $where 
                    ORDER BY a.status ASC, a.harga ASC 
                    LIMIT ? OFFSET ?", array_merge($params, [$per_page, $offset]));

// Ambil semua game untuk filter
$semua_game = query("SELECT * FROM game ORDER BY nama_game");

// Helper build URL dengan parameter existing
function buildUrl($params_override = []) {
    $existing = [];
    if (isset($_GET['game']) && $_GET['game'] !== '') $existing['game'] = (int)$_GET['game'];
    if (isset($_GET['cari']) && trim($_GET['cari']) !== '') $existing['cari'] = trim($_GET['cari']);
    foreach ($params_override as $k => $v) {
        if ($v === '' || $v === null || $v === false) unset($existing[$k]);
        else $existing[$k] = $v;
    }
    return '?' . http_build_query($existing);
}
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
                    <div class="col-4">
                        <select name="game" onchange="this.form.submit()" class="form-select border-0 bg-light">
                            <option value="">Semua Game</option>
                            <?php foreach ($semua_game as $game): ?>
                                <option value="<?= $game['id_game'] ?>" <?= ($id_game === (int)$game['id_game']) ? 'selected' : '' ?>>
                                    <?= $game['nama_game'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="search" name="cari" class="form-control border-0 bg-light" placeholder="Cari akun..." value="<?= htmlspecialchars($cari, ENT_QUOTES) ?>">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (empty($semua_akun)): ?>
        <div class="text-center py-5">
            <i class="bi bi-emoji-frown display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Belum ada akun ditemukan</h4>
            <p>Coba ubah filter atau kata pencarian.</p>
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
        
        <!-- Info & Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-5 p-3 bg-light rounded-3 border">
            <span class="text-muted mb-2 mb-md-0">Menampilkan <strong><?= count($semua_akun) ?></strong> dari <strong><?= $total_akun ?></strong> akun</span>
            
            <?php if ($total_halaman > 1): ?>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildUrl(['halaman' => $page - 1]) ?>">«</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= buildUrl(['halaman' => $i]) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_halaman ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildUrl(['halaman' => $page + 1]) ?>">»</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>