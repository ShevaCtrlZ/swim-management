-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2025 at 03:19 PM
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
  `peserta_id` bigint UNSIGNED NOT NULL,
  `no_lintasan` int DEFAULT NULL,
  `urutan` int DEFAULT NULL,
  `catatan_waktu` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_lomba`
--

INSERT INTO `detail_lomba` (`id`, `lomba_id`, `peserta_id`, `no_lintasan`, `urutan`, `catatan_waktu`, `created_at`, `updated_at`, `start_time`) VALUES
(2, 1, 2, 5, 2, '00:19:21', NULL, '2025-04-22 08:05:27', NULL),
(3, 1, 3, 1, 1, '00:01:05', NULL, '2025-04-23 21:01:51', '2025-04-23 20:47:25'),
(6, 1, 14, 2, 1, '00:00:02', '2025-04-21 02:14:25', '2025-04-23 13:20:20', '2025-04-23 19:59:49'),
(7, 1, 15, 3, 1, '00:00:00', '2025-04-21 02:19:42', '2025-04-23 21:01:53', '2025-04-23 20:47:26'),
(8, 1, 16, 4, 1, '00:00:02', '2025-04-21 02:20:05', '2025-04-23 13:20:21', '2025-04-23 19:59:49'),
(15, 1, 26, 8, 2, '00:00:07', '2025-04-21 08:25:03', '2025-04-23 21:01:55', '2025-04-23 20:47:26'),
(16, 1, 27, 7, 2, '00:00:02', '2025-04-21 08:25:46', '2025-04-23 13:20:20', '2025-04-23 19:59:49'),
(17, 1, 28, 6, 2, '00:00:02', '2025-04-21 08:26:13', '2025-04-23 13:20:21', '2025-04-23 19:59:49'),
(22, 4, 33, 2, 1, NULL, '2025-04-23 21:01:10', '2025-04-23 21:01:10', NULL),
(23, 4, 34, 1, 1, NULL, '2025-04-23 21:06:19', '2025-04-23 21:06:19', NULL),
(24, 4, 35, 3, 1, NULL, '2025-04-23 21:06:51', '2025-04-23 21:06:51', NULL),
(25, 5, 36, NULL, NULL, '00:00:05', '2025-04-23 22:04:11', '2025-04-23 22:07:45', NULL),
(26, 5, 37, NULL, NULL, '00:00:11', '2025-04-23 22:05:41', '2025-04-23 22:07:51', NULL);

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
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `klub`
--

INSERT INTO `klub` (`id`, `nama_klub`, `alamat`, `kontak`, `created_at`, `updated_at`) VALUES
(1, 'Swiming Club', 'Bengkal', '08100001', '2025-04-20 21:47:39', '2025-04-23 21:07:24'),
(2, 'Letek Jaya', 'Jl Laksamana Maeda', '081212121212', '2025-04-21 02:17:17', '2025-04-21 02:17:17');

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
(1, 'Letek Cup', '2025-04-21', '2025-04-25', 'Pacitan', NULL, NULL),
(3, 'Pacitan Cup', '2025-04-24', '2025-05-01', 'jaten garden', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lomba`
--

CREATE TABLE `lomba` (
  `id` bigint UNSIGNED NOT NULL,
  `kompetisi_id` bigint UNSIGNED NOT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak` int NOT NULL,
  `jenis_gaya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_lintasan` int NOT NULL,
  `nomor_lomba` int DEFAULT NULL,
  `tahun_lahir_minimal` int DEFAULT NULL,
  `tahun_lahir_maksimal` int DEFAULT NULL,
  `jk` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lomba`
--

INSERT INTO `lomba` (`id`, `kompetisi_id`, `start_time`, `jarak`, `jenis_gaya`, `jumlah_lintasan`, `nomor_lomba`, `tahun_lahir_minimal`, `tahun_lahir_maksimal`, `jk`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 100, 'bebas', 4, 1, 2012, 2010, 'Laki-laki', NULL, NULL),
(4, 1, NULL, 50, 'bebas', 4, 2, 2012, 2010, 'Laki-laki', NULL, NULL),
(5, 3, NULL, 100, 'Dada', 4, 1, 2010, 2008, 'Laki-laki', NULL, NULL);

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
(12, '2025_04_23_051634_create_wasit_table', 2),
(13, '2025_04_23_191444_add_start_time_to_lomba_table', 3),
(14, '2025_04_23_192222_add_start_time_to_detail_lomba_table', 4);

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
  `limit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lomba_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id`, `klub_id`, `nama_peserta`, `jenis_kelamin`, `tgl_lahir`, `asal_klub`, `limit`, `lomba_id`, `created_at`, `updated_at`) VALUES
(2, 1, 'Ronaldo Nazario', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(3, 1, 'Tamtama', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(4, 1, 'El Tama', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(6, 1, 'HAJI GAMING', 'L', '2025-01-21', 'Swiming Club', '12,5', 1, NULL, NULL),
(9, 1, 'toldetol', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(10, 1, 'Dontol', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(11, 1, 'dontrol', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(14, 1, 'mbappe', 'L', '2010-10-10', 'Swiming Club', '99:99:99', 1, NULL, NULL),
(15, 2, 'BRavo', 'L', '2010-10-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(16, 2, 'Gonzalo', 'L', '2010-10-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(17, 2, 'freaky', 'L', '2010-10-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(18, 2, 'higuain', 'L', '2010-02-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(26, 2, 'geanza', 'L', '2010-10-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(27, 2, 'fiii', 'L', '2010-10-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(28, 2, 'greas', 'L', '2010-10-10', 'Letek Jaya', '99:99:99', 1, NULL, NULL),
(33, 1, 'Kevin', 'L', '2012-12-12', 'Swiming Club', '99:99:99', 4, NULL, NULL),
(34, 2, 'Katmun JR', 'L', '2012-12-12', 'Letek Jaya', '99:99:99', 4, NULL, NULL),
(35, 2, 'tama jaya', 'L', '2012-12-12', 'Letek Jaya', '99:99:99', 4, NULL, NULL),
(36, 2, 'iqbal', 'L', '2009-08-17', 'Letek Jaya', '99:99:99', 5, NULL, NULL),
(37, 1, 'mamadgo', 'L', '2009-12-12', 'Swiming Club', '99:99:99', 5, NULL, NULL);

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
('jUDmv2e5Do33S5OKJUHqR2oAJlX496F12898lMhP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWVljN3hmRmhDZU9VOThMNDFxaWo1SGd6QllWWHVLYXpmc1FyWHBzMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saWhhdF9rb21wZXRpc2kvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1745752005),
('p4E5rtAjdTd2PIhby0pQYhws4ibVS7WavaQhz52C', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWU5zbXNmVUtWcm0wRjNWY2t2TEQyVzJoZm9uZWNkS2FSQU9SSVpwciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saWhhdF9rb21wZXRpc2kvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1745766933);

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
(1, 'atmin', 'atmin@gmail.com', NULL, '$2y$12$1Tjo2ufjgTP3uUSX.0MqVuDfNlE5.zkb2ODGfbD4mGWL.62FVrV0y', 'PjEEzkfpHvNO1TocUeTu6WHPPw1OAyJ4zCQaaALYKIIUkdoInrq15gKTopGT', '2025-04-20 21:46:44', '2025-04-20 21:46:44', NULL, 'admin'),
(2, 'Swiming Club', 'swim@gmail.com', NULL, '$2y$12$U/JIZ.kw4ZYFioAml2FDK.kNy1Lq5kB8f0Aax0XDO0fwMnsRXmlnO', NULL, '2025-04-20 21:47:39', '2025-04-20 21:47:39', 1, 'klub'),
(3, 'Letek Jaya', 'letek@gmail.com', NULL, '$2y$12$vW2H5ZX15k6WcPtjOBYMM.9uFKH0ITbj3ifc0sG9g0bSJdT8xiV6W', NULL, '2025-04-21 02:17:17', '2025-04-21 02:17:17', 2, 'klub');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kompetisi`
--
ALTER TABLE `kompetisi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lomba`
--
ALTER TABLE `lomba`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
