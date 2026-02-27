<?php
session_start();
include "koneksi.php";

// === SANITASI INPUT ===
$nama     = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
$kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$modal    = (float) $_POST['harga_modal'];
$jual     = (float) $_POST['harga'];
$stok     = (int)   $_POST['stok'];
$id_user  = (int)   $_SESSION['id'];

// === INSERT DENGAN PREPARED STATEMENT ===
$stmt = mysqli_prepare($koneksi, "INSERT INTO produk (nama_produk, kategori, harga_modal, harga, stok) 
                                   VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssddi", $nama, $kategori, $modal, $jual, $stok);
$query = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// === CATAT LOG ===
// Bugfix: sebelumnya pakai $nama_produk (undefined), seharusnya $nama
if ($query) {
    catat_log($koneksi, $id_user, "Menambah produk baru: " . $nama);
    header("location:index.php?pesan=berhasil");
} else {
    echo "Gagal menambah data: " . mysqli_error($koneksi);
}
?>
