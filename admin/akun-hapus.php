<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

// Ambil data akun untuk hapus foto
$akun = query("SELECT * FROM akun WHERE id_akun = '$id'");
if (!empty($akun) && $akun[0]['foto'] != '') {
    $foto = $akun[0]['foto'];
    if (file_exists('../uploads/' . $foto)) {
        unlink('../uploads/' . $foto);
    }
}

// Hapus dari database
execute("DELETE FROM akun WHERE id_akun = '$id'");

header('Location: akun.php');
exit;
?>