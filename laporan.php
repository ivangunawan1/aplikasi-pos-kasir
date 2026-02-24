<?php 
session_start();
// Jika bukan admin, tendang balik ke dashboard
if ($_SESSION['level'] != "admin") {
    header("location:index.php?pesan=bukan_admin");
    exit;
}
include "koneksi.php";

$tgl1 = isset($_GET['tgl1']) ? $_GET['tgl1'] : date('Y-m-01');
$tgl2 = isset($_GET['tgl2']) ? $_GET['tgl2'] : date('Y-m-d');

// 1. QUERY REKAP PRODUK + HITUNG CUAN
$sql_rekap = "SELECT 
                pr.nama_produk, 
                SUM(dp.jumlah) as total_qty, 
                SUM(dp.subtotal) as total_uang,
                SUM(pr.harga_modal * dp.jumlah) as total_modal
              FROM penjualan p
              JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan
              JOIN produk pr ON dp.id_produk = pr.id
              WHERE DATE(p.tanggal) BETWEEN '$tgl1' AND '$tgl2'
              GROUP BY pr.id ORDER BY total_qty DESC";
$query_rekap = mysqli_query($koneksi, $sql_rekap);

// 2. QUERY RIWAYAT TRANSAKSI + NAMA USER (KASIR)
// Kita gunakan LEFT JOIN ke tabel user agar nama kasir muncul
// Ganti u.nama menjadi u.username
$sql_riwayat = "SELECT p.*, u.username as nama_kasir 
                FROM penjualan p
                LEFT JOIN user u ON p.id_user = u.id_user
                WHERE DATE(p.tanggal) BETWEEN '$tgl1' AND '$tgl2' 
                ORDER BY p.tanggal DESC";
$query_riwayat = mysqli_query($koneksi, $sql_riwayat);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pro - POS Kita</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .section-title { background: #f1f5f9; padding: 10px; border-left: 5px solid #3b82f6; margin: 20px 0 10px 0; border-radius: 0 8px 8px 0; }
        .btn-print { background: #6366f1; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 11px; font-weight: bold; }
        .btn-print:hover { background: #4f46e5; }
        .badge-kasir { background: #e2e8f0; padding: 2px 8px; border-radius: 10px; font-size: 10px; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📊 Laporan Penjualan</h2>
        
        <div class="nav-menu">
            <a href="index.php" class="btn btn-blue">🏠 Dashboard</a>
            <a href="kasir.php" class="btn btn-green" style="background: #10b981;">🛒 Kasir</a>
            <a href="laporan.php" class="btn btn-orange" style="background: #f59e0b;">📊 Laporan</a>
        </div>

        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center; background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <input type="date" name="tgl1" value="<?php echo $tgl1; ?>" style="padding: 8px; border-radius: 6px; border: 1px solid #ddd;">
            <span style="color: #64748b;">s/d</span>
            <input type="date" name="tgl2" value="<?php echo $tgl2; ?>" style="padding: 8px; border-radius: 6px; border: 1px solid #ddd;">
            
            <button type="submit" class="btn btn-filter">🔍 Filter</button>
            
            <a href="ekspor_excel.php?tgl1=<?php echo $tgl1; ?>&tgl2=<?php echo $tgl2; ?>" class="btn btn-excel">
                📊 Ekspor ke Excel
            </a>
        </form>

        <div class="section-title"><strong>📦 Ringkasan Produk Terjual</strong></div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Terjual</th>
                    <th>Omzet (Kotor)</th>
                    <th>Laba Bersih</th> </tr>
            </thead>
            <tbody>
                <?php 
                $grand_kotor = 0;
                $grand_bersih = 0;
                while($row = mysqli_fetch_array($query_rekap)){ 
                    $laba_bersih = $row['total_uang'] - $row['total_modal']; // RUMUS CUAN
                    $grand_kotor += $row['total_uang'];
                    $grand_bersih += $laba_bersih;
                ?>
                <tr>
                    <td><?php echo $row['nama_produk']; ?></td>
                    <td><?php echo $row['total_qty']; ?></td>
                    <td>Rp <?php echo number_format($row['total_uang']); ?></td>
                    <td style="color: #10b981; font-weight: bold;">Rp <?php echo number_format($laba_bersih); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr style="font-weight:bold; background:#f8fafc; font-size: 1.1em;">
                    <td colspan="2" align="right">REKAP TOTAL:</td>
                    <td style="color: #64748b;">Kotor: Rp <?php echo number_format($grand_kotor); ?></td>
                    <td style="color: #10b981;">Bersih: Rp <?php echo number_format($grand_bersih); ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="section-title"><strong>🧾 Riwayat Transaksi</strong></div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Kasir</th>
                    <th>Total Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($h = mysqli_fetch_array($query_riwayat)){ ?>
                <tr>
                    <td><?php echo date('d/m H:i', strtotime($h['tanggal'])); ?></td>
                    <td><span class="badge-kasir">👤 <?php echo $h['nama_kasir'] ?? 'System'; ?></span></td>
                    <td><strong>Rp <?php echo number_format($h['total_harga']); ?></strong></td>
                    <td>
                        <a href="cetak_nota.php?id=<?php echo $h['id_penjualan']; ?>" target="_blank" class="btn-print">🖨️ Cetak Nota</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>