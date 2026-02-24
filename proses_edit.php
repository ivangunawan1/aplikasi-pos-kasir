<?php
/**
 * Proses Update Data Produk
 * Menangani upload foto baru atau tetap menggunakan foto lama
 */
session_start();
include "koneksi.php";

$id           = $_POST['id'];
$nama         = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
$kategori     = $_POST['kategori'];
$harga_modal  = $_POST['harga_modal']; 
$harga_jual   = $_POST['harga'];       
$stok         = $_POST['stok'];
$foto         = $_FILES['foto']['name'];
$tmp          = $_FILES['foto']['tmp_name'];

if(!empty($foto)){
    // Jika user mengunggah foto baru
    move_uploaded_file($tmp, "img/".$foto);
    $query = mysqli_query($koneksi, "UPDATE produk SET nama_produk='$nama', kategori='$kategori', harga_modal='$harga_modal', harga='$harga_jual', stok='$stok', foto='$foto' WHERE id='$id'");
} else {
    // Jika user tidak mengubah foto
    $query = mysqli_query($koneksi, "UPDATE produk SET nama_produk='$nama', kategori='$kategori', harga_modal='$harga_modal', harga='$harga_jual', stok='$stok' WHERE id='$id'");
}

// Mencatat aktivitas ke log
$id_user = $_SESSION['id_user'] ?? $_SESSION['id'];
catat_log($koneksi, $id_user, "Mengubah data produk: " . $nama);

if ($query) {
    header("location:index.php?pesan=update_berhasil");
} else {
    die("Gagal update: " . mysqli_error($koneksi));
}
?>