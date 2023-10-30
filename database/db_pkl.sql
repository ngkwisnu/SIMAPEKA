/*
SQLyog Community v13.1.9 (64 bit)
MySQL - 8.0.30 : Database - db_pkl
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_pkl` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_pkl`;

/*Table structure for table `aktivitas_pkl` */

DROP TABLE IF EXISTS `aktivitas_pkl`;

CREATE TABLE `aktivitas_pkl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `jenis_kegiatan` varchar(100) DEFAULT NULL,
  `uraian_kegiatan` varchar(500) DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `validasi` enum('belum_validasi','sudah_validasi') DEFAULT NULL,
  `pkl_id` int DEFAULT NULL,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nik` (`nik`),
  KEY `pkl_id` (`pkl_id`),
  KEY `nim` (`nim`),
  CONSTRAINT `aktivitas_pkl_ibfk_1` FOREIGN KEY (`nik`) REFERENCES `pembimbing_industri` (`nik`),
  CONSTRAINT `aktivitas_pkl_ibfk_2` FOREIGN KEY (`pkl_id`) REFERENCES `pkl` (`id`),
  CONSTRAINT `aktivitas_pkl_ibfk_3` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `aktivitas_pkl` */

insert  into `aktivitas_pkl`(`id`,`tanggal`,`jenis_kegiatan`,`uraian_kegiatan`,`jam`,`nik`,`validasi`,`pkl_id`,`nim`) values 
(1,'2023-06-18','Pemasangan/Instalasi','Pemasangan Internet 200Mbps di Politeknik Negeri Bali','15:51:00','0012345678','sudah_validasi',1,'2115354075'),
(2,'2023-06-18','Maintenance/Perbaikan','Melakukan Perbaikan Webiste https://pnb.ac.id','08:00:00','0012345678','sudah_validasi',1,'2115354015'),
(3,'2023-06-20','Coding','Membantu dalam integrasi TailwindCSS pada website utama industri.','13:13:00','0012345678','sudah_validasi',1,'2115354015'),
(4,'2023-06-20','Bantuan','Membantu membelikan gorengan.','13:15:00','0012345678','sudah_validasi',1,'2115354015'),
(5,'2023-06-20','Bantuan','Ini teks panjangggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg','13:15:00','0012345678','belum_validasi',1,'2115354015'),
(6,'2023-06-21','Perbaikan Sistem Web E-learning','memperbaiki bug yang ada di web','09:39:00','0012345678','sudah_validasi',1,'2115354075'),
(7,'2023-06-21','Perbaikan jaringan','memperbaiki jaringan lan politeknik','09:40:00','0012345678','belum_validasi',1,'2115354075'),
(8,'2023-06-26','Memperbaiki Web','memperbaiki tampilan web elearninng PNB','10:49:00','0012345678','belum_validasi',1,'2115354075'),
(9,'2023-06-26','Perbaikan','Perbaikan Website','13:47:00','0012345678','belum_validasi',1,'2115354015');

/*Table structure for table `anggota_pkl` */

DROP TABLE IF EXISTS `anggota_pkl`;

CREATE TABLE `anggota_pkl` (
  `pkl_id` int NOT NULL,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  KEY `pkl_id` (`pkl_id`),
  KEY `nim` (`nim`),
  CONSTRAINT `anggota_pkl_ibfk_1` FOREIGN KEY (`pkl_id`) REFERENCES `pkl` (`id`),
  CONSTRAINT `anggota_pkl_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `anggota_pkl` */

insert  into `anggota_pkl`(`pkl_id`,`nim`,`nip`) values 
(1,'2115354075','2115354099'),
(1,'2115354015','191212121'),
(2,'2115354091',NULL);

/*Table structure for table `bimbingan_pkl` */

DROP TABLE IF EXISTS `bimbingan_pkl`;

CREATE TABLE `bimbingan_pkl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `deskripsi` varchar(200) DEFAULT NULL,
  `status` enum('validasi','revisi','menunggu') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL,
  `uraian` varchar(100) DEFAULT NULL,
  `id_pkl` int DEFAULT NULL,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pkl` (`id_pkl`),
  KEY `nim` (`nim`),
  KEY `nip` (`nip`),
  CONSTRAINT `bimbingan_pkl_ibfk_1` FOREIGN KEY (`id_pkl`) REFERENCES `pkl` (`id`),
  CONSTRAINT `bimbingan_pkl_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`),
  CONSTRAINT `bimbingan_pkl_ibfk_3` FOREIGN KEY (`nip`) REFERENCES `pembimbing_kampus` (`nip`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `bimbingan_pkl` */

insert  into `bimbingan_pkl`(`id`,`tanggal`,`deskripsi`,`status`,`file`,`uraian`,`id_pkl`,`nim`,`nip`) values 
(1,'2023-06-20','Bimbingan Bab 1 : Menentukan Latar Belakang','validasi','C:\\fakepath\\Kelompok1PKL_SKPL.pdf','Latar Belakang Harus Berisi Minimal 3 Paragraf!',1,'2115354075','2115354099'),
(2,'2023-06-20','Bimbingan Bab 2 : Pengaruh Buruk Mobile Legend','validasi','C:\\fakepath\\SIMAPEKA (3).pdf','-',1,'2115354015','2115354099'),
(3,'2023-06-21','Bimbingan Bab 3 : Pengetahuan Seputar CSS','revisi','C:\\fakepath\\Surat Pengantar PKL.pdf','Bimbingan Kurang',1,'2115354015','2115354099'),
(4,'2022-01-01','asd','revisi','C:\\fakepath\\BimbinganMhs.php','ggfggfgf',1,'2115354015','2115354099'),
(5,'2023-06-21','Revisi Bab 1','menunggu','C:\\fakepath\\ANALISA KELAYAKAN BISNIS - Kelompok 5 - 4C TRPL Minggu 3.pdf',NULL,1,'2115354075','2115354099'),
(6,'1111-01-01','asd','menunggu','ANALISA_KELAYAKAN_BISNIS_-_Kelompok_5_-_4C_TRPL.pdf',NULL,1,'2115354075','2115354099'),
(7,'2023-06-21','Ini teks panjanggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg','menunggu','Surat_Pengantar_PKL_(1).pdf',NULL,1,'2115354015','2115354099'),
(8,'2023-06-26','dfdefdd','menunggu','1318412.png',NULL,1,'2115354015','191212121');

/*Table structure for table `industri` */

DROP TABLE IF EXISTS `industri`;

CREATE TABLE `industri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telepon` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `penanggung_jawab` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_maps` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bidang_industri` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `industri` */

insert  into `industri`(`id`,`nama`,`alamat`,`telepon`,`penanggung_jawab`,`google_maps`,`bidang_industri`) values 
(1,'Indomaret','Nusa Dua','1234567890','Alif','','Swalayan'),
(2,'Alfamart','Gianyar','089765654567','Wisnu',NULL,'Swalayan'),
(3,'PNB','Jimbaran','0812543984',NULL,NULL,NULL);

/*Table structure for table `jurusan` */

DROP TABLE IF EXISTS `jurusan`;

CREATE TABLE `jurusan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `jurusan` */

insert  into `jurusan`(`id`,`nama`) values 
(1,'Teknik Elektro');

/*Table structure for table `mahasiswa` */

DROP TABLE IF EXISTS `mahasiswa`;

CREATE TABLE `mahasiswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_hp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prodi_id` int NOT NULL,
  `semester` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`),
  UNIQUE KEY `nim_2` (`nim`),
  KEY `prodi_id` (`prodi_id`),
  CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `mahasiswa` */

insert  into `mahasiswa`(`id`,`nim`,`nama`,`email`,`nomor_hp`,`alamat`,`prodi_id`,`semester`) values 
(1,'2115354011','I Wayan Ryan Adi Pastika','','','',1,4),
(2,'2115354015','Ngakan Made Wisnu Mahesa Adnyana','ngakanmadewisnu33@gmail.com','082147390668','Jl. Raya Suwat, Gianyar',1,4),
(3,'2115354019','Ni Luh Putu Utari Candra Dewi','','','',1,4),
(4,'2115354023','I PUTU WAHYU BUDI SETIAWAN','','','',1,4),
(5,'2115354027','DEWA GEDE SIDAN BAGUS TARUNA ISMAYANATHA P','','','',1,4),
(6,'2115354031','Alief Muhammad Kahfi','','','',1,4),
(7,'2115354035','I Komang Angga Buana Wicaksana','','','',1,4),
(8,'2115354039','I KADEK BAGUS RIO NANDIKA','','','',1,4),
(9,'2115354043','RAFI FARIDZ UTOMO','','','',1,4),
(10,'2115354047','MADE YUDA PRAWIRA','','','',1,4),
(11,'2115354051','KADEK DWIYANA PERNANDA','','','',1,4),
(12,'2115354055','I KADEK SANTIKA JAYA RATA','','','',1,4),
(13,'2115354059','I DEWA MADE ERWIN SURYA MAHOTAMA','','','',1,4),
(14,'2115354063','I GUSTI BAGUS ARYA SENTANA PUTRA','','','',1,4),
(15,'2115354064','I GEDE KRISNA ASTIKA NANDA','','','',1,4),
(16,'2115354067','I WAYAN SANJAYA MARANTIKA','','','',1,4),
(17,'2115354068','I MADE ANDI PARAMARTHA','','','',1,4),
(18,'2115354071','I NYOMAN BAGUS LANANG ASMARA','','','',1,4),
(19,'2115354072','I MADE RADITYA PURNAMA','','','',1,4),
(20,'2115354075','RISTA BELLA WAHYUNINGSIH','','','',1,4),
(21,'2115354076','I PUTU BAGUS PRADIPTA SAPUTRA','','','',1,4),
(22,'2115354079','Dewa Putu Satria Ananda Putra','','','',1,4),
(23,'2115354083','Fikri Bintang Achmada','','','',1,4),
(24,'2115354084','Gede Agus Aditya Dharma Serna Putra','','','',1,4),
(25,'2115354087','Dwiki christianto Novoselik','','','',1,4),
(26,'2115354088','I Gusti Ngurah Widi Dwi Laksana','','','',1,4),
(27,'2115354091','Aimar Sechan Adhitya','aimaradhitya@gmail.com','082121216644','',1,4),
(28,'2215354087','Alwan Hilmy','alwanhilmy@gmail.com','087654565432','Jl. Raya Denpasar Selatan',1,4),
(29,'2115354099','Willyam Moses','wilyammoses@gmail.com','089777878767','Bandung',1,4),
(30,'2115354111','Utomo Rafi','utomokun@gmail.com','087654565432','Jl. Raya Denpasar Selatan',1,8);

/*Table structure for table `nilai_kampus` */

DROP TABLE IF EXISTS `nilai_kampus`;

CREATE TABLE `nilai_kampus` (
  `id_nilai` int DEFAULT NULL,
  `motivasi` float DEFAULT NULL,
  `kreativitas` float DEFAULT NULL,
  `disiplin` float DEFAULT NULL,
  `metode` float DEFAULT NULL,
  KEY `id_nilai` (`id_nilai`),
  CONSTRAINT `nilai_kampus_ibfk_1` FOREIGN KEY (`id_nilai`) REFERENCES `nilai_mahasiswa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `nilai_kampus` */

insert  into `nilai_kampus`(`id_nilai`,`motivasi`,`kreativitas`,`disiplin`,`metode`) values 
(1,8.7,7.9,9.2,8.3);

/*Table structure for table `nilai_mahasiswa` */

DROP TABLE IF EXISTS `nilai_mahasiswa`;

CREATE TABLE `nilai_mahasiswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nim` (`nim`),
  CONSTRAINT `nilai_mahasiswa_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `nilai_mahasiswa` */

insert  into `nilai_mahasiswa`(`id`,`nim`) values 
(2,'2115354015'),
(1,'2115354075'),
(3,'2115354091');

/*Table structure for table `nilai_pkl` */

DROP TABLE IF EXISTS `nilai_pkl`;

CREATE TABLE `nilai_pkl` (
  `id_nilai` int DEFAULT NULL,
  `kemampuan_kerja` float DEFAULT NULL,
  `disiplin` float DEFAULT NULL,
  `komunikasi` float DEFAULT NULL,
  `inisiatif` float DEFAULT NULL,
  `kreativitas` float DEFAULT NULL,
  `kerjasama` float DEFAULT NULL,
  KEY `id_nilai` (`id_nilai`),
  CONSTRAINT `nilai_pkl_ibfk_1` FOREIGN KEY (`id_nilai`) REFERENCES `nilai_mahasiswa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `nilai_pkl` */

insert  into `nilai_pkl`(`id_nilai`,`kemampuan_kerja`,`disiplin`,`komunikasi`,`inisiatif`,`kreativitas`,`kerjasama`) values 
(1,8.8,7.9,9.2,7.9,8.8,7.7),
(2,9.1,8.7,7.8,8.4,7.7,8.9);

/*Table structure for table `pembimbing_industri` */

DROP TABLE IF EXISTS `pembimbing_industri`;

CREATE TABLE `pembimbing_industri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `jabatan` varchar(32) DEFAULT NULL,
  `industri_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nik` (`nik`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pembimbing_industri` */

insert  into `pembimbing_industri`(`id`,`nik`,`nama`,`alamat`,`telepon`,`email`,`jabatan`,`industri_id`) values 
(1,'0012345678','Wawan Ajahxxxxx','Jimbaran','08145368','wawan@gmail.comxxxxxxxxxx','HRD',1),
(2,'00112233448','Jupri','Nusa Dua','0815694732','jupri@gmail.com','Karyawan',2),
(3,'443123','amir','jimbaran','025487864','amir@gmail.com','hrd',3),
(4,'123123','hehe','jrj','hee','hehe','hehe',2),
(5,'hehe','hehe','hehe','hehe','hehe','hehe',2);

/*Table structure for table `pembimbing_kampus` */

DROP TABLE IF EXISTS `pembimbing_kampus`;

CREATE TABLE `pembimbing_kampus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `bidang_ilmu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pembimbing_kampus` */

insert  into `pembimbing_kampus`(`id`,`nip`,`nama`,`alamat`,`telepon`,`email`,`bidang_ilmu`) values 
(1,'191212121','I Made Andi Paramartha                                                                              ','Badunggggggggggggggggggggggggggggggggggggggggg','085111222333','made@gmail.com','Bahasa Indonesia'),
(2,'2115354099','Made Pradnyana Ambara, S.Kom., M.T.','Br. Guliang, Pejeng, Gianyar','089786545432','pradnyaambara@gmail.com','Database Administrator'),
(3,'19123432','Ni Ketut Pradani Gayatri Sarja, S.Kom., M.Kom','Denpasar','081212787969','tutgayatri@gmail.com','Pemrograman Web Dasar'),
(4,'19112233','I Putu Oka Wisnawa, S.Kom., MT.','Buleleng','081232787656','okawisnawa@gmail.com','SIM Paris'),
(5,'haha','ha','a','a','a','a');

/*Table structure for table `pkl` */

DROP TABLE IF EXISTS `pkl`;

CREATE TABLE `pkl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `industri_id` int NOT NULL,
  `tahap` int DEFAULT NULL,
  `status` enum('success','failed','waiting','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `industri_id` (`industri_id`),
  CONSTRAINT `pkl_ibfk_1` FOREIGN KEY (`industri_id`) REFERENCES `industri` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pkl` */

insert  into `pkl`(`id`,`industri_id`,`tahap`,`status`,`created_at`,`updated_at`) values 
(1,1,1,NULL,'2023-06-20 11:56:01','2023-06-20 11:56:05'),
(2,1,1,NULL,'2023-06-26 13:54:08','2023-06-26 14:07:42');

/*Table structure for table `program_studi` */

DROP TABLE IF EXISTS `program_studi`;

CREATE TABLE `program_studi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenjang` enum('Diploma II','Diploma III','Sarjana Terapan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jurusan_id` (`jurusan_id`),
  CONSTRAINT `program_studi_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `program_studi` */

insert  into `program_studi`(`id`,`nama`,`jenjang`,`jurusan_id`) values 
(1,'Teknologi Rekayasa Perangkat Lunak','Sarjana Terapan',1);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','mahasiswa','pembimbing_industri','pembimbing_kampus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `picture` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verified` tinyint(1) NOT NULL,
  `status` enum('active','inactive','disabled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`role`,`picture`,`verified`,`status`,`created_at`,`updated_at`) values 
(1,'admin','21232f297a57a5a743894a0e4a801fc3','admin','assets/img/profile/0dcdf4ba4c735c77405da8383ee4c1bc.jpg',1,'active','2023-05-08 05:48:05',NULL),
(5,'aimaradhitya@gmail.com','fd4b754da481d2915e8fa6c65cc3987d','mahasiswa',NULL,1,'active',NULL,NULL),
(8,'ristabellaa03@gmail.com','25d55ad283aa400af464c76d713c07ad','mahasiswa',NULL,1,'active',NULL,NULL),
(9,'paramarthaandi@gmail.com','827ccb0eea8a706c4c34a16891f84e7b','mahasiswa',NULL,1,'active',NULL,NULL),
(10,'pem_indus','25f9e794323b453885f5181f1b624d0b','pembimbing_industri',NULL,1,'active',NULL,NULL),
(11,'pem_kampus','25f9e794323b453885f5181f1b624d0b','pembimbing_kampus',NULL,1,'active',NULL,NULL),
(12,'admin3','d41d8cd98f00b204e9800998ecf8427e','admin',NULL,1,'active',NULL,NULL),
(13,'admin5','d41d8cd98f00b204e9800998ecf8427e','admin',NULL,0,'active',NULL,NULL),
(17,'bintangfikri31@gmail.com','b3c045681840683f78634ff905d544dd','mahasiswa',NULL,1,'active',NULL,NULL),
(23,'ngakanmadewisnu33@gmail.com','f5bb0c8de146c67b44babbf4e6584cc0','mahasiswa',NULL,1,'active',NULL,NULL),
(24,'pemdus2','530179d0b95bfb6ea477a53c039df0f6','pembimbing_industri',NULL,1,'active',NULL,NULL),
(25,'pempus2','19d49c6404a8855193f2c7c8c5855034','pembimbing_kampus',NULL,1,'active',NULL,NULL),
(26,'ngakanmadewisnu44@gmail.com','f5bb0c8de146c67b44babbf4e6584cc0','mahasiswa',NULL,1,'active',NULL,NULL),
(27,'aimar.adhit.ya@gmail.com','1702a641771a58424c6ef4dbcf5223e9','mahasiswa',NULL,1,'active',NULL,NULL);

/*Table structure for table `user_mahasiswa` */

DROP TABLE IF EXISTS `user_mahasiswa`;

CREATE TABLE `user_mahasiswa` (
  `user_id` int NOT NULL,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`nim`),
  KEY `nim` (`nim`),
  CONSTRAINT `user_mahasiswa_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`),
  CONSTRAINT `user_mahasiswa_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user_mahasiswa` */

insert  into `user_mahasiswa`(`user_id`,`nim`) values 
(27,'2115354011'),
(23,'2115354015'),
(26,'2115354051'),
(9,'2115354068'),
(8,'2115354075'),
(17,'2115354083'),
(5,'2115354091');

/*Table structure for table `user_pembimbing_industri` */

DROP TABLE IF EXISTS `user_pembimbing_industri`;

CREATE TABLE `user_pembimbing_industri` (
  `user_id` int DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  KEY `user_id` (`user_id`,`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user_pembimbing_industri` */

insert  into `user_pembimbing_industri`(`user_id`,`nik`) values 
(10,'0012345678'),
(24,'hehe');

/*Table structure for table `user_pembimbing_kampus` */

DROP TABLE IF EXISTS `user_pembimbing_kampus`;

CREATE TABLE `user_pembimbing_kampus` (
  `user_id` int DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user_pembimbing_kampus` */

insert  into `user_pembimbing_kampus`(`user_id`,`nip`) values 
(11,'2115354099'),
(25,'haha');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
