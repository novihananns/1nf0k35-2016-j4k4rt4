CREATE TABLE `cl_phc_bpjs` (
  `code` char(11) CHARACTER SET utf8 NOT NULL,
  `server` varchar(200) CHARACTER SET utf8 NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `consid` varchar(20) DEFAULT NULL,
  `secretKey` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
