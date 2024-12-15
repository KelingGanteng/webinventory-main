-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 01:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webinventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `id` int(11) NOT NULL,
  `kode_aset` varchar(50) NOT NULL,
  `jenis_barang_id` int(11) DEFAULT NULL,
  `kode_lengkap` varchar(100) NOT NULL,
  `nama_aset` varchar(255) NOT NULL,
  `departemen_id` int(11) DEFAULT NULL,
  `karyawan_id` int(11) DEFAULT NULL,
  `lokasi` varchar(255) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `kondisi` enum('Baik','Rusak','Perlu Perawatan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aset`
--

INSERT INTO `aset` (`id`, `kode_aset`, `jenis_barang_id`, `kode_lengkap`, `nama_aset`, `departemen_id`, `karyawan_id`, `lokasi`, `status`, `tanggal_pembelian`, `kondisi`) VALUES
(5, 'SF/IT/I', NULL, 'SF/IT/I/Printer/0004', 'edwd', 23, 3, 'kontol', 'Aktif', '2024-12-13', 'Baik'),
(6, 'SF/IT/I', NULL, 'SF/IT/I/Printer/0001', 'dawwfa', 23, 3, 'fawfafw', 'Aktif', '2024-12-11', 'Baik');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id` int(11) NOT NULL,
  `id_transaksi` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` varchar(100) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `jumlah` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `karyawan_id` int(11) NOT NULL,
  `departemen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id`, `id_transaksi`, `tanggal`, `kode_barang`, `nama_barang`, `kondisi`, `jumlah`, `total`, `satuan`, `karyawan_id`, `departemen_id`) VALUES
(41, 'TRK-1124003', '2024-11-29', '', '', '', '5', 0.00, '', 0, 0),
(90, 'TRK-1224006', '2024-12-04', 'SF/MIS-002', 'Sahtel Panasonic', 'Baik', '0', 0.00, '', 2, 1),
(91, 'MIS-1224007', '2024-12-09', 'SF/MIS-001', 'Sahtel LG', 'Baik', '1', 0.00, '', 3, 23);

--
-- Triggers `barang_keluar`
--
DELIMITER $$
CREATE TRIGGER `barang_keluar` AFTER INSERT ON `barang_keluar` FOR EACH ROW BEGIN
	UPDATE gudang SET jumlah = jumlah-new.jumlah
    WHERE kode_barang=new.kode_barang;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int(11) NOT NULL,
  `id_transaksi` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kode_barang` varchar(100) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `kondisi` varchar(100) NOT NULL,
  `jumlah` varchar(150) DEFAULT NULL,
  `satuan` varchar(100) NOT NULL,
  `daftar_karyawan` varchar(250) NOT NULL,
  `departement` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id`, `id_transaksi`, `tanggal`, `kode_barang`, `nama_barang`, `kondisi`, `jumlah`, `satuan`, `daftar_karyawan`, `departement`) VALUES
(104, 'MIS-1224001', '2024-12-10', 'SF/MIS-001', 'Sahtel LG', 'Baik', '1', 'Unit', '', '');

--
-- Triggers `barang_masuk`
--
DELIMITER $$
CREATE TRIGGER `barang_masuk` AFTER INSERT ON `barang_masuk` FOR EACH ROW BEGIN
	UPDATE gudang SET jumlah = jumlah+new.jumlah
    WHERE kode_barang=new.kode_barang;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barang_retur`
--

CREATE TABLE `barang_retur` (
  `id_retur` varchar(20) NOT NULL,
  `id_transaksi` varchar(20) DEFAULT NULL,
  `tanggal_retur` date DEFAULT NULL,
  `kode_barang` varchar(20) DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `jenis_barang` varchar(100) NOT NULL,
  `kondisi` text DEFAULT NULL,
  `kerusakan_dropdown` text DEFAULT NULL,
  `kerusakan` text DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tujuan` varchar(255) DEFAULT NULL,
  `karyawan_id` int(11) NOT NULL,
  `departemen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_retur`
--

INSERT INTO `barang_retur` (`id_retur`, `id_transaksi`, `tanggal_retur`, `kode_barang`, `nama_barang`, `jenis_barang`, `kondisi`, `kerusakan_dropdown`, `kerusakan`, `jumlah`, `tujuan`, `karyawan_id`, `departemen_id`) VALUES
('RTR-1224001', 'RTR-1224001', '2024-12-04', '', '', '', 'Baik', NULL, '', 0, '', 0, 1),
('RTR-1224002', 'RTR-1224002', '2024-12-05', 'SF/MIS-002', 'Sahtel Panasonic', '', 'Baik', NULL, '', 0, '', 1, 22),
('RTR-1224003', 'RTR-1224003', '2024-12-05', 'SF/MIS-002', 'Sahtel Panasonic', '', 'Baik', NULL, '', 0, '', 1, 21);

-- --------------------------------------------------------

--
-- Table structure for table `daftar_karyawan`
--

CREATE TABLE `daftar_karyawan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `departemen_id` int(11) DEFAULT NULL,
  `bagian` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_karyawan`
--

INSERT INTO `daftar_karyawan` (`id`, `nama`, `departemen_id`, `bagian`) VALUES
(3, 'alfin', 23, '');

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`id`, `nama`) VALUES
(23, 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(100) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kondisi` varchar(100) NOT NULL,
  `jenis_barang` varchar(100) NOT NULL,
  `jumlah` varchar(250) NOT NULL,
  `satuan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gudang`
--

INSERT INTO `gudang` (`id`, `kode_barang`, `nama_barang`, `kondisi`, `jenis_barang`, `jumlah`, `satuan`) VALUES
(66, 'SF/MIS-001', 'Sahtel LG', 'Baik', 'sahtel', '5', 'Unit');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_barang`
--

CREATE TABLE `jenis_barang` (
  `id` int(11) NOT NULL,
  `jenis_barang` varchar(100) NOT NULL,
  `code_barang` varchar(100) NOT NULL,
  `kerusakan_barang` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jenis_barang`
--

INSERT INTO `jenis_barang` (`id`, `jenis_barang`, `code_barang`, `kerusakan_barang`) VALUES
(39, 'Printer', 'SF/IT/I', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan_barang`
--

CREATE TABLE `kerusakan_barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `kerusakan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id` int(11) NOT NULL,
  `satuan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id`, `satuan`) VALUES
(40, 'Unit');

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `id` int(100) NOT NULL,
  `kode_supplier` varchar(100) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `telepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nik` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `telepon` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` varchar(25) NOT NULL DEFAULT 'member',
  `foto` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `nama`, `alamat`, `telepon`, `username`, `password`, `level`, `foto`) VALUES
(10, '3423515151', 'babi', 'fwegweg', '124115153551', 'yudha', '202cb962ac59075b964b07152d234b70', 'member', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departemen_id` (`departemen_id`),
  ADD KEY `karyawan_id` (`karyawan_id`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`) USING BTREE;

--
-- Indexes for table `barang_retur`
--
ALTER TABLE `barang_retur`
  ADD PRIMARY KEY (`id_retur`);

--
-- Indexes for table `daftar_karyawan`
--
ALTER TABLE `daftar_karyawan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departemen_id` (`departemen_id`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_barang`
--
ALTER TABLE `jenis_barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_barang` (`code_barang`);

--
-- Indexes for table `kerusakan_barang`
--
ALTER TABLE `kerusakan_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aset`
--
ALTER TABLE `aset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `daftar_karyawan`
--
ALTER TABLE `daftar_karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `gudang`
--
ALTER TABLE `gudang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `jenis_barang`
--
ALTER TABLE `jenis_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `kerusakan_barang`
--
ALTER TABLE `kerusakan_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`),
  ADD CONSTRAINT `aset_ibfk_2` FOREIGN KEY (`karyawan_id`) REFERENCES `daftar_karyawan` (`id`);

--
-- Constraints for table `daftar_karyawan`
--
ALTER TABLE `daftar_karyawan`
  ADD CONSTRAINT `daftar_karyawan_ibfk_1` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
