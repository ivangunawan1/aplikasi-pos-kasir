<?php
include "koneksi.php";

// Tangkap data dari form Edit
$id = $_POST['id'];
$nama = $_POST['nama_produk'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];

// Perintah SQL untuk mengubah data (UPDATE)
$query = "UPDATE produk SET
        nama_produk = '$nama',
        harga = '$harga',
        stok = '$stok'
        WHERE id = '$id'";

if (mysqli_query($koneksi, $query)) {
    header("location:index.php");
} else {
    echo "Gagal Mengupdate: " . mysqli_error($koneksi);
}
?>