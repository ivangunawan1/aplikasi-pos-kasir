<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = md5($_POST['password']);

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
$cek   = mysqli_num_rows($query);

if ($cek > 0) {
    $data = mysqli_fetch_array($query); 
    $_SESSION['status'] = "login";
    $_SESSION['user']   = $username;
    $_SESSION['id']     = $data['id_user']; 
    $_SESSION['level']  = $data['level']; // <--- TAMBAHKAN INI
    
    header("location:index.php");
} else {
    echo "<script>alert('Login Gagal! Username atau Password salah.'); window.location='login.php';</script>";
}
?>