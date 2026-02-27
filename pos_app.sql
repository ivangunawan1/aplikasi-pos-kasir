-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 05:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_satuan` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail`, `id_penjualan`, `id_produk`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 1, 15000, 15000),
(2, 1, 3, 1, 6000, 6000),
(3, 1, 6, 1, 7500, 7500),
(4, 2, 4, 1, 10000, 10000),
(5, 2, 3, 1, 6000, 6000),
(6, 2, 6, 1, 7500, 7500),
(7, 3, 9, 5, 35000, 175000),
(8, 4, 2, 4, 4000, 16000),
(9, 5, 13, 1, 15000, 15000),
(10, 5, 12, 1, 9500, 9500),
(11, 5, 10, 1, 12000, 12000),
(12, 5, 8, 1, 9500, 9500),
(13, 5, 7, 1, 8000, 8000),
(14, 5, 6, 1, 7500, 7500),
(15, 5, 5, 1, 12000, 12000),
(16, 5, 1, 1, 15000, 15000),
(17, 6, 1, 1, 15000, 15000),
(18, 6, 5, 1, 12000, 12000),
(19, 6, 9, 1, 35000, 35000),
(20, 6, 10, 1, 12000, 12000),
(21, 6, 6, 1, 7500, 7500),
(22, 6, 2, 1, 4000, 4000),
(23, 6, 3, 1, 6000, 6000),
(24, 6, 7, 1, 8000, 8000),
(25, 6, 12, 1, 9500, 9500),
(26, 6, 13, 1, 15000, 15000),
(27, 6, 8, 1, 9500, 9500),
(28, 6, 4, 1, 10000, 10000),
(29, 7, 14, 1, 6000, 6000),
(30, 8, 14, 5, 6000, 30000),
(31, 9, 7, 2, 8000, 16000),
(32, 10, 2, 2, 4000, 8000),
(33, 10, 5, 1, 12000, 12000),
(34, 10, 6, 1, 7500, 7500),
(35, 10, 7, 1, 8000, 8000),
(36, 11, 15, 3, 10000, 30000),
(37, 12, 1, 1, 15000, 15000),
(38, 12, 2, 1, 4000, 4000),
(39, 12, 3, 1, 6000, 6000),
(40, 12, 4, 1, 10000, 10000),
(41, 13, 2, 3, 4000, 12000),
(42, 14, 6, 1, 7500, 7500),
(43, 15, 4, 1, 10000, 10000),
(44, 16, 2, 1, 4000, 4000),
(45, 17, 6, 2, 7500, 15000),
(46, 18, 5, 2, 12000, 24000),
(47, 18, 4, 1, 10000, 10000),
(48, 19, 2, 2, 4000, 8000),
(49, 20, 5, 1, 12000, 12000),
(50, 20, 6, 1, 7500, 7500),
(51, 20, 2, 1, 4000, 4000),
(52, 21, 2, 1, 4000, 4000),
(53, 22, 3, 1, 6000, 6000),
(54, 22, 2, 1, 4000, 4000),
(55, 23, 9, 1, 35000, 35000),
(56, 24, 1, 1, 15000, 15000),
(57, 24, 3, 1, 6000, 6000),
(58, 24, 5, 1, 12000, 12000),
(59, 24, 4, 1, 10000, 10000),
(60, 1, 7, 3, 8000, 24000),
(61, 2, 2, 1, 4000, 4000),
(62, 3, 2, 3, 4000, 12000),
(63, 4, 6, 1, 7500, 7500),
(64, 4, 5, 1, 12000, 12000),
(65, 4, 3, 1, 6000, 6000),
(66, 29, 4, 1, 10000, 10000),
(67, 30, 3, 3, 6000, 18000),
(68, 31, 2, 3, 4000, 12000),
(69, 32, 4, 4, 10000, 40000);

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aksi` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aksi`, `tanggal`) VALUES
(1, 3, 'Melakukan transaksi baru senilai Rp 24,000', '2026-02-12 04:37:46'),
(2, 1, 'Melakukan transaksi baru senilai Rp 4,000', '2026-02-12 04:44:13'),
(3, 1, 'Melakukan transaksi baru senilai Rp 12,000', '2026-02-24 08:35:08'),
(4, 1, 'Melakukan transaksi baru senilai Rp 25,500', '2026-02-24 08:38:53'),
(5, 1, 'Melakukan transaksi baru #29 senilai Rp 10,000', '2026-02-24 08:49:21'),
(6, 3, 'Melakukan transaksi baru #30 senilai Rp 18,000', '2026-02-24 08:57:29'),
(7, 1, 'Menambah user baru: kasir03 (kasir)', '2026-02-24 09:15:01'),
(8, 4, 'Melakukan transaksi baru #31 senilai Rp 12,000', '2026-02-24 09:15:52'),
(9, 1, 'Menambah user baru: kasirujicoba (kasir)', '2026-02-24 09:24:44'),
(11, 1, 'Menghapus user: kasirujicoba dan membersihkan log-nya', '2026-02-24 09:31:24'),
(12, 1, 'Mengubah data produk: Es Teh Reguler', '2026-02-24 09:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `diskon` int(11) NOT NULL DEFAULT 0,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `bayar` int(11) DEFAULT NULL,
  `kembalian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_user`, `total_harga`, `diskon`, `tanggal`, `metode_pembayaran`, `bayar`, `kembalian`) VALUES
(1, 0, 28500, 0, '2026-01-31 23:27:57', 'Tunai', 30000, 1500),
(2, 0, 23500, 0, '2026-01-31 23:31:46', 'Tunai', 23500, 0),
(3, 0, 175000, 0, '2026-02-01 00:02:53', 'Tunai', 175000, 0),
(4, 0, 16000, 0, '2026-02-01 08:14:06', 'QRIS', 16000, 0),
(5, 1, 88500, 0, '2026-02-01 15:13:00', 'Tunai', 100000, 11500),
(6, 2, 143500, 0, '2026-02-01 15:36:57', 'Tunai', 200000, 56500),
(7, 1, 6000, 0, '2026-02-01 09:51:04', 'Tunai', 10000, 4000),
(8, 1, 30000, 0, '2026-02-01 10:02:07', 'QRIS', 30000, 0),
(9, 1, 16000, 0, '2026-02-02 23:21:05', 'QRIS', 16000, 0),
(10, 1, 35500, 0, '2026-02-11 11:13:32', 'Tunai', 35500, 0),
(11, 1, 30000, 0, '2026-02-11 11:47:28', 'QRIS', 30000, 0),
(12, 1, 30000, 5000, '2026-02-11 12:22:28', 'Tunai', 30000, 0),
(13, 1, 12000, 0, '2026-02-11 12:27:46', 'Tunai', 15000, 3000),
(14, 1, 7500, 0, '2026-02-11 12:30:28', 'Tunai', 10000, 2500),
(15, 1, 10000, 0, '2026-02-11 12:33:17', 'QRIS', 10000, 0),
(16, 1, 4000, 0, '2026-02-11 12:35:16', 'Tunai', 4000, 0),
(17, 1, 15000, 0, '2026-02-11 12:46:03', 'Tunai', 15000, 0),
(18, 1, 34000, 0, '2026-02-11 12:54:40', 'Tunai', 35000, 1000),
(19, 1, 8000, 0, '2026-02-11 12:56:37', 'Transfer', 8000, 0),
(20, 1, 23500, 0, '2026-02-11 22:07:00', 'Tunai', 25000, 1500),
(21, 1, 4000, 0, '2026-02-11 22:08:50', 'Transfer', 4000, 0),
(22, 1, 10000, 0, '2026-02-11 22:14:41', 'Transfer', 10000, 0),
(23, 1, 35000, 0, '2026-02-11 22:15:19', 'Tunai', 35000, 0),
(24, 3, 43000, 0, '2026-02-11 22:19:19', 'Transfer', 43000, 0),
(25, 3, 24000, 0, '2026-02-11 22:37:46', 'Transfer', 24000, 0),
(26, 1, 4000, 0, '2026-02-11 22:44:13', 'Tunai', 4000, 0),
(27, 1, 12000, 0, '2026-02-24 02:35:08', 'Transfer', 12000, 0),
(28, 1, 25500, 0, '2026-02-24 02:38:53', 'Tunai', 25500, 0),
(29, 1, 10000, 0, '2026-02-24 02:49:21', 'Tunai', 10000, 0),
(30, 3, 18000, 0, '2026-02-24 02:57:29', 'Transfer', 18000, 0),
(31, 4, 12000, 0, '2026-02-24 03:15:52', 'Tunai', 12000, 0),
(32, 0, 40000, 0, '2026-02-24 09:31:24', 'Tunai', 40000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga_modal` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `kategori`, `harga_modal`, `harga`, `stok`, `foto`) VALUES
(1, 'Kopi Susu', 'Minuman', 11500, 15000, 30, 'images.jpg'),
(2, 'Es Teh Reguler', 'Minuman', 3000, 4000, 50, 'es-teh-manis-segar-foto-resep-utama.jpg'),
(3, 'Es Teh Jumbo', 'Minuman', 4000, 6000, 30, 'whatsapp-image-2025-05-18-at-22-38-02-6829ff3734777c0232205382.jpeg'),
(4, 'Kentang Goreng', 'Snack', 8500, 10000, 18, '632159cd811f2.jpg'),
(5, 'Nugget', 'Snack', 10000, 12000, 17, '013238200_1675179244-shutterstock_1681721086.jpg'),
(6, 'Mie Goreng Biasa', 'Makanan', 5000, 7500, 40, 'indomie-goreng.jpg'),
(7, 'Mie Kuah Soto', 'Makanan', 6000, 8000, 34, '2494a.jpg'),
(8, 'Mie Kuah Bangladesh', 'Makanan', 8000, 9500, 36, NULL),
(9, 'Kopi V60', 'Minuman', 32000, 35000, 10, NULL),
(10, 'Americano', 'Minuman', 10500, 12000, 23, NULL),
(12, 'Mie Goreng Geprek', 'Makanan', 8000, 9500, 38, NULL),
(13, 'Mie Nyemek', 'Makanan', 13000, 15000, 18, NULL),
(14, 'Nutrisari Mangga', 'Minuman', 5000, 6000, 20, NULL),
(15, 'Wedang Jahe', 'Minuman', 8000, 10000, 22, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `level`) VALUES
(1, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'Kasir#01', 'kasir1', 'b433703d252fb9d965d18d14e2985080', 'kasir'),
(3, 'Kasir#02', 'kasir2', 'f6133e87e2290a17f375b7ba50105b54', 'kasir'),
(4, 'Kasir#03', 'kasir03', 'e6169988f4cf878500fef6a756eb0c8f', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
