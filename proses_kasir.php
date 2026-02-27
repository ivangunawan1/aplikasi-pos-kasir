<?php
session_start();
include "koneksi.php";

// === SANITASI INPUT ===
// Paksa tipe data yang benar — ini mencegah SQL Injection dari $_POST
$total_akhir = (float) $_POST['total_akhir'];
$diskon      = (float) $_POST['diskon'];
$metode      = mysqli_real_escape_string($koneksi, $_POST['metode_bayar']);
$bayar       = (float) $_POST['bayar'];
$kembali     = $bayar - $total_akhir;
$tanggal     = date("Y-m-d H:i:s");
$id_kasir    = (int) $_SESSION['id'];

// === VALIDASI ===
if ($bayar < $total_akhir) {
    echo "<script>alert('Uang bayar kurang!'); window.history.back();</script>";
    exit;
}

if (empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong!'); window.history.back();</script>";
    exit;
}

// === 1. INSERT KE TABEL PENJUALAN (Prepared Statement) ===
$stmt = mysqli_prepare($koneksi, "INSERT INTO penjualan (tanggal, total_harga, diskon, metode_pembayaran, bayar, kembalian, id_user) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sddsddi", $tanggal, $total_akhir, $diskon, $metode, $bayar, $kembali, $id_kasir);
$query_p = mysqli_stmt_execute($stmt);
$id_penjualan = mysqli_insert_id($koneksi);
mysqli_stmt_close($stmt);

// === 2. CATAT LOG ===
if ($query_p) {
    catat_log($koneksi, $id_kasir, "Melakukan transaksi baru #" . $id_penjualan . " senilai Rp " . number_format($total_akhir));
}

// === 3. SIMPAN DETAIL & POTONG STOK ===
foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
    $id_produk = (int) $id_produk;
    $jumlah    = (int) $jumlah;

    // Ambil data produk
    $stmt2 = mysqli_prepare($koneksi, "SELECT harga, stok FROM produk WHERE id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id_produk);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    $dt = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt2);

    if (!$dt) continue; // Skip kalau produk tidak ditemukan

    $harga_satuan = (float) $dt['harga'];
    $subtotal     = $harga_satuan * $jumlah;
    $stok_baru    = (int) $dt['stok'] - $jumlah;

    // Insert detail penjualan
    $stmt3 = mysqli_prepare($koneksi, "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, harga_satuan, subtotal) 
                                        VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt3, "iiidd", $id_penjualan, $id_produk, $jumlah, $harga_satuan, $subtotal);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);

    // Update stok (tidak bisa negatif)
    if ($stok_baru >= 0) {
        $stmt4 = mysqli_prepare($koneksi, "UPDATE produk SET stok = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt4, "ii", $stok_baru, $id_produk);
        mysqli_stmt_execute($stmt4);
        mysqli_stmt_close($stmt4);
    }
}

// === 4. BERSIHKAN KERANJANG & REDIRECT ===
unset($_SESSION['keranjang']);
header("location:cetak_nota.php?id=$id_penjualan");
?>
