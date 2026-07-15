<?php include 'includes/header.php'; ?>

<style>
    .order-container { max-width: 800px; margin: 0 auto; }
    
    /* Styling Step */
    .step-item {
        position: relative;
        padding-left: 60px;
        margin-bottom: 30px;
    }
    .step-number {
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 40px;
        background: #4f46e5;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    }
    .step-item::before {
        content: "";
        position: absolute;
        left: 20px;
        top: 40px;
        bottom: -30px;
        width: 2px;
        background: #e2e8f0;
    }
    .step-item:last-child::before { display: none; }
    
    .step-content {
        background: white;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .note-card {
        background: #fff9db;
        border-left: 5px solid #fcc419;
        border-radius: 10px;
        padding: 20px;
    }
    
    .cta-whatsapp {
        background: #25d366;
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-weight: bold;
        transition: 0.3s;
    }
    .cta-whatsapp:hover {
        background: #128c7e;
        color: white;
        transform: translateY(-3px);
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Panduan Pembelian</h2>
        <p class="text-muted">Ikuti langkah mudah berikut untuk mendapatkan akun impianmu di <?= $pengaturan['nama_toko'] ?></p>
    </div>

    <div class="order-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <h5 class="fw-bold">Pilih Akun</h5>
                <p class="text-muted mb-0">Cari akun favoritmu di halaman <a href="katalog.php" class="text-primary text-decoration-none fw-bold">Katalog</a>. Filter berdasarkan game yang kamu mainkan.</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <h5 class="fw-bold">Lihat Detail & Klik Beli</h5>
                <p class="text-muted mb-0">Pastikan spesifikasi sudah sesuai. Klik tombol <strong>"Amankan Akun Sekarang"</strong> untuk terhubung ke WhatsApp Admin.</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <h5 class="fw-bold">Konfirmasi & Pembayaran</h5>
                <p class="text-muted mb-0">Admin akan mengonfirmasi stok. Jika tersedia, silakan lakukan transfer ke rekening yang diberikan oleh Admin.</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">4</div>
            <div class="step-content">
                <h5 class="fw-bold">Kirim Bukti & Terima Akun</h5>
                <p class="text-muted mb-0">Kirim bukti transfer. Admin akan memproses data akun (Email & Password) dalam waktu <strong>5-15 menit</strong> saja!</p>
            </div>
        </div>

        <div class="note-card mt-5 mb-5">
            <h5 class="fw-bold"><i class="bi bi-exclamation-circle me-2"></i>Catatan Penting:</h5>
            <ul class="mb-0 mt-2">
                <li>Garansi 1x24 jam jika akun bermasalah (S&K Berlaku).</li>
                <li>Semua transaksi dilakukan via WhatsApp resmi kami.</li>
                <li>Hati-hati terhadap penipuan yang mengatasnamakan admin kami.</li>
            </ul>
        </div>

        <div class="text-center">
<a href="https://wa.me/<?= formatWA($pengaturan['no_wa']) ?>" class="cta-whatsapp shadow">
                <i class="bi bi-whatsapp fs-4 me-2"></i> TANYA ADMIN SEKARANG
            </a>
            <p class="mt-3 small text-muted">Ada kendala? Hubungi kami kapan saja.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>