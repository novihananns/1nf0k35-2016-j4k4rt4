
CREATE TABLE IF NOT EXISTS `data_kegiatan` (
  `id_data_kegiatan` varchar(13) NOT NULL,
  `tgl` date DEFAULT NULL,
  `kode_kelompok` varchar(10) DEFAULT NULL,
  `kode_club` varchar(10) DEFAULT NULL,
  `status_penyuluhan` int(11) DEFAULT NULL,
  `status_senam` int(11) DEFAULT NULL,
  `materi` varchar(200) DEFAULT NULL,
  `pembicara` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `biaya` double(20,2) DEFAULT NULL,
  `keterangan` text,
  `eduId` varchar(20) DEFAULT NULL,
  `code_cl_phc` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id_data_kegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_kegiatan`
--

INSERT INTO `data_kegiatan` (`id_data_kegiatan`, `tgl`, `kode_kelompok`, `kode_club`, `status_penyuluhan`, `status_senam`, `materi`, `pembicara`, `lokasi`, `biaya`, `keterangan`, `eduId`, `code_cl_phc`) VALUES
('3172040201001', '2016-06-16', '01', '1874', 1, 1, 'po', 'po', 'po', 11.00, 'pp', '16060003951', NULL),
('3172040201002', '2016-06-16', '01', '1874', 1, 0, 'oi', 'oi', 'oi', 89.00, 'i', '', NULL),
('3172040201003', '2016-06-16', '01', '1874', 0, 1, 'asd', 'd', 'd', 11.00, 'd', '16060003949', 'P3172040201'),
('3172040201004', '2016-06-16', '00', '0', 1, 0, 'jkhkjh', 'kjh', 'kjhkjjkh', 890.00, 'kjhkj', NULL, 'P3172040201');

-- --------------------------------------------------------

--
-- Table structure for table `data_kegiatan_peserta`
--

CREATE TABLE IF NOT EXISTS `data_kegiatan_peserta` (
  `id_data_kegiatan` varchar(13) NOT NULL,
  `no_kartu` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `sex` varchar(2) DEFAULT NULL,
  `jenis_peserta` varchar(30) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `eduId` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_data_kegiatan`,`no_kartu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_kegiatan_peserta`
--

INSERT INTO `data_kegiatan_peserta` (`id_data_kegiatan`, `no_kartu`, `nama`, `sex`, `jenis_peserta`, `tgl_lahir`, `eduId`) VALUES
('3172040201001', '0001101972328', 'ALAMSAH', 'L', 'TNI ANGKATAN DARAT', '1979-06-26', NULL),
('3172040201001', '0001459658327', 'ROHMAN', 'L', 'PEKERJA MANDIRI', '1973-05-07', NULL),
('3172040201001', '0001459658439', 'RINI', 'P', 'PEKERJA MANDIRI', '1978-02-08', NULL),
('3172040201001', '0001459658518', 'MIFTAKHUR RIZQI', 'L', 'PEKERJA MANDIRI', '1999-03-21', NULL),
('3172040201001', '0002058390988', 'MASRIL', 'L', 'TNI ANGKATAN LAUT', '1949-10-31', NULL),
('3172040201002', '0001101972328', 'ALAMSAH', 'L', 'TNI ANGKATAN DARAT', '1979-06-26', NULL),
('3172040201002', '0001101972339', 'S O D R I', 'L', 'TNI ANGKATAN DARAT', '1979-12-09', NULL),
('3172040201003', '0001459658597', 'MUHAMAD ANNUR ROFIQ', '5', 'PEKERJA MANDIRI', '2002-12-19', NULL);

