# Laporan QC & Temuan Perbaikan (Vaultix)

## Ringkasan
Repo adalah aplikasi PHP (server-rendered) untuk toko akun game. Setelah review kode pada beberapa halaman utama, ditemukan isu keamanan, potensi bug runtime, redundansi, serta fitur yang belum tersedia/kurang.

---

## A. Bug / Issue Kritis

### 1) SQL Injection (kritikal)
**Lokasi:**
- `katalog.php`:
  ```php
  if ($id_game != '') {
      $where .= " AND a.id_game = '$id_game'";
  }
  ```
  `id_game` berasal dari `$_GET['game']` tanpa validasi maupun prepared statements.

- `detail.php`:
  ```php
  $id = isset($_GET['id']) ? $_GET['id'] : 0;
  $akun = query("... WHERE a.id_akun = '$id'");
  ```

**Dampak:** penyerang dapat memodifikasi query, membaca data sensitif, atau merusak data.

**Saran perbaikan:**
- Gunakan prepared statements (mysqli prepared atau PDO).
- Validasi input: `id` dan `game` harus integer.

---

### 2) XSS Risk (kritikal bila data terkontaminasi)
Banyak output ditampilkan langsung tanpa `htmlspecialchars`, misalnya:
- `includes/header.php`:
  ```php
  <title><?= $pengaturan['nama_toko'] ?> - <?= $pengaturan['slogan'] ?></title>
  ```
- `index.php`:
  ```php
  Selamat Datang di <?= $pengaturan['nama_toko'] ?>
  ```
- `katalog.php` dan `detail.php` menampilkan data dari DB langsung:
  ```php
  <?= $akun['nama_akun'] ?>
  <?= nl2br($akun['spesifikasi']) ?>
  ```

**Dampak:** jika nilai DB berisi script berbahaya, dapat terjadi XSS.

**Saran perbaikan:**
- Escape semua output HTML: `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')`.
- Untuk `nl2br`, escape dulu baru beri `nl2br`.

---

### 3) Potensi error runtime di `detail.php`
- Akses `$galeri[0]['foto']` tanpa cek apakah `$galeri` tidak kosong:
  ```php
  !empty($akun['foto']) ? $akun['foto'] : $galeri[0]['foto']
  ```

**Dampak:** undefined offset / warning.

---

### 4) JavaScript `detail.php` tampak incomplete
Terlihat ada stub:
```js
// ... fungsi openFullscreen ...
```
Jika implementasi `openFullscreen` dan error handling tidak ada di file final, klik fullscreen berpotensi error.

---

### 5) Minor markup issue di footer
`includes/footer.php` mengawali dengan `</main> <footer ...>` dalam satu baris.

**Dampak:** bukan fatal, namun bisa menyebabkan layout/structure HTML kurang rapi.

---

## B. Cacat Logika / Konsistensi

### 1) Logic stok hanya mempertimbangkan `tersedia`
- `katalog.php` filter:
  ```php
  $where = "WHERE a.status = 'tersedia'";
  ```
- `detail.php` hanya menampilkan tombol beli jika status `tersedia`.

Jika status lain (mis. pending/habis) ada di DB, UX mungkin tidak ideal.

---

### 2) Validasi input kurang
- `katalog.php` menerima `$_GET['game']` tanpa validasi.
- `detail.php` menerima `$_GET['id']` tanpa validasi integer.

---

## C. Fungsi / Fitur yang Kurang

### 1) Tidak ada pagination & pencarian
`katalog.php` menampilkan seluruh akun yang tersedia. Saat data besar, performa turun.

### 2) Tidak ada sistem transaksi terstruktur
Admin dashboard terlihat punya tabel `pembelian` (diasumsikan), tetapi front-end tidak tampak memiliki proses pembelian selain redirect ke WhatsApp.

### 3) Validasi upload belum terlihat (kemungkinan ada di file admin lain)
Folder `uploads/` ada, tapi validasi tipe/ukuran file belum terlihat pada bagian yang direview.

### 4) Tidak ada rate limiting / proteksi brute force untuk admin login (belum dicek semua file admin)

---

## D. Redundansi / Kualitas Kode

### 1) Query `$pengaturan` berulang di header & footer
- `includes/header.php` query `pengaturan`.
- `includes/footer.php` juga mencoba query lagi bila `$pengaturan` belum ada.

Ini bisa dipangkas agar lebih efisien dan konsisten.

### 2) `display_errors` di beberapa halaman
Contoh `index.php` mengaktifkan:
```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

**Saran:** matikan di production.

---

## E. Daftar Perbaikan yang Direkomendasikan (Prioritas)

### P0 (Wajib)
1. Ganti SQL raw dengan prepared statements.
2. Escape semua output HTML (XSS).
3. Validasi integer untuk `$_GET['id']` dan `$_GET['game']`.
4. Perbaiki `detail.php` agar aman ketika tabel `galeri` kosong.
5. Pastikan fungsi JS untuk fullscreen benar-benar terdefinisi.

### P1 (Penting)
1. Tambahkan pagination + search di `katalog.php`.
2. Hilangkan query redundan `$pengaturan` di footer.
3. Matikan `display_errors` untuk production.

### P2 (Nice-to-have)
1. Normalisasi status & messaging.
2. Audit file admin untuk konsistensi proteksi akses.
3. Perbaiki struktur HTML footer agar rapi.

---

## Lampiran
- Validasi syntax PHP dilakukan via `php -l` pada file berikut (tidak ada syntax error):
  - `index.php`
  - `katalog.php`
  - `detail.php`
  - `cara-order.php`
  - `admin/pengaturan.php`

