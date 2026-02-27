<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pos_app";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
} else {
    // echo "Koneksi Berhasil! Gudang data siap digunakan.";
}

function catat_log($koneksi, $id_user, $aksi) {
    $aksi_aman = mysqli_real_escape_string($koneksi, $aksi);
    mysqli_query($koneksi, "INSERT INTO log_aktivitas (id_user, aksi) VALUES ('$id_user', '$aksi_aman')");
}
?>