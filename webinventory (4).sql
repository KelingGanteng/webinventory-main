-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Des 2024 pada 03.25
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

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
-- Struktur dari tabel `barang_keluar`
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
-- Dumping data untuk tabel `barang_keluar`
--

INSERT INTO `barang_keluar` (`id`, `id_transaksi`, `tanggal`, `kode_barang`, `nama_barang`, `kondisi`, `jumlah`, `total`, `satuan`, `karyawan_id`, `departemen_id`) VALUES
(41, 'TRK-1124003', '2024-11-29', '', '', '', '5', 0.00, '', 0, 0),
(90, 'TRK-1224006', '2024-12-04', 'SF/MIS-002', 'Sahtel Panasonic', 'Baik', '0', 0.00, '', 2, 1);

--
-- Trigger `barang_keluar`
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
-- Struktur dari tabel `barang_masuk`
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
-- Trigger `barang_masuk`
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
-- Struktur dari tabel `barang_retur`
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
-- Dumping data untuk tabel `barang_retur`
--

INSERT INTO `barang_retur` (`id_retur`, `id_transaksi`, `tanggal_retur`, `kode_barang`, `nama_barang`, `jenis_barang`, `kondisi`, `kerusakan_dropdown`, `kerusakan`, `jumlah`, `tujuan`, `karyawan_id`, `departemen_id`) VALUES
('RTR-1224001', 'RTR-1224001', '2024-12-04', '', '', '', 'Baik', NULL, '', 0, '', 0, 1),
('RTR-1224002', 'RTR-1224002', '2024-12-05', 'SF/MIS-002', 'Sahtel Panasonic', '', 'Baik', NULL, '', 0, '', 1, 22),
('RTR-1224003', 'RTR-1224003', '2024-12-05', 'SF/MIS-002', 'Sahtel Panasonic', '', 'Baik', NULL, '', 0, '', 1, 21);

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_karyawan`
--

CREATE TABLE `daftar_karyawan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `departemen_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `departemen`
--

CREATE TABLE `departemen` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gudang`
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
-- Dumping data untuk tabel `gudang`
--

INSERT INTO `gudang` (`id`, `kode_barang`, `nama_barang`, `kondisi`, `jenis_barang`, `jumlah`, `satuan`) VALUES
(65, 'SF/MIS-001', 'Hardisk seagate ', 'Rusak', 'Hardisk', '5', 'Unit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_barang`
--

CREATE TABLE `jenis_barang` (
  `id` int(11) NOT NULL,
  `jenis_barang` varchar(100) NOT NULL,
  `code_barang` varchar(100) NOT NULL,
  `kerusakan_barang` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `jenis_barang`
--

INSERT INTO `jenis_barang` (`id`, `jenis_barang`, `code_barang`, `kerusakan_barang`) VALUES
(37, 'Hardisk', 'SF/MIS-001', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kerusakan_barang`
--

CREATE TABLE `kerusakan_barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `kerusakan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan`
--

CREATE TABLE `satuan` (
  `id` int(11) NOT NULL,
  `satuan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `satuan`
--

INSERT INTO `satuan` (`id`, `satuan`) VALUES
(40, 'Unit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_supplier`
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
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nik`, `nama`, `alamat`, `telepon`, `username`, `password`, `level`, `foto`) VALUES
(10, '3423515151', 'babi', 'fwegweg', '124115153551', 'yudha', '202cb962ac59075b964b07152d234b70', 'member', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`) USING BTREE;

--
-- Indeks untuk tabel `barang_retur`
--
ALTER TABLE `barang_retur`
  ADD PRIMARY KEY (`id_retur`);

--
-- Indeks untuk tabel `daftar_karyawan`
--
ALTER TABLE `daftar_karyawan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departemen_id` (`departemen_id`);

--
-- Indeks untuk tabel `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jenis_barang`
--
ALTER TABLE `jenis_barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_barang` (`code_barang`);

--
-- Indeks untuk tabel `kerusakan_barang`
--
ALTER TABLE `kerusakan_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT untuk tabel `daftar_karyawan`
--
ALTER TABLE `daftar_karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `gudang`
--
ALTER TABLE `gudang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `jenis_barang`
--
ALTER TABLE `jenis_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `kerusakan_barang`
--
ALTER TABLE `kerusakan_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `daftar_karyawan`
--
ALTER TABLE `daftar_karyawan`
  ADD CONSTRAINT `daftar_karyawan_ibfk_1` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
