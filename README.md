# 🛒 Sistem POS (Point of Sale) Kasir Sederhana

Aplikasi kasir berbasis web yang dirancang untuk mengelola inventaris produk, transaksi penjualan, hingga pemantauan laba bersih secara real-time. Dibangun menggunakan PHP Native dan database MySQL dengan antarmuka yang responsif.



## 🚀 Fitur Utama

* **Dashboard Interaktif**: Statistik stok, omzet harian, dan grafik penjualan 7 hari terakhir.
* **Manajemen Inventori**: Kelola produk (tambah, edit, hapus) lengkap dengan harga modal, harga jual, dan kategori.
* **Sistem Kasir (Point of Sale)**: 
    * Filter produk per kategori.
    * Fitur diskon dinamis.
    * Perhitungan kembalian otomatis.
* **Laporan Keuangan**: Rekap penjualan berdasarkan rentang tanggal, perhitungan laba bersih (Omzet - Modal), dan ekspor ke Excel.
* **Manajemen Pengguna**: Multi-user (Admin & Kasir) dengan hak akses yang berbeda.
* **Log Aktivitas**: Mencatat setiap tindakan krusial yang dilakukan pengguna untuk keamanan data.

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman**: PHP (Native)
* **Database**: MySQL / MariaDB
* **Styling**: CSS3 (Custom Responsive Design - Tanpa Framework)
* **Icons & Fonts**: Google Fonts (Inter)

## 💻 Cara Instalasi (Localhost)

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/ivangunawan1/aplikasi-pos-kasir.git](https://github.com/ivangunawan1/aplikasi-pos-kasir.git)
    ```
2.  **Pindahkan ke htdocs**
    Letakkan folder project di dalam folder `C:/xampp/htdocs/`.
3.  **Persiapan Database**
    * Buka XAMPP dan jalankan **Apache** & **MySQL**.
    * Buka `http://localhost/phpmyadmin`.
    * Buat database baru dengan nama `belajar_pos`.
    * Import file `user.sql` (atau file .sql yang tersedia) ke dalam database tersebut.
4.  **Konfigurasi Koneksi**
    Sesuaikan kredensial database kamu di file `koneksi.php` jika diperlukan.
5.  **Jalankan Aplikasi**
    Buka browser dan akses `http://localhost/aplikasi-pos-kasir/login.php`.

## 🔑 Akun Demo Default

* **Admin**: username `admin` | password `admin123`
* **Kasir**: username `kasir2` | password `kasir020202`

---
Dibuat dengan ❤️ oleh [Ivan Gunawan](https://github.com/ivangunawan1)