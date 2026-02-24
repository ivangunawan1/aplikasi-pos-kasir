<?php 
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
include "koneksi.php";

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
$row = mysqli_fetch_array($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk - POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>📝 Edit Menu Restoran</h2>

        <div style="background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" value="<?php echo $row['nama_produk']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="Makanan" <?php if($row['kategori'] == 'Makanan') echo 'selected'; ?>>Makanan</option>
                        <option value="Minuman" <?php if($row['kategori'] == 'Minuman') echo 'selected'; ?>>Minuman</option>
                        <option value="Snack" <?php if($row['kategori'] == 'Snack') echo 'selected'; ?>>Snack</option>
                        <option value="Dessert" <?php if($row['kategori'] == 'Dessert') echo 'selected'; ?>>Dessert</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga Modal (Beli)</label>
                    <input type="number" name="harga_modal" value="<?php echo $row['harga_modal']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" name="harga" value="<?php echo $row['harga']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?php echo $row['stok']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Foto Menu (Kosongkan jika tidak diubah)</label>
                    <input type="file" name="foto">
                    <br>
                    <small>Foto saat ini: <?php echo $row['foto'] ?: 'Belum ada foto'; ?></small>
                </div>

                <button type="submit" class="btn btn-blue" style="width: 100%; padding: 15px; font-size: 16px;">
                    💾 Simpan Perubahan
                </button>
            </form>
            
            <center style="margin-top: 20px;">
                <a href="index.php" style="color: var(--gray); text-decoration: none;">← Batal</a>
            </center>
        </div>
    </div>
</body>
</html>