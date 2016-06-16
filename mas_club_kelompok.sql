-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 16 Jun 2016 pada 08.30
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
-- Struktur dari tabel `mas_club_kelompok`
--

CREATE TABLE IF NOT EXISTS `mas_club_kelompok` (
  `id_mas_club_kelompok` varchar(3) NOT NULL,
  `value` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mas_club_kelompok`
--

INSERT INTO `mas_club_kelompok` (`id_mas_club_kelompok`, `value`) VALUES
('00', 'Non-Prolanis'),
('01', 'Diabetes Melitus'),
('02', 'Hipertensi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mas_club_kelompok`
--
ALTER TABLE `mas_club_kelompok`
  ADD PRIMARY KEY (`id_mas_club_kelompok`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
