<?php
session_start(); // Tambahkan ini di baris paling atas
include "koneksi.php";

$id = $_GET['id'];
$id_user = $_SESSION['id']; // Ambil ID user dari session

// Catat log SEBELUM data dihapus agar kita tahu nama produknya (Opsional tapi bagus)
$ambil_nama = mysqli_query($koneksi, "SELECT nama_produk FROM produk WHERE id='$id'");
$data_p = mysqli_fetch_array($ambil_nama);
$nama_p = $data_p['nama_produk'];

$query = "DELETE FROM produk WHERE id = '$id'";

if (mysqli_query($koneksi, $query)) {
    // CATAT LOG PENGHAPUSAN
    catat_log($koneksi, $id_user, "Menghapus produk: $nama_p (ID: $id)");
    header("location:index.php");
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
?>