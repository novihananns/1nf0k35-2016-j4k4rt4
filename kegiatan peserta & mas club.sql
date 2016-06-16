-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 16 Jun 2016 pada 08.03
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
-- Struktur dari tabel `data_kegiatan_peserta`
--

CREATE TABLE IF NOT EXISTS `data_kegiatan_peserta` (
  `id_data_kegiatan` varchar(13) NOT NULL,
  `no_kartu` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `sex` varchar(100) DEFAULT NULL,
  `jenis_peserta` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_kegiatan_peserta`
--

INSERT INTO `data_kegiatan_peserta` (`id_data_kegiatan`, `no_kartu`, `nama`, `sex`, `jenis_peserta`, `tgl_lahir`) VALUES
('172020101001', '0000039513723', 'Irma jaya', '5', 'Jawa barat', '1966-05-13'),
('172020101001', '0000039513734', 'Siti aminah', '6', 'Jawa tengah', '1965-01-04'),
('172020101001', '0000168149384', 'idrus jamalullail', '5', 'betawi', '1999-04-01'),
('172020101001', '0000369447827', 'moch fadhol', '5', 'jawa', '1971-04-15'),
('172020101001', '0001110538337', 'MARYANI', '6', 'betawi', '1979-03-18'),
('172020101001', '0001125175948', 'Umar', '5', 'Betawi', '1984-12-30'),
('172020101001', '0001215782627', 'YUYUN YULIATI', '6', 'apa', '1980-07-01'),
('172020101001', '0001216014917', 'Dian Ayu Wulandari', '6', 'jawa', '1995-06-03'),
('172020101001', '0001217642501', 'Liman', '5', 'Betawi', '1970-08-27'),
('172020101001', '0001217642578', 'Etty Rohayati', '6', 'Betawi', '1975-02-07'),
('172020101001', '0001218691664', 'mussalim ridho', '5', 'betawi', '2006-04-30'),
('172020101001', '0001283450466', 'Warno', '5', 'Jawa', '1953-06-15'),
('172020101001', '0001283450578', 'Ruminah', '6', 'Jawa', '1957-03-16'),
('172020101001', '0001283458961', 'ros acih', '6', 'sunda', '1982-03-04'),
('172020101001', '0001283459589', 'GIYEM', '6', 'jawa', '1971-04-20'),
('172020101001', '0001283459758', 'Aura Aji Mulyawan', '5', 'jawa', '2002-09-14'),
('172020101001', '0001283465024', 'Rumsiti', '6', 'Jawa', '1985-09-08'),
('172020101001', '0001459658327', 'ROHMAN', '5', 'JAWA', '1973-05-07'),
('172020101001', '0001459658439', 'RINI', '6', 'BETAWI', '1978-02-08'),
('172020101001', '0001459658518', 'MIFTAKHUR RIZQI', '5', 'BETAWI', '1999-03-21'),
('172020101001', '0001459658597', 'MUHAMAD ANNUR ROFIQ', '5', 'BETAWI', '2002-12-19'),
('172020101001', '0002058390988', 'MASRIL', '5', 'betawi', '1949-10-31'),
('172020101001', '0002058394533', 'ISAH', '6', 'jawa', '1956-10-25');

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
  `ketua_nama` varchar(100) DEFAULT NULL,
  `provider` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mas_club`
--

INSERT INTO `mas_club` (`clubId`, `kdProgram`, `tglMulai`, `tglAkhir`, `alamat`, `nama`, `ketua_noHP`, `ketua_nama`, `provider`) VALUES
('12248', '02', '2016-01-04', '0000-00-00', 'Jl. Kayu Manis II, Kec. Matraman', 'PKM KEL KAYU MANIS', '081', 'DR HERY', 'P3172100202'),
('12249', '02', '2016-01-04', '0000-00-00', 'Jl. Pegayoman Komp. Kehakiman, Kec. Matraman', 'PKM KEL UTAN KAYU UTARA', '081', 'DR HERY M ZAINAL', 'P3172100203'),
('12250', '02', '2016-01-04', '0000-00-00', '	 Jl. Galur Sari Raya, Kec. Matraman', 'PKM KEL UTAN KAYU SELATAN II', '081', 'DR HERRY M ZAINAL', 'P3172100207'),
('12252', '02', '2016-01-04', '0000-00-00', 'Jl. Palmeriem, Kec. Matraman', 'PKM KEL PALMARAIAM', '081', 'DR HERRY M ZAINAL', 'P3172100206'),
('1804', '01', '2015-04-08', '0000-00-00', 'Kelurahan Kayu Putih', 'PKM Kel Kayu Putih', '0', 'dr Lida Nurhisan', 'P3172090202'),
('1805', '01', '2014-01-01', '0000-00-00', 'Jl Kayu Putih', 'PKM Kec Pulo Gadung', '081295219789', 'dr Wayan Suwastini', 'P3172090201'),
('1806', '01', '2014-01-01', '0000-00-00', 'Kelurahan Susukan', 'PKM Kec Ciracas', '0818662298', 'dr. Trianawati', 'P3172020101'),
('1807', '02', '2014-01-01', '0000-00-00', 'Kelurahan Susukan', 'PKM KeC Ciracas', '0818662298', 'dr Trianawati', 'P3172020101'),
('1809', '02', '2014-01-01', '0000-00-00', 'Kecamatan Matraman', 'PKM Kec Matraman', '0817850838', 'dr Herry Zainail', 'P3172100201'),
('1810', '02', '2014-01-01', '0000-00-00', 'Jl H Dogol', 'PKM Kec Duren Sawit', '082113910048', 'Yossy Yunilaris', 'P3172070101'),
('1811', '02', '2014-01-01', '0000-00-00', 'Kramat Jati', 'PKM Kec Kramat Jati', '0811886180', 'dr Citra', 'P3172050101'),
('1813', '02', '2015-04-21', '0000-00-00', 'Kel Gedong', 'PKM Kel Gedong', '081808892302', 'dr Dwi Listyorini', 'P3172010203'),
('1827', '02', '2014-01-01', '0000-00-00', 'Kel Malaka Jaya', 'PKM Kel Malaka Jaya', '082113910048', 'Yossy Yulinaris', 'P3172070208'),
('1863', '01', '2015-04-07', '0000-00-00', 'Kelurahan Pekayon', 'PKM Kel Pekayon', '081584062132', 'drg Titik', 'P3172010206'),
('1867', '01', '2015-05-21', '0000-00-00', 'Kalisari', 'PKM Kel Kalisari', '08129025870', 'dr Usdarwati', 'P3172010205'),
('1869', '01', '2015-04-19', '0000-00-00', 'Gedong', 'PKM Kel Gedong', '081808892302', 'dr Dwi Listiyorini', 'P3172010203'),
('1870', '01', '2015-05-07', '0000-00-00', 'Ciracas', 'PKM Kel Ciracas', '085642246885', 'dr Fitria', 'P3172020203'),
('1872', '01', '2015-04-09', '0000-00-00', 'Kampung Baru', 'PKM Kel Baru', '081310397756', 'dr Winarni', 'P3172010202'),
('1873', '01', '2014-12-11', '0000-00-00', 'Jati', 'PKM Kel Jati II', '081295219789', 'dr Wayan', 'P3172090204'),
('1874', '01', '2014-01-01', '0000-00-00', 'Makasar', 'PKM Kec Makasar', '081586276669', 'Lilik', 'P3172040201'),
('1875', '01', '2014-01-01', '0000-00-00', 'Jl Raya Kalisari Pasar Rebo', 'PKM Kec Pasar Rebo', '082111360024', 'Nanda', 'P3172010101'),
('1877', '01', '2015-03-04', '0000-00-00', 'Cipinang', 'PKM Kel Cipinang', '081348855628', 'dr Kirana', 'P3172090208'),
('1878', '01', '2015-04-06', '0000-00-00', 'Pisanagn Timur', 'PKM Kel PisTim II', '081', 'dr Wayan', 'P3172090207'),
('1880', '01', '2014-08-01', '0000-00-00', 'Jl Jeruk', 'PKM Kel Rawamangun', '081', 'dr Wayan', 'P3172090205'),
('1931', '01', '2015-02-01', '0000-00-00', 'Kelurahan Jati I', 'PKM Kel Jati I', '081', 'dr Wayan', 'P3172090203'),
('2331', '01', '2015-02-01', '0000-00-00', 'Jatinegara Kaum', 'PKM Kel Jatinegara Kaum', '081', 'dr Wayan', 'P3172090209'),
('2490', '02', '2015-05-19', '0000-00-00', 'Kel Ciracas', 'KEL CIRACAS', '081', 'dr Fitria', 'P3172020203'),
('2648', '01', '2014-01-01', '0000-00-00', 'Kelurahan Makasar', 'PKM Kel Makasar', '081586276669', 'Ibu Lilik', 'P3172040207'),
('2649', '01', '2014-01-01', '0000-00-00', 'Kebon Pala', 'PKM Kel Kebon Pala', '081586276669', 'Ibu Lilik', 'P3172040205'),
('2652', '01', '2014-01-01', '0000-00-00', 'Kelapa Dua Wetan', 'PKM Kel Kelapa Dua Wetan', '081385070870', 'dr Indira', 'P3172020206'),
('2653', '02', '2014-01-01', '0000-00-00', 'Kelapa Dua Wetna', 'PKM Kel Kelapa Dua Wetan', '081', 'dr Indira', 'P3172020206'),
('2655', '01', '2014-11-01', '0000-00-00', 'Pisangan Timur', 'PKM KEL PIS TIM I', '081', 'dr Wigati', 'P3172090206'),
('2656', '01', '2014-01-03', '0000-00-00', 'Matraman', 'PKM Kec Matraman', '081', 'dr Hery', 'P3172100201'),
('2735', '01', '2015-01-02', '0000-00-00', 'Jl. Skip Ujung Rt 0010/07, Kec. Matraman', 'PKM kel Utan Kayu Selatan I', '081', 'dr Herry', 'P3172100205'),
('2819', '02', '2015-04-02', '0000-00-00', 'Kelurahan Baru', 'PKM Kel Baru', '081', 'dr Retno', 'P3172010202'),
('2820', '02', '2015-04-01', '0000-00-00', 'Pasar Rebo', 'PKM Kel Pasar Rebo', '081', 'Nanda', 'P3172010101'),
('2821', '02', '2015-04-01', '0000-00-00', 'Cijantung', 'PKM Kel Cijantung', '081', 'dr Neneng', 'P3172010204'),
('2822', '02', '2015-04-01', '0000-00-00', 'Pekayon', 'PKM Kel Pekayon', '081', 'dr Neneng', 'P3172010206'),
('2823', '02', '2015-04-01', '0000-00-00', 'Cibubur', 'PKM Kel Cibubur', '081', 'dr Nia', 'P3172020204'),
('2824', '02', '2015-05-21', '0000-00-00', 'Kalisari', 'Kel Kalisari', '081', 'Nanda', 'P3172010205'),
('2845', '02', '2015-04-01', '0000-00-00', 'Kelurahan Utan Kayu Selatan ', 'PKM Kel Utan Kayu Selatan I', '081', 'dr Herry', 'P3172100205'),
('2846', '01', '2014-01-02', '0000-00-00', 'Duren Sawit', 'PKM Kec Duren Sawit', '081', 'Yossy', 'P3172070101'),
('2847', '01', '2014-01-02', '0000-00-00', 'Malaka Sari', 'PKM KEL MALAKA SARI', '081', 'Yossy', 'P3172070209'),
('2848', '01', '2014-01-02', '0000-00-00', 'Klender II', 'PKM Kel Klender II', '081', 'Yossy', 'P3172070205'),
('2849', '01', '2014-01-02', '0000-00-00', 'Pondok Bambu I', 'PKM KEL PONDOK BAMBU I', '081', 'Yossy', 'P3172070202'),
('2850', '01', '2014-01-02', '0000-00-00', 'Pondok Bambu II', 'PKM Kel Pondok Bambu II', '081', 'Yossy ', 'P3172070203'),
('2851', '01', '2014-01-02', '0000-00-00', 'Klender I', 'PKM Kel Klender I', '081', 'Yossy', 'P3172070204'),
('2852', '01', '2014-01-02', '0000-00-00', 'Klender', 'PKM Kel Klender III', '081', 'Yossy', 'P3172070206'),
('2853', '01', '2014-01-02', '0000-00-00', 'Kel Duren Sawit', 'PKM Kel Duren Sawit', '081', 'Yossy', 'P3172070207'),
('2854', '01', '2014-01-02', '0000-00-00', 'Malaka Jaya', 'PKM Kel Malaka Jaya', '081', 'Yossy ', 'P3172070208'),
('2855', '01', '2014-01-02', '0000-00-00', 'Pd Kopi', 'PKM Kel Pondok Kopi I', '081', 'Yossy', 'P3172070210'),
('2857', '02', '2014-01-02', '0000-00-00', 'Kelurahan Pondok Bambu I', 'PKM Kel Pondok Bambu I', '081', 'Yossy', 'P3172070202'),
('2858', '02', '2014-01-02', '0000-00-00', 'Pondok Bambu', 'PKM Kel Pondok Bambu II', '081', 'Yossy', 'P3172070203'),
('2859', '02', '2014-01-02', '0000-00-00', 'Klender', 'PKM Kel Klender I', '081', 'Yossy', 'P3172070204'),
('2860', '02', '2014-01-02', '0000-00-00', 'Klender', 'PKM Klender II', '081', 'Yossy', 'P3172070205'),
('2861', '02', '2014-01-02', '0000-00-00', 'Klender III', 'PKM Kel Klender III', '081', 'Yossy', 'P3172070206'),
('2862', '02', '2014-01-02', '0000-00-00', 'Duren Sawit', 'PKM Kel Duren Sawit', '081', 'Yossy', 'P3172070207'),
('2863', '02', '2014-01-02', '0000-00-00', 'Malaka Sari', 'PKM Kel Malaka Sari', '081', 'Yossy', 'P3172070209'),
('2864', '02', '2014-01-02', '0000-00-00', 'Pondok Kopi', 'PKM Kel Pondok Kopi  I', '081', 'Yossy', 'P3172070210'),
('2893', '01', '2015-04-01', '0000-00-00', 'CIJANTUNG', 'PKM KEL CIJANTUNG', '081', 'dr Neneng', 'P3172010204'),
('3053', '01', '2015-09-01', '0000-00-00', 'Kel Rambutan', 'PKM Kel Rambutan', '081', 'dr Nancy', 'P3172020202'),
('3054', '01', '2015-09-01', '0000-00-00', 'Kel Cibubur', 'PKM Kel Cibubur', '081', 'dr Rita', 'P3172020204'),
('3113', '01', '2015-07-01', '0000-00-00', 'Kp Tengah', 'PKM Kel Kampung Tengah', '081', 'dr Aulia', 'P3172050208'),
('3114', '01', '2014-01-01', '0000-00-00', 'Jl RS Polro', 'PKM Kec Kramat Jati', '081', 'dr Citra', 'P3172050101'),
('3115', '02', '2015-07-01', '0000-00-00', 'AJL RS POLRI', 'PKM KEL TENGAH', '081', 'DR AULIA', 'P3172050208'),
('3116', '02', '2015-05-01', '0000-00-00', 'DUKUH', 'PKM KEL DUKUH', '081', 'DR CITRA', 'P3172050209'),
('3117', '02', '2015-02-01', '0000-00-00', 'BATU AMPAR', 'PKM KEL BATU AMPAR', '081', 'DR CITRA', 'P3172050206'),
('7177', '01', '2016-01-04', '0000-00-00', 'Jl. Skip Ujung Rt 0010/07, Kec. Matraman', 'PKM KEL KAYU MANIS', '081', 'dr Herry M Zainal', 'P3172100202'),
('7178', '01', '2016-01-04', '0000-00-00', 'Jl. Pegayoman Komp. Kehakiman, Kec. Matraman', 'PKM KEL UTAN KAYU UTARA', '081', 'dr Herry M Zainal', 'P3172100203'),
('7179', '01', '2016-01-04', '0000-00-00', 'Jl. Galur Sari Raya, Kec. Matraman', 'PKM KEL UTAN KAYU SELATAN II', '081', 'dr Herry M zainal', 'P3172100207'),
('7182', '01', '2016-01-04', '0000-00-00', 'Jl. Palmeriem, Kec. Matraman', 'PKM KEL PALMARIAM', '081', 'dr Herry M Zainal', 'P3172100206'),
('7236', '01', '2015-12-01', '0000-00-00', 'Jl. Batu Ampar II Wadas Rt 008/03, Kec. Kramat Jati', 'PKM KEL BATU AMPAR', '081', 'RENNY', 'P3172050206'),
('7626', '01', '2015-02-15', '0000-00-00', 'JL GARDU', 'PKM KEL BALE KAMBANG', '081', 'dr Melda', 'P3172050207'),
('9390', '02', '2015-10-01', '0000-00-00', 'Jl. H. Jenih, Kec. Ciracas', 'PKM KEL RAMBUTAN', '081', 'DR NANCY', 'P3172020202'),
('9947', '02', '2015-09-01', '0000-00-00', 'Jl. Gardu Rt 001/02, Kec. Kramat Jati', 'PKM KEL BALE KAMBANG', '081', 'dr Melda', 'P3172050207');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_kegiatan_peserta`
--
ALTER TABLE `data_kegiatan_peserta`
  ADD PRIMARY KEY (`id_data_kegiatan`,`no_kartu`);

--
-- Indexes for table `mas_club`
--
ALTER TABLE `mas_club`
  ADD PRIMARY KEY (`clubId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
