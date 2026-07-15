<?php
// Pastikan variabel $pengaturan sudah tersedia (biasanya sudah di-query di header, 
// tapi tetap aman jika di-query ulang atau dipastikan ada)
if (!isset($pengaturan)) {
    $pengaturan = query("SELECT * FROM pengaturan WHERE id_pengaturan = 1")[0];
}
?>
    </main> <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container text-md-left">
            <div class="row text-md-left">
                
                <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-primary">
                        <i class="bi bi-controller me-2"></i><?= $pengaturan['nama_toko'] ?>
                    </h5>
                    <p class="text-secondary small">
                        <?= $pengaturan['slogan'] ?>
                    </p>
                    <p class="text-secondary small mt-4">
                        © <?= date('Y') ?> - All Rights Reserved
                    </p>
                </div>

                <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">Kontak Cepat</h5>
                    <p>
<a href="https://wa.me/<?= formatWA($pengaturan['no_wa']) ?>" class="text-white text-decoration-none">
                            <i class="bi bi-whatsapp me-2 text-success"></i> <?= $pengaturan['no_wa'] ?>
                        </a>
                    </p>
                    <p>
                        <a href="mailto:<?= $pengaturan['email'] ?>" class="text-white text-decoration-none">
                            <i class="bi bi-envelope me-2 text-info"></i> <?= $pengaturan['email'] ?>
                        </a>
                    </p>
                    <p>
                        <a href="tentang.php" class="text-primary text-decoration-none fw-bold small">
                             Info Lengkap &raquo;
                        </a>
                    </p>
                </div>

                <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">Media Sosial</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-twitter-x"></i></a>
                    </div>
                    <p class="mt-3 text-secondary small italic">Follow kami untuk info promo!</p>
                </div>

            </div>

            <hr class="mb-4 mt-4 border-secondary opacity-25">

            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <p class="fw-bold mb-0" style="letter-spacing: 2px;">
                        ⚡ VAULTIX, HARGA TERJANGKAU ⚡
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Tambahan style halus untuk footer */
        footer a:hover {
            color: #4f46e5 !important;
            transition: 0.3s;
        }
        .bi {
            vertical-align: middle;
        }
    </style>
</body>
</html>