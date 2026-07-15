<?php
// Koneksi ke database
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'Vaultix';


$koneksi = mysqli_connect($host, $user, $pass, $db, 3306,);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk mengambil data
function query($sql) {
    global $koneksi;
    $result = mysqli_query($koneksi, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menjalankan perintah (insert, update, delete)
function execute($sql) {
    global $koneksi;
    return mysqli_query($koneksi, $sql);
}

// Prepared statement versions of query/execute
function query_prepare($sql, $params = []) {
    global $koneksi;
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        error_log("SQL prepare error: " . mysqli_error($koneksi));
        return [];
    }
    if ($params) {
        $types = '';
        $bindParams = [];
        foreach ($params as $p) {
            if (is_int($p)) {
                $types .= 'i';
            } elseif (is_float($p)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $bindParams[] = $p;
        }
        $bindRefs = [$stmt, $types];
        foreach ($bindParams as $i => $v) {
            $bindRefs[] = &$bindParams[$i];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindRefs);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $rows;
}

function execute_prepare($sql, $params = []) {
    global $koneksi;
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        error_log("SQL prepare error: " . mysqli_error($koneksi));
        return false;
    }
    if ($params) {
        $types = '';
        $bindParams = [];
        foreach ($params as $p) {
            if (is_int($p)) {
                $types .= 'i';
            } elseif (is_float($p)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $bindParams[] = $p;
        }
        $bindRefs = [$stmt, $types];
        foreach ($bindParams as $i => $v) {
            $bindRefs[] = &$bindParams[$i];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindRefs);
    }
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Format rupiah
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Format nomor WhatsApp ke format internasional (62xxx)
function formatWA($no_wa) {
    // Hapus semua karakter non-digit
    $no_wa = preg_replace('/[^0-9]/', '', $no_wa);
    
    // Jika dimulai dengan 0, ubah ke 62
    if (substr($no_wa, 0, 1) == '0') {
        $no_wa = '62' . substr($no_wa, 1);
    }
    
    // Jika belum dimulai dengan 62, tambahkan 62
    if (substr($no_wa, 0, 2) != '62') {
        $no_wa = '62' . $no_wa;
    }
    
    return $no_wa;
}
?>
