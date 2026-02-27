<?php
session_start();
include "koneksi.php";

// === CEK LOGIN & ROLE ADMIN ===
if ($_SESSION['status'] != "login" || $_SESSION['level'] != "admin") {
    header("location:index.php?pesan=bukan_admin");
    exit;
}

$id      = (int) $_GET['id'];
$id_user = (int) $_SESSION['id'];

// Ambil nama produk untuk log (pakai prepared statement)
$stmt = mysqli_prepare($koneksi, "SELECT nama_produk FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_p = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$nama_p = $data_p['nama_produk'] ?? 'tidak diketahui';

// Hapus produk
$stmt2 = mysqli_prepare($koneksi, "DELETE FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
$query = mysqli_stmt_execute($stmt2);
mysqli_stmt_close($stmt2);

if ($query) {
    catat_log($koneksi, $id_user, "Menghapus produk: $nama_p (ID: $id)");
    header("location:index.php");
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
?>
