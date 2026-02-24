<?php 
session_start();
include "koneksi.php";

// Gunakan real_escape_string agar karakter aneh tidak bikin error SQL
$nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = md5($_POST['password']); 
$level    = $_POST['level'];

// Tambahkan NULL di depan untuk id_user (karena id_user auto increment)
$query = mysqli_query($koneksi, "INSERT INTO user VALUES (NULL, '$nama', '$username', '$password', '$level')");

if($query) {
    // Ambil ID dari session dengan aman
    $id_admin = $_SESSION['id_user'] ?? $_SESSION['id'] ?? 0;
    catat_log($koneksi, $id_admin, "Menambah user baru: $username ($level)");
    header("location:user.php?pesan=sukses");
} else {
    // Jika masih error, ini akan memunculkan pesan error spesifik dari database
    die("Error Database: " . mysqli_error($koneksi));
}
?>