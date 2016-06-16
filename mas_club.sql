-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 16 Jun 2016 pada 03.17
-- Versi Server: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epus_prog_3172`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `mas_club`
--

CREATE TABLE IF NOT EXISTS `mas_club` (
  `clubId` varchar(5) NOT NULL,
  `kdProgram` varchar(2) DEFAULT NULL,
  `tglMulai` date DEFAULT NULL,
  `tglAkhir` date DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `ketua_noHP` varchar(45) DEFAULT NULL,
  `ketua_nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mas_club`
--

INSERT INTO `mas_club` (`clubId`, `kdProgram`, `tglMulai`, `tglAkhir`, `alamat`, `nama`, `ketua_noHP`, `ketua_nama`) VALUES
('01', '01', NULL, NULL, 'alamat club 1', 'Club DM satu', '085720127801', 'Bambang'),
('34', '01', NULL, NULL, 'alamat club dm 2', 'club DM dua', '085720293132', 'Susi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mas_club`
--
ALTER TABLE `mas_club`
  ADD PRIMARY KEY (`clubId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
