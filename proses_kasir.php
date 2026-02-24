<?php
session_start();
include "koneksi.php";

$total_akhir = $_POST['total_akhir'];
$diskon      = $_POST['diskon']; // <--- Tangkap nilai diskon dari kasir.php
$metode      = $_POST['metode_bayar'];
$bayar       = $_POST['bayar'];
$kembali     = $bayar - $total_akhir;
$tanggal     = date("Y-m-d H:i:s");

// Ambil ID Kasir dari session
$id_kasir    = $_SESSION['id']; 

if($bayar < $total_akhir) {
    echo "<script>alert('Uang bayar kurang!'); window.history.back();</script>";
    exit;
}

// 1. Insert ke tabel penjualan
$query_p = mysqli_query($koneksi, "INSERT INTO penjualan (tanggal, total_harga, diskon, metode_pembayaran, bayar, kembalian, id_user) 
                                   VALUES ('$tanggal', '$total_akhir', '$diskon', '$metode', '$bayar', '$kembali', '$id_kasir')");

// 2. LANGSUNG ambil ID-nya di sini sebelum panggil fungsi lain!
$id_penjualan = mysqli_insert_id($koneksi);

// 3. Baru catat log (agar tidak mengganggu ID insert)
if($query_p) {
    catat_log($koneksi, $id_kasir, "Melakukan transaksi baru #" . $id_penjualan . " senilai Rp " . number_format($total_akhir));
}

// 3. Simpan rincian barang ke tabel DETAIL_PENJUALAN (Logika stok tetap sama)
if(!empty($_SESSION['keranjang'])) {
    foreach($_SESSION['keranjang'] as $id_produk => $jumlah) {
        
        $ambil_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id_produk'");
        $dt = mysqli_fetch_array($ambil_produk);
        $harga_satuan = $dt['harga'];
        $subtotal     = $harga_satuan * $jumlah;
        $stok_sekarang = $dt['stok'];

        mysqli_query($koneksi, "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, harga_satuan, subtotal) 
                                VALUES ('$id_penjualan', '$id_produk', '$jumlah', '$harga_satuan', '$subtotal')");

        // Potong stok produk
        $stok_baru = $stok_sekarang - $jumlah;
        mysqli_query($koneksi, "UPDATE produk SET stok='$stok_baru' WHERE id='$id_produk'");
    }
}

// Kosongkan keranjang setelah sukses
unset($_SESSION['keranjang']);

// Lempar ke halaman cetak nota
header("location:cetak_nota.php?id=$id_penjualan");
?>