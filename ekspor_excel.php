<?php
session_start();
include "koneksi.php";

// === CEK LOGIN & ROLE ADMIN ===
if ($_SESSION['status'] != "login" || $_SESSION['level'] != "admin") {
    die("Akses ditolak. Hanya admin yang boleh mengekspor laporan.");
}

// === SANITASI INPUT TANGGAL ===
$tgl1 = isset($_GET['tgl1']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['tgl1']) ? $_GET['tgl1'] : date('Y-m-01');
$tgl2 = isset($_GET['tgl2']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['tgl2']) ? $_GET['tgl2'] : date('Y-m-d');

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan_" . $tgl1 . "_sd_" . $tgl2 . ".xls");
?>
<h2>Laporan Penjualan (<?php echo htmlspecialchars($tgl1); ?> s/d <?php echo htmlspecialchars($tgl2); ?>)</h2>
<table border="1">
    <thead>
        <tr><th>Produk</th><th>Terjual</th><th>Omzet (Kotor)</th><th>Laba Bersih</th></tr>
    </thead>
    <tbody>
        <?php
        $stmt = mysqli_prepare($koneksi, "SELECT pr.nama_produk, SUM(dp.jumlah) as total_qty, SUM(dp.subtotal) as total_uang, SUM(pr.harga_modal * dp.jumlah) as total_modal FROM penjualan p JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan JOIN produk pr ON dp.id_produk = pr.id WHERE DATE(p.tanggal) BETWEEN ? AND ? GROUP BY pr.id");
        mysqli_stmt_bind_param($stmt, "ss", $tgl1, $tgl2);
        mysqli_stmt_execute($stmt);
        $query_rekap = mysqli_stmt_get_result($stmt);
        $grand_kotor = 0; $grand_bersih = 0;
        while ($row = mysqli_fetch_array($query_rekap)) {
            $laba_bersih = $row['total_uang'] - $row['total_modal'];
            $grand_kotor += $row['total_uang'];
            $grand_bersih += $laba_bersih;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
            <td><?php echo $row['total_qty']; ?></td>
            <td><?php echo $row['total_uang']; ?></td>
            <td><?php echo $laba_bersih; ?></td>
        </tr>
        <?php } mysqli_stmt_close($stmt); ?>
    </tbody>
    <tr>
        <td colspan="2"><b>TOTAL</b></td>
        <td><b><?php echo $grand_kotor; ?></b></td>
        <td><b><?php echo $grand_bersih; ?></b></td>
    </tr>
</table>
