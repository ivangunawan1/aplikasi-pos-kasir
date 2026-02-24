<?php 
session_start();
include "koneksi.php";

if ($_SESSION['level'] != "admin") {
    header("location:index.php");
    exit;
}

$id = $_GET['id'];

// 1. Ambil username untuk keperluan catat log terakhir
$ambil = mysqli_query($koneksi, "SELECT username FROM user WHERE id_user='$id'");
$data = mysqli_fetch_array($ambil);
$user_hapus = $data['username'];

// 2. HAPUS DULU data di tabel log_aktivitas yang berhubungan dengan user ini
// Ini supaya tidak terjadi error "Foreign Key Constraint"
mysqli_query($koneksi, "DELETE FROM log_aktivitas WHERE id_user='$id'");

// 3. (Opsional) Jika user ini pernah jualan, ID-nya juga ada di tabel penjualan. 
// Agar laporan tidak rusak, kita set id_user di penjualan jadi NULL atau 0, jangan dihapus penjualannya.
mysqli_query($koneksi, "UPDATE penjualan SET id_user=NULL WHERE id_user='$id'");

// 4. Baru sekarang kita hapus user-nya
$query = mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");

if($query) {
    // Catat aksi penghapusan oleh admin yang sedang login
    $id_admin = $_SESSION['id_user'] ?? $_SESSION['id'];
    catat_log($koneksi, $id_admin, "Menghapus user: $user_hapus dan membersihkan log-nya");
    
    header("location:user.php?pesan=hapus_berhasil");
} else {
    echo "Gagal menghapus user: " . mysqli_error($koneksi);
}
?>