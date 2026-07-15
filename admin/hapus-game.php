<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Hapus data game berdasarkan ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: list-game.php');
    exit;
}

$id_game = $_GET['id'];

// Cek apakah game ada
$game = query("SELECT * FROM game WHERE id_game = $id_game");

if (empty($game)) {
    header('Location: list-game.php');
    exit;
}

$game = $game[0];

// Cek apakah ada akun yang menggunakan game ini
$akun_terkait = query("SELECT COUNT(*) as total FROM akun WHERE id_game = $id_game")[0]['total'];

if ($akun_terkait > 0) {
    // Jika ada akun terkait, hapus juga akun-akun tersebut
    // (sesuai dengan constraint foreign key, akun akan dihapus atau id_game di-set NULL)
    
    // Hapus galeri terkait akun
    execute("DELETE FROM galeri WHERE id_akun IN (SELECT id_akun FROM akun WHERE id_game = $id_game)");
    
    // Hapus pembelian terkait akun
    execute("DELETE FROM pembelian WHERE id_akun IN (SELECT id_akun FROM akun WHERE id_game = $id_game)");
    
    // Hapus akun-akun terkait
    execute("DELETE FROM akun WHERE id_game = $id_game");
}

// Hapus game
$sql = "DELETE FROM game WHERE id_game = $id_game";

if (execute($sql)) {
    header('Location: list-game.php');
    exit;
} else {
    echo "Gagal menghapus game!";
}
?>

