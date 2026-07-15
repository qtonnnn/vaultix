<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data akun untuk hapus foto
$akun = query_prepare("SELECT * FROM akun WHERE id_akun = ?", [$id]);
if (!empty($akun) && $akun[0]['foto'] != '') {
    $foto = $akun[0]['foto'];
    if (file_exists('../uploads/' . $foto)) {
        unlink('../uploads/' . $foto);
    }
}

// Hapus galeri & pembelian terkait
execute_prepare("DELETE FROM galeri WHERE id_akun = ?", [$id]);
execute_prepare("DELETE FROM pembelian WHERE id_akun = ?", [$id]);

// Hapus dari database
execute_prepare("DELETE FROM akun WHERE id_akun = ?", [$id]);

header('Location: akun.php');
exit;
?>
