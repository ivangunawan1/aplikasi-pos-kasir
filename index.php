<?php 
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
include "koneksi.php";

// --- 1. LOGIKA DATA GRAFIK ---
$label_hari = [];
$data_omzet = [];
for ($i = 6; $i >= 0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i days"));
    $label_hari[] = date('d M', strtotime($tgl)); 
    $sql_omzet = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM penjualan WHERE tanggal LIKE '$tgl%'");
    $res = mysqli_fetch_array($sql_omzet);
    $data_omzet[] = (int)($res['total'] ?? 0);
}

// --- 2. DATA SUMMARY ---
$q_stok = mysqli_query($koneksi, "SELECT SUM(stok) as total_stok FROM produk");
$r_stok = mysqli_fetch_assoc($q_stok);

$hari_ini = date('Y-m-d');
$q_laku = mysqli_query($koneksi, "SELECT SUM(total_harga) as total_hari_ini FROM penjualan WHERE tanggal LIKE '$hari_ini%'");
$r_laku = mysqli_fetch_assoc($q_laku);

$q_tipis = mysqli_query($koneksi, "SELECT COUNT(*) as jml_tipis FROM produk WHERE stok < 10");
$r_tipis = mysqli_fetch_assoc($q_tipis);

$query = mysqli_query($koneksi, "SELECT * FROM produk");
?>

<?php
// 1. Hitung Total Pendapatan Hari Ini
$hari_ini = date('Y-m-d');
$query_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM penjualan WHERE DATE(tanggal) = '$hari_ini'");
$data_p = mysqli_fetch_array($query_pendapatan);
$total_cuan = $data_p['total'] ?? 0;

// 2. Hitung Jumlah Transaksi Hari Ini
$query_transaksi = mysqli_query($koneksi, "SELECT COUNT(id_penjualan) as jumlah FROM penjualan WHERE DATE(tanggal) = '$hari_ini'");
$data_t = mysqli_fetch_array($query_transaksi);
$total_order = $data_t['jumlah'] ?? 0;

// 3. Cari Produk Terlaris
$query_laris = mysqli_query($koneksi, "SELECT p.nama_produk, SUM(dp.jumlah) as total_terjual 
                                       FROM detail_penjualan dp 
                                       JOIN produk p ON dp.id_produk = p.id 
                                       GROUP BY dp.id_produk 
                                       ORDER BY total_terjual DESC LIMIT 1");
$data_l = mysqli_fetch_array($query_laris);
$produk_populer = $data_l['nama_produk'] ?? "-";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - POS Restoran</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>📊 Dashboard Restoran</h2>

        <div class="nav-menu">
            <a href="index.php" class="btn btn-blue">📦 Inventory</a>
            <a href="kasir.php" class="btn btn-green">🛒 Kasir</a>
            <a href="laporan.php" class="btn btn-orange">📊 Laporan</a>
            <?php if($_SESSION['level'] == 'admin') { ?>
                <a href="user.php" class="btn" style="background: #6366f1; color: white;">👥 User</a>
            <?php } ?>
            <a href="logout.php" class="btn btn-red">🚪 Logout</a>
        </div>

        <style>
            .summary-container { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
            .summary-card { flex: 1; min-width: 200px; color: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: 0.3s; }
            .summary-card:hover { transform: translateY(-5px); }
            .bg-green { background: linear-gradient(135deg, #10b981, #059669); }
            .bg-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
            .bg-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
            .bg-red { background: linear-gradient(135deg, #ef4444, #b91c1c); }
            .summary-card small { opacity: 0.8; font-size: 0.85em; text-transform: uppercase; letter-spacing: 1px; }
            .summary-card h2 { margin: 10px 0 0; font-size: 1.6em; }
        </style>

        <div class="summary-container">
            <div class="summary-card bg-green">
                <small>💰 Omzet Hari Ini</small>
                <h2>Rp <?php echo number_format($r_laku['total_hari_ini'] ?? 0); ?></h2>
            </div>

            <div class="summary-card bg-blue">
                <small>📦 Total Stok</small>
                <h2><?php echo number_format($r_stok['total_stok'] ?? 0); ?> <span style="font-size: 14px;">Pcs</span></h2>
            </div>

            <?php
            $q_laris = mysqli_query($koneksi, "SELECT p.nama_produk, SUM(dp.jumlah) as total FROM detail_penjualan dp JOIN produk p ON dp.id_produk = p.id GROUP BY dp.id_produk ORDER BY total DESC LIMIT 1");
            $r_laris = mysqli_fetch_assoc($q_laris);
            ?>
            <div class="summary-card bg-orange">
                <small>🔥 Produk Terlaris</small>
                <h2 style="font-size: 1.2em;"><?php echo $r_laris['nama_produk'] ?? '-'; ?></h2>
            </div>

            <div class="summary-card bg-red">
                <small>⚠️ Stok Kritis (< 10)</small>
                <h2><?php echo $r_tipis['jml_tipis'] ?? 0; ?> <span style="font-size: 14px;">Item</span></h2>
            </div>
        </div>

        <?php if ($r_tipis['jml_tipis'] > 0) { ?>
            <div style="background: #fffbeb; border-left: 5px solid #f59e0b; padding: 15px; border-radius: 8px; margin: 20px 0; display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 24px;">⚠️</span>
                <div>
                    <h4 style="margin: 0; color: #92400e;">Perhatian: Ada <?php echo $r_tipis['jml_tipis']; ?> Produk Hampir Habis!</h4>
                    <p style="margin: 5px 0 0; color: #b45309; font-size: 14px;"> Segera cek daftar stok barang di bawah untuk melakukan pengisian ulang.</p>
                </div>
            </div>
        <?php } ?>

        <div class="chart-container">
            <h4 style="margin-top:0;">📈 Tren Penjualan 7 Hari Terakhir</h4>
            <canvas id="grafikOmzet" height="100"></canvas>
        </div>

        <div style="margin-top: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4>📦 Daftar Stok Barang</h4>
                <a href="tambah.php" class="btn btn-blue">+ Tambah Produk</a>
            </div>
            
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>NO</th><th>NAMA PRODUK</th><th>KATEGORI</th><th>HARGA</th><th>STOK</th><th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_array($query)) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nama_produk']; ?></td>
                        <td><span class="badge"><?php echo $row['kategori'] ?? '-'; ?></span></td>
                        <td>Rp <?php echo number_format($row['harga']); ?></td>

                        <td style="font-weight: bold; color: <?php echo $row['stok'] < 10 ? '#ef4444' : '#1e293b'; ?>;">
                            <?php echo $row['stok']; ?>
                            <?php if($row['stok'] < 10) echo " <br><small style='color:#ef4444'>(Kritis!)</small>"; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="link-edit">Edit</a>
                            
                            <?php if($_SESSION['level'] == 'admin') { ?>
                                | <a href="hapus.php?id=<?php echo $row['id']; ?>" class="link-hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('grafikOmzet').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($label_hari); ?>,
            datasets: [{
                label: 'Omzet (Rp)',
                data: <?php echo json_encode($data_omzet); ?>,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
    </script>

    <?php if($_SESSION['level'] == 'admin') { ?>
        <div class="chart-container" style="margin-top: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;">📜 Log Aktivitas Terbaru</h3>
                <span style="font-size: 12px; background: #e2e8f0; padding: 4px 10px; border-radius: 20px; color: #64748b;">Khusus Admin</span>
            </div>
            
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Mengambil 5 log terbaru dengan join ke tabel user untuk ambil username
                    $logs = mysqli_query($koneksi, "SELECT log_aktivitas.*, user.username FROM log_aktivitas JOIN user ON log_aktivitas.id_user = user.id_user ORDER BY tanggal DESC LIMIT 5");
                    while($l = mysqli_fetch_array($logs)){
                    ?>
                    <tr>
                        <td style="font-size: 13px; color: #64748b;"><?php echo date('d/m H:i', strtotime($l['tanggal'])); ?></td>
                        <td>
                            <span style="color: <?php echo $l['username'] == 'admin' ? '#4f46e5' : '#10b981'; ?>;">
                                <strong><?php echo $l['username']; ?></strong>
                            </span>
                        </td>
                        <td style="font-size: 13px;"><?php echo $l['aksi']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</body>
</html>