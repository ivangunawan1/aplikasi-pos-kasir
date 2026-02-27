<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password']; // Jangan hash dulu, nanti dicocokkan dengan password_verify()

// === PREPARED STATEMENT — Mencegah SQL Injection ===
$stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// === VERIFIKASI PASSWORD ===
// Mendukung dua format: password_hash (baru & aman) dan MD5 (lama)
// Ini memungkinkan migrasi bertahap tanpa harus reset semua password sekaligus
$login_berhasil = false;

if ($data) {
    $hash_tersimpan = $data['password'];

    if (password_verify($password, $hash_tersimpan)) {
        // Password format baru (password_hash) — aman
        $login_berhasil = true;
    } elseif ($hash_tersimpan === md5($password)) {
        // Password format lama (MD5) — masih bisa login, tapi langsung upgrade hash-nya
        $login_berhasil = true;
        $hash_baru = password_hash($password, PASSWORD_DEFAULT);
        $stmt_upd = mysqli_prepare($koneksi, "UPDATE user SET password = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt_upd, "si", $hash_baru, $data['id_user']);
        mysqli_stmt_execute($stmt_upd);
        mysqli_stmt_close($stmt_upd);
    }
}

if ($login_berhasil) {
    $_SESSION['status'] = "login";
    $_SESSION['user']   = $username;
    $_SESSION['id']     = $data['id_user'];
    $_SESSION['level']  = $data['level'];
    header("location:index.php");
} else {
    echo "<script>alert('Login Gagal! Username atau Password salah.'); window.location='login.php';</script>";
}
?>
