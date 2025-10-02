-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 02, 2025 at 05:59 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id` int NOT NULL,
  `id_anggota` varchar(100) DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foto` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `alamat` text,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Aktif',
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `instansi` varchar(100) DEFAULT NULL,
  `tanggal_daftar` date DEFAULT NULL,
  `berlaku_hingga` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id`, `id_anggota`, `nama`, `foto`, `created_at`, `updated_at`, `jenis_kelamin`, `alamat`, `nomor_hp`, `email`, `status`, `pendidikan_terakhir`, `pekerjaan`, `instansi`, `tanggal_daftar`, `berlaku_hingga`) VALUES
(6, 'A-20251001-001', 'adad', '1759278111_68dc741f4c180.jpg', '2025-09-30 16:21:51', '2025-09-30 16:34:18', 'Laki-laki', 'adadd', '2424224', 'oswaldwilybald@gmail.com', 'Tidak Aktif', 'SD', 'apdapd', 'pns', '2025-11-06', '2025-10-29'),
(8, 'A-20251001-003', 'kepo', '1759280983_68dc7f57b0419.jpeg', '2025-09-30 17:09:43', '2025-09-30 17:09:43', 'Laki-laki', 'jkt', '24444', 'oswaldwilybald@gmail.com', 'Tidak Aktif', 'SD', 'Buruh', 'pns', '2025-10-24', '2025-11-07'),
(9, 'A-20251001-004', 'wrrwrwr', '1759281462_68dc81363f3e4.jpeg', '2025-09-30 17:17:42', '2025-09-30 17:17:42', 'Laki-laki', 'wrwrrw', '23524242424', 'skgfsfn@gmail.com', 'Aktif', 'SMA/SMK', 'wdewe', 'wrrrwrwr', '2025-10-01', '2025-11-07'),
(11, 'A-20251001-005', 'konjuring', '1759289530_68dca0bab6470.jpeg', '2025-09-30 19:32:10', '2025-09-30 21:47:16', 'Laki-laki', 'bjm', '33131313131', 'oswaldwilybald@gmail.com', 'Aktif', 'S3', 'Pilot', 'qmall', NULL, NULL),
(12, 'A-20251001-006', 'joko', '1759297614_68dcc04ec987c.jpeg', '2025-09-30 21:46:54', '2025-09-30 21:46:54', 'Laki-laki', 'bjb', '11212121', 'oswaldwilybald@gmail.com', 'Aktif', 'SD', 'gur', 'qmall', '2025-10-01', '2025-10-30');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_buku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_buku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengarang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `isbn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_halaman` int DEFAULT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `status` enum('Ada','Dipinjam','Hilang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `kode_buku`, `judul_buku`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `kategori`, `jumlah_halaman`, `stok`, `status`, `created_at`, `updated_at`) VALUES
(1, '900-25100001', 'ewean', 'rijal', 'noori', 1911, 'ya', 'porn', 5, 1, 'Ada', '2025-09-30 22:07:21', '2025-09-30 22:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_buku`
--

INSERT INTO `kategori_buku` (`id`, `kode`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(2, '100', 'Filsafat & Psikologi', 'Buku tentang filsafat, metafisika, epistemologi, etika, logika, dan psikologi.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(3, '200', 'Agama', 'Buku tentang agama-agama di dunia (Islam, Kristen, Hindu, Buddha, dll), teologi, dan filsafat agama.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(4, '300', 'Ilmu Sosial', 'Buku tentang sosiologi, politik, hukum, pendidikan, ekonomi, administrasi, hubungan internasional, dan kebudayaan.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(5, '400', 'Bahasa', 'Buku tentang linguistik, tata bahasa, kamus, dan pembelajaran berbagai bahasa.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(6, '500', 'Ilmu Murni (Sains)', 'Buku tentang sains dasar seperti matematika, astronomi, fisika, kimia, biologi, geologi, dan ekologi.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(7, '600', 'Teknologi & Ilmu Terapan', 'Buku tentang penerapan ilmu, misalnya teknik, kedokteran, pertanian, bisnis, manajemen, komputer terapan, dan rekayasa.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(8, '700', 'Seni, Rekreasi & Olahraga', 'Buku tentang seni rupa, musik, tari, teater, arsitektur, fotografi, desain, hobi, permainan, olahraga, dan hiburan.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(9, '800', 'Sastra', 'Buku tentang karya sastra: puisi, drama, prosa, novel, cerita pendek, esai, serta kritik sastra.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(10, '900', 'Sejarah & Geografi', 'Buku tentang sejarah dunia, biografi, perjalanan, geografi, dan ilmu bumi.', '2025-10-01 06:16:15', '2025-10-01 06:16:15'),
(11, '555', 'kpk', 'kepin bgl', '2025-09-30 22:24:08', '2025-09-30 22:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_01_060455_create_buku_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `level`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'admin', 'admin@gmail.com', NULL, '$2y$12$DahP7TwhYZgzkCG2BX1.xuY9LK.rk1igsCwoEBpLAcy2u2FsvooTi', NULL, '2025-09-26 06:11:15', '2025-09-26 06:11:15'),
(2, 'Akun User1', 'user1', 'user', 'user1@gmail.com', NULL, '$2y$12$raeA/Sxictb55GOw9yyIROrMxiZnb9N5oobhrAvCIVaKcw97KYjSy', NULL, '2025-09-26 06:11:15', '2025-09-26 06:11:15'),
(3, 'Akun User2', 'user2', 'user', 'user2@gmail.com', NULL, '$2y$12$tlEJwWrPFzR8L24qMK.FDOWcMCCOHtP5vPKidiFMU.JDtZt2Fu0vO', NULL, '2025-09-26 06:11:15', '2025-09-26 06:11:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buku_kode_buku_unique` (`kode_buku`),
  ADD KEY `buku_kode_buku_index` (`kode_buku`),
  ADD KEY `buku_judul_buku_index` (`judul_buku`),
  ADD KEY `buku_status_index` (`status`),
  ADD KEY `buku_kategori_index` (`kategori`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `idx_kode` (`kode`),
  ADD KEY `idx_nama_kategori` (`nama_kategori`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
