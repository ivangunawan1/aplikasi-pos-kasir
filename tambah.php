<?php 
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk Baru - POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>🍱 Tambah Produk Restoran</h2>

        <div class="nav-menu">
            <a href="index.php" class="btn btn-blue">📦 Inventory</a>
            <a href="kasir.php" class="btn btn-green">🛒 Kasir</a>
            <a href="laporan.php" class="btn btn-orange">📊 Laporan</a>
        </div>

        <div style="background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
            <form action="proses_tambah.php" method="POST">
                
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" placeholder="Contoh: Ayam Bakar" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Snack">Snack</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga Modal (Harga Beli ke Supplier)</label>
                    <input type="number" name="harga_modal" placeholder="Contoh: 10000" required>
                </div>

                <div class="form-group">
                    <label>Harga Jual (Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 15000" required>
                </div>

                <div class="form-group">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" placeholder="0" required>
                </div>

                <button type="submit" class="btn btn-blue" style="width: 100%; padding: 15px; font-size: 16px; margin-top: 10px;">
                    💾 Simpan Menu Baru
                </button>
            </form>
            
            <center style="margin-top: 20px;">
                <a href="index.php" style="color: var(--gray); text-decoration: none; font-size: 14px;">← Kembali ke Daftar</a>
            </center>
        </div>
    </div>
</body>
</html>