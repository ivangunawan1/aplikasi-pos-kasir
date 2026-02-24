<?php
include "koneksi.php";

// 1. Tangkap ID dari URL
$id_cetak = isset($_GET['id']) ? $_GET['id'] : '';

if(empty($id_cetak)) {
    die("ID Transaksi tidak ditemukan.");
}

// 2. Query Data Utama (Sesuai perbaikan agar nama kasir muncul)
// Kita gunakan LEFT JOIN ke tabel user
$query_p = mysqli_query($koneksi, "SELECT penjualan.*, user.username FROM penjualan 
                                   LEFT JOIN user ON penjualan.id_user = user.id_user 
                                   WHERE id_penjualan = '$id_cetak'");
$data = mysqli_fetch_array($query_p);

if(!$data) {
    die("Data transaksi tidak ditemukan. Pastikan ID benar.");
}

// 3. Query Rincian Barang
$rincian = mysqli_query($koneksi, "SELECT detail_penjualan.*, produk.nama_produk 
                                   FROM detail_penjualan 
                                   JOIN produk ON detail_penjualan.id_produk = produk.id 
                                   WHERE id_penjualan = '$id_cetak'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nota Transaksi - #<?php echo $id_cetak; ?></title>
    <style>
        /* Tampilan Nota Struk Thermal */
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 300px; 
            margin: 20px auto; 
            color: #333; 
            line-height: 1.2;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        
        /* Tombol Cetak agar tidak ikut terprint */
        @media print { .no-print { display: none; } }
        
        .btn-cetak {
            width: 100%; 
            padding: 10px; 
            background: #4f46e5; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">KEDAI KITA</h2>
        <p style="margin:5px 0;">Jl. Raya No. 123, Tuban</p>
        <div class="line"></div>
        <table style="font-size: 12px;">
            <tr>
                <td>Tgl: <?php echo date('d/m/Y H:i', strtotime($data['tanggal'])); ?></td>
                <td class="text-right">ID: #<?php echo $id_cetak; ?></td>
            </tr>
            <tr>
                <td colspan="2">Kasir: <?php echo $data['username'] ?? 'System'; ?></td>
            </tr>
        </table>
    </div>

    <div class="line"></div>

    <table>
        <?php while($row = mysqli_fetch_array($rincian)){ ?>
        <tr>
            <td style="padding-bottom: 5px;">
                <?php echo $row['nama_produk']; ?><br>
                <small><?php echo $row['jumlah']; ?> x <?php echo number_format($row['harga_satuan']); ?></small>
            </td>
            <td class="text-right" valign="top">
                Rp <?php echo number_format($row['subtotal']); ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="line"></div>

    <table>
        <?php if($data['diskon'] > 0) { ?>
        <tr>
            <td>Total Item</td>
            <td class="text-right">Rp <?php echo number_format($data['total_harga'] + $data['diskon']); ?></td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="text-right">- Rp <?php echo number_format($data['diskon']); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td><strong>TOTAL BAYAR</strong></td>
            <td class="text-right"><strong>Rp <?php echo number_format($data['total_harga']); ?></strong></td>
        </tr>
        <tr><td colspan="2"><div class="line"></div></td></tr>
        <tr>
            <td>Bayar</td>
            <td class="text-right">Rp <?php echo number_format($data['bayar']); ?></td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">Rp <?php echo number_format($data['kembalian']); ?></td>
        </tr>
    </table>

    <div class="footer">
        <p>*** TERIMA KASIH ***</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        
        <div class="no-print" style="margin-top: 30px;">
            <button onclick="window.print()" class="btn-cetak">🖨️ Cetak Nota Sekarang</button>
            <a href="laporan.php" style="display: block; color: #64748b; text-decoration: none; font-size: 13px;">← Kembali ke Laporan</a>
        </div>
    </div>
</body>
</html>