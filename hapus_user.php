<?php 
session_start();
include "koneksi.php";

// === CEK ROLE ADMIN ===
if ($_SESSION['level'] != "admin") {
    header("location:index.php");
    exit;
}

$id       = (int) $_GET['id'];
$id_admin = (int) ($_SESSION['id_user'] ?? $_SESSION['id']);

// Ambil username untuk log
$stmt = mysqli_prepare($koneksi, "SELECT username FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
$user_hapus = $data['username'] ?? 'tidak diketahui';

// Hapus log aktivitas user ini
$stmt2 = mysqli_prepare($koneksi, "DELETE FROM log_aktivitas WHERE id_user = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
mysqli_stmt_close($stmt2);

// Set id_user di penjualan jadi NULL agar laporan tidak rusak
$stmt3 = mysqli_prepare($koneksi, "UPDATE penjualan SET id_user = NULL WHERE id_user = ?");
mysqli_stmt_bind_param($stmt3, "i", $id);
mysqli_stmt_execute($stmt3);
mysqli_stmt_close($stmt3);

// Hapus user
$stmt4 = mysqli_prepare($koneksi, "DELETE FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt4, "i", $id);
$query = mysqli_stmt_execute($stmt4);
mysqli_stmt_close($stmt4);

if ($query) {
    catat_log($koneksi, $id_admin, "Menghapus user: $user_hapus dan membersihkan log-nya");
    header("location:user.php?pesan=hapus_berhasil");
} else {
    echo "Gagal menghapus user: " . mysqli_error($koneksi);
}
?>
