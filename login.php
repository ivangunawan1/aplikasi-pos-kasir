<!DOCTYPE html>
<html>
<head>
    <title>Login - POS Sederhana</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Memastikan form login berada di tengah secara vertikal & horizontal */
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            background: #f1f5f9; 
            margin: 0;
        }
        .login-box { width: 100%; max-width: 400px; }
    </style>
</head>
<body>
    <div class="container login-box">
        <h2 style="justify-content: center;">🔐 Login Kasir</h2>
        <form action="cek_login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn btn-blue" style="width: 100%; padding: 15px; justify-content: center;">Masuk ke Aplikasi</button>
        </form>
    </div>
</body>
</html>