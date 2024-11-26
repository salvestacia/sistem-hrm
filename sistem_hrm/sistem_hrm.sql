/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `absensi` (
  `id_absensi` int(5) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(5) DEFAULT NULL,
  `tgl_tap` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_absensi`),
  KEY `id_pegawai` (`id_pegawai`),
  CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cuti` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(5) DEFAULT NULL,
  `jenis_cuti` enum('sakit','izin') DEFAULT NULL,
  `tanggal_awal` date DEFAULT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `alasan_cuti` varchar(100) DEFAULT NULL,
  `status` enum('approve','reject') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pegawai` (`id_pegawai`),
  CONSTRAINT `cuti_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `hak_akses` (
  `id_hak_akses` int(10) NOT NULL,
  `nama_hak_akses` varchar(50) DEFAULT NULL,
  `fitur` varchar(50) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  KEY `role_id` (`id_hak_akses`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `payroll` (
  `id_payroll` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(5) DEFAULT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `total_jam_kerja` int(20) DEFAULT NULL,
  `gaji` int(20) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_payroll`),
  KEY `id_pegawai` (`id_pegawai`),
  CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pegawai` (
  `id_pegawai` int(5) NOT NULL AUTO_INCREMENT,
  `nik` char(16) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `divisi` varchar(50) DEFAULT NULL,
  `gaji_pokok` int(20) DEFAULT NULL,
  `hari_kerja_1_bulan` int(20) DEFAULT NULL,
  `jam_kerja_1_hari` int(20) DEFAULT NULL,
  PRIMARY KEY (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `id_pegawai` int(5) DEFAULT NULL,
  `hak_akses` int(10) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `last_change_pass` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_pegawai` (`id_pegawai`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `absensi` (`id_absensi`, `id_pegawai`, `tgl_tap`, `status`) VALUES
(1, 3, '2024-10-13 08:14:07', 'Tap In');
INSERT INTO `absensi` (`id_absensi`, `id_pegawai`, `tgl_tap`, `status`) VALUES
(2, 3, '2024-10-13 17:14:11', 'Tap Out');
INSERT INTO `absensi` (`id_absensi`, `id_pegawai`, `tgl_tap`, `status`) VALUES
(3, 3, '2024-10-14 08:53:00', 'Tap In');
INSERT INTO `absensi` (`id_absensi`, `id_pegawai`, `tgl_tap`, `status`) VALUES
(4, 3, '2024-10-14 17:53:02', 'Tap Out'),
(5, 3, '2024-10-15 08:53:04', 'Tap In'),
(6, 3, '2024-10-15 17:53:04', 'Tap Out'),
(7, 3, '2024-10-16 08:53:05', 'Tap In'),
(8, 3, '2024-10-16 17:53:05', 'Tap Out'),
(9, 3, '2024-10-17 08:53:06', 'Tap In'),
(10, 3, '2024-10-17 17:53:06', 'Tap Out'),
(11, 3, '2024-11-18 07:35:59', 'Tap In'),
(12, 3, '2024-11-18 14:36:03', 'Tap Out'),
(13, 3, '2024-11-19 07:00:02', 'Tap In'),
(14, 3, '2024-11-19 10:48:04', 'Tap Out'),
(15, 3, '2024-11-20 08:10:30', 'Tap In'),
(16, 3, '2024-11-20 18:19:31', 'Tap Out'),
(17, 3, '2024-11-21 08:24:01', 'Tap In'),
(18, 3, '2024-11-21 17:24:02', 'Tap Out'),
(19, 3, '2024-11-22 08:24:02', 'Tap In'),
(20, 3, '2024-11-22 16:24:03', 'Tap Out'),
(21, 3, '2024-11-23 09:24:03', 'Tap In'),
(22, 3, '2024-11-23 20:24:04', 'Tap Out'),
(23, 3, '2024-11-24 07:24:04', 'Tap In'),
(24, 3, '2024-11-24 18:24:05', 'Tap Out'),
(25, 3, '2024-11-25 08:24:05', 'Tap In'),
(26, 3, '2024-11-25 20:24:05', 'Tap Out'),
(27, 3, '2024-11-26 08:24:06', 'Tap In'),
(28, 3, '2024-11-26 15:24:06', 'Tap Out'),
(29, 3, '2024-11-27 08:24:06', 'Tap In'),
(30, 3, '2024-11-27 16:24:07', 'Tap Out'),
(31, 3, '2024-11-28 10:24:07', 'Tap In'),
(32, 3, '2024-11-28 17:24:07', 'Tap Out'),
(33, 1, '2024-11-29 08:00:29', 'Tap In'),
(34, 1, '2024-11-29 17:51:30', 'Tap Out'),
(35, 3, '2024-11-29 08:17:03', 'Tap In'),
(36, 3, '2024-11-29 17:30:15', 'Tap Out');

INSERT INTO `cuti` (`id`, `id_pegawai`, `jenis_cuti`, `tanggal_awal`, `tanggal_akhir`, `alasan_cuti`, `status`) VALUES
(3, 1, 'sakit', '2024-11-18', '2024-11-19', 'sakit', 'reject');
INSERT INTO `cuti` (`id`, `id_pegawai`, `jenis_cuti`, `tanggal_awal`, `tanggal_akhir`, `alasan_cuti`, `status`) VALUES
(4, 1, 'izin', '2024-11-18', '2024-11-29', 'pelatihan coding', 'reject');
INSERT INTO `cuti` (`id`, `id_pegawai`, `jenis_cuti`, `tanggal_awal`, `tanggal_akhir`, `alasan_cuti`, `status`) VALUES
(5, 1, 'sakit', '2024-11-18', '2024-11-19', 'sakit', 'approve');
INSERT INTO `cuti` (`id`, `id_pegawai`, `jenis_cuti`, `tanggal_awal`, `tanggal_akhir`, `alasan_cuti`, `status`) VALUES
(6, 1, 'sakit', '2024-11-18', '2024-11-19', 'sakit', 'reject'),
(7, 1, 'sakit', '2024-11-19', '2024-11-20', 'sakit', 'reject'),
(8, 2, 'izin', '2024-11-19', '2024-11-22', 'pelatihan akuntansi 2.0', 'approve'),
(9, 2, 'sakit', '2024-11-19', '2024-11-19', 'pemeriksaan badan', 'approve'),
(10, 2, 'izin', '2024-11-20', '2024-11-20', 'menjenguk ibu', 'approve'),
(11, 2, 'izin', '2024-11-19', '2024-11-22', 'pelatihan bootcamp coding', 'approve'),
(12, 1, 'sakit', '2024-11-21', '2024-11-25', 'sakit demam', 'approve'),
(13, 1, 'sakit', '2024-11-21', '2024-11-22', 'sakit perut', 'reject'),
(14, 2, 'izin', '2024-11-25', '2024-11-29', 'pelatihan machine learning ', 'approve'),
(15, 1, 'izin', '2024-11-25', '2024-11-29', 'pelatihan web develompent 3.0', 'approve');

INSERT INTO `hak_akses` (`id_hak_akses`, `nama_hak_akses`, `fitur`, `link`) VALUES
(1, 'Karyawan', 'Presensi', 'http://localhost/sistem_hrm/absensi.php');
INSERT INTO `hak_akses` (`id_hak_akses`, `nama_hak_akses`, `fitur`, `link`) VALUES
(2, 'Admin', 'Manajemen Data Karyawan', 'http://localhost/sistem_hrm/tabel_pegawai/read.php');
INSERT INTO `hak_akses` (`id_hak_akses`, `nama_hak_akses`, `fitur`, `link`) VALUES
(2, 'Admin', 'Manajemen Data User', 'http://localhost/sistem_hrm/tabel_user/read.php');
INSERT INTO `hak_akses` (`id_hak_akses`, `nama_hak_akses`, `fitur`, `link`) VALUES
(2, 'Admin', 'Presensi', 'http://localhost/sistem_hrm/absensi.php'),
(2, 'Admin', 'Rekap Presensi', 'http://localhost/sistem_hrm/rekapAbsensi.php'),
(2, 'Admin', 'Payroll', 'http://localhost/sistem_hrm/payroll/readPayroll.php'),
(2, 'Admin', 'Profile', 'http://localhost/sistem_hrm/profile.php'),
(1, 'Karyawan', 'Profile', 'http://localhost/sistem_hrm/profile.php'),
(2, 'Admin', 'Manajemen Cuti', 'http://localhost/sistem_hrm/manajemen_cuti.php'),
(1, 'Karyawan', 'Pengajuan Cuti', 'http://localhost/sistem_hrm/pengajuan_cuti.php');

INSERT INTO `payroll` (`id_payroll`, `id_pegawai`, `periode_awal`, `periode_akhir`, `total_jam_kerja`, `gaji`, `keterangan`) VALUES
(6, 3, '2024-10-13', '2024-10-16', 45, 3125000, 'gaji 13-16 okt 2024');
INSERT INTO `payroll` (`id_payroll`, `id_pegawai`, `periode_awal`, `periode_akhir`, `total_jam_kerja`, `gaji`, `keterangan`) VALUES
(7, 3, '2024-10-13', '2024-10-16', 45, 3125000, 'gaji');
INSERT INTO `payroll` (`id_payroll`, `id_pegawai`, `periode_awal`, `periode_akhir`, `total_jam_kerja`, `gaji`, `keterangan`) VALUES
(8, 3, '2024-11-13', '2024-11-29', 141, 9791667, 'gaji 13-29 nov');
INSERT INTO `payroll` (`id_payroll`, `id_pegawai`, `periode_awal`, `periode_akhir`, `total_jam_kerja`, `gaji`, `keterangan`) VALUES
(10, 3, '2024-11-13', '2024-11-29', 141, 9791667, 'gaji 13-29 nov. pakai dgn sebaiknya!'),
(11, 3, '2024-11-13', '2024-11-29', 141, 9791667, 'gaji ya'),
(12, 3, '2024-11-13', '2024-11-29', 141, 9791667, 'gaji yoks');

INSERT INTO `pegawai` (`id_pegawai`, `nik`, `nama`, `alamat`, `tgl_lahir`, `divisi`, `gaji_pokok`, `hari_kerja_1_bulan`, `jam_kerja_1_hari`) VALUES
(1, '6482910375264892', 'Darren Christian', 'Kelapa Gading', '2004-06-13', 'IT', 6000000, 20, 8);
INSERT INTO `pegawai` (`id_pegawai`, `nik`, `nama`, `alamat`, `tgl_lahir`, `divisi`, `gaji_pokok`, `hari_kerja_1_bulan`, `jam_kerja_1_hari`) VALUES
(2, '6482910375264892', 'Fiony Alveria Tantri', 'Kelapa Gading', '2004-06-12', 'UI/UX', 6000000, 20, 8);
INSERT INTO `pegawai` (`id_pegawai`, `nik`, `nama`, `alamat`, `tgl_lahir`, `divisi`, `gaji_pokok`, `hari_kerja_1_bulan`, `jam_kerja_1_hari`) VALUES
(3, '6482910375264892', 'Jessica Chandra', 'Jakarta Utara', '2004-06-13', 'IT', 10000000, 18, 8);
INSERT INTO `pegawai` (`id_pegawai`, `nik`, `nama`, `alamat`, `tgl_lahir`, `divisi`, `gaji_pokok`, `hari_kerja_1_bulan`, `jam_kerja_1_hari`) VALUES
(11, '6482910375264892', 'Stacia Christy', 'Kelapa Gading', '2004-06-13', 'Accounting', 2000000, 20, 8);

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `id_pegawai`, `hak_akses`, `reset_token`, `reset_expires`, `last_change_pass`) VALUES
(1, 'darren', '2022105358@student.kalbis.ac.id', '123456', 1, 1, NULL, NULL, '2024-10-24 18:26:20');
INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `id_pegawai`, `hak_akses`, `reset_token`, `reset_expires`, `last_change_pass`) VALUES
(2, 'fiony', 'darrensiwibu@gmail.com', '123456', 2, 1, NULL, NULL, '2024-10-24 18:25:49');
INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `id_pegawai`, `hak_akses`, `reset_token`, `reset_expires`, `last_change_pass`) VALUES
(3, 'jessi', 'darrenchrist2@gmail.com', '123456', 3, 2, NULL, NULL, '2024-10-24 18:28:08');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;