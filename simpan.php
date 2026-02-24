<?php
include "koneksi.php";

//Menangkap data yang dikirim dari form
$nama = $_POST['nama_produk'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];

//Perintah SQL untuk memasukan data ke tabel produk
$query = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama', '$harga', '$stok')";

if (mysqli_query($koneksi, $query)) {
    // Jika berhasil, pindah ke halaman index.php
    header("location:index.php");
} else {
    echo "Gagal menyimpan: " . mysqli_error($koneksi);
}
?>