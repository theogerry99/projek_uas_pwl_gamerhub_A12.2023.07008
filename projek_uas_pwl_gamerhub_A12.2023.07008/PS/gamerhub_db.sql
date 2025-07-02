-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2025 at 06:43 AM
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
-- Database: `gamerhub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_sewa`
--

CREATE TABLE `detail_sewa` (
  `id_detail_sewa` int(11) NOT NULL,
  `id_sewa` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `harga_saat_sewa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_sewa`
--

INSERT INTO `detail_sewa` (`id_detail_sewa`, `id_sewa`, `id_produk`, `jumlah`, `harga_saat_sewa`) VALUES
(8, 4, 4, 1, 30000.00),
(9, 4, 2, 1, 25000.00),
(10, 4, 6, 1, 100000.00),
(11, 5, 10, 1, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tipe` enum('game','ps') NOT NULL,
  `harga_sewa_harian` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 1,
  `gambar` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `deskripsi`, `tipe`, `harga_sewa_harian`, `stok`, `gambar`) VALUES
(1, 'Red Dead Redemption 2', 'Action, Adventure, Open World', 'game', 30000.00, 5, 'game_rdr2.jpg'),
(2, 'EA FC 25', 'Sports, Football Simulation', 'game', 25000.00, 8, 'game_fc25.jpg'),
(3, 'Marvel\'s Spider-Man 2', 'Action, Superhero, Open World', 'game', 35000.00, 4, 'game_spiderman2.jpg'),
(4, 'Elden Ring', 'Action, RPG, Souls-like', 'game', 30000.00, 3, 'game_eldenring.jpg'),
(5, 'PlayStation 5 Pro', 'Konsol generasi terbaru, support 8K', 'ps', 120000.00, 3, 'ps5_pro.jpg'),
(6, 'PlayStation 5 Slim', 'Desain lebih ramping, 1TB SSD', 'ps', 100000.00, 5, 'ps5_slim.jpg'),
(7, 'PlayStation 4 Pro', 'Performa lebih kencang, 1TB HDD', 'ps', 75000.00, 7, 'ps4_pro.jpg'),
(8, 'PlayStation VR2 Bundle', 'Paket VR lengkap dengan controller Sense', 'ps', 150000.00, 2, 'ps_vr2.jpg'),
(9, 'Minecraft', 'Action, Advanture, Open world', 'game', 12000.00, 7, 'game_minecraft.jpg'),
(10, 'Gta V', 'Open World, Crime, Action', 'game', 20000.00, 10, 'game_gta5.jpg'),
(11, 'PS 3', 'Performa lebih Cepat, 500GB HDD', 'ps', 25000.00, 20, 'ps3.jpg'),
(12, 'Watch Dog 1', 'Action, Open World, Crime', 'game', 15000.00, 10, 'game_watchdog1.jpg'),
(13, 'Need For Speed Most Wanted', 'Open World, Racing, Car', 'game', 5000.00, 20, 'game_nfs.jpg'),
(14, 'PS 2', 'Konsol Nostalgia, 32GB', 'ps', 12000.00, 30, 'ps2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sewa`
--

CREATE TABLE `sewa` (
  `id_sewa` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_sewa` datetime DEFAULT NULL,
  `tanggal_kembali_rencana` datetime DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status` enum('keranjang','disewa','selesai','dibatalkan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sewa`
--

INSERT INTO `sewa` (`id_sewa`, `id_user`, `tanggal_sewa`, `tanggal_kembali_rencana`, `total_harga`, `status`, `created_at`) VALUES
(1, 1, '2025-06-30 01:30:02', '2025-07-01 01:30:02', 130000.00, 'disewa', '2025-06-29 23:18:45'),
(2, 1, '2025-06-30 04:13:13', '2025-07-02 04:13:13', 300000.00, 'disewa', '2025-06-30 02:11:00'),
(3, 2, NULL, NULL, NULL, 'keranjang', '2025-06-30 02:19:40'),
(4, 3, '2025-06-29 21:38:09', '2025-06-30 21:38:09', 155000.00, 'disewa', '2025-06-29 19:36:47'),
(5, 4, NULL, NULL, NULL, 'keranjang', '2025-06-30 06:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `password`, `no_hp`, `alamat`, `role`, `created_at`) VALUES
(1, 'aa', 'aaa@gmail.com', '$2y$10$R0Ndu5SHuLcXNxEo2Sr71efcp7zqFXY1VzOi0hg1c132sT4EXA7xC', '12356789', 'aaaaaaaaaaaaaaaaaaaaa', 'user', '2025-06-29 23:10:49'),
(2, 'hamit', 'hamitgeming@gmail.com', '$2y$10$jhnn6tOWuKoQ71x8KdA/suTMEMpn0XVHJAjs0AkN6mA2J/Bmbbzuu', '0987654321', 'KUDUS PLAT K', 'user', '2025-06-30 02:19:03'),
(3, 'geri geming', 'gerigeming@gmail.com', '$2y$10$ccCkFSLnBIR96jlR1lB0p.KkHeiSzFUb2o0xuzEnXQhq3.TF9eAGi', '09878909', 'semaring', 'user', '2025-06-29 19:34:50'),
(4, 'agung', 'agunggaming@gmail.com', '$2y$10$y4N6YcaPRTXZdOi0T.rfruwQpxSJq5SyrK7r9wJc3Xb6kL4FXfI8K', '082777788889', 'Jl.Udinus 90', 'user', '2025-06-30 06:13:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_sewa`
--
ALTER TABLE `detail_sewa`
  ADD PRIMARY KEY (`id_detail_sewa`),
  ADD KEY `id_sewa` (`id_sewa`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id_sewa`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_sewa`
--
ALTER TABLE `detail_sewa`
  MODIFY `id_detail_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_sewa`
--
ALTER TABLE `detail_sewa`
  ADD CONSTRAINT `detail_sewa_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id_sewa`),
  ADD CONSTRAINT `detail_sewa_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `sewa`
--
ALTER TABLE `sewa`
  ADD CONSTRAINT `sewa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
