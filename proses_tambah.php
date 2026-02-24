<?php
include "koneksi.php";

$nama     = $_POST['nama_produk'];
$kategori = $_POST['kategori'];
$modal    = $_POST['harga_modal']; // Tangkap modal
$jual     = $_POST['harga'];      // Ini harga jual
$stok     = $_POST['stok'];

$query = mysqli_query($koneksi, "INSERT INTO produk (nama_produk, kategori, harga_modal, harga, stok) 
                                 VALUES ('$nama', '$kategori', '$modal', '$jual', '$stok')");
catat_log($koneksi, $_SESSION['id'], "Menambah produk baru: " . $nama_produk);

if ($query) {
    header("location:index.php?pesan=berhasil");
} else {
    echo "Gagal menambah data: " . mysqli_error($koneksi);
}
?>