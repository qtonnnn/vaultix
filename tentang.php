<?php include 'includes/header.php'; ?>

<h2>Tentang <?= $pengaturan['nama_toko'] ?></h2>

<p>
    <strong><?= $pengaturan['nama_toko'] ?></strong> adalah toko online yang menjual 
    berbagai akun game premium dengan harga terjangkau. Kami berkomitmen untuk memberikan 
    pelayanan terbaik dan akun-akun berkualitas kepada para gamers Indonesia.
</p>

<h3>Mengapa Memilih Kami?</h3>
<ul>
    <li>✅ Akun 100% Original, bukan hasil hack</li>
    <li>✅ Harga kompetitif lebih murah dari pasaran</li>
    <li>✅ Stok selalu update setiap hari</li>
    <li>✅ Admin fast response via WhatsApp</li>
    <li>✅ Proses cepat, akun dikirim segera setelah pembayaran</li>
    <li>✅ Garansi 1x24 jam</li>
</ul>

<h3>Game yang Tersedia:</h3>
<ul>
    <?php
    $games = query("SELECT * FROM game ORDER BY nama_game");
    foreach ($games as $game) {
        echo "<li>" . $game['nama_game'] . "</li>";
    }
    ?>
</ul>

<hr>

<h2>Kontak Kami</h2>

<table border="1" cellpadding="10">
    <tr>
        <td width="150">Nama Toko</td>
        <td><strong><?= $pengaturan['nama_toko'] ?></strong></td>
    </tr>
    <tr>
        <td>WhatsApp</td>
<td>
            <a href="https://wa.me/<?= formatWA($pengaturan['no_wa']) ?>" target="_blank">
                <?= $pengaturan['no_wa'] ?>
            </a>
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td>
            <a href="mailto:<?= $pengaturan['email'] ?>">
                <?= $pengaturan['email'] ?>
            </a>
        </td>
    </tr>
    <tr>
        <td>Jam Operasional</td>
        <td>Senin - Minggu, 09.00 - 22.00 WIB</td>
    </tr>
    <tr>
        <td>Respon Maksimal</td>
        <td>15 menit</td>
    </tr>
</table>

<h3>Media Sosial</h3>
<ul>
    <li>📱 Instagram: @elangstore</li>
    <li>🎵 TikTok: @elangstore</li>
    <li>💬 Discord: elangstore.gg</li>
</ul>

<p>
    Untuk pertanyaan, kritik, atau saran, jangan ragu untuk menghubungi kami via WhatsApp.
</p>

<p>
    <a href="https://wa.me/<?= formatWA($pengaturan['no_wa']) ?>" target="_blank">
        <strong>📞 Chat Admin Sekarang</strong>
    </a>
</p>

<?php include 'includes/footer.php'; ?>