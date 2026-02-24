<?php
include "koneksi.php";

// Memberi tahu browser bahwa ini adalah file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");

$tgl1 = $_GET['tgl1'];
$tgl2 = $_GET['tgl2'];
?>

<h2>Laporan Penjualan (<?php echo $tgl1; ?> s/d <?php echo $tgl2; ?>)</h2>

<table border="1">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Terjual</th>
            <th>Omzet (Kotor)</th>
            <th>Laba Bersih</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $sql_rekap = "SELECT 
                        pr.nama_produk, 
                        SUM(dp.jumlah) as total_qty, 
                        SUM(dp.subtotal) as total_uang,
                        SUM(pr.harga_modal * dp.jumlah) as total_modal
                      FROM penjualan p
                      JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan
                      JOIN produk pr ON dp.id_produk = pr.id
                      WHERE DATE(p.tanggal) BETWEEN '$tgl1' AND '$tgl2'
                      GROUP BY pr.id";
        $query_rekap = mysqli_query($koneksi, $sql_rekap);
        
        $grand_kotor = 0;
        $grand_bersih = 0;

        while($row = mysqli_fetch_array($query_rekap)){ 
            $laba_bersih = $row['total_uang'] - $row['total_modal'];
            $grand_kotor += $row['total_uang'];
            $grand_bersih += $laba_bersih;
        ?>
        <tr>
            <td><?php echo $row['nama_produk']; ?></td>
            <td><?php echo $row['total_qty']; ?></td>
            <td><?php echo $row['total_uang']; ?></td>
            <td><?php echo $laba_bersih; ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tr>
        <td colspan="2"><b>TOTAL</b></td>
        <td><b><?php echo $grand_kotor; ?></b></td>
        <td><b><?php echo $grand_bersih; ?></b></td>
    </tr>
</table>