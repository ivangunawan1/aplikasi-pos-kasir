<?php 
session_start();
if ($_SESSION['level'] != "admin") {
    header("location:index.php?pesan=bukan_admin");
    exit;
}
include "koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User - POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>👥 Manajemen Pengguna</h2>
        
        <div class="nav-menu">
            <a href="index.php" class="btn btn-blue">🏠 Dashboard</a>
            <a href="laporan.php" class="btn btn-orange">📋 Laporan</a>
        </div>

        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px; background: #f8fafc; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0;">
                <h3>➕ Tambah User Baru</h3>
                <form action="proses_user.php" method="POST">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama asli" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Untuk login" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Minimal 5 karakter" required>
                    </div>
                    <div class="form-group">
                        <label>Level Akses</label>
                        <select name="level" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-blue" style="width: 100%; padding: 12px; margin-top: 10px;">💾 Simpan Pengguna</button>
                </form>
            </div>

            <div style="flex: 2; min-width: 300px;">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $u = mysqli_query($koneksi, "SELECT * FROM user");
                        while($data = mysqli_fetch_array($u)){
                        ?>
                        <tr>
                            <td><strong><?php echo $data['nama'] ?: $data['username']; ?></strong></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><span class="badge-kasir" style="background: <?php echo $data['level']=='admin' ? '#fee2e2' : '#dcfce7'; ?>; color: <?php echo $data['level']=='admin' ? '#ef4444' : '#10b981'; ?>;">
                                <?php echo strtoupper($data['level']); ?></span>
                            </td>
                            <td>
                                <?php 
                                // Kita bandingkan id_user di database dengan id_user di session login
                                $id_saya = $_SESSION['id_user'] ?? $_SESSION['id'] ?? 0;

                                if($data['id_user'] != $id_saya) { 
                                ?>
                                    <a href="hapus_user.php?id=<?php echo $data['id_user']; ?>" class="link-hapus" onclick="return confirm('Hapus user ini?')">Hapus</a>
                                <?php } else { echo "<small>Anda (Aktif)</small>"; } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>