<?php 
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
include "koneksi.php";

// --- LOGIKA KERANJANG ---
if(isset($_GET['aksi']) && $_GET['aksi'] == "tambah"){
    $id = $_GET['id'];
    $_SESSION['keranjang'][$id] = ($_SESSION['keranjang'][$id] ?? 0) + 1;
    header("location:kasir.php");
    exit;
}

// --- TAMBAHKAN INI: Fungsi hapus per item ---
if(isset($_GET['aksi']) && $_GET['aksi'] == "hapus_item"){
    $id = $_GET['id'];
    if(isset($_SESSION['keranjang'][$id])){
        unset($_SESSION['keranjang'][$id]); // Menghapus item tertentu dari session
    }
    header("location:kasir.php");
    exit;
}

if(isset($_GET['aksi']) && $_GET['aksi'] == "kosongkan"){
    unset($_SESSION['keranjang']);
    header("location:kasir.php");
    exit;
}

// Ambil filter kategori dari URL
$filter_kat = $_GET['kat'] ?? '';

// Query Produk berdasarkan filter
if ($filter_kat != '') {
    $produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE stok > 0 AND kategori = '$filter_kat'");
} else {
    $produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE stok > 0");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasir - POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 1200px;">
        <div class="nav-menu">
            <a href="index.php" class="btn btn-blue">🏠 Inventory</a>
            <a href="kasir.php" class="btn btn-green">🛒 Kasir</a>
            <a href="laporan.php" class="btn btn-orange">📊 Laporan</a>
            <a href="logout.php" class="btn btn-red">🚪 Logout</a>
        </div>

        <div style="display: flex; gap: 20px; align-items: flex-start;">
            
            <div style="flex: 2;">
                <h3 style="margin-top: 0;">🍴 Pilih Menu</h3>
                
                <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="kasir.php" class="btn" style="background: <?php echo $filter_kat == '' ? '#4f46e5' : '#cbd5e1'; ?>; color: white;">Semua</a>
                    
                    <a href="kasir.php?kat=Makanan" class="btn" style="background: <?php echo $filter_kat == 'Makanan' ? '#4f46e5' : '#cbd5e1'; ?>; color: white;">🍔 Makanan</a>
                    
                    <a href="kasir.php?kat=Minuman" class="btn" style="background: <?php echo $filter_kat == 'Minuman' ? '#4f46e5' : '#cbd5e1'; ?>; color: white;">🥤 Minuman</a>
                    
                    <a href="kasir.php?kat=Snack" class="btn" style="background: <?php echo $filter_kat == 'Snack' ? '#4f46e5' : '#cbd5e1'; ?>; color: white;">🍿 Snack</a>
                    
                    <a href="kasir.php?kat=Dessert" class="btn" style="background: <?php echo $filter_kat == 'Dessert' ? '#4f46e5' : '#cbd5e1'; ?>; color: white;">🍰 Dessert</a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px;">
                    <?php while($row = mysqli_fetch_array($produk)){ ?>
                        <a href="kasir.php?aksi=tambah&id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="card-produk" style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: 0.3s; position: relative;">
                                
                                <div style="width: 100%; height: 120px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <?php if(!empty($row['foto']) && file_exists("img/".$row['foto'])){ ?>
                                        <img src="img/<?php echo $row['foto']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php } else { ?>
                                        <div style="text-align: center; color: #cbd5e1;">
                                            <span style="font-size: 40px;">🍛</span><br>
                                            <small style="font-size: 10px;">No Image</small>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div style="position: absolute; top: 5px; right: 5px; background: rgba(255,255,255,0.8); padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: bold;">
                                    📦 <?php echo $row['stok']; ?>
                                </div>

                                <div style="padding: 10px; text-align: center;">
                                    <strong style="display: block; font-size: 14px; margin-bottom: 4px; color: #1e293b;"><?php echo $row['nama_produk']; ?></strong>
                                    <span style="color: #10b981; font-weight: 700; font-size: 13px;">
                                        Rp <?php echo number_format($row['harga']); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <div style="flex: 1; background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; position: sticky; top: 20px;">
                <h3 style="margin-top: 0;">🛒 Keranjang</h3>
                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 15px;">
                
                <?php 
                $total_belanja = 0;
                if(!empty($_SESSION['keranjang'])){
                    foreach($_SESSION['keranjang'] as $id => $jumlah){
                        $ambil = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
                        $b = mysqli_fetch_array($ambil);
                        $subtotal = $b['harga'] * $jumlah;
                        $total_belanja += $subtotal;
                ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 14px; background: #fff; padding: 8px; border-radius: 8px; border: 1px solid #f1f5f9;">
                        <div style="flex: 1;">
                            <strong style="display: block;"><?php echo $b['nama_produk']; ?></strong>
                            <small style="color: #64748b;"><?php echo $jumlah; ?> x <?php echo number_format($b['harga']); ?></small>
                        </div>
                        <div style="text-align: right; display: flex; align-items: center; gap: 10px;">
                            <span style="font-weight: bold; color: #1e293b;">Rp <?php echo number_format($subtotal); ?></span>
                            
                            <a href="kasir.php?aksi=hapus_item&id=<?php echo $id; ?>" 
                            onclick="return confirm('Hapus menu ini?')" 
                            style="text-decoration: none; color: #ef4444; background: #fee2e2; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 12px; font-weight: bold;">
                            ✕
                            </a>
                        </div>
                    </div>
                <?php }} ?>

                <form action="proses_kasir.php" method="POST" style="margin-top: 20px;">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Diskon (Rp)</label>
                        <input type="number" name="diskon" value="0" class="form-control" oninput="hitungSemua()">
                    </div>

                    <div style="background: #fff; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 15px;">
                        <span style="font-size: 12px; color: #64748b;">Total yang harus dibayar:</span>
                        <h1 id="tampil-total-akhir" style="margin: 5px 0; color: #1e293b;">Rp <?php echo number_format($total_belanja); ?></h1>
                        <input type="hidden" name="total_akhir" id="input-total-akhir" value="<?php echo $total_belanja; ?>">
                    </div>

                    <div class="form-group" style="margin-bottom: 10px;">
                        <label>Metode Bayar</label>
                        <select name="metode_bayar" class="form-control">
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer / QRIS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Uang Bayar</label>
                        <input type="number" name="bayar" class="form-control" required oninput="hitungSemua()">
                    </div>

                    <p id="info-kembalian" style="font-weight: bold; font-size: 1.1em; margin: 15px 0;"></p>

                    <button type="submit" class="btn btn-blue" style="width: 100%; height: 50px; font-size: 16px;">💳 Selesaikan Transaksi</button>
                    
                    <a href="kasir.php?aksi=kosongkan" onclick="return confirm('Kosongkan keranjang?')" style="display:block; text-align:center; margin-top:15px; color:#ef4444; text-decoration:none; font-size:12px;">
                        🗑️ Kosongkan Keranjang
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function hitungSemua() {
            let totalAsli = <?php echo $total_belanja; ?>;
            let diskon = document.getElementsByName('diskon')[0].value || 0;
            let bayar = document.getElementsByName('bayar')[0].value || 0;
            
            let totalSetelahDiskon = totalAsli - diskon;
            if(totalSetelahDiskon < 0) totalSetelahDiskon = 0;
            
            document.getElementById('tampil-total-akhir').innerText = "Rp " + totalSetelahDiskon.toLocaleString();
            document.getElementById('input-total-akhir').value = totalSetelahDiskon;
            
            let kembali = bayar - totalSetelahDiskon;
            let infoKembali = document.getElementById('info-kembalian');
            
            if (kembali < 0) {
                infoKembali.innerText = "Uang kurang: Rp " + Math.abs(kembali).toLocaleString();
                infoKembali.style.color = "red";
            } else {
                infoKembali.innerText = "Kembalian: Rp " + kembali.toLocaleString();
                infoKembali.style.color = "#10b981";
            }
        }
    </script>
</body>
</html>