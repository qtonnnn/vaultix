# TODO: Konsistenkan Header Admin

## Analisis
Semua halaman admin perlu memiliki header yang konsisten dengan:
- 5 menu: Dashboard, Kelola Game, Kelola Akun, Transaksi, Pengaturan
- Tombol Logout
- Menu aktif sesuai halaman

## Files yang perlu diedit:

- [x] 1. admin/pengaturan.php - Tambah Kelola Game, Kelola Akun, Transaksi, Logout
- [x] 2. admin/pembelian.php - Tambah Kelola Game, Kelola Akun, Pengaturan, Logout
- [x] 3. admin/list-game.php - Tambah Pengaturan
- [x] 4. admin/akun.php - Tambah Transaksi, Pengaturan, Logout
- [x] 5. admin/tambah-game.php - Tambah Transaksi, Pengaturan, Logout
- [x] 6. admin/edit.game.php - Tambah Transaksi, Pengaturan, Logout
- [x] 7. admin/akun-tambah.php - Tambah Kelola Game, Transaksi, Pengaturan, Logout
- [x] 8. admin/akun-edit.php - Tambah Kelola Game, Transaksi, Pengaturan, Logout

## Standar header yang digunakan (dari index.php):
```html
<nav class="navbar navbar-expand-lg navbar-admin mb-4 shadow-sm">
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
                <li class="nav-item"><a class="nav-link nav-link-admin" href="pengaturan.php">Pengaturan</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="text-white-50 me-3 small">Halo, <?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill">Logout</a>
            </div>
        </div>
    </div>
</nav>
```

