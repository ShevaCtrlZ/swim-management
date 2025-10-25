-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 25, 2025 at 07:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tugas_akhir`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_admin@example.co|127.0.0.1', 'i:1;', 1754834711),
('laravel_cache_admin@example.co|127.0.0.1:timer', 'i:1754834711;', 1754834711),
('laravel_cache_admin@example.com|127.0.0.1', 'i:1;', 1756780368),
('laravel_cache_admin@example.com|127.0.0.1:timer', 'i:1756780368;', 1756780368),
('laravel_cache_atmin@gmail.com|127.0.0.1', 'i:1;', 1754833798),
('laravel_cache_atmin@gmail.com|127.0.0.1:timer', 'i:1754833798;', 1754833798);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_lomba`
--

CREATE TABLE `detail_lomba` (
  `id` bigint UNSIGNED NOT NULL,
  `lomba_id` bigint UNSIGNED NOT NULL,
  `seri` int DEFAULT NULL,
  `peserta_id` bigint UNSIGNED NOT NULL,
  `no_lintasan` int DEFAULT NULL,
  `urutan` int DEFAULT NULL,
  `catatan_waktu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `limit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_lomba`
--

INSERT INTO `detail_lomba` (`id`, `lomba_id`, `seri`, `peserta_id`, `no_lintasan`, `urutan`, `catatan_waktu`, `keterangan`, `created_at`, `updated_at`, `limit`) VALUES
(1, 3, 1, 3, 1, 1, '00:09:00', NULL, '2025-08-10 08:07:45', '2025-08-10 08:07:45', '99:99:99'),
(2, 3, 1, 5, 2, 1, '00:10:00', NULL, '2025-08-10 08:12:26', '2025-08-10 08:12:26', '99:99:99'),
(3, 3, 2, 11, 2, 2, '00:08:25', NULL, '2025-08-10 10:18:01', '2025-08-10 10:18:01', '99:99:99'),
(4, 4, 1, 11, 2, 1, '00:10:00', NULL, '2025-08-10 10:18:01', '2025-08-10 10:18:01', '99:99:99'),
(5, 3, 2, 15, 3, 2, '00:11:00', NULL, '2025-08-14 16:15:05', '2025-08-14 16:15:05', '99:99:99'),
(6, 4, 1, 15, 3, 1, '00:21:77', NULL, '2025-08-14 16:15:05', '2025-08-14 16:15:05', '99:99:99'),
(7, 5, 1, 15, 2, 1, '00:13:00', NULL, '2025-08-14 16:15:05', '2025-08-14 16:15:05', '99:99:99'),
(8, 3, 2, 12, 1, 2, '00:10:00', NULL, '2025-08-14 16:15:25', '2025-08-14 16:15:25', '99:99:99'),
(9, 4, 1, 12, 1, 1, '00:12:00', NULL, '2025-08-14 16:15:25', '2025-08-14 16:15:25', '99:99:99'),
(10, 5, 1, 12, 3, 1, '00:09:00', NULL, '2025-08-14 16:15:25', '2025-08-14 16:15:25', '99:99:99'),
(11, 3, 2, 13, 4, 2, '00:07:00', NULL, '2025-08-14 16:15:46', '2025-08-14 16:15:46', '99:99:99'),
(12, 4, 1, 13, 4, 1, '00:10:00', NULL, '2025-08-14 16:15:46', '2025-08-14 16:15:46', '99:99:99'),
(13, 5, 1, 13, 1, 1, '00:10:00', NULL, '2025-08-14 16:15:46', '2025-08-14 16:15:46', '99:99:99');

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klub`
--

CREATE TABLE `klub` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_klub` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Total biaya yang dikeluarkan oleh klub',
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `klub`
--

INSERT INTO `klub` (`id`, `nama_klub`, `total_harga`, `alamat`, `kontak`, `created_at`, `updated_at`) VALUES
(1, 'Letek Jaya', '0.00', 'Bengkal', '081212121212', '2025-08-10 07:08:01', '2025-08-10 07:08:01'),
(2, 'Swimming club', '0.00', 'Jl Laksamana Maeda', '08115251981', '2025-08-16 04:36:34', '2025-08-16 04:36:34');

-- --------------------------------------------------------

--
-- Table structure for table `kompetisi`
--

CREATE TABLE `kompetisi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kompetisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kompetisi`
--

INSERT INTO `kompetisi` (`id`, `nama_kompetisi`, `tgl_mulai`, `tgl_selesai`, `lokasi`, `created_at`, `updated_at`) VALUES
(2, 'Pacitan Cup', '2025-08-30', '2025-09-02', 'jaten garden', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lomba`
--

CREATE TABLE `lomba` (
  `id` bigint UNSIGNED NOT NULL,
  `kompetisi_id` bigint UNSIGNED NOT NULL,
  `jarak` int NOT NULL,
  `jenis_gaya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_lintasan` int NOT NULL,
  `nomor_lomba` int DEFAULT NULL,
  `tahun_lahir_minimal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_lahir_maksimal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lomba`
--

INSERT INTO `lomba` (`id`, `kompetisi_id`, `jarak`, `jenis_gaya`, `jumlah_lintasan`, `nomor_lomba`, `tahun_lahir_minimal`, `tahun_lahir_maksimal`, `jk`, `harga`, `created_at`, `updated_at`) VALUES
(3, 2, 25, 'bebas', 4, 1, '2012', '2012', 'Laki-laki', '10000.00', NULL, NULL),
(4, 2, 25, 'Kupu', 4, 2, '2012', '2012', 'L', '10000.00', NULL, NULL),
(5, 2, 25, 'Dada', 4, 3, '2012', '2012', 'L', '10000.00', NULL, NULL);

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_19_025328_create_kompetisi_table', 1),
(5, '2025_03_19_025418_create_lomba_table', 1),
(6, '2025_03_19_025623_create_peserta_table', 1),
(7, '2025_03_19_031231_create_detail_lomba_table', 1),
(8, '2025_04_12_130550_create_klub_table', 1),
(9, '2025_04_12_131124_add_klub_id_to_users_table', 1),
(10, '2025_04_12_135137_add_role_to_users_table', 1),
(11, '2025_04_13_032755_add_klub_id_to_peserta_table', 1),
(12, '2025_04_23_051634_create_wasit_table', 1),
(13, '2025_06_24_045710_add_harga_to_lomba', 1),
(14, '2025_06_24_052749_add_total_biaya_to_klub_table', 1),
(15, '2025_08_05_061849_change_catatan_waktu_type_on_detail_lomba_table', 1),
(16, '2025_08_05_064403_add_keterangan_to_detail_lomba_table', 1),
(17, '2025_08_05_092407_add_seri_to_detail_lomba_table', 1),
(18, '2025_08_06_074848_add_limit_to_detail_lomba_table', 1),
(19, '2025_08_06_080518_remove_limit_from_peserta_table', 1);

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
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id` bigint UNSIGNED NOT NULL,
  `klub_id` bigint UNSIGNED DEFAULT NULL,
  `nama_peserta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `asal_klub` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lomba_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id`, `klub_id`, `nama_peserta`, `jenis_kelamin`, `tgl_lahir`, `asal_klub`, `lomba_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Messi', 'L', '2010-10-11', 'Letek Jaya', NULL, NULL, NULL),
(2, 1, 'Ronaldo Nazario', 'L', '2011-02-23', 'Letek Jaya', NULL, NULL, NULL),
(3, 1, 'Tamtama', 'L', '2012-12-11', 'Letek Jaya', NULL, NULL, NULL),
(4, 1, 'El Tama', 'L', '2011-01-11', 'Letek Jaya', NULL, NULL, NULL),
(5, 1, 'dani', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(6, 1, 'hansda', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(7, 1, 'jqdo', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(8, 1, 'mbpae', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(9, 1, 'ginting', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(10, 1, 'kiriada', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(11, 1, 'vincent', 'L', '2012-12-12', 'Letek Jaya', NULL, NULL, NULL),
(12, 1, 'dontrol', 'L', '2012-06-12', 'Letek Jaya', NULL, NULL, NULL),
(13, 1, 'dijk', 'L', '2012-01-01', 'Letek Jaya', NULL, NULL, NULL),
(14, 1, 'Modric', 'L', '2012-11-11', 'Letek Jaya', NULL, NULL, NULL),
(15, 1, 'Klop', 'L', '2012-12-11', 'Letek Jaya', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2mHM6kzLlEkejtkn58mUWsYQ0CSTqRaodzqN8tr7', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMGZTYXptTTQxNkhEYnlWT1h4U29UTVBndkZ5dDVJdEFDWlVyOGxyWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rb21wZXRpc2kvMi9zdGFydGluZy1saXN0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1761376068),
('7wCOWkVW8Tk7hFB0NG7CQY7O7ug46kzuky2lhio0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3BaRTduYzkyUmJnem1VdVJPc1Rpcnl3cHJRWlAzMnZ3QXVyUUxnTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761370935);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `klub_id` bigint UNSIGNED DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'klub'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `klub_id`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2a$12$F2cpgBgmIpj4G/KCAS0KuOR1yfAzYhbgAi0Wfq9lhXfcJh27Gu1XO', NULL, NULL, NULL, NULL, 'admin'),
(2, 'Admin', 'admin@example.com', NULL, '$2y$12$mt5oAIiUWGe2Fvf4DQesgeGzyTuTMYN/lqfO8.k5HR6Hk7L93Y//S', NULL, '2025-08-10 07:01:57', '2025-08-10 07:01:57', NULL, 'admin'),
(3, 'Letek Jaya', 'letek@gmail.com', NULL, '$2y$12$rcplqC3sY/u/svJs8rioc.2EKSxre9mj6m2gL2G74llK9NAEEwtdW', NULL, '2025-08-10 07:08:02', '2025-08-10 07:08:02', 1, 'klub'),
(4, 'Swimming club', 'swim@gmail.com', NULL, '$2y$12$ErWsSwMUjrKzKOrpqxAPkOVyxGt8XzSgfprpH3gtdAQmFXtDvzime', NULL, '2025-08-16 04:36:35', '2025-08-16 04:36:35', 2, 'klub'),
(5, 'Admin', 'admin@swimtour.com', NULL, '$2y$12$t6PmfYfpezX.5XnX9VWof.FQ.YLlLVkgbTqtYZfAsoZIr0W09cpZ2', NULL, '2025-09-01 19:35:57', '2025-09-01 19:35:57', NULL, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `wasit`
--

CREATE TABLE `wasit` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lomba_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_lomba`
--
ALTER TABLE `detail_lomba`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_lomba_lomba_id_foreign` (`lomba_id`),
  ADD KEY `detail_lomba_peserta_id_foreign` (`peserta_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klub`
--
ALTER TABLE `klub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kompetisi`
--
ALTER TABLE `kompetisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lomba`
--
ALTER TABLE `lomba`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lomba_kompetisi_id_foreign` (`kompetisi_id`);

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
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peserta_lomba_id_foreign` (`lomba_id`),
  ADD KEY `peserta_klub_id_foreign` (`klub_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_klub_id_foreign` (`klub_id`);

--
-- Indexes for table `wasit`
--
ALTER TABLE `wasit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wasit_lomba_id_foreign` (`lomba_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_lomba`
--
ALTER TABLE `detail_lomba`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `klub`
--
ALTER TABLE `klub`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kompetisi`
--
ALTER TABLE `kompetisi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lomba`
--
ALTER TABLE `lomba`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wasit`
--
ALTER TABLE `wasit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_lomba`
--
ALTER TABLE `detail_lomba`
  ADD CONSTRAINT `detail_lomba_lomba_id_foreign` FOREIGN KEY (`lomba_id`) REFERENCES `lomba` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_lomba_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lomba`
--
ALTER TABLE `lomba`
  ADD CONSTRAINT `lomba_kompetisi_id_foreign` FOREIGN KEY (`kompetisi_id`) REFERENCES `kompetisi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peserta`
--
ALTER TABLE `peserta`
  ADD CONSTRAINT `peserta_klub_id_foreign` FOREIGN KEY (`klub_id`) REFERENCES `klub` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peserta_lomba_id_foreign` FOREIGN KEY (`lomba_id`) REFERENCES `lomba` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_klub_id_foreign` FOREIGN KEY (`klub_id`) REFERENCES `klub` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wasit`
--
ALTER TABLE `wasit`
  ADD CONSTRAINT `wasit_lomba_id_foreign` FOREIGN KEY (`lomba_id`) REFERENCES `lomba` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
