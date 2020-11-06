-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT '0',
  `harga_barang` double DEFAULT NULL,
  `satuan_barang_id` int(11) DEFAULT NULL,
  `jenis_barang_id` int(11) DEFAULT NULL,
  `kategori_barang_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_barang_id` (`jenis_barang_id`),
  KEY `kategori_barang_id` (`kategori_barang_id`),
  KEY `satuan_barang_id` (`satuan_barang_id`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`jenis_barang_id`) REFERENCES `jenis_barang` (`id`),
  CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`kategori_barang_id`) REFERENCES `kategori_barang` (`id`),
  CONSTRAINT `barang_ibfk_3` FOREIGN KEY (`satuan_barang_id`) REFERENCES `satuan_barang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `history_stock_barang`;
CREATE TABLE `history_stock_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `history_stock_barang_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_barang`;
CREATE TABLE `jenis_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_linen`;
CREATE TABLE `jenis_linen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `kategori_barang`;
CREATE TABLE `kategori_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `linen`;
CREATE TABLE `linen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trs_serah_terima_id` int(11) DEFAULT NULL,
  `spesifikasi_id` int(11) DEFAULT NULL,
  `jenis_linen_id` int(11) DEFAULT NULL,
  `hitung_lidi` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trs_serah_terima_id` (`trs_serah_terima_id`),
  KEY `spesifikasi_id` (`spesifikasi_id`),
  KEY `jenis_linen_id` (`jenis_linen_id`),
  CONSTRAINT `linen_ibfk_1` FOREIGN KEY (`trs_serah_terima_id`) REFERENCES `trs_serah_terima` (`id`),
  CONSTRAINT `linen_ibfk_2` FOREIGN KEY (`spesifikasi_id`) REFERENCES `spesifikasi` (`id`),
  CONSTRAINT `linen_ibfk_3` FOREIGN KEY (`jenis_linen_id`) REFERENCES `jenis_linen` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `nip` char(50) DEFAULT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ruangan`;
CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `satuan_barang`;
CREATE TABLE `satuan_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `spesifikasi`;
CREATE TABLE `spesifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `status_serah_terima`;
CREATE TABLE `status_serah_terima` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `trs_barang_masuk`;
CREATE TABLE `trs_barang_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `trs_barang_masuk_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `trs_pemakaian_barang`;
CREATE TABLE `trs_pemakaian_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `trs_pemakaian_barang_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `trs_serah_terima`;
CREATE TABLE `trs_serah_terima` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_trs` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `tanggal_pengiriman` datetime DEFAULT NULL,
  `ruangan_id` int(11) DEFAULT NULL,
  `petugas_pengambil` int(11) DEFAULT NULL,
  `petugas_pencuci` int(11) DEFAULT NULL,
  `petugas_penyetrika` int(11) DEFAULT NULL,
  `petugas_pendistribusi` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `petugas_pengambil` (`petugas_pengambil`),
  KEY `petugas_pencuci` (`petugas_pencuci`),
  KEY `petugas_penyetrika` (`petugas_penyetrika`),
  KEY `petugas_pendistribusi` (`petugas_pendistribusi`),
  KEY `ruangan_id` (`ruangan_id`),
  CONSTRAINT `trs_serah_terima_ibfk_1` FOREIGN KEY (`petugas_pengambil`) REFERENCES `pegawai` (`id`),
  CONSTRAINT `trs_serah_terima_ibfk_2` FOREIGN KEY (`petugas_pencuci`) REFERENCES `pegawai` (`id`),
  CONSTRAINT `trs_serah_terima_ibfk_3` FOREIGN KEY (`petugas_penyetrika`) REFERENCES `pegawai` (`id`),
  CONSTRAINT `trs_serah_terima_ibfk_4` FOREIGN KEY (`petugas_pendistribusi`) REFERENCES `pegawai` (`id`),
  CONSTRAINT `trs_serah_terima_ibfk_5` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2020-11-06 12:36:47
