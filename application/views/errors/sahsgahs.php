phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 27, 2023 at 03:16 AM
-- Server version: 10.11.2-MariaDB-1:10.11.2+maria~ubu2204
-- PHP Version: 8.1.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_aktivitas`
--

CREATE TABLE `tb_aktivitas` (
`id` int(11) NOT NULL,
`nim` varchar(16) NOT NULL,
`dudi_id` int(11) NOT NULL,
`jenis` text NOT NULL,
`deskripsi` text NOT NULL,
`status` varchar(16) DEFAULT NULL,
`created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_aktivitas`
--

INSERT INTO `tb_aktivitas` (`id`, `nim`, `dudi_id`, `jenis`, `deskripsi`, `status`, `created_at`) VALUES
(1, '1915354091', 1, 'Hello', 'World', 'Sudah Divalidasi', '2023-03-27 03:26:54'),
(2, '1915354091', 3, 'HEllo', 'Again', NULL, '2023-03-27 03:32:18'),
(3, '1915354091', 3, 'XSS', '&lt;script&gt;1&lt;/script&gt;', NULL, '2023-03-27 03:39:15');

-- --------------------------------------------------------

--
-- Table structure for table `tb_anggota`
--

CREATE TABLE `tb_anggota` (
`pkl_id` int(11) NOT NULL,
`nim` varchar(16) NOT NULL,
`nomor_hp` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_anggota`
--

INSERT INTO `tb_anggota` (`pkl_id`, `nim`, `nomor_hp`) VALUES
(3, '1915323001', '08123456781'),
(3, '1915354091', '082121216644');

-- --------------------------------------------------------

--
-- Table structure for table `tb_dosen`
--

CREATE TABLE `tb_dosen` (
`nip` varchar(32) NOT NULL,
`nama` varchar(128) NOT NULL,
`prodi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dosen`
--

INSERT INTO `tb_dosen` (`nip`, `nama`, `prodi_id`) VALUES
('123', 'Andika', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_dospem`
--

CREATE TABLE `tb_dospem` (
`id` int(11) NOT NULL,
`nip` varchar(32) NOT NULL,
`nim` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dospem`
--

INSERT INTO `tb_dospem` (`id`, `nip`, `nim`) VALUES
(4, '123', '1915354091');

-- --------------------------------------------------------

--
-- Table structure for table `tb_dudi`
--

CREATE TABLE `tb_dudi` (
`id` int(11) NOT NULL,
`nama_tempat` varchar(128) NOT NULL,
`alamat` varchar(256) NOT NULL,
`kontak` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dudi`
--

INSERT INTO `tb_dudi` (`id`, `nama_tempat`, `alamat`, `kontak`) VALUES
(1, 'Indomaret', 'Jalan Bypass Nusa Dua', '081284872960'),
(2, 'PT. Mencari Cinta Sejati', 'Jalan Bypass Ngurah Rai No. 18', '081284872960'),
(3, 'Hotel Pop Nusa Dua', 'Nusa Dua', '08124567890'),
(4, 'Toko Wijayas', 'Denpasar', '123456789');

-- --------------------------------------------------------

--
-- Table structure for table `tb_dudipem`
--

CREATE TABLE `tb_dudipem` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`dudi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dudipem`
--

INSERT INTO `tb_dudipem` (`id`, `user_id`, `dudi_id`) VALUES
(1, 6, 3),
(2, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jurusan`
--

CREATE TABLE `tb_jurusan` (
`jurusan_id` int(11) NOT NULL,
`nama` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jurusan`
--

INSERT INTO `tb_jurusan` (`jurusan_id`, `nama`) VALUES
(1, 'Teknik Elektro');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mhs`
--

CREATE TABLE `tb_mhs` (
`nim` varchar(16) NOT NULL,
`nama` varchar(64) NOT NULL,
`prodi_id` int(11) NOT NULL,
`semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_mhs`
--

INSERT INTO `tb_mhs` (`nim`, `nama`, `prodi_id`, `semester`) VALUES
('1915323001', 'Gede Chandra Adjie Mikeyana', 2, 7),
('1915323002', 'Made Stevian Raras Pujadananbawa', 1, 7),
('1915323003', 'Made Ardhi Laksana Kumara', 1, 7),
('1915323004', 'I Kadek Darmadi', 1, 7),
('1915323005', 'I Nyoman Arya Candrayana', 1, 7),
('1915323006', 'Dian Aulia Safitri', 1, 7),
('1915323008', 'Ni Putu Yunia Nurcahyani', 1, 7),
('1915323009', 'Ni Luh Made Sugitayani Putri', 1, 7),
('1915323010', 'Komang Arya Ardana', 1, 7),
('1915323011', 'Anak Agung Sagung Mirah Prajunika Dewi', 1, 7),
('1915323012', 'Komang Prabudinata', 1, 7),
('1915323013', 'Dewa Gede Aditya Putra', 1, 7),
('1915323014', 'Desak Ayu Leony Julianita', 1, 7),
('1915323015', 'I Gusti Putu Wika Wardana', 1, 7),
('1915323016', 'Made Dwiyanti Gunaswari', 1, 7),
('1915323018', 'Putu Vika Anggi Yanti', 1, 7),
('1915323019', 'I Putu Agus Eka Wira Yuda', 1, 7),
('1915323020', 'Gusti Ayu Putu Ratih Widya Apsari', 1, 7),
('1915323021', 'Bagus Made Arta Nugraha', 1, 7),
('1915323022', 'I Wayan Manu Wijaya Kusuma', 1, 7),
('1915323023', 'I Nyoman Ary Wirawan Saputra', 1, 7),
('1915323024', 'Angga Hadi Permana', 1, 7),
('1915323025', 'I Putu Surya Jayantara', 1, 7),
('1915323026', 'I Nyoman Cahaya Krisna Putra', 1, 7),
('1915323029', 'I Gusti Ayu Adnya Pramesti', 1, 7),
('1915323031', 'I Gede Leo Partha', 1, 7),
('1915323034', 'Ni Luh Putu Savita Dewi', 1, 7),
('1915323037', 'I Putu Dedi Suardana', 1, 7),
('1915323038', 'Ayu Krisna Melati', 1, 7),
('1915323039', 'Yunita Salsabila', 1, 7),
('1915323040', 'Putu Rona Litana Wijaya', 1, 7),
('1915323042', 'Ni Made Claresta Artanti', 1, 7),
('1915323044', 'Royan Fauzan', 1, 7),
('1915323045', 'I Putu Agus Eka Cahyadi', 1, 7),
('1915323046', 'I Putu Bagus Wiranata', 1, 7),
('1915323048', 'Dinda Intan Anjelika', 1, 7),
('1915323049', 'I Komang Gede Maha Wijasa', 1, 7),
('1915323050', 'Sang Made Dwi Cahya Premana', 1, 7),
('1915323051', 'Dani Rizal Firmansyah', 1, 7),
('1915323052', 'Ketut Kevin Arya Baskara', 1, 7),
('1915323053', 'Gilang Bagus Saputra', 1, 7),
('1915323055', 'I Gusti Ngurah Agung Krishna Aditya', 1, 7),
('1915323057', 'Made Simayodika', 1, 7),
('1915323058', 'I Komang Gede Wira Putra', 1, 7),
('1915323060', 'Kieran Dwimardana Putra', 1, 7),
('1915323061', 'Ni Kadek Dwi Juliantari', 1, 7),
('1915323062', 'Gd Risky Andika Widiarta', 1, 7),
('1915323063', 'I Made Arya Sadiva Ambara', 1, 7),
('1915323064', 'I Gusti Nyoman Ariyoga Widagda', 1, 7),
('1915323065', 'I Made Andre Prayoga Putra', 1, 7),
('1915323066', 'Joan Jasmine Malelak', 1, 7),
('1915323067', 'Kadek Wahyu Dwipayana', 1, 7),
('1915323068', 'I Wayan Fajar Fabradana', 1, 7),
('1915323069', 'Ida Bujangga Bagus Gede Risa Rynanda Wanapala', 1, 7),
('1915323070', 'Ni Luh Anik Sidhi Anggraeni', 1, 7),
('1915323071', 'I Nyoman Indra Kusuma', 1, 7),
('1915323072', 'I Putu Sony Adi Pratama', 1, 7),
('1915323073', 'Muhammad Alievyo Ramadhani', 1, 7),
('1915323074', 'Ni Kadek Risma Arisanti', 1, 7),
('1915323075', 'Gede Wisnu Prayoga', 1, 7),
('1915323076', 'I Putu Gede Bayu Puja Dana', 1, 7),
('1915323077', 'Ida Bagus Gede Wirajaya P', 1, 7),
('1915323079', 'I Komang Dwi Padmayana', 1, 7),
('1915323080', 'I Wayan Agus Wika Sedana', 1, 7),
('1915323081', 'I Kadek Agustika Pramana Utama', 1, 7),
('1915323082', 'I Ketut Adi Candra', 1, 7),
('1915323083', 'Dewa Gede Pramudya', 1, 7),
('1915323084', 'Dewa Putu Ari Permana Putra', 1, 7),
('1915323085', 'I Putu Setiawan', 1, 7),
('1915323086', 'Muhammad Anggoro Afif Azinuddin', 1, 7),
('1915323087', 'I Ketut Nurasadi Darma Pala Guna', 1, 7),
('1915323088', 'Ni Putu Widya Ariani', 1, 7),
('1915323089', 'Putu Citananta Indrawan Sloka', 1, 7),
('1915323090', 'Made Crespo Mahesa Nirwikara', 1, 7),
('1915323092', 'I Nyoman Ananda Kusuma', 1, 7),
('1915323094', 'Ni Made Gita Pramudyacwari', 1, 7),
('1915323095', 'Muhammad Wafiuddin Rafly', 1, 7),
('1915323096', 'I Putu Dedy Alan Kusuma', 1, 7),
('1915323097', 'Panji Suryadi', 1, 7),
('1915323100', 'I Made Mario Jose Valentino', 1, 7),
('1915323101', 'I Putu Dede Pramana Putra', 1, 7),
('1915323102', 'I Kadek Purnada Antara', 1, 7),
('1915323103', 'I Gd. Putu Bagus Nugraha Sindhu', 1, 7),
('1915323105', 'Kadek Feby Agnasari', 1, 7),
('1915323106', 'Ida Ayu Alit Swantari', 1, 7),
('1915323107', 'I Made Sandhi Putrawan', 1, 7),
('1915323108', 'Tanjung Prawira Yuda', 1, 7),
('1915323110', 'I Kadek Darma Yoga', 1, 7),
('1915323111', 'I Kadek Angga Ardika', 1, 7),
('1915323112', 'Ni Made Dwi Eva Pramesti', 1, 7),
('1915323113', ' I Kadek Angga Winata Pramudiya', 1, 7),
('1915323116', 'I Made Dharma Yoga Pratama', 1, 7),
('1915323117', 'I Made Arya Permana', 1, 7),
('1915323119', 'Ni Luh Putu Ayu Budiarini', 1, 7),
('1915323120', 'Pande Kadek Dwijayendra', 1, 7),
('1915354091', 'Aimar Sechan Adhitya', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilaidosen`
--

CREATE TABLE `tb_nilaidosen` (
`id` int(11) NOT NULL,
`motivasi` float NOT NULL,
`kreativitas` float NOT NULL,
`disiplin` float NOT NULL,
`metode_pembahasan_laporan` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_nilaidosen`
--

INSERT INTO `tb_nilaidosen` (`id`, `motivasi`, `kreativitas`, `disiplin`, `metode_pembahasan_laporan`) VALUES
(2, 100, 100, 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilaiindustri`
--

CREATE TABLE `tb_nilaiindustri` (
`id` int(11) NOT NULL,
`kemampuan_kerja` float NOT NULL,
`disiplin` float NOT NULL,
`komunikasi` float NOT NULL,
`inisiatif` float NOT NULL,
`kreativitas` float NOT NULL,
`kerjasama` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_notification`
--

CREATE TABLE `tb_notification` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`title` varchar(256) NOT NULL,
`content` text NOT NULL,
`created_at` datetime NOT NULL,
`read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_notification`
--

INSERT INTO `tb_notification` (`id`, `user_id`, `title`, `content`, `created_at`, `read_at`) VALUES
(1, 2, 'Respon Undangan PKL', '<b>Gede Chandra Adjie Mikeyana</b> telah menerima undangan PKL anda.', '2023-03-20 04:50:09', '2023-03-20 04:51:49'),
(2, 3, 'Status Permohonan PKL', 'Dikarenakan semua anggota telah menerima undangan pada grup PKL anda, permohonan akan segera di proses oleh kajur.', '2023-03-20 04:50:09', '2023-03-20 04:52:15'),
(3, 2, 'Status Permohonan PKL', 'Dikarenakan semua anggota telah menerima undangan pada grup PKL anda, permohonan akan segera di proses oleh kajur.', '2023-03-20 04:50:09', '2023-03-20 04:52:10'),
(4, 3, 'Undangan PKL', '<b>Aimar Sechan Adhitya</b> telah menundang anda untuk mengikuti Praktek Kerja Lapangan (PKL), silahkan cek pada dashboard anda untuk informasi lebih lanjut.', '2023-03-20 14:45:05', NULL),
(5, 3, 'Undangan PKL', '<b>Aimar Sechan Adhitya</b> telah menundang anda untuk mengikuti Praktek Kerja Lapangan (PKL), silahkan cek pada dashboard anda untuk informasi lebih lanjut.', '2023-03-20 14:45:12', NULL),
(6, 3, 'Undangan PKL', '<b>Aimar Sechan Adhitya</b> telah menundang anda untuk mengikuti Praktek Kerja Lapangan (PKL), silahkan cek pada dashboard anda untuk informasi lebih lanjut.', '2023-03-20 14:46:27', '2023-03-20 14:46:36'),
(7, 2, 'Respon Undangan PKL', '<b>Gede Chandra Adjie Mikeyana</b> telah menerima undangan PKL anda.', '2023-03-20 14:46:47', '2023-03-20 14:48:07'),
(8, 3, 'Status Permohonan PKL', 'Dikarenakan semua anggota telah menerima undangan pada grup PKL anda, permohonan akan segera di proses oleh kajur.', '2023-03-20 14:46:47', NULL),
(9, 2, 'Status Permohonan PKL', 'Dikarenakan semua anggota telah menerima undangan pada grup PKL anda, permohonan akan segera di proses oleh kajur.', '2023-03-20 14:46:47', '2023-03-20 14:48:14'),
(10, 3, 'Hasil Permohonan PKL', '<b>Kajur</b> telah menolak permohonan PKL anda, Alasan:<br><i>123</i>.', '2023-03-23 23:36:02', NULL),
(11, 2, 'Hasil Permohonan PKL', '<b>Kajur</b> telah menolak permohonan PKL anda, Alasan:<br><i>123</i>.', '2023-03-23 23:36:02', '2023-03-23 23:36:39'),
(12, 3, 'Hasil Permohonan PKL', '<b>Kajur</b> telah menerima permohonan PKL anda. Silahkan cek pada dashboard anda untuk instruksi lebih lanjut.', '2023-03-23 23:38:39', NULL),
(13, 2, 'Hasil Permohonan PKL', '<b>Kajur</b> telah menerima permohonan PKL anda. Silahkan cek pada dashboard anda untuk instruksi lebih lanjut.', '2023-03-23 23:38:39', '2023-03-23 23:38:45');

-- --------------------------------------------------------

--
-- Table structure for table `tb_periode`
--

CREATE TABLE `tb_periode` (
`id` int(11) NOT NULL,
`tgl_mulai` date NOT NULL,
`tgl_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_periode`
--

INSERT INTO `tb_periode` (`id`, `tgl_mulai`, `tgl_selesai`) VALUES
(1, '2023-01-04', '2023-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pkl`
--

CREATE TABLE `tb_pkl` (
`id` int(11) NOT NULL,
`nim` int(11) NOT NULL,
`dudi_id` int(11) NOT NULL,
`status` enum('selesai','disetujui','menunggu_persetujuan_kajur','menunggu_persetujuan_anggota','menunggu_konfirmasi_pemohon','ditolak') NOT NULL,
`created_at` datetime DEFAULT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pkl`
--

INSERT INTO `tb_pkl` (`id`, `nim`, `dudi_id`, `status`, `created_at`, `updated_at`) VALUES
(3, 1915354091, 3, 'disetujui', '2023-03-20 14:46:27', '2023-03-23 03:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `tb_prodi`
--

CREATE TABLE `tb_prodi` (
`prodi_id` int(11) NOT NULL,
`jurusan_id` int(11) NOT NULL,
`nama` varchar(64) NOT NULL,
`jenjang` enum('Diploma I','Diploma II','Diploma III','Sarjana Terapan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_prodi`
--

INSERT INTO `tb_prodi` (`prodi_id`, `jurusan_id`, `nama`, `jenjang`) VALUES
(1, 1, 'Teknologi Rekayasa Perangkat Lunak', 'Sarjana Terapan'),
(2, 1, 'Manajemen Informatika', 'Diploma III');

-- --------------------------------------------------------

--
-- Table structure for table `tb_resetpwd`
--

CREATE TABLE `tb_resetpwd` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`secret` char(32) NOT NULL,
`expiry` datetime NOT NULL,
`used` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_resetpwd`
--

INSERT INTO `tb_resetpwd` (`id`, `user_id`, `secret`, `expiry`, `used`) VALUES
(1, 1, 'wxWcrP0fmEbXpBezs18FK94qRDCNMdAt', '2023-03-17 04:10:05', 0),
(2, 1, 'aJcYd0p9hiyKSO7CBZs3tFHor5m8Llqu', '2023-03-17 10:00:21', 0),
(3, 1, 'i9rpYXxDkS5U4COd6uBPwy2GRNcAKQzH', '2023-03-17 10:06:27', 0),
(4, 2, '63unabRYfFPtm09EAQhXlwjIvCUdey8O', '2023-03-17 12:24:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_udosen`
--

CREATE TABLE `tb_udosen` (
`nip` varchar(32) NOT NULL,
`user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_udosen`
--

INSERT INTO `tb_udosen` (`nip`, `user_id`) VALUES
('123', 16);

-- --------------------------------------------------------

--
-- Table structure for table `tb_ukaprodi`
--

CREATE TABLE `tb_ukaprodi` (
`user_id` int(11) NOT NULL,
`prodi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ukaprodi`
--

INSERT INTO `tb_ukaprodi` (`user_id`, `prodi_id`) VALUES
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_umhs`
--

CREATE TABLE `tb_umhs` (
`nim` varchar(16) NOT NULL,
`user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_umhs`
--

INSERT INTO `tb_umhs` (`nim`, `user_id`) VALUES
('1915354091', 2),
('1915323001', 3),
('1915323002', 19);

-- --------------------------------------------------------

--
-- Table structure for table `tb_undangan`
--

CREATE TABLE `tb_undangan` (
`id` int(11) NOT NULL,
`nim_from` varchar(16) NOT NULL,
`nim_to` varchar(16) NOT NULL,
`pkl_id` int(11) NOT NULL,
`status` enum('diterima','ditolak','dibatalkan','menunggu') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_undangan`
--

INSERT INTO `tb_undangan` (`id`, `nim_from`, `nim_to`, `pkl_id`, `status`) VALUES
(3, '1915354091', '1915323001', 3, 'diterima');

-- --------------------------------------------------------

--
-- Table structure for table `tb_unilaidosen`
--

CREATE TABLE `tb_unilaidosen` (
`id` int(11) NOT NULL,
`nim` varchar(16) NOT NULL,
`nip` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_unilaidosen`
--

INSERT INTO `tb_unilaidosen` (`id`, `nim`, `nip`) VALUES
(2, '1915354091', '123');

-- --------------------------------------------------------

--
-- Table structure for table `tb_unilaiindustri`
--

CREATE TABLE `tb_unilaiindustri` (
`id` int(11) NOT NULL,
`nim` varchar(16) NOT NULL,
`user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
`user_id` int(11) NOT NULL,
`username` varchar(32) NOT NULL,
`password` char(64) DEFAULT NULL,
`role` enum('admin','kajur','kaprodi','pemb_pkl','pemb_indus','mahasiswa') NOT NULL,
`verified` tinyint(1) NOT NULL,
`status` enum('active','inactive','disabled') NOT NULL,
`pp_name` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `username`, `password`, `role`, `verified`, `status`, `pp_name`) VALUES
(1, 'admin', 'c280ec55eb6fb058fb5fc1df5d05c8cff39a062a0c9b5b396bc0c2206723fce9', 'admin', 1, 'active', 'c4ca4238a0b923820dcc509a6f75849b.jpg'),
(2, 'aimar.adhitya@gmail.com', '27579664b4a93e68040dc64352fca1ae266d30fbe794ee5fc3d42e6a6d71a404', 'mahasiswa', 1, 'active', 'c81e728d9d4c2f636f067f89cc14862c.jpg'),
(3, 'aimaradhitya@gmail.com', '8ec302c02b5f202bd9029587c89ad8e2dc2425c0ae8bbe7886d58d3b389d7c11', 'mahasiswa', 1, 'active', NULL),
(4, 'kajur', 'd0db6121fd3c196c100983c26be47355cd194fa878b2112a7c6b83205d08b64f', 'kajur', 1, 'active', NULL),
(5, 'kaprodi', '9352cb346bb20774af4dabacc95a5cb78de3d95526f0bc4157e41ca9230ba4f7', 'kaprodi', 1, 'active', 'e4da3b7fbbce2345d7772b0674a318d5.jpg'),
(6, 'dudi', '50f28685637d0f833a9e066c53dee9f423f38be485437d341bffaab1323d082f', 'pemb_indus', 1, 'active', NULL),
(12, 'dudi2', '425558b49b81a1ddaee1fbfbae36384bc4b95ccaf4704ddfc07f4ba1ff550016', 'pemb_indus', 1, 'active', NULL),
(16, 'dosen', '74c39323a1435e512ae8f7d34877c19102d511fe4785a66d9e0d2e380f3cb03b', 'pemb_pkl', 1, 'active', NULL),
(19, 'aimaradhity.a@gmail.com', '59af41e8d5a665af780d82930e8960bc0bb48cc9f10b8e9b74f49d47e2e3b0ae', 'mahasiswa', 0, 'inactive', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_verifcode`
--

CREATE TABLE `tb_verifcode` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`code` int(11) NOT NULL,
`secret` char(32) NOT NULL,
`expiry` datetime NOT NULL,
`used` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_verifcode`
--

INSERT INTO `tb_verifcode` (`id`, `user_id`, `code`, `secret`, `expiry`, `used`) VALUES
(1, 1, 210146, 'VCL8A15MwHjSscRaEkvdhUuri2FbgJtm', '2023-03-15 17:58:52', 1),
(2, 2, 528719, 'v6TXiqjEu5dQCRGDnAblFJN98fMVrw43', '2023-03-17 12:00:11', 0),
(3, 3, 902920, '6yW0X1MrQCmuVkeUD4HthPjcNRTdJ8li', '2023-03-19 19:41:30', 1),
(4, 3, 264009, '3vFsLNjoiM7ROyg21uqDYptnbfXrxm8W', '2023-03-27 11:13:35', 0),
(6, 0, 119825, 'Wgf2bTQUoPpKAjFDkhYXL97lIevGnE6Z', '2023-03-27 11:20:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_aktivitas`
--
ALTER TABLE `tb_aktivitas`
ADD PRIMARY KEY (`id`),
ADD KEY `nim` (`nim`),
ADD KEY `dudi_id` (`dudi_id`);

--
-- Indexes for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
ADD UNIQUE KEY `pkl_id` (`pkl_id`,`nim`),
ADD KEY `tb_anggota_ibfk_1` (`nim`);

--
-- Indexes for table `tb_dosen`
--
ALTER TABLE `tb_dosen`
ADD PRIMARY KEY (`nip`),
ADD KEY `prodi_id` (`prodi_id`);

--
-- Indexes for table `tb_dospem`
--
ALTER TABLE `tb_dospem`
ADD PRIMARY KEY (`id`),
ADD KEY `nip` (`nip`),
ADD KEY `nim` (`nim`);

--
-- Indexes for table `tb_dudi`
--
ALTER TABLE `tb_dudi`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_dudipem`
--
ALTER TABLE `tb_dudipem`
ADD PRIMARY KEY (`id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `dudi_id` (`dudi_id`);

--
-- Indexes for table `tb_jurusan`
--
ALTER TABLE `tb_jurusan`
ADD UNIQUE KEY `jurusan_id` (`jurusan_id`);

--
-- Indexes for table `tb_mhs`
--
ALTER TABLE `tb_mhs`
ADD UNIQUE KEY `nim` (`nim`),
ADD KEY `prodi_id` (`prodi_id`);

--
-- Indexes for table `tb_nilaidosen`
--
ALTER TABLE `tb_nilaidosen`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_nilaiindustri`
--
ALTER TABLE `tb_nilaiindustri`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_notification`
--
ALTER TABLE `tb_notification`
ADD PRIMARY KEY (`id`),
ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_periode`
--
ALTER TABLE `tb_periode`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `start_at` (`tgl_mulai`,`tgl_selesai`);

--
-- Indexes for table `tb_pkl`
--
ALTER TABLE `tb_pkl`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `dudi_id` (`dudi_id`);

--
-- Indexes for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
ADD UNIQUE KEY `prodi_id` (`prodi_id`),
ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indexes for table `tb_resetpwd`
--
ALTER TABLE `tb_resetpwd`
ADD PRIMARY KEY (`id`),
ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_udosen`
--
ALTER TABLE `tb_udosen`
ADD UNIQUE KEY `nip` (`nip`,`user_id`),
ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_ukaprodi`
--
ALTER TABLE `tb_ukaprodi`
ADD UNIQUE KEY `user_id` (`user_id`),
ADD KEY `prodi_id` (`prodi_id`);

--
-- Indexes for table `tb_umhs`
--
ALTER TABLE `tb_umhs`
ADD UNIQUE KEY `nim` (`nim`),
ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_undangan`
--
ALTER TABLE `tb_undangan`
ADD PRIMARY KEY (`id`),
ADD KEY `nim_from` (`nim_from`),
ADD KEY `nim_to` (`nim_to`);

--
-- Indexes for table `tb_unilaidosen`
--
ALTER TABLE `tb_unilaidosen`
ADD PRIMARY KEY (`id`),
ADD KEY `nim` (`nim`),
ADD KEY `nip` (`nip`);

--
-- Indexes for table `tb_unilaiindustri`
--
ALTER TABLE `tb_unilaiindustri`
ADD UNIQUE KEY `id_3` (`id`,`nim`,`user_id`),
ADD KEY `id` (`id`),
ADD KEY `nim` (`nim`),
ADD KEY `user_id` (`user_id`),
ADD KEY `id_2` (`id`,`nim`,`user_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
ADD PRIMARY KEY (`user_id`),
ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_verifcode`
--
ALTER TABLE `tb_verifcode`
ADD PRIMARY KEY (`id`),
ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_aktivitas`
--
ALTER TABLE `tb_aktivitas`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_dospem`
--
ALTER TABLE `tb_dospem`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_dudi`
--
ALTER TABLE `tb_dudi`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_dudipem`
--
ALTER TABLE `tb_dudipem`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_nilaidosen`
--
ALTER TABLE `tb_nilaidosen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_nilaiindustri`
--
ALTER TABLE `tb_nilaiindustri`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_notification`
--
ALTER TABLE `tb_notification`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_periode`
--
ALTER TABLE `tb_periode`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_pkl`
--
ALTER TABLE `tb_pkl`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_resetpwd`
--
ALTER TABLE `tb_resetpwd`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_undangan`
--
ALTER TABLE `tb_undangan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_unilaidosen`
--
ALTER TABLE `tb_unilaidosen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_verifcode`
--
ALTER TABLE `tb_verifcode`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_aktivitas`
--
ALTER TABLE `tb_aktivitas`
ADD CONSTRAINT `tb_aktivitas_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `tb_mhs` (`nim`),
ADD CONSTRAINT `tb_aktivitas_ibfk_2` FOREIGN KEY (`dudi_id`) REFERENCES `tb_dudi` (`id`);

--
-- Constraints for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
ADD CONSTRAINT `tb_anggota_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `tb_mhs` (`nim`);

--
-- Constraints for table `tb_dosen`
--
ALTER TABLE `tb_dosen`
ADD CONSTRAINT `tb_dosen_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `tb_prodi` (`prodi_id`);

--
-- Constraints for table `tb_dudipem`
--
ALTER TABLE `tb_dudipem`
ADD CONSTRAINT `tb_dudipem_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`),
ADD CONSTRAINT `tb_dudipem_ibfk_2` FOREIGN KEY (`dudi_id`) REFERENCES `tb_dudi` (`id`);

--
-- Constraints for table `tb_mhs`
--
ALTER TABLE `tb_mhs`
ADD CONSTRAINT `tb_mhs_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `tb_prodi` (`prodi_id`);

--
-- Constraints for table `tb_notification`
--
ALTER TABLE `tb_notification`
ADD CONSTRAINT `tb_notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_pkl`
--
ALTER TABLE `tb_pkl`
ADD CONSTRAINT `tb_pkl_ibfk_1` FOREIGN KEY (`dudi_id`) REFERENCES `tb_dudi` (`id`);

--
-- Constraints for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
ADD CONSTRAINT `tb_prodi_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `tb_jurusan` (`jurusan_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_resetpwd`
--
ALTER TABLE `tb_resetpwd`
ADD CONSTRAINT `tb_resetpwd_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_udosen`
--
ALTER TABLE `tb_udosen`
ADD CONSTRAINT `tb_udosen_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `tb_dosen` (`nip`),
ADD CONSTRAINT `tb_udosen_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_ukaprodi`
--
ALTER TABLE `tb_ukaprodi`
ADD CONSTRAINT `tb_ukaprodi_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `tb_prodi` (`prodi_id`),
ADD CONSTRAINT `tb_ukaprodi_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_umhs`
--
ALTER TABLE `tb_umhs`
ADD CONSTRAINT `tb_umhs_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `tb_mhs` (`nim`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `tb_umhs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_undangan`
--
ALTER TABLE `tb_undangan`
ADD CONSTRAINT `tb_undangan_ibfk_1` FOREIGN KEY (`nim_from`) REFERENCES `tb_mhs` (`nim`),
ADD CONSTRAINT `tb_undangan_ibfk_2` FOREIGN KEY (`nim_to`) REFERENCES `tb_mhs` (`nim`);

--
-- Constraints for table `tb_unilaidosen`
--
ALTER TABLE `tb_unilaidosen`
ADD CONSTRAINT `tb_unilaidosen_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_nilaidosen` (`id`),
ADD CONSTRAINT `tb_unilaidosen_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `tb_mhs` (`nim`),
ADD CONSTRAINT `tb_unilaidosen_ibfk_3` FOREIGN KEY (`nip`) REFERENCES `tb_dosen` (`nip`);

--
-- Constraints for table `tb_unilaiindustri`
--
ALTER TABLE `tb_unilaiindustri`
ADD CONSTRAINT `tb_unilaiindustri_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_nilaiindustri` (`id`),
ADD CONSTRAINT `tb_unilaiindustri_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `tb_mhs` (`nim`),
ADD CONSTRAINT `tb_unilaiindustri_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;