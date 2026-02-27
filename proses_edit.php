<?php
/**
 * Proses Update Data Produk
 * Menangani upload foto baru atau tetap menggunakan foto lama
 */
session_start();
include "koneksi.php";

// === SANITASI INPUT ===
$id          = (int)   $_POST['id'];
$nama        = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
$kategori    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$harga_modal = (float) $_POST['harga_modal'];
$harga_jual  = (float) $_POST['harga'];
$stok        = (int)   $_POST['stok'];
$id_user     = (int)   ($_SESSION['id_user'] ?? $_SESSION['id']);

// === HANDLE UPLOAD FOTO ===
$foto      = $_FILES['foto']['name'];
$tmp       = $_FILES['foto']['tmp_name'];

if (!empty($foto)) {
    // Validasi ekstensi file — hanya izinkan gambar
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ekstensi = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    if (!in_array($ekstensi, $ekstensi_diizinkan)) {
        echo "<script>alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF.'); window.history.back();</script>";
        exit;
    }

    // Rename file agar tidak ada konflik nama
    $nama_file_baru = uniqid('produk_') . '.' . $ekstensi;
    move_uploaded_file($tmp, "img/" . $nama_file_baru);

    $stmt = mysqli_prepare($koneksi, "UPDATE produk SET nama_produk=?, kategori=?, harga_modal=?, harga=?, stok=?, foto=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssddisi", $nama, $kategori, $harga_modal, $harga_jual, $stok, $nama_file_baru, $id);
} else {
    $stmt = mysqli_prepare($koneksi, "UPDATE produk SET nama_produk=?, kategori=?, harga_modal=?, harga=?, stok=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssddi i", $nama, $kategori, $harga_modal, $harga_jual, $stok, $id);
}

$query = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// === CATAT LOG ===
catat_log($koneksi, $id_user, "Mengubah data produk: " . $nama);

if ($query) {
    header("location:index.php?pesan=update_berhasil");
} else {
    die("Gagal update: " . mysqli_error($koneksi));
}
?>
