-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2026 at 09:57 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nurul`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_calendars`
--

CREATE TABLE `academic_calendars` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_holiday` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_calendars`
--

INSERT INTO `academic_calendars` (`id`, `date`, `description`, `is_holiday`, `created_at`, `updated_at`, `unit_id`) VALUES
(2, '2026-01-03', 'Libur Hari Sabtu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(3, '2026-01-04', 'Libur Hari Minggu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(4, '2026-01-10', 'Libur Hari Sabtu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(5, '2026-01-11', 'Libur Hari Minggu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(6, '2026-01-17', 'Libur Hari Sabtu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(7, '2026-01-18', 'Libur Hari Minggu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(8, '2026-01-24', 'Libur Hari Sabtu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(9, '2026-01-25', 'Libur Hari Minggu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(10, '2026-01-31', 'Libur Hari Sabtu', 1, '2025-12-19 06:58:21', '2025-12-19 06:58:21', 1),
(20, '2026-02-01', 'Libur Hari Minggu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(21, '2026-02-07', 'Libur Hari Sabtu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(22, '2026-02-08', 'Libur Hari Minggu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(23, '2026-02-14', 'Libur Hari Sabtu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(24, '2026-02-15', 'Libur Hari Minggu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(25, '2026-02-21', 'Libur Hari Sabtu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(26, '2026-02-22', 'Libur Hari Minggu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(27, '2026-02-28', 'Libur Hari Sabtu', 1, '2025-12-19 07:18:03', '2025-12-19 07:18:03', 1),
(30, '2026-03-01', 'Libur Hari Minggu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(31, '2026-03-07', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(32, '2026-03-08', 'Libur Hari Minggu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(33, '2026-03-14', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(34, '2026-03-15', 'Libur Hari Minggu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(35, '2026-03-21', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(36, '2026-03-22', 'Libur Hari Minggu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(37, '2026-03-28', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(38, '2026-03-29', 'Libur Hari Minggu', 1, '2025-12-19 07:23:04', '2025-12-19 07:23:04', 1),
(39, '2026-04-04', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(40, '2026-04-05', 'Libur Hari Minggu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(41, '2026-04-11', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(42, '2026-04-12', 'Libur Hari Minggu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(43, '2026-04-18', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(44, '2026-04-19', 'Libur Hari Minggu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(45, '2026-04-25', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(46, '2026-04-26', 'Libur Hari Minggu', 1, '2025-12-19 07:23:13', '2025-12-19 07:23:13', 1),
(47, '2026-05-02', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(48, '2026-05-03', 'Libur Hari Minggu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(49, '2026-05-09', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(50, '2026-05-10', 'Libur Hari Minggu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(51, '2026-05-16', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(52, '2026-05-17', 'Libur Hari Minggu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(53, '2026-05-23', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(54, '2026-05-24', 'Libur Hari Minggu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(55, '2026-05-30', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(56, '2026-05-31', 'Libur Hari Minggu', 1, '2025-12-19 07:23:23', '2025-12-19 07:23:23', 1),
(57, '2026-06-06', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(58, '2026-06-07', 'Libur Hari Minggu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(59, '2026-06-13', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(60, '2026-06-14', 'Libur Hari Minggu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(61, '2026-06-20', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(62, '2026-06-21', 'Libur Hari Minggu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(63, '2026-06-27', 'Libur Hari Sabtu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(64, '2026-06-28', 'Libur Hari Minggu', 1, '2025-12-19 07:23:33', '2025-12-19 07:23:33', 1),
(65, '2025-11-01', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(66, '2025-11-02', 'Libur Hari Minggu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(67, '2025-11-08', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(68, '2025-11-09', 'Libur Hari Minggu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(69, '2025-11-15', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(70, '2025-11-16', 'Libur Hari Minggu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(71, '2025-11-22', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(72, '2025-11-23', 'Libur Hari Minggu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(73, '2025-11-29', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(74, '2025-11-30', 'Libur Hari Minggu', 1, '2025-12-19 07:26:05', '2025-12-19 07:26:05', 1),
(75, '2025-10-04', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(76, '2025-10-05', 'Libur Hari Minggu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(77, '2025-10-11', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(78, '2025-10-12', 'Libur Hari Minggu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(79, '2025-10-18', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(80, '2025-10-19', 'Libur Hari Minggu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(81, '2025-10-25', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(82, '2025-10-26', 'Libur Hari Minggu', 1, '2025-12-19 07:26:14', '2025-12-19 07:26:14', 1),
(83, '2025-09-06', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(84, '2025-09-07', 'Libur Hari Minggu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(85, '2025-09-13', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(86, '2025-09-14', 'Libur Hari Minggu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(87, '2025-09-20', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(88, '2025-09-21', 'Libur Hari Minggu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(89, '2025-09-27', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(90, '2025-09-28', 'Libur Hari Minggu', 1, '2025-12-19 07:26:20', '2025-12-19 07:26:20', 1),
(91, '2025-08-02', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(92, '2025-08-03', 'Libur Hari Minggu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(93, '2025-08-09', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(94, '2025-08-10', 'Libur Hari Minggu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(95, '2025-08-16', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(96, '2025-08-17', 'Libur Hari Minggu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(97, '2025-08-23', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(98, '2025-08-24', 'Libur Hari Minggu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(99, '2025-08-30', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(100, '2025-08-31', 'Libur Hari Minggu', 1, '2025-12-19 07:26:26', '2025-12-19 07:26:26', 1),
(101, '2025-07-05', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(102, '2025-07-06', 'Libur Hari Minggu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(103, '2025-07-12', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(104, '2025-07-13', 'Libur Hari Minggu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(105, '2025-07-19', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(106, '2025-07-20', 'Libur Hari Minggu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(107, '2025-07-26', 'Libur Hari Sabtu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(108, '2025-07-27', 'Libur Hari Minggu', 1, '2025-12-19 07:26:31', '2025-12-19 07:26:31', 1),
(109, '2025-07-03', 'Libur', 1, '2025-12-19 07:27:08', '2025-12-19 07:27:08', 1),
(112, '2025-12-06', 'Libur Hari Sabtu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(113, '2025-12-07', 'Libur Hari Minggu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(114, '2025-12-13', 'Libur Hari Sabtu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(115, '2025-12-14', 'Libur Hari Minggu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(116, '2025-12-20', 'Libur Hari Sabtu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(117, '2025-12-21', 'Libur Hari Minggu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(118, '2025-12-27', 'Libur Hari Sabtu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(119, '2025-12-28', 'Libur Hari Minggu', 1, '2025-12-19 08:07:21', '2025-12-19 08:07:21', 2),
(122, '2025-12-19', 'Libur', 1, '2025-12-19 08:35:50', '2025-12-19 08:35:50', 2),
(123, '2025-12-06', 'Libur Hari Sabtu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(124, '2025-12-07', 'Libur Hari Minggu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(125, '2025-12-13', 'Libur Hari Sabtu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(126, '2025-12-14', 'Libur Hari Minggu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(127, '2025-12-19', 'Libur', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(128, '2025-12-20', 'Libur Hari Sabtu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(129, '2025-12-21', 'Libur Hari Minggu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(130, '2025-12-27', 'Libur Hari Sabtu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(131, '2025-12-28', 'Libur Hari Minggu', 1, '2025-12-19 14:10:17', '2025-12-19 14:10:17', 1),
(148, '2025-12-06', 'Libur Hari Sabtu', 1, '2025-12-21 15:56:45', '2025-12-21 15:56:45', 4),
(149, '2025-12-07', 'Libur Hari Minggu', 1, '2025-12-21 15:56:45', '2025-12-21 15:56:45', 4),
(150, '2025-12-13', 'Libur Hari Sabtu', 1, '2025-12-21 15:56:45', '2025-12-21 15:56:45', 4),
(151, '2025-12-14', 'Libur Hari Minggu', 1, '2025-12-21 15:56:45', '2025-12-21 15:56:45', 4),
(152, '2025-12-20', 'Libur Hari Sabtu', 1, '2025-12-21 15:56:45', '2025-12-21 15:56:45', 4),
(153, '2025-12-21', 'Libur Hari Minggu', 1, '2025-12-21 15:56:45', '2025-12-21 15:56:45', 4),
(158, '2026-01-03', 'Libur', 1, '2025-12-27 04:59:17', '2026-01-01 10:54:19', 4),
(159, '2026-01-04', 'Libur', 1, '2025-12-27 04:59:17', '2026-01-01 10:54:19', 4),
(160, '2026-01-10', 'Libur Hari Sabtu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(161, '2026-01-11', 'Libur Hari Minggu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(162, '2026-01-17', 'Libur Hari Sabtu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(163, '2026-01-18', 'Libur Hari Minggu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(164, '2026-01-24', 'Libur Hari Sabtu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(165, '2026-01-25', 'Libur Hari Minggu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(166, '2026-01-31', 'Libur Hari Sabtu', 1, '2025-12-27 04:59:17', '2025-12-27 04:59:17', 4),
(167, '2026-12-05', 'Libur Hari Sabtu', 1, '2025-12-27 11:31:09', '2025-12-27 11:31:09', 4),
(168, '2026-12-06', 'Libur Hari Minggu', 1, '2025-12-27 11:31:09', '2025-12-27 11:31:09', 4),
(169, '2026-12-12', 'Libur Hari Sabtu', 1, '2025-12-27 11:31:09', '2025-12-27 11:31:09', 4),
(170, '2026-12-13', 'Libur Hari Minggu', 1, '2025-12-27 11:31:09', '2025-12-27 11:31:09', 4),
(171, '2026-12-19', 'Libur Hari Sabtu', 1, '2025-12-27 11:31:09', '2025-12-27 11:31:09', 4),
(172, '2026-12-20', 'Libur Hari Minggu', 1, '2025-12-27 11:31:09', '2025-12-27 11:31:09', 4),
(174, '2025-12-06', 'Libur Hari Sabtu', 1, '2025-12-27 12:06:09', '2025-12-27 12:06:09', 5),
(175, '2025-12-07', 'Libur Hari Minggu', 1, '2025-12-27 12:06:09', '2025-12-27 12:06:09', 5),
(176, '2025-12-13', 'Libur Hari Sabtu', 1, '2025-12-27 12:06:09', '2025-12-27 12:06:09', 5),
(177, '2025-12-14', 'Libur Hari Minggu', 1, '2025-12-27 12:06:09', '2025-12-27 12:06:09', 5),
(178, '2025-12-20', 'Libur Hari Sabtu', 1, '2025-12-27 12:06:09', '2025-12-27 12:06:09', 5),
(179, '2025-12-21', 'Libur Hari Minggu', 1, '2025-12-27 12:06:09', '2025-12-27 12:06:09', 5),
(183, '2025-12-27', 'Libur Hari Sabtu', 1, '2025-12-27 12:09:59', '2025-12-27 12:09:59', 4),
(184, '2025-12-28', 'Libur Hari Minggu', 1, '2025-12-27 12:09:59', '2025-12-27 12:09:59', 4),
(185, '2025-12-27', 'Libur Hari Sabtu', 1, '2025-12-27 12:10:20', '2025-12-27 12:12:41', 5),
(186, '2025-12-28', 'Libur Hari Minggu', 1, '2025-12-27 12:10:20', '2025-12-27 12:10:20', 5),
(187, '2025-12-29', 'Libur', 1, '2025-12-27 12:10:20', '2025-12-27 12:10:20', 5),
(188, '2025-12-26', 'Libur', 1, '2025-12-27 12:10:28', '2025-12-27 12:10:28', 5),
(189, '2026-12-26', 'Libur Hari Sabtu', 1, '2026-01-01 08:36:14', '2026-01-01 08:36:14', 4),
(190, '2026-12-27', 'Libur Hari Minggu', 1, '2026-01-01 08:36:14', '2026-01-01 08:36:14', 4);

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` bigint UNSIGNED NOT NULL,
  `start_year` year NOT NULL,
  `end_year` year NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `start_year`, `end_year`, `status`, `created_at`, `updated_at`) VALUES
(5, '2025', '2026', 'active', '2025-12-17 15:40:06', '2026-01-01 11:48:23'),
(6, '2026', '2027', 'inactive', '2025-12-17 15:46:31', '2026-01-01 11:48:23'),
(7, '2027', '2028', 'inactive', '2025-12-18 09:56:49', '2026-01-01 11:48:23'),
(8, '2028', '2029', 'inactive', '2025-12-19 07:24:50', '2026-01-01 11:48:23'),
(9, '2029', '2030', 'inactive', '2025-12-24 04:48:06', '2026-01-01 11:48:23');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `type` enum('news','poster','running_text') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `unit_id`, `title`, `content`, `type`, `image`, `is_active`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Info Penting', 'Selamat datang di SD NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(2, 1, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', 'announcements/VyzHJjxGQR8NvxukN2q8DclsBbqvmQCW2KtRjesl.jpg', 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:26:39'),
(3, 2, 'Info Penting', 'Selamat datang di TK NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(4, 2, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(5, 3, 'Info Penting', 'Selamat datang di SMP NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(6, 3, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(7, 4, 'Info Penting', 'Selamat datang di SMA NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(8, 4, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-17 10:07:42', '2025-12-17 10:07:42'),
(11, NULL, 'Keterlambatan Guru', 'Guru Staff terlambat masuk kelas kelas 1 (BAHASA INDO) selama 11 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-18 10:38:29', '2025-12-18 10:38:29'),
(12, NULL, 'Keterlambatan Guru', 'Guru Staff terlambat masuk kelas kelas 1 (BAHASA INDO) selama 36 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-18 11:02:54', '2025-12-18 11:02:54'),
(18, 1, 'Info Penting', 'Selamat datang di SD NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(19, 1, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(20, 2, 'Info Penting', 'Selamat datang di TK NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(21, 2, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(22, 3, 'Info Penting', 'Selamat datang di SMP NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(23, 3, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(24, 4, 'Info Penting', 'Selamat datang di SMA NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(26, 5, 'Info Penting', 'Selamat datang di SMK NURUL ILMI. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.', 'running_text', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(27, 5, 'Kegiatan Ekskul', 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.', 'news', NULL, 1, NULL, NULL, '2025-12-26 08:30:49', '2025-12-26 08:30:49'),
(28, NULL, 'Keterlambatan Guru', 'Guru Herdi Yupika,M.Pd. terlambat masuk kelas KELAS X SMA 2025/2026 (Informatika) selama 16 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-29 03:56:00', '2025-12-29 03:56:00'),
(29, NULL, 'Keterlambatan Guru', 'Guru LIA LAILI ROSADAH,S.Pd terlambat masuk kelas KELAS XI SMA 2025/2026 (PAI dan Budi Pekerti) selama 717 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-29 12:56:51', '2025-12-29 12:56:51'),
(30, NULL, 'Keterlambatan Guru', 'Guru LIA LAILI ROSADAH,S.Pd terlambat masuk kelas KELAS XI SMA 2025/2026 (PAI dan Budi Pekerti) selama 16 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-30 01:15:48', '2025-12-30 01:15:48'),
(32, NULL, 'Keterlambatan Guru', 'Guru CANDRA FITRIANSYAH,S.Pd terlambat masuk kelas KELAS X SMA 2025/2026 (Matematika) selama 39 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-30 07:49:16', '2025-12-30 07:49:16'),
(33, NULL, 'Keterlambatan Guru', 'Guru LIA LAILI ROSADAH,S.Pd terlambat masuk kelas KELAS XI SMA 2025/2026 (PAI dan Budi Pekerti) selama 481 menit.', 'news', NULL, 1, NULL, NULL, '2025-12-30 09:01:14', '2025-12-30 09:01:14'),
(34, NULL, 'Keterlambatan Guru', 'Guru LIA LAILI ROSADAH,S.Pd terlambat masuk kelas KELAS XI SMA 2025/2026 (PAI dan Budi Pekerti) selama 651 menit.', 'news', NULL, 1, NULL, NULL, '2026-01-01 11:50:34', '2026-01-01 11:50:34'),
(35, NULL, 'Keterlambatan Guru', 'Guru LIA LAILI ROSADAH,S.Pd terlambat masuk kelas KELAS XI SMA 2025/2026 (PAI dan Budi Pekerti) selama 137 menit.', 'news', NULL, 1, NULL, NULL, '2026-01-02 03:16:56', '2026-01-02 03:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `bank_name`, `account_number`, `account_holder`, `balance`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'BSI', '4447776644', 'LEMBAGA PENDIDIKAN TERPADU NURUL ILMI', -4800000.00, NULL, 1, '2025-12-31 01:26:22', '2026-01-01 12:05:44');

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
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint UNSIGNED DEFAULT NULL,
  `student_leader_id` bigint UNSIGNED DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `code`, `grade_code`, `unit_id`, `created_at`, `updated_at`, `teacher_id`, `student_leader_id`, `academic_year_id`) VALUES
(39, 'KELAS X SMA 2025/2026', 'KELAS_X_SMA', '10', 4, '2025-12-21 13:38:34', '2025-12-21 13:38:34', 27, 30, 5),
(40, 'KELAS XI SMA 2025/2026', 'KELAS_XI_SMA', '11', 4, '2025-12-23 05:13:18', '2025-12-23 05:14:48', 26, NULL, 5),
(41, 'KELAS XII SMA 2025/2026', 'KELAS_XII_SMA', '12', 4, '2025-12-23 05:14:27', '2025-12-23 05:24:16', 20, 63, 5),
(42, 'X PBS 2025/2026', 'X_PBS', '10', 5, '2025-12-27 04:32:39', '2025-12-27 06:36:50', 38, 120, 5),
(43, 'X TJKT 2025/2026', 'X_TJKT', '10', 5, '2025-12-27 04:35:00', '2025-12-27 06:37:01', 23, 119, 5),
(44, 'XI TJKT 2025/2026', 'XI_TJKT', '11', 5, '2025-12-27 04:37:40', '2025-12-27 06:15:06', 39, 103, 5),
(45, 'XII TJKT 2025/2026', 'XII_TJKT', '12', 5, '2025-12-27 04:38:47', '2025-12-27 06:36:11', 34, 89, 5),
(46, 'XII PBS 2025/2026', 'XII_PBS', '12', 5, '2025-12-27 04:39:29', '2025-12-27 06:33:57', 35, 90, 5),
(58, 'KELAS VII ASY-SYAMS', 'KELAS_VII_ASYAMS', '7', 3, '2026-01-01 14:23:58', '2026-01-02 01:45:06', 65, 347, 5),
(59, 'KELAS VII AT-TARIQ', 'KELAS_VII_AT', '7', 3, '2026-01-01 14:30:49', '2026-01-02 01:45:49', 62, 354, 5),
(60, 'KELAS VIII AL-QAMAR', 'KELAS_VIII_ALQAMAR', '8', 3, '2026-01-01 14:36:26', '2026-01-02 01:46:03', 63, 293, 5),
(61, 'KELAS VIII AN-NAJM', 'KELAS_VIII_ANNAJM', '8', 3, '2026-01-01 14:42:08', '2026-01-02 01:46:21', 55, 286, 5),
(62, 'KELAS IX AN-NAML', 'KELAS_IX_ANNAML', '9', 3, '2026-01-01 14:52:07', '2026-01-02 01:46:45', 57, 245, 5),
(63, 'KELAS IX AL-FIL', 'KELAS_IX_ALFIL', '9', 3, '2026-01-01 14:55:22', '2026-01-02 01:47:06', 58, 322, 5),
(64, 'KELAS IX AN-NAHL', 'KELAS_IX_ANNAHL', '9', 3, '2026-01-01 14:56:32', '2026-01-02 01:47:20', 59, 240, 5);

-- --------------------------------------------------------

--
-- Table structure for table `class_announcements`
--

CREATE TABLE `class_announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_checkins`
--

CREATE TABLE `class_checkins` (
  `id` bigint UNSIGNED NOT NULL,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `checkin_time` timestamp NOT NULL,
  `checkout_time` timestamp NULL DEFAULT NULL,
  `status` enum('ontime','late','absent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_checkins`
--

INSERT INTO `class_checkins` (`id`, `schedule_id`, `user_id`, `checkin_time`, `checkout_time`, `status`, `notes`, `photo`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(18, 158, 27, '2026-01-01 11:50:34', NULL, 'late', ' (Terlambat 651 menit)', 'checkins/rsMdv85cLSCberfIZfh0CCd7WwcZggAOb0woaUW0.jpg', NULL, NULL, '2026-01-01 11:50:34', '2026-01-01 11:50:34'),
(19, 159, 27, '2026-01-02 03:16:56', NULL, 'late', ' (Terlambat 137 menit)', 'checkins/vtaA6M4TwJZ7Z9CWSvBbOmPCsimphPKtajnYwTt7.jpg', NULL, NULL, '2026-01-02 03:16:56', '2026-01-02 03:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `class_student`
--

CREATE TABLE `class_student` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_student`
--

INSERT INTO `class_student` (`id`, `student_id`, `class_id`, `academic_year_id`, `created_at`, `updated_at`) VALUES
(5, 30, 39, 5, '2025-12-21 13:38:34', '2026-01-01 06:31:13'),
(6, 31, 39, 5, '2025-12-21 13:38:34', '2025-12-28 17:26:51'),
(7, 32, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(8, 33, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(9, 34, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(10, 35, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(11, 36, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(12, 37, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(13, 38, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(14, 39, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(15, 40, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(16, 41, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(17, 42, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(18, 43, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(19, 44, 39, 5, '2025-12-21 13:38:34', '2025-12-21 13:38:34'),
(20, 45, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(21, 46, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(22, 47, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(23, 48, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(24, 49, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(25, 50, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(26, 51, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(27, 52, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(28, 53, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(29, 54, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(30, 55, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(31, 56, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(32, 57, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(33, 58, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(34, 59, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(35, 60, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(36, 61, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(37, 62, 40, 5, '2025-12-23 05:13:18', '2025-12-23 05:13:18'),
(38, 63, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(39, 64, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(40, 65, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(41, 66, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(42, 67, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(43, 68, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(44, 69, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(45, 70, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(46, 71, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(47, 72, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(48, 73, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(49, 74, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(50, 75, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(51, 76, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(52, 77, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(53, 78, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(54, 79, 41, 5, '2025-12-23 05:14:27', '2025-12-23 05:14:27'),
(55, 120, 42, 5, '2025-12-27 04:32:39', '2025-12-27 04:32:39'),
(56, 121, 42, 5, '2025-12-27 04:32:39', '2025-12-27 04:32:39'),
(57, 122, 42, 5, '2025-12-27 04:32:39', '2025-12-27 04:32:39'),
(58, 123, 42, 5, '2025-12-27 04:32:39', '2025-12-27 04:32:39'),
(59, 124, 42, 5, '2025-12-27 04:32:39', '2025-12-27 04:32:39'),
(60, 125, 42, 5, '2025-12-27 04:32:39', '2025-12-27 04:32:39'),
(61, 109, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(62, 110, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(63, 111, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(64, 112, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(65, 113, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(66, 114, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(67, 115, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(68, 116, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(69, 117, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(70, 118, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(71, 119, 43, 5, '2025-12-27 04:35:00', '2025-12-27 04:35:00'),
(72, 96, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(73, 97, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(74, 98, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(75, 99, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(76, 100, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(77, 101, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(78, 102, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(79, 103, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(80, 104, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(81, 105, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(82, 106, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(83, 107, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(84, 108, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(85, 95, 44, 5, '2025-12-27 04:37:40', '2025-12-27 04:37:40'),
(86, 80, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(87, 81, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(88, 82, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(89, 83, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(90, 84, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(91, 85, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(92, 86, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(93, 87, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(94, 88, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(95, 89, 45, 5, '2025-12-27 04:38:47', '2025-12-27 04:38:47'),
(96, 90, 46, 5, '2025-12-27 04:39:29', '2025-12-27 04:39:29'),
(97, 91, 46, 5, '2025-12-27 04:39:29', '2025-12-27 04:39:29'),
(98, 92, 46, 5, '2025-12-27 04:39:29', '2025-12-27 04:39:29'),
(99, 93, 46, 5, '2025-12-27 04:39:29', '2025-12-27 04:39:29'),
(100, 94, 46, 5, '2025-12-27 04:39:29', '2025-12-27 04:39:29'),
(117, 327, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(118, 328, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(119, 331, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(120, 333, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(121, 334, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(122, 335, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(123, 336, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(124, 340, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(125, 341, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(126, 346, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(127, 347, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(128, 348, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(129, 349, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(130, 355, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(131, 356, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(132, 357, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(133, 358, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(134, 359, 58, 5, '2026-01-01 14:23:58', '2026-01-02 01:45:06'),
(135, 361, 58, 5, '2026-01-01 14:23:59', '2026-01-02 01:45:06'),
(136, 362, 58, 5, '2026-01-01 14:23:59', '2026-01-02 01:45:06'),
(137, 363, 58, 5, '2026-01-01 14:23:59', '2026-01-02 01:45:06'),
(138, 367, 58, 5, '2026-01-01 14:23:59', '2026-01-02 01:45:06'),
(139, 370, 58, 5, '2026-01-01 14:23:59', '2026-01-02 01:45:06'),
(140, 329, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(141, 330, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(142, 332, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(143, 337, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(144, 338, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(145, 339, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(146, 342, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(147, 343, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(148, 344, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(149, 345, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(150, 350, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(151, 351, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(152, 352, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(153, 353, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(154, 354, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(155, 360, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(156, 364, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(157, 365, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(158, 366, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(159, 368, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(160, 369, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(161, 371, 59, 5, '2026-01-01 14:30:49', '2026-01-02 01:45:49'),
(162, 272, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(163, 274, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(164, 278, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(165, 279, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(166, 281, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(167, 282, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(168, 283, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(169, 284, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(170, 324, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:03'),
(171, 290, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(172, 291, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(173, 293, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(174, 296, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(175, 297, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(176, 298, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(177, 303, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(178, 304, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(179, 305, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(180, 307, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(181, 308, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(182, 310, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(183, 311, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(184, 315, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(185, 316, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(186, 318, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(187, 320, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(188, 372, 60, 5, '2026-01-01 14:36:26', '2026-01-02 01:46:04'),
(189, 273, 61, 5, '2026-01-01 14:42:08', '2026-01-02 01:46:21'),
(190, 275, 61, 5, '2026-01-01 14:42:08', '2026-01-02 01:46:21'),
(191, 276, 61, 5, '2026-01-01 14:42:08', '2026-01-02 01:46:21'),
(192, 277, 61, 5, '2026-01-01 14:42:08', '2026-01-02 01:46:21'),
(193, 323, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(194, 325, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(195, 280, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(196, 285, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(197, 286, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(198, 287, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(199, 288, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(200, 294, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(201, 295, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(202, 299, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(203, 300, 61, 5, '2026-01-01 14:42:09', '2026-01-02 01:46:21'),
(204, 289, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(205, 301, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(206, 302, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(207, 306, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(208, 309, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(209, 312, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(210, 313, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(211, 314, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(212, 317, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(213, 319, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(214, 321, 61, 5, '2026-01-01 14:46:11', '2026-01-02 01:46:21'),
(215, 205, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(216, 209, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(217, 213, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(218, 214, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(219, 217, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(220, 219, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(221, 220, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(222, 270, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(223, 226, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(224, 231, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(225, 232, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(226, 292, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(227, 237, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(228, 243, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(229, 245, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(230, 326, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(231, 248, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(232, 250, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(233, 251, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(234, 254, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(235, 259, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(236, 260, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(237, 265, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(238, 266, 62, 5, '2026-01-01 14:52:07', '2026-01-02 01:46:45'),
(239, 204, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:06'),
(240, 210, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:06'),
(241, 211, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:06'),
(242, 212, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:06'),
(243, 216, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(244, 218, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(245, 221, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(246, 249, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(247, 224, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(248, 322, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(249, 228, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(250, 234, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(251, 233, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(252, 235, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(253, 239, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(254, 238, 63, 5, '2026-01-01 14:55:22', '2026-01-02 01:47:07'),
(255, 242, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(256, 246, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(257, 247, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(258, 253, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(259, 256, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(260, 258, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(261, 261, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(262, 264, 63, 5, '2026-01-01 14:55:23', '2026-01-02 01:47:07'),
(263, 206, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(264, 373, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(265, 207, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(266, 208, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(267, 267, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(268, 268, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(269, 215, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(270, 222, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(271, 223, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(272, 225, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(273, 227, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(274, 229, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(275, 230, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(276, 269, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(277, 236, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(278, 240, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(279, 241, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(280, 271, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(281, 244, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(282, 252, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(283, 255, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(284, 257, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(285, 262, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20'),
(286, 263, 64, 5, '2026-01-01 14:56:32', '2026-01-02 01:47:20');

-- --------------------------------------------------------

--
-- Table structure for table `consumables`
--

CREATE TABLE `consumables` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consumables`
--

INSERT INTO `consumables` (`id`, `inventory_category_id`, `name`, `stock`, `unit_name`, `min_stock`, `created_at`, `updated_at`, `unit_id`, `academic_year_id`) VALUES
(3, 12, 'KERTAS A4', 8, 'RIM', 1, '2025-12-25 17:28:58', '2025-12-29 12:52:11', NULL, NULL),
(4, 12, 'KERTAS A4 70G', 9, 'RIM', 1, '2025-12-25 17:38:39', '2025-12-25 17:39:12', 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `consumable_transactions`
--

CREATE TABLE `consumable_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `consumable_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consumable_transactions`
--

INSERT INTO `consumable_transactions` (`id`, `consumable_id`, `user_id`, `quantity`, `type`, `note`, `created_at`, `updated_at`) VALUES
(3, 3, 23, 1, 'out', 'GURU', '2025-12-25 17:29:15', '2025-12-25 17:29:15'),
(4, 4, 23, 1, 'out', 'KEBUTUHAN GURU', '2025-12-25 17:39:12', '2025-12-25 17:39:12'),
(5, 3, 1, 1, 'out', 'untuk print guru', '2025-12-29 12:52:11', '2025-12-29 12:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `damage_reports`
--

CREATE TABLE `damage_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Damaged',
  `user_id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `follow_up_action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follow_up_description` text COLLATE utf8mb4_unicode_ci,
  `principal_approval_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `principal_id` bigint UNSIGNED DEFAULT NULL,
  `principal_note` text COLLATE utf8mb4_unicode_ci,
  `director_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `director_id` bigint UNSIGNED DEFAULT NULL,
  `director_note` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Gaji Guru & Karyawan', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22'),
(2, 'Operasional Sekolah', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22'),
(3, 'Pemeliharaan Gedung', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22'),
(4, 'ATK & Perlengkapan', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22'),
(5, 'Listrik & Internet', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22'),
(6, 'Kegiatan Siswa', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22'),
(7, 'Lain-lain', NULL, '2025-12-29 07:14:22', '2025-12-29 07:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` bigint UNSIGNED NOT NULL,
  `income_expense_id` bigint UNSIGNED NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_items`
--

INSERT INTO `expense_items` (`id`, `income_expense_id`, `item_name`, `quantity`, `unit_name`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(10, 43, 'A', 1, 'PCS', 10000.00, 10000.00, '2026-01-02 06:39:23', '2026-01-02 06:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `extracurriculars`
--

CREATE TABLE `extracurriculars` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coach_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extracurriculars`
--

INSERT INTO `extracurriculars` (`id`, `unit_id`, `academic_year_id`, `name`, `coach_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, NULL, 'PRAMUKA', 'RAHMAD', NULL, 'active', '2025-12-25 04:51:56', '2025-12-25 04:51:56');

-- --------------------------------------------------------

--
-- Table structure for table `extracurricular_members`
--

CREATE TABLE `extracurricular_members` (
  `id` bigint UNSIGNED NOT NULL,
  `extracurricular_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Anggota',
  `grade_ganjil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ganjil` text COLLATE utf8mb4_unicode_ci,
  `grade_genap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_genap` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extracurricular_reports`
--

CREATE TABLE `extracurricular_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `extracurricular_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `graduation_announcements`
--

CREATE TABLE `graduation_announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `announcement_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_categories`
--

CREATE TABLE `income_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_categories`
--

INSERT INTO `income_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Dana BOS', NULL, '2025-12-29 07:08:48', '2025-12-29 07:08:48'),
(2, 'Sumbangan', NULL, '2025-12-29 07:08:48', '2025-12-29 07:08:48'),
(3, 'Penjualan Seragam', NULL, '2025-12-29 07:08:48', '2025-12-29 07:08:48'),
(4, 'Lain-lain', NULL, '2025-12-29 07:08:48', '2025-12-29 07:08:48'),
(5, 'SUMBANGAN KONBLOCK', NULL, '2026-01-02 06:37:57', '2026-01-02 06:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `income_expenses`
--

CREATE TABLE `income_expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tunai',
  `bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `payer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `nota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_proof_needed` tinyint(1) NOT NULL DEFAULT '0',
  `proof_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Reported, Verified',
  `proof_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procurement_request_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_expenses`
--

INSERT INTO `income_expenses` (`id`, `unit_id`, `type`, `category`, `amount`, `payment_method`, `bank_account_id`, `payer_name`, `transaction_date`, `description`, `nota`, `photo`, `is_proof_needed`, `proof_status`, `proof_code`, `procurement_request_code`, `user_id`, `created_at`, `updated_at`) VALUES
(42, NULL, 'income', 'SUMBANGAN KONBLOCK', 1000000.00, 'tunai', NULL, 'BAPAK A', '2026-01-02', NULL, NULL, NULL, 0, 'Pending', NULL, NULL, 1, '2026-01-02 06:38:22', '2026-01-02 06:38:22'),
(43, NULL, 'expense', 'Operasional Sekolah', 10000.00, 'tunai', NULL, 'A', '2026-01-02', NULL, 'expenses/proofs/XAzihzj1o6j3RwzFTmH7IH9FKzeZRddFyneCtXai.jpg', NULL, 1, 'Verified', 'EXP-PRF-20260102133923-249', NULL, 1, '2026-01-02 06:39:23', '2026-01-02 06:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_category_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Good',
  `price` decimal(15,2) DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `person_in_charge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_grant` tinyint(1) NOT NULL DEFAULT '0',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `disposal_reason` text COLLATE utf8mb4_unicode_ci,
  `disposal_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `inventory_category_id`, `room_id`, `name`, `code`, `condition`, `price`, `source`, `person_in_charge`, `is_grant`, `photo`, `purchase_date`, `created_at`, `updated_at`, `deleted_at`, `disposal_reason`, `disposal_photo`) VALUES
(15, 9, NULL, 'canon', 'SMANURULILMI/IVN-00002', 'Good', 1000000.00, NULL, NULL, 0, NULL, '2025-12-25', '2025-12-25 11:01:15', '2025-12-25 11:01:15', NULL, NULL, NULL),
(18, 9, 4, 'PRINTER EPSON', 'SMANURULILMI/IVN-00003', 'Good', 1000000.00, 'PRESIDEN', 'LIA LAILI ROSADAH', 1, 'inventory-photos/SEE8Ad0GPmYToLCE6wvEZRB2qXiCVsnTpg2xd2Cw.jpg', '2025-12-25', '2025-12-25 13:01:49', '2025-12-25 16:49:41', '2025-12-25 16:05:40', 'Dihapus berdasarkan Laporan #6. Alasan: rusak pemakaian', 'disposal-proofs/zTLjqUinKKrpQ5skW46s99BP5jbH4jBFjArBPMUn.jpg'),
(19, 9, NULL, 'canon', 'GUDANG/IVN-00004', 'Good', 100000.00, NULL, NULL, 0, NULL, '2025-12-25', '2025-12-25 15:41:51', '2025-12-25 15:41:51', NULL, NULL, NULL),
(20, 9, 4, 'canon', 'SMANURULILMI/IVN-00005', 'Good', 100000.00, NULL, 'LIA LAILI ROSADAH', 0, 'inventory-photos/skmmrewdB8pIJu0qr3HaQkVgzlvaIvGc1VHfTOBE.jpg', '2025-12-25', '2025-12-25 15:42:15', '2025-12-25 17:11:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_consumable` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`id`, `name`, `description`, `is_consumable`, `created_at`, `updated_at`, `unit_id`, `academic_year_id`) VALUES
(9, 'ELEKTRONIK', NULL, 0, '2025-12-25 08:09:38', '2025-12-25 12:44:22', 4, 5),
(12, 'ATK', NULL, 1, '2025-12-25 17:26:50', '2025-12-25 17:26:50', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `inventory_id`, `user_id`, `action`, `details`, `created_at`, `updated_at`) VALUES
(4, 15, 1, 'Created', 'Barang ditambahkan via Input Banyak.', '2025-12-25 11:01:15', '2025-12-25 11:01:15'),
(14, 18, 23, 'Created', 'Barang ditambahkan via Input Banyak.', '2025-12-25 13:01:49', '2025-12-25 13:01:49'),
(15, 18, 23, 'Updated', 'Pindah Ruangan: KELAS X -> None', '2025-12-25 13:02:03', '2025-12-25 13:02:03'),
(16, 18, 23, 'Updated', 'Pindah Ruangan: KELAS X -> None', '2025-12-25 13:04:52', '2025-12-25 13:04:52'),
(17, 18, 23, 'Pelaporan', 'Melaporkan Damaged: RUSAK. Usulan: Repair', '2025-12-25 13:07:57', '2025-12-25 13:07:57'),
(18, 18, 19, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #4. Catatan: oke', '2025-12-25 13:13:08', '2025-12-25 13:13:08'),
(19, 18, 19, 'Validasi KS', 'Kepala Sekolah Menolak laporan #4. Catatan: oke', '2025-12-25 13:18:31', '2025-12-25 13:18:31'),
(20, 18, 19, 'Validasi KS', 'Kepala Sekolah Menolak laporan #4. Catatan: buat penghapusan barang', '2025-12-25 13:18:56', '2025-12-25 13:18:56'),
(21, 18, 19, 'Validasi KS', 'Kepala Sekolah Menolak laporan #4. Catatan: buat penghapusan barang', '2025-12-25 13:19:09', '2025-12-25 13:19:09'),
(22, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 13:23:07', '2025-12-25 13:23:07'),
(23, 18, 1, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #4. Catatan: buat penghapusan barang', '2025-12-25 13:24:23', '2025-12-25 13:24:23'),
(24, 18, 1, 'Approval Pimpinan', 'Pimpinan Lembaga Menyetujui (Final) laporan #4. Catatan: ', '2025-12-25 13:24:44', '2025-12-25 13:24:44'),
(25, 18, 1, 'Approval Pimpinan', 'Pimpinan Lembaga Menolak (Final) laporan #4. Catatan: ', '2025-12-25 13:27:24', '2025-12-25 13:27:24'),
(26, 18, 1, 'Approval Pimpinan', 'Pimpinan Lembaga Menolak (Final) laporan #4. Catatan: ', '2025-12-25 13:27:25', '2025-12-25 13:27:25'),
(27, 18, 19, 'Validasi KS', 'Kepala Sekolah Menolak laporan #4. Catatan: buat penghapusan barang', '2025-12-25 13:27:57', '2025-12-25 13:27:57'),
(28, 18, 19, 'Validasi KS', 'Kepala Sekolah Menolak laporan #4. Catatan: buat penghapusan barang', '2025-12-25 13:27:57', '2025-12-25 13:27:57'),
(29, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Pending.', '2025-12-25 13:31:16', '2025-12-25 13:31:16'),
(30, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Pending.', '2025-12-25 13:33:07', '2025-12-25 13:33:07'),
(31, 18, 1, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #4. Catatan: buat penghapusan barang', '2025-12-25 13:33:23', '2025-12-25 13:33:23'),
(32, 18, 1, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #4. Catatan: oke', '2025-12-25 13:33:27', '2025-12-25 13:33:27'),
(33, 18, 1, 'Approval Pimpinan', 'Pimpinan Lembaga Menyetujui (Final) laporan #4. Catatan: ', '2025-12-25 13:33:42', '2025-12-25 13:33:42'),
(34, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 13:35:24', '2025-12-25 13:35:24'),
(35, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Processed.', '2025-12-25 13:35:30', '2025-12-25 13:35:30'),
(36, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 13:35:47', '2025-12-25 13:35:47'),
(37, 19, 23, 'Created', 'Barang ditambahkan via Input Banyak.', '2025-12-25 15:41:51', '2025-12-25 15:41:51'),
(38, 20, 23, 'Created', 'Barang ditambahkan via Input Banyak.', '2025-12-25 15:42:15', '2025-12-25 15:42:15'),
(39, 20, 23, 'Updated', 'Pindah Ruangan: KELAS X -> None', '2025-12-25 15:42:23', '2025-12-25 15:42:23'),
(40, 20, 23, 'Pelaporan', 'Melaporkan Damaged: barang rusak parah. Usulan: Disposal', '2025-12-25 15:43:28', '2025-12-25 15:43:28'),
(41, 18, 23, 'Pelaporan', 'Melaporkan Damaged: rusak berat. Usulan: Disposal', '2025-12-25 16:05:00', '2025-12-25 16:05:00'),
(42, 18, 7, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #6. Catatan: ', '2025-12-25 16:05:28', '2025-12-25 16:05:28'),
(43, 18, 7, 'Approval Pimpinan', 'Pimpinan Lembaga Menyetujui (Final) laporan #6. Catatan: ', '2025-12-25 16:05:40', '2025-12-25 16:05:40'),
(44, 18, 7, 'Disposed', 'Pemusnahan otomatis setelah Approval Pimpinan (Laporan #6)', '2025-12-25 16:05:40', '2025-12-25 16:05:40'),
(45, 18, 23, 'Bukti Pemusnahan', 'Mengunggah foto bukti penghapusan/pemusnahan barang.', '2025-12-25 16:28:18', '2025-12-25 16:28:18'),
(46, 18, 23, 'Status Update', 'Laporan #4 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 16:49:41', '2025-12-25 16:49:41'),
(47, 20, 23, 'Updated', 'Kondisi: Repairing -> Good, Pindah Ruangan: KELAS X -> None', '2025-12-25 16:56:13', '2025-12-25 16:56:13'),
(48, 20, 23, 'Pelaporan', 'Melaporkan Damaged: rusak pemakaian. Usulan: Repair', '2025-12-25 17:00:23', '2025-12-25 17:00:23'),
(49, 20, 7, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #7. Catatan: ', '2025-12-25 17:01:36', '2025-12-25 17:01:36'),
(50, 20, 7, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #7. Catatan: ', '2025-12-25 17:01:36', '2025-12-25 17:01:36'),
(51, 20, 7, 'Approval Pimpinan', 'Pimpinan Lembaga Menyetujui (Final) laporan #7. Catatan: ', '2025-12-25 17:01:49', '2025-12-25 17:01:49'),
(52, 20, 23, 'Status Update', 'Laporan #7 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 17:02:21', '2025-12-25 17:02:21'),
(53, 20, 23, 'Status Update', 'Laporan #7 diupdate oleh Admin menjadi: Pending.', '2025-12-25 17:05:13', '2025-12-25 17:05:13'),
(54, 20, 23, 'Status Update', 'Laporan #7 diupdate oleh Admin menjadi: Processed.', '2025-12-25 17:07:09', '2025-12-25 17:07:09'),
(55, 20, 23, 'Status Update', 'Laporan #7 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 17:07:14', '2025-12-25 17:07:14'),
(56, 20, 23, 'Pelaporan', 'Melaporkan Damaged: rusak. Usulan: Repair', '2025-12-25 17:08:14', '2025-12-25 17:08:14'),
(57, 20, 7, 'Validasi KS', 'Kepala Sekolah Memvalidasi laporan #8. Catatan: ', '2025-12-25 17:08:54', '2025-12-25 17:08:54'),
(58, 20, 7, 'Approval Pimpinan', 'Pimpinan Lembaga Menyetujui (Final) laporan #8. Catatan: ', '2025-12-25 17:09:03', '2025-12-25 17:09:03'),
(59, 20, 23, 'Status Update', 'Laporan #8 diupdate oleh Admin menjadi: Fixed.', '2025-12-25 17:11:30', '2025-12-25 17:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('guru','tambahan','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guru',
  `tipe` enum('struktural','fungsional','tambahan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fungsional',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatans`
--

INSERT INTO `jabatans` (`id`, `kode_jabatan`, `nama_jabatan`, `kategori`, `tipe`, `created_at`, `updated_at`, `unit_id`) VALUES
(53, 'guru', 'Guru Mapel SD', 'guru', 'fungsional', '2025-12-19 13:18:02', '2025-12-26 14:10:47', 1),
(54, 'kepala_sekolah', 'Kepala Sekolah SD', 'tambahan', 'struktural', '2025-12-19 13:20:57', '2025-12-26 14:10:55', 1),
(55, 'wakil_kurikulum', 'Wakasek Kurikulum SD', 'guru', 'struktural', '2025-12-19 13:21:19', '2025-12-26 14:19:27', 1),
(58, 'wakil_kurikulum', 'Wakil Kurikulum TK', 'tambahan', 'struktural', '2025-12-19 14:20:04', '2025-12-19 14:20:04', 2),
(60, 'admin_keuangan', 'Admin Keuangan SD', 'staff', 'fungsional', '2025-12-19 14:34:06', '2025-12-19 14:34:06', 1),
(61, 'guru', 'Guru Mapel TK', 'guru', 'fungsional', '2025-12-20 05:46:34', '2025-12-20 05:46:34', 2),
(62, 'wali_kelas', 'Wali Kelas SD', 'tambahan', 'fungsional', '2025-12-21 12:37:36', '2025-12-26 14:11:05', 1),
(63, 'kepala_sekolah', 'Kepala Sekolah SMA', 'tambahan', 'struktural', '2025-12-21 13:04:26', '2025-12-21 13:04:26', 4),
(64, 'wakil_kurikulum', 'Wakasek Kurikulum SMA', 'tambahan', 'tambahan', '2025-12-21 13:04:46', '2025-12-26 14:20:19', 4),
(65, 'wali_kelas', 'Wali Kelas SMA', 'tambahan', 'tambahan', '2025-12-21 13:05:10', '2025-12-21 13:05:10', 4),
(66, 'guru', 'Guru Mapel SMA', 'guru', 'fungsional', '2025-12-21 13:05:29', '2025-12-21 13:05:29', 4),
(67, 'wakil_kesiswaan', 'Wakil Kesiswaan SMA', 'tambahan', 'struktural', '2025-12-24 02:32:09', '2025-12-24 02:32:09', 4),
(68, 'wakil_kesiswaan', 'Wakil Kesiswaan SMK', 'tambahan', 'struktural', '2025-12-24 04:45:15', '2025-12-24 04:45:15', 5),
(69, 'wakil_sarana_prasarana', 'Wakil Sarana Prasarana SMA', 'tambahan', 'struktural', '2025-12-25 12:28:05', '2025-12-25 12:28:05', 4),
(70, 'wakil_sarana_prasarana', 'Wakil Sarana Prasarana SMK', 'tambahan', 'struktural', '2025-12-25 12:38:05', '2025-12-25 12:38:05', 5),
(71, 'kepala_sekolah', 'Kepala Sekolah SMK', 'tambahan', 'struktural', '2025-12-26 08:19:03', '2025-12-26 08:19:03', 5),
(72, 'guru', 'Guru Mapel SMK', 'guru', 'fungsional', '2025-12-26 14:07:16', '2025-12-26 14:11:38', 5),
(73, 'wakil_kesiswaan', 'Wakasek Kesiswaan SD NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(74, 'wakil_sarana_prasarana', 'Wakasek Sarpras SD NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(75, 'wakil_humas', 'Wakasek Humas SD NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(76, 'guru_bk', 'Guru BK SD NURUL ILMI', 'guru', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(77, 'pembina_osis', 'Pembina OSIS SD NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(78, 'koordinator_ekstrakurikuler', 'Koordinator Ekskul SD NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(79, 'kepala_tu', 'Kepala TU SD NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(80, 'staff_tu', 'Staff TU SD NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(81, 'bendahara', 'Bendahara Unit SD NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(82, 'pustakawan', 'Pustakawan SD NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(83, 'security', 'Security SD NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(84, 'cleaning_service', 'Cleaning Service SD NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 1),
(85, 'kepala_sekolah', 'Kepala Sekolah TK NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(86, 'wakil_kesiswaan', 'Wakasek Kesiswaan TK NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(87, 'wakil_sarana_prasarana', 'Wakasek Sarpras TK NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(88, 'wakil_humas', 'Wakasek Humas TK NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(89, 'guru_bk', 'Guru BK TK NURUL ILMI', 'guru', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(90, 'wali_kelas', 'Wali Kelas TK NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(91, 'pembina_osis', 'Pembina OSIS TK NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(92, 'koordinator_ekstrakurikuler', 'Koordinator Ekskul TK NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(93, 'kepala_tu', 'Kepala TU TK NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(94, 'staff_tu', 'Staff TU TK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(95, 'bendahara', 'Bendahara Unit TK NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(96, 'pustakawan', 'Pustakawan TK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(97, 'security', 'Security TK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(98, 'cleaning_service', 'Cleaning Service TK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 2),
(99, 'kepala_sekolah', 'Kepala Sekolah SMP NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(100, 'wakil_kurikulum', 'Wakasek Kurikulum SMP NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(101, 'wakil_kesiswaan', 'Wakasek Kesiswaan SMP NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(102, 'wakil_sarana_prasarana', 'Wakasek Sarpras SMP NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(103, 'wakil_humas', 'Wakasek Humas SMP NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(104, 'guru', 'Guru Mapel SMP NURUL ILMI', 'guru', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(105, 'guru_bk', 'Guru BK SMP NURUL ILMI', 'guru', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(106, 'wali_kelas', 'Wali Kelas SMP NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(107, 'pembina_osis', 'Pembina OSIS SMP NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(108, 'koordinator_ekstrakurikuler', 'Koordinator Ekskul SMP NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(109, 'kepala_tu', 'Kepala TU SMP NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(110, 'staff_tu', 'Staff TU SMP NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(111, 'bendahara', 'Bendahara Unit SMP NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(112, 'pustakawan', 'Pustakawan SMP NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(113, 'security', 'Security SMP NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(114, 'cleaning_service', 'Cleaning Service SMP NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 3),
(115, 'wakil_humas', 'Wakasek Humas SMA NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(116, 'guru_bk', 'Guru BK SMA NURUL ILMI', 'guru', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(117, 'pembina_osis', 'Pembina OSIS SMA NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(118, 'koordinator_ekstrakurikuler', 'Koordinator Ekskul SMA NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(119, 'kepala_tu', 'Kepala TU SMA NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(120, 'staff_tu', 'Staff TU SMA NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(121, 'bendahara', 'Bendahara Unit SMA NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(122, 'pustakawan', 'Pustakawan SMA NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(123, 'security', 'Security SMA NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(124, 'cleaning_service', 'Cleaning Service SMA NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 4),
(125, 'wakil_kurikulum', 'Wakasek Kurikulum SMK NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(126, 'wakil_humas', 'Wakasek Humas SMK NURUL ILMI', 'guru', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(127, 'guru_bk', 'Guru BK SMK NURUL ILMI', 'guru', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(128, 'wali_kelas', 'Wali Kelas SMK NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(129, 'pembina_osis', 'Pembina OSIS SMK NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(130, 'koordinator_ekstrakurikuler', 'Koordinator Ekskul SMK NURUL ILMI', 'tambahan', 'tambahan', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(131, 'kepala_tu', 'Kepala TU SMK NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(132, 'staff_tu', 'Staff TU SMK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(133, 'bendahara', 'Bendahara Unit SMK NURUL ILMI', 'staff', 'struktural', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(134, 'pustakawan', 'Pustakawan SMK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(135, 'security', 'Security SMK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5),
(136, 'cleaning_service', 'Cleaning Service SMK NURUL ILMI', 'staff', 'fungsional', '2025-12-26 14:09:01', '2025-12-26 14:09:01', 5);

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
-- Table structure for table `login_histories`
--

CREATE TABLE `login_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_siswa_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_histories`
--

INSERT INTO `login_histories` (`id`, `user_id`, `user_siswa_id`, `ip_address`, `user_agent`, `login_at`, `created_at`, `updated_at`) VALUES
(2, 23, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-31 10:15:34', '2025-12-31 10:15:34', '2025-12-31 10:15:34'),
(4, 49, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-31 10:34:51', '2025-12-31 10:34:51', '2025-12-31 10:34:51'),
(5, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:47:47', '2025-12-31 17:47:47', '2025-12-31 17:47:47'),
(6, 49, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-31 17:48:12', '2025-12-31 17:48:12', '2025-12-31 17:48:12'),
(7, 49, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 18:17:03', '2025-12-31 18:17:03', '2025-12-31 18:17:03'),
(8, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-31 18:22:52', '2025-12-31 18:22:52', '2025-12-31 18:22:52'),
(9, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 05:04:48', '2026-01-01 05:04:48', '2026-01-01 05:04:48'),
(10, 50, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-01 05:06:18', '2026-01-01 05:06:18', '2026-01-01 05:06:18'),
(11, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 05:11:12', '2026-01-01 05:11:12', '2026-01-01 05:11:12'),
(12, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 05:12:00', '2026-01-01 05:12:00', '2026-01-01 05:12:00'),
(13, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 05:45:32', '2026-01-01 05:45:32', '2026-01-01 05:45:32'),
(14, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 08:32:34', '2026-01-01 08:32:34', '2026-01-01 08:32:34'),
(15, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 10:42:06', '2026-01-01 10:42:06', '2026-01-01 10:42:06'),
(16, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 10:42:34', '2026-01-01 10:42:34', '2026-01-01 10:42:34'),
(17, 23, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-01 10:56:49', '2026-01-01 10:56:49', '2026-01-01 10:56:49'),
(18, 23, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-01 10:56:57', '2026-01-01 10:56:57', '2026-01-01 10:56:57'),
(19, NULL, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-01 10:57:04', '2026-01-01 10:57:04', '2026-01-01 10:57:04'),
(20, 27, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-01 10:57:18', '2026-01-01 10:57:18', '2026-01-01 10:57:18'),
(21, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-01 12:23:54', '2026-01-01 12:23:54', '2026-01-01 12:23:54'),
(22, 22, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 12:37:57', '2026-01-01 12:37:57', '2026-01-01 12:37:57'),
(23, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 01:35:05', '2026-01-02 01:35:05', '2026-01-02 01:35:05'),
(24, 54, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 01:49:18', '2026-01-02 01:49:18', '2026-01-02 01:49:18'),
(25, 32, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 02:48:39', '2026-01-02 02:48:39', '2026-01-02 02:48:39'),
(26, 19, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 02:53:31', '2026-01-02 02:53:31', '2026-01-02 02:53:31'),
(27, 27, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 03:16:40', '2026-01-02 03:16:40', '2026-01-02 03:16:40'),
(28, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 04:45:54', '2026-01-02 04:45:54', '2026-01-02 04:45:54'),
(29, 19, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 05:19:12', '2026-01-02 05:19:12', '2026-01-02 05:19:12'),
(30, 54, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 05:53:06', '2026-01-02 05:53:06', '2026-01-02 05:53:06'),
(31, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 15:53:11', '2026-01-02 15:53:11', '2026-01-02 15:53:11'),
(32, 54, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-02 15:53:26', '2026-01-02 15:53:26', '2026-01-02 15:53:26'),
(33, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 18:19:26', '2026-01-02 18:19:26', '2026-01-02 18:19:26'),
(34, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 21:45:17', '2026-01-02 21:45:17', '2026-01-02 21:45:17');

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
(4, '2025_12_15_150500_create_jabatans_table', 1),
(5, '2025_12_15_152000_add_photo_to_users_table', 1),
(6, '2025_12_15_153000_add_kode_jabatan_to_jabatans_table', 2),
(7, '2025_12_15_154500_add_jabatan_id_to_users_table', 3),
(8, '2025_12_15_160000_create_units_and_classes_tables', 4),
(9, '2025_12_15_160500_create_students_table', 4),
(10, '2025_12_15_173500_create_academic_years_table', 5),
(11, '2025_12_15_174000_create_semesters_table', 6),
(12, '2025_12_15_175000_add_unit_id_to_users_table', 7),
(13, '2025_12_15_183000_add_details_to_classes_table', 8),
(14, '2025_12_15_184500_add_plain_password_to_users_table', 9),
(15, '2025_12_15_190000_update_status_enum_in_students_table', 10),
(16, '2025_12_15_190500_add_username_to_users_table', 11),
(17, '2025_12_15_192000_separate_student_users', 12),
(18, '2025_12_15_212500_add_details_to_students_table', 13),
(19, '2025_12_16_090000_add_status_to_users_table', 14),
(20, '2025_12_16_092000_create_jabatan_user_table', 15),
(21, '2025_12_16_093000_add_attributes_to_guru_and_jabatan', 16),
(22, '2025_12_16_101000_restructure_jabatans_table', 17),
(23, '2025_12_16_101500_create_subjects_table', 18),
(24, '2025_12_16_103000_create_teaching_assignments_table', 19),
(25, '2025_12_16_105000_add_leader_to_classes_table', 20),
(26, '2025_12_16_110500_make_class_id_nullable_in_students_table', 20),
(27, '2025_12_16_112000_update_student_class_fk', 21),
(28, '2025_12_16_120300_add_code_to_classes_table', 22),
(29, '2025_12_16_122500_add_is_boarding_to_students_table', 23),
(30, '2025_12_16_140000_create_user_jabatan_units_table', 24),
(31, '2025_12_16_150000_create_schedules_table', 25),
(32, '2025_12_16_160000_add_tipe_kelas_to_students_table', 26),
(33, '2025_12_16_163000_modify_tipe_kelas_to_boolean', 27),
(34, '2025_12_16_164500_drop_tipe_kelas_from_students', 28),
(35, '2025_12_16_203000_make_unit_id_nullable_in_user_jabatan_units_table', 29),
(36, '2025_12_16_213000_simplify_user_jabatan_structure', 30),
(37, '2025_12_16_220000_add_type_to_jabatans_table', 31),
(38, '2025_12_17_071000_add_academic_year_id_to_classes_table', 32),
(39, '2025_12_17_170612_create_announcements_table', 33),
(40, '2025_12_17_174645_add_is_break_to_schedules_table', 34),
(41, '2025_12_17_181011_create_time_slots_table', 35),
(42, '2025_12_17_183658_make_subject_and_user_nullable_in_schedules_table', 36),
(43, '2025_12_17_190559_add_login_attempts_to_user_siswa_table', 37),
(44, '2025_12_17_223341_restructure_academic_years_table', 38),
(45, '2025_12_18_155636_add_academic_year_id_to_user_jabatan_units_table', 39),
(46, '2025_12_18_160253_create_class_student_table', 39),
(47, '2025_12_18_163730_add_academic_year_id_to_teaching_assignments_table', 40),
(48, '2025_12_18_170334_create_class_checkins_table', 41),
(49, '2025_12_18_182547_create_settings_table', 42),
(50, '2025_12_18_223817_create_teacher_document_tables', 43),
(51, '2025_12_19_124850_create_student_attendances_table', 44),
(52, '2025_12_19_130804_modify_status_enum_in_student_attendances_table', 45),
(53, '2025_12_19_133437_create_academic_calendars_table', 46),
(54, '2025_12_19_134519_add_unit_id_to_academic_calendars_table', 47),
(55, '2025_12_19_211621_modify_kode_jabatan_unique_constraint', 48),
(56, '2025_12_19_213807_create_finance_module_tables', 49),
(57, '2025_12_20_091030_add_filters_to_teacher_document_requests', 50),
(58, '2025_12_20_095613_change_teacher_doc_targets_to_json', 51),
(59, '2025_12_20_101517_update_teacher_document_submissions_status', 52),
(60, '2025_12_21_180607_add_identity_fields_to_users_table', 53),
(61, '2025_12_23_123626_create_login_histories_table', 54),
(62, '2025_12_23_144824_create_supervisions_table', 55),
(63, '2025_12_23_150506_add_workflow_cols_to_supervisions_table', 56),
(64, '2025_12_23_173733_add_subject_and_class_to_supervisions_table', 57),
(65, '2025_12_24_093422_create_student_affairs_tables', 58),
(66, '2025_12_24_095229_add_proof_to_student_violations_table', 59),
(67, '2025_12_24_104506_add_black_book_threshold_to_units_table', 60),
(68, '2025_12_24_114955_add_follow_up_result_to_student_violations_table', 61),
(69, '2025_12_24_120919_add_follow_up_status_to_student_violations_table', 62),
(70, '2025_12_24_122444_add_follow_up_attachment_to_student_violations_table', 63),
(71, '2025_12_25_112500_create_extracurriculars_table', 64),
(72, '2025_12_25_112858_create_extracurricular_members_table', 64),
(73, '2025_12_25_115259_add_academic_year_id_to_extracurriculars_table', 65),
(74, '2025_12_25_123132_add_achievements_to_extracurricular_members_table', 66),
(75, '2025_12_25_123140_create_extracurricular_reports_table', 66),
(76, '2025_12_25_124311_split_extracurricular_grades', 67),
(77, '2025_12_25_130441_create_inventory_categories_table', 68),
(78, '2025_12_25_130526_create_rooms_table', 69),
(79, '2025_12_25_130530_create_inventories_table', 70),
(80, '2025_12_25_130542_create_damage_reports_table', 70),
(81, '2025_12_25_131507_seed_inventory_categories', 70),
(82, '2025_12_25_132745_add_academic_year_id_to_rooms_table', 71),
(83, '2025_12_25_134158_create_consumables_table', 72),
(84, '2025_12_25_134212_create_consumable_transactions_table', 72),
(85, '2025_12_25_142644_create_room_types_table', 73),
(86, '2025_12_25_150050_add_unit_id_to_inventory_categories_table', 74),
(87, '2025_12_25_151219_add_unit_id_to_room_types_table', 75),
(88, '2025_12_25_160448_add_photo_to_inventories_table', 76),
(89, '2025_12_25_152607_add_academic_year_id_to_rooms_table', 999),
(90, '2025_12_25_170454_update_damage_reports_for_follow_up', 1000),
(91, '2025_12_25_171000_add_photo_to_damage_reports_table', 1001),
(92, '2025_12_25_172100_adjust_damage_reports_to_new_workflow', 1002),
(93, '2025_12_25_173421_create_inventory_logs_table', 1003),
(94, '2025_12_25_194107_add_academic_year_id_to_sarpras_master_tables', 1004),
(95, '2025_12_25_194659_add_source_and_is_grant_to_inventories_table', 1005),
(96, '2025_12_25_195136_add_person_in_charge_to_inventories_table', 1006),
(97, '2025_12_25_195332_add_person_in_charge_to_rooms_table', 1007),
(98, '2025_12_25_224609_add_soft_deletes_to_inventories_table', 1008),
(99, '2025_12_25_232147_add_disposal_photo_to_inventories_table', 1009),
(100, '2025_12_26_001839_add_is_consumable_to_inventory_categories_table', 1010),
(101, '2025_12_26_003454_add_unit_and_year_to_consumables_table', 1011),
(102, '2025_12_26_004259_create_procurement_requests_table', 1012),
(103, '2025_12_26_012117_add_request_id_to_procurement_requests_table', 1013),
(104, '2025_12_26_012838_add_activity_fields_to_procurement_requests', 1014),
(105, '2025_12_26_102900_add_approved_price_to_procurement_requests_table', 1015),
(106, '2025_12_26_110444_add_approved_quantity_to_procurement_requests_table', 1016),
(107, '2025_12_26_153202_modify_login_histories_for_students', 1017),
(108, '2025_12_26_193712_add_withdrawal_proof_to_students_table', 1018),
(109, '2025_12_26_200521_add_status_to_user_siswa_table', 1019),
(110, '2025_12_26_205127_add_academic_year_id_to_jabatans_table', 1020),
(111, '2025_12_26_210329_remove_academic_year_id_from_jabatans_table', 1021),
(112, '2025_12_26_215750_create_graduation_announcements_table', 1022),
(113, '2025_12_26_215817_create_student_graduation_results_table', 1022),
(114, '2025_12_26_224603_add_skl_file_to_student_graduation_results_table', 1023),
(115, '2025_12_27_005734_create_class_announcements_table', 1024),
(116, '2025_12_27_012413_add_attachment_to_class_announcements_table', 1025),
(117, '2025_12_27_151606_add_break_name_to_schedules_table', 1026),
(118, '2025_12_27_171013_add_academic_year_id_to_student_violations_table', 1027),
(119, '2025_12_27_171457_add_academic_year_id_to_student_achievements_table', 1027),
(120, '2025_12_27_194155_create_student_payment_settings_table', 1028),
(121, '2025_12_27_201627_create_income_expenses_table', 1029),
(122, '2025_12_27_205136_add_code_to_payment_types_table', 1030),
(123, '2025_12_27_211113_update_student_payment_settings_add_month', 1031),
(124, '2025_12_27_220953_add_due_month_to_student_payment_settings_table', 1032),
(125, '2025_12_28_013313_create_student_bills_table', 1033),
(126, '2025_12_28_022000_add_discount_and_is_free_to_bills_and_settings', 1034),
(127, '2025_12_28_101713_enhance_class_student_table', 1035),
(128, '2025_12_28_114624_drop_class_id_from_students_table', 1036),
(129, '2025_12_28_120913_remove_class_id_from_students_table', 1037),
(130, '2025_12_28_133213_create_receipts_table', 1038),
(131, '2025_12_28_133214_create_payments_table', 1038),
(132, '2025_12_28_142135_create_bank_accounts_table', 1039),
(133, '2025_12_28_143447_add_bank_account_id_to_transactions_table', 1040),
(134, '2025_12_28_151500_add_unit_id_to_finance_tables', 1041),
(135, '2025_12_28_152000_fix_missing_columns_finance', 1042),
(136, '2025_12_28_152500_remove_unique_from_transactions_invoice', 1043),
(137, '2025_12_29_092122_make_unit_id_nullable_in_payment_types_table', 1044),
(138, '2025_12_29_102018_create_transaction_items_table', 1045),
(139, '2025_12_29_132001_create_payment_requests_tables', 1046),
(140, '2025_12_29_143000_make_transaction_payment_type_nullable', 1047),
(141, '2025_12_29_144500_add_bill_id_to_transaction_items', 1048),
(142, '2025_12_29_150000_create_income_categories_table', 1049),
(143, '2025_12_29_151000_create_expense_categories_table', 1050),
(144, '2025_12_29_152000_add_procurement_code_to_income_expenses', 1051),
(145, '2025_12_29_153829_add_reference_code_to_payment_requests_table', 1052),
(146, '2025_12_30_222628_add_code_to_units_table', 1053),
(147, '2025_12_30_225014_add_details_to_income_expenses_table', 1053),
(148, '2025_12_31_083910_add_reporting_fields_to_procurement_requests_table', 1054),
(149, '2025_12_31_083927_add_proof_fields_to_income_expenses_table', 1054),
(150, '2025_12_31_103049_add_proof_tracking_to_income_expenses_table', 1055),
(151, '2025_12_31_105704_create_expense_items_table', 1056),
(152, '2026_01_01_210611_make_birthday_nullable_in_students_table', 1057);

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transaction_date` date DEFAULT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_requests`
--

CREATE TABLE `payment_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `reference_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','verified','rejected','waiting_proof') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_proof',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_request_items`
--

CREATE TABLE `payment_request_items` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_request_id` bigint UNSIGNED NOT NULL,
  `student_bill_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE `payment_types` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('monthly','one_time') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `unit_id`, `code`, `name`, `type`, `nominal`, `created_at`, `updated_at`) VALUES
(18, NULL, 'SPP', 'SPP', 'monthly', 0.00, '2025-12-29 02:43:08', '2025-12-29 02:43:08'),
(19, NULL, 'PONDOK', 'PONDOK', 'monthly', 0.00, '2025-12-29 02:44:52', '2025-12-29 02:44:52'),
(20, NULL, 'KEGIATAN', 'KEGIATAN', 'one_time', 0.00, '2025-12-29 02:45:40', '2025-12-29 02:45:40'),
(21, NULL, 'PERLENGKAPAN_PONDOK', 'PERLENGKAPAN PONDOK', 'one_time', 0.00, '2025-12-29 02:58:06', '2025-12-29 02:58:06'),
(22, NULL, 'PERALATAN', 'PERALATAN', 'one_time', 0.00, '2025-12-30 02:38:04', '2025-12-30 02:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_requests`
--

CREATE TABLE `procurement_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `request_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_description` text COLLATE utf8mb4_unicode_ci,
  `unit_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `inventory_category_id` bigint UNSIGNED NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `approved_quantity` int DEFAULT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_price` decimal(15,2) DEFAULT NULL,
  `approved_price` decimal(15,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('Asset','Consumable') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pending','Validated','Approved','Rejected','Processed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `principal_status` enum('Pending','Validated','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `principal_note` text COLLATE utf8mb4_unicode_ci,
  `validated_at` timestamp NULL DEFAULT NULL,
  `director_status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `director_note` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_nota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `report_at` timestamp NULL DEFAULT NULL,
  `report_note` text COLLATE utf8mb4_unicode_ci,
  `finance_approved_at` timestamp NULL DEFAULT NULL,
  `finance_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transaction_date` date DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `unit_id`, `category`, `amount`, `transaction_date`, `payment_method`, `description`, `reference_number`, `created_at`, `updated_at`, `user_id`) VALUES
(31, 4, 'Pembayaran Siswa', 1570000.00, '2025-12-29', 'cash', 'Pembayaran: SPP (Bulan 1), PONDOK (Bulan 1) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229110758-677', '2025-12-29 04:07:58', '2025-12-29 04:07:58', 1),
(32, 4, 'Pembayaran Siswa', 2400000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 7), PONDOK (Bulan 1) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229110902-673', '2025-12-29 04:09:02', '2025-12-29 04:09:02', 1),
(33, 4, 'Pembayaran Siswa', 2400000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 9), PONDOK (Bulan 3) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111109-257', '2025-12-29 04:11:10', '2025-12-29 04:11:10', 1),
(34, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 10) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111241-887', '2025-12-29 04:12:41', '2025-12-29 04:12:41', 1),
(35, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 11) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111246-190', '2025-12-29 04:12:46', '2025-12-29 04:12:46', 1),
(36, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 12) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111252-362', '2025-12-29 04:12:52', '2025-12-29 04:12:52', 1),
(37, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 1) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111257-557', '2025-12-29 04:12:57', '2025-12-29 04:12:57', 1),
(38, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 2) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111303-566', '2025-12-29 04:13:03', '2025-12-29 04:13:03', 1),
(39, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 4) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111308-487', '2025-12-29 04:13:08', '2025-12-29 04:13:08', 1),
(40, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 5) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111315-709', '2025-12-29 04:13:15', '2025-12-29 04:13:15', 1),
(41, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 8) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229111329-854', '2025-12-29 04:13:29', '2025-12-29 04:13:29', 1),
(42, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 7) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229112707-217', '2025-12-29 04:27:07', '2025-12-29 04:27:07', 1),
(43, 4, 'Pembayaran Siswa', 370000.00, '2025-12-29', 'cash', 'Pembayaran: SPP (Bulan 1) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229112809-506', '2025-12-29 04:28:09', '2025-12-29 04:28:09', 1),
(44, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 8) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229112905-630', '2025-12-29 04:29:05', '2025-12-29 04:29:05', 1),
(45, 4, 'Pembayaran Siswa', 20000.00, '2025-12-29', 'cash', 'Pembayaran: SPP (Bulan 7) oleh Alfish Janky Candish', 'INV-20251229114801-232', '2025-12-29 04:48:01', '2025-12-29 04:48:01', 1),
(46, 4, 'Pembayaran Siswa', 1320000.00, '2025-12-29', 'cash', 'Pembayaran: SPP (Bulan 7), KEGIATAN (Bulan 12) oleh Alfish Janky Candish', 'INV-20251229121317-623', '2025-12-29 05:13:17', '2025-12-29 05:13:17', 1),
(47, 4, 'Pembayaran Siswa', 100000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 1) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229121531-448', '2025-12-29 05:15:31', '2025-12-29 05:15:31', 1),
(48, 4, 'Pembayaran Siswa', 12140000.00, '2025-12-29', 'cash', 'Pembayaran: SPP (Bulan 7), SPP (Bulan 8), SPP (Bulan 9), SPP (Bulan 10), SPP (Bulan 11), SPP (Bulan 12), SPP (Bulan 1), SPP (Bulan 2), SPP (Bulan 3), SPP (Bulan 4), SPP (Bulan 5), SPP (Bulan 6), PONDOK (Bulan 7), PONDOK (Bulan 8), PONDOK (Bulan 9), PONDOK (Bulan 10), PONDOK (Bulan 11), PONDOK (Bulan 12), PONDOK (Bulan 1), PONDOK (Bulan 2), PONDOK (Bulan 3), PONDOK (Bulan 4) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229131240-947', '2025-12-29 06:12:40', '2025-12-29 06:12:40', 1),
(49, 4, 'Pembayaran Siswa', 2400000.00, '2025-12-29', 'cash', 'Pembayaran: PONDOK (Bulan 5), PONDOK (Bulan 6) oleh AMALLIA PUTRI PRATAMA', 'INV-20251229131359-270', '2025-12-29 06:13:59', '2025-12-29 06:13:59', 1),
(51, 4, 'Pembayaran Siswa', 6000000.00, '2025-12-30', 'cash', 'Pembayaran: SPP (Bulan 9), SPP (Bulan 10), SPP (Bulan 11), PONDOK (Bulan 8), PONDOK (Bulan 9) oleh AMALLIA PUTRI PRATAMA', 'INV-20251230092733-134', '2025-12-30 02:27:34', '2025-12-30 02:27:34', 1),
(53, 4, 'Pembayaran Siswa', 500000.00, '2025-12-30', 'cash', 'Pembayaran: KEGIATAN (Bulan 12) oleh AMALLIA PUTRI PRATAMA', 'INV-20251230093550-627', '2025-12-30 02:35:50', '2025-12-30 02:35:50', 1),
(54, 4, 'Pembayaran Siswa', 500000.00, '2025-12-30', 'cash', 'Pembayaran: PERALATAN (Bulan 7) oleh AMALLIA PUTRI PRATAMA', 'INV-20251230213011-371', '2025-12-30 14:30:11', '2025-12-30 14:30:11', 1),
(55, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-31', 'cash', 'Pembayaran: SPP (Bulan 7) oleh AMALLIA PUTRI PRATAMA', 'INV-20251231111624-766', '2025-12-31 04:16:24', '2025-12-31 04:16:24', 1),
(56, 4, 'Pembayaran Siswa', 1200000.00, '2025-12-31', 'cash', 'Pembayaran: PONDOK (Bulan 8) oleh AMALLIA PUTRI PRATAMA', 'INV-20251231113755-818', '2025-12-31 04:37:55', '2025-12-31 04:37:55', 1),
(57, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: SPP (Bulan 8) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101005929-381', '2025-12-31 17:59:29', '2025-12-31 17:59:29', 49),
(58, 4, 'Pembayaran Siswa', 3600000.00, '2026-01-01', 'cash', 'Pembayaran: SPP (Bulan 9), SPP (Bulan 10), PONDOK (Bulan 4) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101010617-117', '2025-12-31 18:06:17', '2025-12-31 18:06:17', 49),
(59, 4, 'Pembayaran Siswa', 900000.00, '2026-01-01', 'cash', 'Pembayaran: PERALATAN (Bulan 7) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101010700-915', '2025-12-31 18:07:00', '2025-12-31 18:07:00', 49),
(60, 4, 'Pembayaran Siswa', 1300000.00, '2026-01-01', 'cash', 'Pembayaran: KEGIATAN (Bulan 12) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101010907-951', '2025-12-31 18:09:07', '2025-12-31 18:09:07', 49),
(61, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: PONDOK (Bulan 3) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101011041-995', '2025-12-31 18:10:41', '2025-12-31 18:10:41', 49),
(62, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: PONDOK (Bulan 5) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101011121-875', '2025-12-31 18:11:21', '2025-12-31 18:11:21', 49),
(63, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: SPP (Bulan 11) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101011210-133', '2025-12-31 18:12:10', '2025-12-31 18:12:10', 49),
(65, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: SPP (Bulan 3) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101011512-928', '2025-12-31 18:15:12', '2025-12-31 18:15:12', 49),
(66, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: PONDOK (Bulan 9) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101011750-635', '2025-12-31 18:17:51', '2025-12-31 18:17:51', 49),
(67, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: PONDOK (Bulan 7) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101195609-593', '2026-01-01 12:56:09', '2026-01-01 12:56:09', 1),
(68, 4, 'Pembayaran Siswa', 1200000.00, '2026-01-01', 'cash', 'Pembayaran: PONDOK (Bulan 8) oleh AMALLIA PUTRI PRATAMA', 'INV-20260101195922-559', '2026-01-01 12:59:22', '2026-01-01 12:59:22', 2),
(69, 4, 'Pembayaran Siswa', 1570000.00, '2026-01-02', 'cash', 'Pembayaran: SPP (Bulan 7), PONDOK (Bulan 7) oleh AMALLIA PUTRI PRATAMA', 'INV-20260102133523-698', '2026-01-02 06:35:23', '2026-01-02 06:35:23', 1),
(70, 4, 'Pembayaran Siswa', 740000.00, '2026-01-03', 'cash', 'Pembayaran: SPP (Bulan 8), SPP (Bulan 9) oleh AMALLIA PUTRI PRATAMA', 'INV-20260103023831-650', '2026-01-02 19:38:32', '2026-01-02 19:38:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Classroom',
  `person_in_charge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `unit_id`, `academic_year_id`, `name`, `type`, `person_in_charge`, `description`, `created_at`, `updated_at`) VALUES
(4, 4, 5, 'KELAS X', 'KELAS', 'LIA LAILI ROSADAH', NULL, '2025-12-25 12:45:34', '2025-12-25 12:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'primary',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `label`, `color`, `created_at`, `updated_at`, `unit_id`, `academic_year_id`) VALUES
(8, 'KELAS', 'KELAS', 'primary', '2025-12-25 08:18:12', '2025-12-25 12:44:22', 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `day` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_break` tinyint(1) NOT NULL DEFAULT '0',
  `break_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `unit_id`, `class_id`, `subject_id`, `user_id`, `day`, `start_time`, `end_time`, `created_at`, `updated_at`, `is_break`, `break_name`) VALUES
(150, 4, 40, NULL, NULL, 'Senin', '06:45:00', '08:00:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 1, 'UPACARA'),
(151, 4, 40, NULL, NULL, 'Selasa', '06:45:00', '08:00:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 1, 'UPACARA'),
(152, 4, 40, NULL, NULL, 'Rabu', '06:45:00', '08:00:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 1, 'UPACARA'),
(153, 4, 40, NULL, NULL, 'Kamis', '06:45:00', '08:00:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 1, 'UPACARA'),
(154, 4, 40, NULL, NULL, 'Jumat', '06:45:00', '08:00:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 1, 'UPACARA'),
(155, 4, 40, 38, 27, 'Senin', '08:00:00', '21:10:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 0, NULL),
(156, 4, 40, 38, 27, 'Selasa', '08:00:00', '21:10:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 0, NULL),
(157, 4, 40, 38, 27, 'Rabu', '08:00:00', '21:10:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 0, NULL),
(158, 4, 40, 38, 27, 'Kamis', '08:00:00', '21:10:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 0, NULL),
(159, 4, 40, 38, 27, 'Jumat', '08:00:00', '21:10:00', '2025-12-27 09:01:23', '2025-12-27 09:01:23', 0, NULL),
(333, 4, 39, NULL, NULL, 'Senin', '06:45:00', '08:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'UPACARA'),
(334, 4, 39, NULL, NULL, 'Selasa', '06:45:00', '07:30:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT DHUHA'),
(335, 4, 39, NULL, NULL, 'Rabu', '06:45:00', '07:30:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT DHUHA'),
(336, 4, 39, NULL, NULL, 'Kamis', '06:45:00', '07:30:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT DHUHA'),
(337, 4, 39, NULL, NULL, 'Jumat', '06:45:00', '08:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SENAM DAN SHOLAT DHUHA'),
(338, 4, 39, 55, 27, 'Senin', '08:00:00', '09:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(339, 4, 39, 61, 29, 'Selasa', '07:30:00', '08:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(340, 4, 39, 52, 33, 'Rabu', '08:00:00', '09:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(341, 4, 39, 61, 29, 'Kamis', '07:30:00', '08:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(342, 4, 39, NULL, NULL, 'Jumat', '08:00:00', '10:20:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'EKSTRAKURIKULER'),
(343, 4, 39, 42, 24, 'Senin', '09:10:00', '10:20:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(344, 4, 39, 57, 46, 'Selasa', '08:00:00', '09:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(345, 4, 39, 39, 26, 'Rabu', '09:10:00', '10:20:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(346, 4, 39, 53, 19, 'Kamis', '09:10:00', '10:20:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(347, 4, 39, NULL, NULL, 'Jumat', '10:20:00', '10:40:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISTIRAHAT 1'),
(348, 4, 39, NULL, NULL, 'Senin', '10:20:00', '10:40:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISTIRAHAT 1'),
(349, 4, 39, 44, 20, 'Selasa', '09:10:00', '10:20:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(350, 4, 39, NULL, NULL, 'Rabu', '10:20:00', '10:40:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISTIRAHAT 1'),
(351, 4, 39, NULL, NULL, 'Kamis', '10:20:00', '10:40:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISTIRAHAT 1'),
(352, 4, 39, NULL, NULL, 'Jumat', '10:40:00', '11:50:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'EKSTRAKURIKULER'),
(353, 4, 39, 51, 34, 'Senin', '10:40:00', '11:50:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(354, 4, 39, NULL, NULL, 'Selasa', '10:20:00', '10:40:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISTIRAHAT 1'),
(355, 4, 39, 48, 31, 'Rabu', '10:40:00', '11:50:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(356, 4, 39, 56, 29, 'Kamis', '10:40:00', '11:50:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(357, 4, 39, NULL, NULL, 'Jumat', '11:50:00', '13:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISOMA'),
(358, 4, 39, NULL, NULL, 'Senin', '11:50:00', '13:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISHOMA'),
(359, 4, 39, 49, 36, 'Selasa', '10:40:00', '11:50:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(360, 4, 39, NULL, NULL, 'Rabu', '11:50:00', '13:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISOMA'),
(361, 4, 39, NULL, NULL, 'Kamis', '11:50:00', '13:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISOMA'),
(362, 4, 39, NULL, NULL, 'Jumat', '13:00:00', '14:45:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'PRAMUKA'),
(363, 4, 39, 50, 45, 'Senin', '13:00:00', '14:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(364, 4, 39, NULL, NULL, 'Selasa', '11:50:00', '13:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'ISOMA'),
(365, 4, 39, 47, 23, 'Rabu', '13:00:00', '14:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(366, 4, 39, 40, 43, 'Kamis', '13:00:00', '14:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(367, 4, 39, 38, 27, 'Senin', '14:10:00', '14:45:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(368, 4, 39, 46, 35, 'Selasa', '13:00:00', '14:10:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(369, 4, 39, 57, 46, 'Rabu', '14:10:00', '15:15:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(370, 4, 39, 48, 31, 'Kamis', '14:10:00', '15:15:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(371, 4, 39, 57, 46, 'Senin', '14:45:00', '15:15:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(372, 4, 39, 41, 21, 'Selasa', '14:10:00', '15:15:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 0, NULL),
(373, 4, 39, NULL, NULL, 'Rabu', '15:15:00', '16:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT ASHAR'),
(374, 4, 39, NULL, NULL, 'Kamis', '15:15:00', '16:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT ASHAR'),
(375, 4, 39, NULL, NULL, 'Senin', '15:15:00', '16:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT ASHAR'),
(376, 4, 39, NULL, NULL, 'Selasa', '15:15:00', '16:00:00', '2025-12-27 09:53:53', '2025-12-27 09:53:53', 1, 'SHOLAT ASHAR');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ganjil', 'inactive', '2025-12-15 03:42:38', '2025-12-19 07:18:57'),
(2, 'Genap', 'active', '2025-12-15 03:42:38', '2025-12-19 07:18:57');

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
('2cK17RxOtj7a8TJCtnQXTFj9be7ZC0v6t5ZmCnQM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmpCcmFCOVdXWHhYbTJ0ZENhclhGbHlRZG5wN2xlVDBKbURvalJQciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHA6Ly9udXJ1bC50ZXN0L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==', 1767390631);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('app_address', 'Jl. Palembang - Betung No.KM.18 No.73, RT.09/RW.02, Sukamoro, Kec. Talang Klp., Kab. Banyuasin, Sumatera Selatan 30961', '2025-12-31 04:08:55', '2025-12-31 04:08:55'),
('app_favicon', 'settings/Lw0U62Ajo746JrHANg8cVG5lYOWN2Mxu5LCVy5GE.png', '2025-12-18 11:31:23', '2025-12-18 11:38:34'),
('app_logo', 'settings/6BWueqfCImdym00XZdCli1yFSxsdeyqDs4ewJn8r.png', '2025-12-18 11:30:28', '2025-12-18 11:38:34'),
('app_name', 'LPT Nurul Ilmi', '2025-12-18 11:30:28', '2025-12-18 11:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint UNSIGNED NOT NULL,
  `user_siswa_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED DEFAULT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_boarding` tinyint(1) NOT NULL DEFAULT '0',
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `alamat_rt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_rw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','lulus','keluar','pindah','non-aktif') COLLATE utf8mb4_unicode_ci DEFAULT 'aktif',
  `withdrawal_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_siswa_id`, `unit_id`, `class_id`, `nis`, `nisn`, `nama_lengkap`, `jenis_kelamin`, `is_boarding`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `alamat_rt`, `alamat_rw`, `desa`, `kecamatan`, `kota`, `kode_pos`, `no_hp`, `nama_wali`, `no_hp_wali`, `status`, `withdrawal_proof`, `created_at`, `updated_at`) VALUES
(30, 35, 4, 39, '40001', '0106639369', 'Alfish Janky Candish', 'L', 1, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:38', '2026-01-01 11:47:00'),
(31, 36, 4, 39, '40002', '0101853570', 'AMALLIA PUTRI PRATAMA', 'P', 1, 'palembang', '2013-12-04', 'Islam', 'jl serong', '01', '01', 'sukamoro', 'talang kelapa', 'Banyuasin', '30961', '082282888299', 'mad', '082282888299', 'aktif', NULL, '2025-12-21 13:00:39', '2026-01-01 11:58:35'),
(32, 37, 4, 39, '40003', '0102293667', 'ARIESKA PRIMA SARI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:39', '2026-01-01 11:47:00'),
(33, 38, 4, 39, '40004', '0108642366', 'DINO RIANSYAH', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:39', '2026-01-01 11:47:00'),
(34, 39, 4, 39, '40005', '0109319074', 'DIVA THREE SUSANTO', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:39', '2026-01-01 11:47:00'),
(35, 40, 4, 39, '40006', '0103608328', 'Fakhira Azri Shakila', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:40', '2026-01-01 11:47:00'),
(36, 41, 4, 39, '40007', '0105835815', 'Faris Onedri Wijaya', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:40', '2026-01-01 11:47:00'),
(37, 42, 4, 39, '40008', '0114989061', 'MUHAMMAD ATTAYA ABYAN NAUFAL', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:40', '2026-01-01 11:47:00'),
(38, 43, 4, 39, '40009', '0101156517', 'Muhammad Dzaky Araffah', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:40', '2026-01-01 11:47:00'),
(39, 44, 4, 39, '40010', '0102764716', 'MUHAMMAD NAUFAL', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:40', '2026-01-01 11:47:00'),
(40, 45, 4, 39, '40011', '0101723154', 'MUHAMMAD RAKHA AL FAJRI', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:41', '2026-01-01 11:47:00'),
(41, 46, 4, 39, '40012', '0101723159', 'MUHAMMAD RASYIID AL HAFIIZH', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:41', '2026-01-01 11:47:00'),
(42, 47, 4, 39, '40013', '0107506545', 'Najid Atthalla Mirza', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:41', '2026-01-01 11:47:00'),
(43, 48, 4, 39, '40014', '0109553274', 'NUR AIN AIDIL ADHA', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:41', '2026-01-01 11:47:00'),
(44, 49, 4, 39, '40015', '0109254220', 'REGINA PUTRI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:42', '2026-01-01 11:47:00'),
(45, 50, 4, 40, '40016', '0093000297', 'ADINDA CHAESYA PUTRI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:42', '2026-01-01 11:47:00'),
(46, 51, 4, 40, '40017', '0088616420', 'Athallah Rafialfalih Syear', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:42', '2026-01-01 11:47:00'),
(47, 52, 4, 40, '40018', '0095318114', 'CHERYL CECILLYA YOSIOCTAVIA', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:42', '2026-01-01 11:47:00'),
(48, 53, 4, 40, '40019', '0098815509', 'Daffa Agriansyah', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:43', '2026-01-01 11:47:00'),
(49, 54, 4, 40, '40020', '3097090874', 'DEA INTAN PERMATA SARI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:43', '2026-01-01 11:47:00'),
(50, 55, 4, 40, '40021', '0033260845', 'DERLI AUSDISTIRA', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:43', '2026-01-01 11:47:00'),
(51, 56, 4, 40, '40022', '0098483310', 'FIDELA NAIFAH DWIYANTI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:44', '2026-01-01 11:47:00'),
(52, 57, 4, 40, '40023', '0099323604', 'Lisa Melia Oktavia', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:44', '2026-01-01 11:47:00'),
(53, 58, 4, 40, '40024', '0093113159', 'M. Faris Adila Ammar', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:44', '2026-01-01 11:47:00'),
(54, 59, 4, 40, '40025', '0088917964', 'M. FASHAN EL-MUBAROK', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:44', '2026-01-01 11:47:00'),
(55, 60, 4, 40, '40026', '0095730463', 'M. RIDHO BONIYAGO', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:45', '2026-01-01 11:47:00'),
(56, 61, 4, 40, '40027', '0095954779', 'NABILAH SYAUQIYAH WIDAD', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:45', '2026-01-01 11:47:00'),
(57, 62, 4, 40, '40028', '0094450601', 'OASE FADHILA NUGRAHA', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:45', '2026-01-01 11:47:00'),
(58, 63, 4, 40, '40029', '0091893063', 'RISKA AULIA', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:45', '2026-01-01 11:47:00'),
(59, 64, 4, 40, '40030', '0099588200', 'Siti Syifa Izza Pratiwi', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:46', '2026-01-01 11:47:00'),
(60, 65, 4, 40, '40031', '0095705637', 'Syarofati Dwi Athiyyah', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:46', '2026-01-01 11:47:00'),
(61, 66, 4, 40, '40032', '0095243262', 'SYIFA AZAHRO', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:46', '2026-01-01 11:47:00'),
(62, 67, 4, 40, '40033', '0093616437', 'Zidane Arya Arkadian', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:47', '2026-01-01 11:47:00'),
(63, 68, 4, 41, '40034', '0081652671', 'AHMAD RIFQI', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:47', '2026-01-01 11:47:00'),
(64, 69, 4, 41, '40035', '089630032', 'Aisyah Azzahra', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:47', '2026-01-01 11:47:00'),
(65, 70, 4, 41, '40036', '0088057204', 'DIMAS YOGA SAPUTRA', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:47', '2026-01-01 11:47:00'),
(66, 71, 4, 41, '40037', '076093440', 'DITA LASTARI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:48', '2026-01-01 11:47:00'),
(67, 72, 4, 41, '40038', '0085219364', 'Faiz Muzakky Pramana', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:48', '2026-01-01 11:47:00'),
(68, 73, 4, 41, '40039', '0077249513', 'FAREL RAMADHANI', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:48', '2026-01-01 11:47:00'),
(69, 74, 4, 41, '40040', '0086793411', 'FARHAN DWI SAPUTRA', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:49', '2026-01-01 11:47:00'),
(70, 75, 4, 41, '40041', '0082417908', 'ISMA RAHAYU', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:49', '2026-01-01 11:47:00'),
(71, 76, 4, 41, '40042', '0084094351', 'Khoirul Bariyah', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:49', '2026-01-01 11:47:00'),
(72, 77, 4, 41, '40043', '0081341219', 'LIONEL GARCIA', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:49', '2026-01-01 11:47:00'),
(73, 78, 4, 41, '40044', '0077283758', 'M. ALFARIZI SYAH', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:50', '2026-01-01 11:47:00'),
(74, 79, 4, 41, '40045', '0087030845', 'MEITA PUTRI PRATIWI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:50', '2026-01-01 11:47:00'),
(75, 80, 4, 41, '40046', '0073429107', 'Muhammad Faza Nail Urridho', 'L', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:50', '2026-01-01 11:47:00'),
(76, 81, 4, 41, '40047', '3083454182', 'PUTRI HUMAIRAH LAILANI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:50', '2026-01-01 11:47:00'),
(77, 82, 4, 41, '40048', '0071459925', 'Reva Anisa Putri', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:51', '2026-01-01 11:47:00'),
(78, 83, 4, 41, '40049', '0081478391', 'SALWA ALIA PUTRI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:51', '2026-01-01 11:47:00'),
(79, 84, 4, 41, '40050', '0086738878', 'SEPTIA RAMADHANI', 'P', 0, 'Unknown', '2025-12-21', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-21 13:00:51', '2026-01-01 11:47:00'),
(80, 85, 5, 45, '50001', '0089001458', 'AHMAD AKBAR', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:48', '2026-01-01 11:47:00'),
(81, 86, 5, 45, '50002', '0082507013', 'ELVAN SUBASTIAN', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:48', '2026-01-01 11:47:00'),
(82, 87, 5, 45, '50003', '0089234543', 'Indra Wijaya', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:48', '2026-01-01 11:47:00'),
(83, 88, 5, 45, '50004', '0081756620', 'M Dafa Assiddiq', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:48', '2026-01-01 11:47:00'),
(84, 89, 5, 45, '50005', '0082703505', 'M. REYFAN', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:48', '2026-01-01 11:47:00'),
(85, 90, 5, 45, '50006', '0087786342', 'M. sultan faiz', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:49', '2026-01-01 11:47:00'),
(86, 91, 5, 45, '50007', '0095750106', 'M.SHOLIHIN', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:49', '2026-01-01 11:47:00'),
(87, 92, 5, 45, '50008', '0082041974', 'Muhammad Danny Irawan', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:49', '2026-01-01 11:47:00'),
(88, 93, 5, 45, '50009', '0076086907', 'Muhammad Hafizh Ridho', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:49', '2026-01-01 11:47:00'),
(89, 94, 5, 45, '50010', '0086607002', 'WIRA LESMANA AHMAD', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:50', '2026-01-01 11:47:00'),
(90, 95, 5, 46, '50011', '0097066316', 'M.SHOBIRIN', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:50', '2026-01-01 11:47:00'),
(91, 96, 5, 46, '50012', '0082383438', 'Mufid Arbain', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:50', '2026-01-01 11:47:00'),
(92, 97, 5, 46, '50013', '0062860164', 'NONI AYU NOVIANI', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:50', '2026-01-01 11:47:00'),
(93, 98, 5, 46, '50014', '0085536657', 'ROMI FRIYANSAH', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:51', '2026-01-01 11:47:00'),
(94, 99, 5, 46, '50015', '0081667435', 'SYAFA PRATAMI', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:51', '2026-01-01 11:47:00'),
(95, 100, 5, 44, '50016', '0092248077', 'AHMAD ALMAHDI', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:51', '2026-01-01 11:47:00'),
(96, 101, 5, 44, '50017', '0096439648', 'AHMAD FAIZ FADILLAH', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:51', '2026-01-01 11:47:00'),
(97, 102, 5, 44, '50018', '0096932478', 'Dimas Deri Kusuma', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:52', '2026-01-01 11:47:00'),
(98, 103, 5, 44, '50019', '0097008166', 'FANNDY DONY ADITIA', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:52', '2026-01-01 11:47:00'),
(99, 104, 5, 44, '50020', '0092632396', 'FAREL FAIQ AL KAFILA', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:52', '2026-01-01 11:47:00'),
(100, 105, 5, 44, '50021', '0096485885', 'ILHAM AL \'AZIZ', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:52', '2026-01-01 11:47:00'),
(101, 106, 5, 44, '50022', '3093282148', 'LAURA GISELA VANDA DITA', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:53', '2026-01-01 11:47:00'),
(102, 107, 5, 44, '50023', '0087973635', 'M. ANDREAN MUSLIMIN', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:53', '2026-01-01 11:47:00'),
(103, 108, 5, 44, '50024', '0094826454', 'Muhammad Fairuz Ramadhan', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:53', '2026-01-01 11:47:00'),
(104, 109, 5, 44, '50025', '0093867027', 'NURHAFIDZHA', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:53', '2026-01-01 11:47:00'),
(105, 110, 5, 44, '50026', '0092825803', 'RIKA AMELIA', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:54', '2026-01-01 11:47:00'),
(106, 111, 5, 44, '50027', '0097257981', 'Tofiqur Rohim Harahap', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:54', '2026-01-01 11:47:00'),
(107, 112, 5, 44, '50028', '0097400997', 'Toriqul Rahman Harahap', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:54', '2026-01-01 11:47:00'),
(108, 113, 5, 44, '50029', '0081510030', 'ZAHRA LATIFATUL NUR AINI', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:55', '2026-01-01 11:47:00'),
(109, 114, 5, 43, '50030', '0105747158', 'ADENDA DARMAWAN', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:55', '2026-01-01 11:47:00'),
(110, 115, 5, 43, '50031', '0108363555', 'ALFHADILLAH', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:55', '2026-01-01 11:47:00'),
(111, 116, 5, 43, '50032', '0108974984', 'DIRGA HADITAMA', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:55', '2026-01-01 11:47:00'),
(112, 117, 5, 43, '50033', '0091772169', 'Dzikra Hafidz Farrasi', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:56', '2026-01-01 11:47:00'),
(113, 118, 5, 43, '50034', '0107700429', 'MEYZA MUTIARA KASIH', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:56', '2026-01-01 11:47:00'),
(114, 119, 5, 43, '50035', '0103237424', 'MICKY REVI SAPUTRA', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:56', '2026-01-01 11:47:00'),
(115, 120, 5, 43, '50036', '0095646399', 'NIRWANA', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:56', '2026-01-01 11:47:00'),
(116, 121, 5, 43, '50037', '0104373551', 'Rani Anggraini', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:57', '2026-01-01 11:47:00'),
(117, 122, 5, 43, '50038', '0104165600', 'RESTU WIDIAS TANTO', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:57', '2026-01-01 11:47:00'),
(118, 123, 5, 43, '50039', '0103757715', 'Talitha Sakhi Raniah', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:57', '2026-01-01 11:47:00'),
(119, 124, 5, 43, '50040', '0106854501', 'Tegar Ady Pratama', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:57', '2026-01-01 11:47:00'),
(120, 125, 5, 42, '50041', '0102878755', 'DZULHI NOFELINDA', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:58', '2026-01-01 11:47:00'),
(121, 126, 5, 42, '50042', '0105779350', 'LENI OKTAFIYANI', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:58', '2026-01-01 11:47:00'),
(122, 127, 5, 42, '50043', '0103216743', 'M. ROBIH FERLIANSYAH', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:58', '2026-01-01 11:47:00'),
(123, 128, 5, 42, '50044', '0092606352', 'MARISSA UTARI', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:58', '2026-01-01 11:47:00'),
(124, 129, 5, 42, '50045', '0096376516', 'MUHAMMAD RIDHO TRI SAPUTRA', 'L', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:58', '2026-01-01 11:47:00'),
(125, 130, 5, 42, '50046', '0103746089', 'RIYANI AWALIA PUTRI', 'P', 0, 'Unknown', '2025-12-27', NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Unknown', '-', 'aktif', NULL, '2025-12-27 04:30:59', '2026-01-01 11:47:00'),
(204, 213, 3, NULL, '00281', '0117388597', 'Aerilyn Bellvania Cintakirana', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:35', '2026-01-01 14:16:35'),
(205, 214, 3, NULL, '00282', '0117419603', 'Ahmad Faiz', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:36', '2026-01-01 14:16:36'),
(206, 215, 3, NULL, '00284', '0113097388', 'Aliyu Ratu Jaya', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:36', '2026-01-01 14:16:36'),
(207, 216, 3, NULL, '00285', '0104326285', 'Annisya Al-Zha Hirah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:37', '2026-01-01 14:16:37'),
(208, 217, 3, NULL, '00286', '0115040028', 'Aprilia Nurul Madina', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:37', '2026-01-01 14:16:37'),
(209, 218, 3, NULL, '00287', '0116881950', 'Aqilah Andero Putri', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:38', '2026-01-01 14:16:38'),
(210, 219, 3, NULL, '00288', '0118417198', 'Arif Adi Saputra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:38', '2026-01-01 14:16:38'),
(211, 220, 3, NULL, '00289', '0112615550', 'ASIFA SYAHIRANI', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:39', '2026-01-01 14:16:39'),
(212, 221, 3, NULL, '00290', '0117627797', 'ASSYIFA KHAIRUNNISA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:39', '2026-01-01 14:16:39'),
(213, 222, 3, NULL, '00291', '0119854477', 'AUREL AQILAH PRARIA PUTRI', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:40', '2026-01-01 14:16:40'),
(214, 223, 3, NULL, '00292', '0113033387', 'CLAIRINE AYU SARASWATI', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:40', '2026-01-01 14:16:40'),
(215, 224, 3, NULL, '00294', '0104822932', 'Delvin Agustinus', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:41', '2026-01-01 14:16:41'),
(216, 225, 3, NULL, '00295', '0114609870', 'Dewa Aprilian Caesar', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:41', '2026-01-01 14:16:41'),
(217, 226, 3, NULL, '00296', '0118975075', 'DINARA AULIA ASTREILA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:42', '2026-01-01 14:16:42'),
(218, 227, 3, NULL, '00297', '0129211036', 'DIVA AZZAHRA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:42', '2026-01-01 14:16:42'),
(219, 228, 3, NULL, '00298', '0117898966', 'Dzakiyah Nayla Deandy', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:43', '2026-01-01 14:16:43'),
(220, 229, 3, NULL, '00299', '0129405853', 'Fagan Fabian Altair', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:43', '2026-01-01 14:16:43'),
(221, 230, 3, NULL, '00300', '0112416949', 'FAHRI DERMAWAN', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:44', '2026-01-01 14:16:44'),
(222, 231, 3, NULL, '00301', '0116044975', 'Faza Hakim Firdaus', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:44', '2026-01-01 14:16:44'),
(223, 232, 3, NULL, '00302', '0111692766', 'Fransaid Almuizz', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:44', '2026-01-01 14:16:44'),
(224, 233, 3, NULL, '00303', '0114130299', 'Hafizah Sadira', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:45', '2026-01-01 14:16:45'),
(225, 234, 3, NULL, '00304', '0118480812', 'Ichwan Taufiq', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:45', '2026-01-01 14:16:45'),
(226, 235, 3, NULL, '00305', '0105414523', 'Iinas Firyaal Fakhriyyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:46', '2026-01-01 14:16:46'),
(227, 236, 3, NULL, '00306', '0114845587', 'Ilham Mustapa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:46', '2026-01-01 14:16:46'),
(228, 237, 3, NULL, '00307', '0116393595', 'Kenara Yohanda', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:47', '2026-01-01 14:16:47'),
(229, 238, 3, NULL, '00308', '0113110011', 'Keyzia Morella', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:47', '2026-01-01 14:16:47'),
(230, 239, 3, NULL, '00309', '0117315071', 'Khafka Fadhilul Afif Alfri', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:48', '2026-01-01 14:16:48'),
(231, 240, 3, NULL, '00310', '0116388898', 'Kiagus Muhammad Azzikri', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:48', '2026-01-01 14:16:48'),
(232, 241, 3, NULL, '00311', '0115399774', 'Luckye Alpari', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:49', '2026-01-01 14:16:49'),
(233, 242, 3, NULL, '00312', '0114239467', 'M. Fahruddin', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:49', '2026-01-01 14:16:49'),
(234, 243, 3, NULL, '00314', '0115673605', 'M. Azzafha Alkhatirta Syaher', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:50', '2026-01-01 14:16:50'),
(235, 244, 3, NULL, '00315', '0112083394', 'M. Hafiz At-Taqiy', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:50', '2026-01-01 14:16:50'),
(236, 245, 3, NULL, '00316', '0113864135', 'M. Nabil Yeishaq', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:51', '2026-01-01 14:16:51'),
(237, 246, 3, NULL, '00317', '0112219954', 'M. VARID AMIN KATHABI', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:51', '2026-01-01 14:16:51'),
(238, 247, 3, NULL, '00318', '0112146010', 'M. Zakiy Al Balkis', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:52', '2026-01-01 14:16:52'),
(239, 248, 3, NULL, '00319', '0117590330', 'M. Ikhsan Febriansyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:52', '2026-01-01 14:16:52'),
(240, 249, 3, NULL, '00320', '0114468216', 'M. Nizam Ramadhan', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:53', '2026-01-01 14:16:53'),
(241, 250, 3, NULL, '00321', '3119701204', 'Maheza Aldippa Rifasyanki', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:53', '2026-01-01 14:16:53'),
(242, 251, 3, NULL, '00322', '0117676073', 'Muazara Ulfa Rohmad', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:53', '2026-01-01 14:16:53'),
(243, 252, 3, NULL, '00323', '0119458565', 'Muhammad Alif Junaidi Rasyied', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:54', '2026-01-01 14:16:54'),
(244, 253, 3, NULL, '00324', '0119816128', 'MUHAMMAD FHAIZ MAULANA SUSILO', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:54', '2026-01-01 14:16:54'),
(245, 254, 3, NULL, '00325', '0111126503', 'Muhammad Mahvin Gustiandi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:55', '2026-01-01 14:16:55'),
(246, 255, 3, NULL, '00326', '0104073821', 'Mulla Reza', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:55', '2026-01-01 14:16:55'),
(247, 256, 3, NULL, '00328', '0114898262', 'Nafisha Ananda', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:56', '2026-01-01 14:16:56'),
(248, 257, 3, NULL, '00329', '0116767514', 'Nasywa Aqila Khairani', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:56', '2026-01-01 14:16:56'),
(249, 258, 3, NULL, '00330', '3102879042', 'Prenzi Founjalian', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:57', '2026-01-01 14:16:57'),
(250, 259, 3, NULL, '00331', '0106554050', 'Putra Romadon', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:57', '2026-01-01 14:16:57'),
(251, 260, 3, NULL, '00332', '0128054528', 'Putri Jasmine Harahap', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:58', '2026-01-01 14:16:58'),
(252, 261, 3, NULL, '00333', '0121321934', 'Rangga Dwi Ariyansyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:58', '2026-01-01 14:16:58'),
(253, 262, 3, NULL, '00334', '0122043569', 'Ruli Malik Rasyid Asshidiq', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:59', '2026-01-01 14:16:59'),
(254, 263, 3, NULL, '00335', '0111625728', 'SHELLY AULIA PUTRI', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:16:59', '2026-01-01 14:16:59'),
(255, 264, 3, NULL, '00336', '0118669829', 'Syahrendra Rizla Rayend Utomo', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:00', '2026-01-01 14:17:00'),
(256, 265, 3, NULL, '00337', '0111843142', 'Syarif Abdurrahman', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:00', '2026-01-01 14:17:00'),
(257, 266, 3, NULL, '00338', '0111254083', 'SYAVIRA MARTHA RISKI SAPUTRI', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:01', '2026-01-01 14:17:01'),
(258, 267, 3, NULL, '00339', '0119026445', 'Syifa Aira Mutiara Taruni', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:01', '2026-01-01 14:17:01'),
(259, 268, 3, NULL, '00340', '0116131816', 'TIARA AMANDA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:02', '2026-01-01 14:17:02'),
(260, 269, 3, NULL, '00341', '0116784057', 'Tiara Fitriansyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:02', '2026-01-01 14:17:02'),
(261, 270, 3, NULL, '00342', '0113401908', 'Tyandra Abbra Ganesha', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:03', '2026-01-01 14:17:03'),
(262, 271, 3, NULL, '00344', '0113334539', 'Viona Pramayshella', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:03', '2026-01-01 14:17:03'),
(263, 272, 3, NULL, '00345', '0114665883', 'Wira Imanda Gusti', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:04', '2026-01-01 14:17:04'),
(264, 273, 3, NULL, '00346', '0121164338', 'Zanira Hayu Hafidzah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:04', '2026-01-01 14:17:04'),
(265, 274, 3, NULL, '00347', '0114078369', 'ZASKIA CAESARITA DIAN', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:05', '2026-01-01 14:17:05'),
(266, 275, 3, NULL, '00348', '0117742474', 'ZULFA HANIYYA AZZAHRA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:05', '2026-01-01 14:17:05'),
(267, 276, 3, NULL, '00351', '0127618905', 'Aura Putri Divia', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:05', '2026-01-01 14:17:05'),
(268, 277, 3, NULL, '00352', '0107217620', 'Azza Puspita Ayu', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:06', '2026-01-01 14:17:06'),
(269, 278, 3, NULL, '00355', '0112361124', 'Khanza Nafisah Hadiwijaya', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:06', '2026-01-01 14:17:06'),
(270, 279, 3, NULL, '00358', '0113174169', 'HANIIFAH SYAHFIRA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:07', '2026-01-01 14:17:07'),
(271, 280, 3, NULL, '00361', '0115863646', 'MUHAMMAD FATHAN FARIS', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:07', '2026-01-01 14:17:07'),
(272, 281, 3, NULL, '00362', '3123065879', 'Ahmad Fairuz', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:08', '2026-01-01 14:17:08'),
(273, 282, 3, NULL, '00363', '00115813957', 'Alia Renada', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:08', '2026-01-01 14:17:08'),
(274, 283, 3, NULL, '00364', '3123065880', 'Alikha Al Zahra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:09', '2026-01-01 14:17:09'),
(275, 284, 3, NULL, '00365', '00112717105', 'Aliyah Dzakiyah Uzma', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:09', '2026-01-01 14:17:09'),
(276, 285, 3, NULL, '00367', '0123594693', 'Apta Arsenio Baskoro', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:10', '2026-01-01 14:17:10'),
(277, 286, 3, NULL, '00368', '0127587698', 'Aqilla Zhafirah Arrayan', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:10', '2026-01-01 14:17:10'),
(278, 287, 3, NULL, '00369', '0126199053', 'Balqis Diandratiqah Widuri', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:11', '2026-01-01 14:17:11'),
(279, 288, 3, NULL, '00370', '0123530745', 'Deki Apriansyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:11', '2026-01-01 14:17:11'),
(280, 289, 3, NULL, '00372', '0132974721', 'Donita Queenzha Salsabilah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:12', '2026-01-01 14:17:12'),
(281, 290, 3, NULL, '00373', '0121139534', 'Ferina Luthfia Balqis', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:12', '2026-01-01 14:17:12'),
(282, 291, 3, NULL, '00374', '0117998925', 'Ghali Azka Abdillah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:13', '2026-01-01 14:17:13'),
(283, 292, 3, NULL, '00375', '0127009682', 'Gilang Adhyastha Susanto', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:13', '2026-01-01 14:17:13'),
(284, 293, 3, NULL, '00376', '0123595252', 'Hanun Khairunnisa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:14', '2026-01-01 14:17:14'),
(285, 294, 3, NULL, '00377', '0111142018', 'Harwanto', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:14', '2026-01-01 14:17:14'),
(286, 295, 3, NULL, '00378', '0122017416', 'Husna Najwa Kinara', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:14', '2026-01-01 14:17:14'),
(287, 296, 3, NULL, '00379', '0123211394', 'Intan Sabrina', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:15', '2026-01-01 14:17:15'),
(288, 297, 3, NULL, '00380', '0126958695', 'Keyla Assyifa Maharani', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:15', '2026-01-01 14:17:15'),
(289, 298, 3, NULL, '00381', '1296024891', 'Khansaa Zakiy Nabiilah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:16', '2026-01-01 14:17:16'),
(290, 299, 3, NULL, '00382', '3132574194', 'Kiandra Anaia Putri Wardani', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:16', '2026-01-01 14:17:16'),
(291, 300, 3, NULL, '00383', '0129415284', 'Kinanti Auerelia Putri', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:17', '2026-01-01 14:17:17'),
(292, 301, 3, NULL, '00384', '0105401849', 'M. Iqbal Alfiqi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:17', '2026-01-01 14:17:17'),
(293, 302, 3, NULL, '00385', '0128979292', 'M. Raihan Alfarizqi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:18', '2026-01-01 14:17:18'),
(294, 303, 3, NULL, '00386', '0126255917', 'M. Tristan Alief Alrummi AD', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:18', '2026-01-01 14:17:18'),
(295, 304, 3, NULL, '00387', '0127642195', 'Mahdiyah Rafika', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:19', '2026-01-01 14:17:19'),
(296, 305, 3, NULL, '00388', '0122047053', 'M. Rizky Al-Ghazali', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:19', '2026-01-01 14:17:19'),
(297, 306, 3, NULL, '00389', '0128679897', 'Melinda Juniaty', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:20', '2026-01-01 14:17:20'),
(298, 307, 3, NULL, '00390', '0125949672', 'Michelle Anatacia', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:20', '2026-01-01 14:17:20'),
(299, 308, 3, NULL, '00391', '0125197886', 'Mubdi Baskoro', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:21', '2026-01-01 14:17:21'),
(300, 309, 3, NULL, '00392', '3125319701', 'Muhammad Azzam Dyas Adhipramana', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:21', '2026-01-01 14:17:21'),
(301, 310, 3, NULL, '00393', '3125662107', 'Muhammad Fadhil Alfahrizi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:22', '2026-01-01 14:17:22'),
(302, 311, 3, NULL, '00394', '0118946629', 'Muhammad Izaan Althaf', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:22', '2026-01-01 14:17:22'),
(303, 312, 3, NULL, '00395', '0126901816', 'Mikhayla Dwi Ayumi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:23', '2026-01-01 14:17:23'),
(304, 313, 3, NULL, '00396', '0127742254', 'Muhammad Kevin Junior', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:23', '2026-01-01 14:17:23'),
(305, 314, 3, NULL, '00397', '0126242576', 'Muhammad Malka Haidar Firdaus', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:23', '2026-01-01 14:17:23'),
(306, 315, 3, NULL, '00398', '0124056187', 'Muhammad Syafiq Aesar', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:24', '2026-01-01 14:17:24'),
(307, 316, 3, NULL, '00399', '0122512034', 'Muhammad Tuhfatul Azka', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:24', '2026-01-01 14:17:24'),
(308, 317, 3, NULL, '00400', '0126236146', 'Nabila Aprilia', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:25', '2026-01-01 14:17:25'),
(309, 318, 3, NULL, '00401', '0122286327', 'Naila Kasi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:25', '2026-01-01 14:17:25'),
(310, 319, 3, NULL, '00402', '0121880506', 'Najla Nur Qisya', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:26', '2026-01-01 14:17:26'),
(311, 320, 3, NULL, '00403', '0122166478', 'Neneng Nadya Herawati', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:26', '2026-01-01 14:17:26'),
(312, 321, 3, NULL, '00404', '0126362104', 'Nizham Dzaki Fatahillah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:27', '2026-01-01 14:17:27'),
(313, 322, 3, NULL, '00405', '0127066622', 'Nur Alif Zahran', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:27', '2026-01-01 14:17:27'),
(314, 323, 3, NULL, '00406', '0124662470', 'Nur Rahman', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:28', '2026-01-01 14:17:28'),
(315, 324, 3, NULL, '00407', '0129910642', 'Rafa Prasetia', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:28', '2026-01-01 14:17:28'),
(316, 325, 3, NULL, '00408', '0126927788', 'Raina Fazila', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:29', '2026-01-01 14:17:29'),
(317, 326, 3, NULL, '00409', '0115159163', 'Roro Ayu Safira Arini', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:29', '2026-01-01 14:17:29'),
(318, 327, 3, NULL, '00410', '0123912378', 'Seldi Putra Prayetno', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:30', '2026-01-01 14:17:30'),
(319, 328, 3, NULL, '00412', '3127797320', 'Wahyu Putra Samudra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:30', '2026-01-01 14:17:30'),
(320, 329, 3, NULL, '00413', '0128463260', 'Zahid Raes Al Hidayat', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:30', '2026-01-01 14:17:30'),
(321, 330, 3, NULL, '00414', '00121316476', 'Zaki Pratama', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:31', '2026-01-01 14:17:31'),
(322, 331, 3, NULL, '00415', '0102299611', 'Hafizh Fadli Zharifa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:31', '2026-01-01 14:17:31'),
(323, 332, 3, NULL, '00416', '3125436745', 'Azrul Rizki Parindra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:32', '2026-01-01 14:17:32'),
(324, 333, 3, NULL, '00417', '0124300161', 'Intan Zhafira Kalista', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:32', '2026-01-01 14:17:32'),
(325, 334, 3, NULL, '00419', '0121906464', 'Dea Bilqis Anisa Firly', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:33', '2026-01-01 14:17:33'),
(326, 335, 3, NULL, '00420', '0113602481', 'NAILA AYU PRISA', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:33', '2026-01-01 14:17:33'),
(327, 336, 3, NULL, '00421', '3125201897', 'Abdul Aziiz Saputra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:34', '2026-01-01 14:17:34'),
(328, 337, 3, NULL, '00422', '0134305452', 'Aditya Nukman', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:34', '2026-01-01 14:17:34'),
(329, 338, 3, NULL, '00423', '0132452009', 'Afiqa Deya Nazhwa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:35', '2026-01-01 14:17:35'),
(330, 339, 3, NULL, '00424', '3134334189', 'Aga Aglab Ibrahim Adinata AF', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:35', '2026-01-01 14:17:35'),
(331, 340, 3, NULL, '00425', '03131557666', 'Aisyah Nur Zakiyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:36', '2026-01-01 14:17:36'),
(332, 341, 3, NULL, '00426', '0107181222', 'Alif Ridwan', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:36', '2026-01-01 14:17:36'),
(333, 342, 3, NULL, '00427', '0127834351', 'Alika Ainurohima', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:37', '2026-01-01 14:17:37'),
(334, 343, 3, NULL, '00428', '0139729861', 'Alya Salsabillah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:37', '2026-01-01 14:17:37'),
(335, 344, 3, NULL, '00429', '3136116299', 'Aqila Putri Rahmadani', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:38', '2026-01-01 14:17:38'),
(336, 345, 3, NULL, '00430', '0131645017', 'Aqilah Nuri Khoirunnisa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:38', '2026-01-01 14:17:38'),
(337, 346, 3, NULL, '00431', '0137228101', 'Aril Mahesa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:38', '2026-01-01 14:17:38'),
(338, 347, 3, NULL, '00432', '0129428543', 'Assyifa Niswah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:39', '2026-01-01 14:17:39'),
(339, 348, 3, NULL, '00433', '0137582696', 'Azhara Khairunnisa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:39', '2026-01-01 14:17:39'),
(340, 349, 3, NULL, '00434', '0131125700', 'Belva Yuda Pratiwi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:40', '2026-01-01 14:17:40');
INSERT INTO `students` (`id`, `user_siswa_id`, `unit_id`, `class_id`, `nis`, `nisn`, `nama_lengkap`, `jenis_kelamin`, `is_boarding`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `alamat_rt`, `alamat_rw`, `desa`, `kecamatan`, `kota`, `kode_pos`, `no_hp`, `nama_wali`, `no_hp_wali`, `status`, `withdrawal_proof`, `created_at`, `updated_at`) VALUES
(341, 350, 3, NULL, '00435', '3135970034', 'David Villa Kesuma', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:40', '2026-01-01 14:17:40'),
(342, 351, 3, NULL, '00436', '0116486049', 'Delfia Say Denay', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:41', '2026-01-01 14:17:41'),
(343, 352, 3, NULL, '00437', '0132316089', 'Faizah Majida', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:41', '2026-01-01 14:17:41'),
(344, 353, 3, NULL, '00438', '3137749450', 'Hafidzah Azzahrah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:42', '2026-01-01 14:17:42'),
(345, 354, 3, NULL, '00439', '3134482942', 'Hakim Annabil', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:42', '2026-01-01 14:17:42'),
(346, 355, 3, NULL, '00440', '0136774568', 'Keysah Aqila Azzahra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:43', '2026-01-01 14:17:43'),
(347, 356, 3, NULL, '00441', '0125861015', 'Kia Florisa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:43', '2026-01-01 14:17:43'),
(348, 357, 3, NULL, '00442', '0125144321', 'M. Zain Al Rasyid', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:43', '2026-01-01 14:17:43'),
(349, 358, 3, NULL, '00443', '3131937586', 'Muhammad Rafie Wijaya', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:44', '2026-01-01 14:17:44'),
(350, 359, 3, NULL, '00444', '0137693123', 'Muhammad Abizam', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:44', '2026-01-01 14:17:44'),
(351, 360, 3, NULL, '00445', '0131419404', 'Muhammad Indra Saputra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:45', '2026-01-01 14:17:45'),
(352, 361, 3, NULL, '00446', '0119838421', 'Muhammad Naufal', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:45', '2026-01-01 14:17:45'),
(353, 362, 3, NULL, '00447', '0132728143', 'Muhammad Naufal Arifqi', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:46', '2026-01-01 14:17:46'),
(354, 363, 3, NULL, '00448', '3137069739', 'Muhammad Padlan Paseka', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:46', '2026-01-01 14:17:46'),
(355, 364, 3, NULL, '00449', '0122659354', 'Nadhifah Shaki Kenedy', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:47', '2026-01-01 14:17:47'),
(356, 365, 3, NULL, '00450', '0134276025', 'Najwa Khaira Nadhifa', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:47', '2026-01-01 14:17:47'),
(357, 366, 3, NULL, '00451', '0131229162', 'Nanda Dzakiyah Aftani', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:48', '2026-01-01 14:17:48'),
(358, 367, 3, NULL, '00452', '0134450584', 'Nila Shofiyatul Awaliyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:48', '2026-01-01 14:17:48'),
(359, 368, 3, NULL, '00453', '3136634972', 'Nur\'aini', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:49', '2026-01-01 14:17:49'),
(360, 369, 3, NULL, '00454', '0121083387', 'Putri Lucingga', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:49', '2026-01-01 14:17:49'),
(361, 370, 3, NULL, '00455', '0136246695', 'Ramadhan Khairu Nugraha', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:50', '2026-01-01 14:17:50'),
(362, 371, 3, NULL, '00456', '0126732424', 'Reyvan Daffa Saputra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:50', '2026-01-01 14:17:50'),
(363, 372, 3, NULL, '00457', '0135943912', 'Shifa Shauqiyah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:51', '2026-01-01 14:17:51'),
(364, 373, 3, NULL, '00458', '3136054703', 'Syifatun Jannah', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:51', '2026-01-01 14:17:51'),
(365, 374, 3, NULL, '00459', '3138079664', 'Wira Ananta Rudira Karos', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:51', '2026-01-01 14:17:51'),
(366, 375, 3, NULL, '00460', '0133583363', 'Yafiza Fitria Azzahra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:52', '2026-01-01 14:17:52'),
(367, 376, 3, NULL, '00461', '0134253253', 'Yusuf Efendi Harahap', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:52', '2026-01-01 14:17:52'),
(368, 377, 3, NULL, '00462', '3132366474', 'Zahra Alya Naima', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:53', '2026-01-01 14:17:53'),
(369, 378, 3, NULL, '00463', '3136857713', 'Zaskia Salsabila', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:53', '2026-01-01 14:17:53'),
(370, 379, 3, NULL, '00464', '0127917847', 'Zio Rizky Alvaro', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:54', '2026-01-01 14:17:54'),
(371, 380, 3, NULL, '00465', '0137954562', 'Zulaikha Zhafira', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:54', '2026-01-01 14:17:54'),
(372, 381, 3, NULL, '00466', '0127240243', 'Reyhan Saputra', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:55', '2026-01-01 14:17:55'),
(373, 382, 3, NULL, '0418', '0111769346', 'Alverina Zahra Kirana', 'L', 0, '', NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 'aktif', NULL, '2026-01-01 14:17:55', '2026-01-01 14:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `student_achievements`
--

CREATE TABLE `student_achievements` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `achievement_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_achievements`
--

INSERT INTO `student_achievements` (`id`, `student_id`, `date`, `achievement_name`, `level`, `rank`, `description`, `proof`, `recorded_by`, `created_at`, `updated_at`, `academic_year_id`) VALUES
(3, 31, '2026-01-01', 'LOMBA FUTSAL', 'Kecamatan', NULL, NULL, 'achievements/0wEUhf95QYz6azuQNPFVmQ5Ubzi4MmyWYpReP8c6.jpg', 1, '2026-01-01 11:32:15', '2026-01-01 11:32:15', 5);

-- --------------------------------------------------------

--
-- Table structure for table `student_attendances`
--

CREATE TABLE `student_attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('present','sick','permission','alpha','late','school_activity') COLLATE utf8mb4_unicode_ci DEFAULT 'present',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_attendances`
--

INSERT INTO `student_attendances` (`id`, `student_id`, `class_id`, `academic_year_id`, `date`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(8, 30, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(9, 31, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(10, 32, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(11, 33, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(12, 34, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(13, 35, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(14, 36, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(15, 37, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(16, 38, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(17, 39, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(18, 40, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(19, 41, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:58'),
(20, 42, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:58'),
(21, 43, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(22, 44, 39, 5, '2025-12-24', 'present', NULL, 27, '2025-12-24 01:58:48', '2025-12-24 05:46:29'),
(23, 45, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(24, 46, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(25, 47, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(26, 48, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(27, 49, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(28, 50, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(29, 51, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(30, 52, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(31, 53, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(32, 54, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(33, 55, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(34, 56, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(35, 57, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(36, 58, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(37, 59, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(38, 60, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(39, 61, 40, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(40, 62, 40, 5, '2025-12-24', 'alpha', NULL, 1, '2025-12-24 02:06:47', '2025-12-24 02:06:47'),
(41, 63, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(42, 64, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(43, 65, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(44, 66, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(45, 67, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(46, 68, 41, 5, '2025-12-24', 'permission', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(47, 69, 41, 5, '2025-12-24', 'permission', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(48, 70, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(49, 71, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(50, 72, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(51, 73, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(52, 74, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(53, 75, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(54, 76, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(55, 77, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(56, 78, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(57, 79, 41, 5, '2025-12-24', 'present', NULL, 1, '2025-12-24 02:07:23', '2025-12-24 02:07:23'),
(58, 30, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(59, 31, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(60, 32, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(61, 33, 39, 5, '2025-12-25', 'late', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(62, 34, 39, 5, '2025-12-25', 'alpha', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(63, 35, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(64, 36, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(65, 37, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(66, 38, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(67, 39, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(68, 40, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(69, 41, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(70, 42, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(71, 43, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(72, 44, 39, 5, '2025-12-25', 'present', NULL, 1, '2025-12-25 03:31:37', '2025-12-25 03:31:37'),
(73, 30, 39, 5, '2025-12-29', 'late', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(74, 31, 39, 5, '2025-12-29', 'alpha', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(75, 32, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(76, 33, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(77, 34, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(78, 35, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(79, 36, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(80, 37, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(81, 38, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(82, 39, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(83, 40, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(84, 41, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(85, 42, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(86, 43, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(87, 44, 39, 5, '2025-12-29', 'present', NULL, 27, '2025-12-29 12:32:56', '2025-12-29 12:32:56'),
(88, 30, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(89, 31, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 06:03:42'),
(90, 32, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(91, 33, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(92, 34, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(93, 35, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(94, 36, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(95, 37, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(96, 38, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(97, 39, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(98, 40, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 06:03:42'),
(99, 41, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 06:03:42'),
(100, 42, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 06:03:42'),
(101, 43, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(102, 44, 39, 5, '2025-12-30', 'present', NULL, 27, '2025-12-30 01:52:08', '2025-12-30 01:52:08'),
(103, 30, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(104, 31, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(105, 32, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(106, 33, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(107, 34, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(108, 35, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(109, 36, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(110, 37, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(111, 38, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(112, 39, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(113, 40, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(114, 41, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(115, 42, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(116, 43, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(117, 44, 39, 5, '2025-12-17', 'present', NULL, 27, '2025-12-30 13:43:11', '2025-12-30 13:43:11'),
(118, 109, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(119, 110, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(120, 111, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(121, 112, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(122, 113, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(123, 114, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(124, 115, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(125, 116, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(126, 117, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(127, 118, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(128, 119, 43, 5, '2025-12-30', 'present', NULL, 23, '2025-12-30 14:42:50', '2025-12-30 14:42:50'),
(129, 30, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(130, 31, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(131, 32, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(132, 33, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(133, 34, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(134, 35, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(135, 36, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(136, 37, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(137, 38, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(138, 39, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(139, 40, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(140, 41, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(141, 42, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(142, 43, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(143, 44, 39, 5, '2026-01-01', 'present', NULL, 27, '2026-01-01 10:57:31', '2026-01-01 11:49:56'),
(144, 30, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(145, 31, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(146, 32, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(147, 33, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(148, 34, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(149, 35, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(150, 36, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(151, 37, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(152, 38, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(153, 39, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(154, 40, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(155, 41, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(156, 42, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(157, 43, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47'),
(158, 44, 39, 5, '2026-01-02', 'present', NULL, 27, '2026-01-02 03:21:47', '2026-01-02 03:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `student_bills`
--

CREATE TABLE `student_bills` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `payment_type_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `due_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_bills`
--

INSERT INTO `student_bills` (`id`, `student_id`, `payment_type_id`, `academic_year_id`, `month`, `year`, `amount`, `discount_amount`, `is_free`, `paid_amount`, `status`, `due_date`, `notes`, `created_at`, `updated_at`) VALUES
(868, 30, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(869, 30, 18, 5, 8, 2025, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(870, 30, 18, 5, 9, 2025, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(871, 30, 18, 5, 10, 2025, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(872, 30, 18, 5, 11, 2025, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(873, 30, 18, 5, 12, 2025, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(874, 30, 18, 5, 1, 2026, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(875, 30, 18, 5, 2, 2026, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(876, 30, 18, 5, 3, 2026, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(877, 30, 18, 5, 4, 2026, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(878, 30, 18, 5, 5, 2026, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(879, 30, 18, 5, 6, 2026, 0.00, 0.00, 1, 0.00, 'paid', NULL, NULL, '2025-12-29 03:28:26', '2026-01-01 06:29:13'),
(882, 31, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-02 19:38:46'),
(883, 31, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:13'),
(884, 31, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:13'),
(885, 31, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:13'),
(886, 31, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:13'),
(887, 31, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(888, 31, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(889, 31, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(890, 31, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(891, 31, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(892, 32, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(893, 32, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(894, 32, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(895, 32, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(896, 32, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(897, 32, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(898, 32, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(899, 32, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(900, 32, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(901, 32, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(902, 32, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(903, 32, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(904, 33, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(905, 33, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(906, 33, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(907, 33, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(908, 33, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(909, 33, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(910, 33, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(911, 33, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(912, 33, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(913, 33, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(914, 33, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(915, 33, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(916, 34, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(917, 34, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(918, 34, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(919, 34, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(920, 34, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(921, 34, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(922, 34, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(923, 34, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(924, 34, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(925, 34, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(926, 34, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(927, 34, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(928, 35, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(929, 35, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(930, 35, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(931, 35, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(932, 35, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(933, 35, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(934, 35, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(935, 35, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(936, 35, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(937, 35, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(938, 35, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(939, 35, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(940, 36, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(941, 36, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(942, 36, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(943, 36, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(944, 36, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(945, 36, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(946, 36, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(947, 36, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(948, 36, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(949, 36, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(950, 36, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(951, 36, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(952, 37, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(953, 37, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(954, 37, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(955, 37, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(956, 37, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(957, 37, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(958, 37, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(959, 37, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:14'),
(960, 37, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(961, 37, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(962, 37, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(963, 37, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(964, 38, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(965, 38, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(966, 38, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(967, 38, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(968, 38, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(969, 38, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(970, 38, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(971, 38, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(972, 38, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(973, 38, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(974, 38, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(975, 38, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(976, 39, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(977, 39, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(978, 39, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(979, 39, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(980, 39, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(981, 39, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(982, 39, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(983, 39, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(984, 39, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(985, 39, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(986, 39, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(987, 39, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(988, 40, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(989, 40, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(990, 40, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(991, 40, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(992, 40, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(993, 40, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(994, 40, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(995, 40, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(996, 40, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(997, 40, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(998, 40, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(999, 40, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1000, 41, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1001, 41, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1002, 41, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1003, 41, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1004, 41, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1005, 41, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1006, 41, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1007, 41, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1008, 41, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1009, 41, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1010, 41, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1011, 41, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1012, 42, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1013, 42, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1014, 42, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1015, 42, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1016, 42, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1017, 42, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1018, 42, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1019, 42, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1020, 42, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1021, 42, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1022, 42, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1023, 42, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1024, 43, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1025, 43, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1026, 43, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1027, 43, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1028, 43, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1029, 43, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1030, 43, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1031, 43, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1032, 43, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1033, 43, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1034, 43, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1035, 43, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1036, 44, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1037, 44, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:27', '2026-01-01 06:29:15'),
(1038, 44, 18, 5, 9, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:15'),
(1039, 44, 18, 5, 10, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:15'),
(1040, 44, 18, 5, 11, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:15'),
(1041, 44, 18, 5, 12, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:15'),
(1042, 44, 18, 5, 1, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:15'),
(1043, 44, 18, 5, 2, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:15'),
(1044, 44, 18, 5, 3, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:16'),
(1045, 44, 18, 5, 4, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:16'),
(1046, 44, 18, 5, 5, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:16'),
(1047, 44, 18, 5, 6, 2026, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:28:28', '2026-01-01 06:29:16'),
(1048, 30, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1050, 32, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1051, 33, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1052, 34, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1053, 35, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1054, 36, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1055, 37, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1056, 38, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1057, 39, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1058, 40, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1059, 41, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1060, 42, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1061, 43, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1062, 44, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:23', '2025-12-30 14:35:14'),
(1063, 31, 19, 5, 7, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-02 19:38:52'),
(1064, 31, 19, 5, 8, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-02 02:44:42'),
(1065, 31, 19, 5, 9, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1066, 31, 19, 5, 10, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1067, 31, 19, 5, 11, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1068, 31, 19, 5, 12, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1069, 31, 19, 5, 1, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1070, 31, 19, 5, 2, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1071, 31, 19, 5, 3, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1072, 31, 19, 5, 4, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1073, 31, 19, 5, 5, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1074, 31, 19, 5, 6, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-29 03:29:41', '2026-01-01 08:30:03'),
(1087, 30, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1088, 31, 22, 5, 7, NULL, 900000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2026-01-01 05:41:13'),
(1089, 32, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1090, 33, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1091, 34, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1092, 35, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1093, 36, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1094, 37, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1095, 38, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1096, 39, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1097, 40, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1098, 41, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1099, 42, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1100, 43, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1101, 44, 22, 5, 7, NULL, 1000000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(1102, 31, 20, 5, 12, NULL, 1300000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 14:35:14', '2026-01-01 05:41:07'),
(1103, 31, 18, 5, 7, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 14:39:42', '2026-01-02 19:38:52'),
(1104, 31, 18, 5, 8, 2025, 370000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2025-12-30 14:39:42', '2026-01-02 19:38:46'),
(1105, 30, 19, 5, 7, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(1106, 30, 19, 5, 8, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(1107, 30, 19, 5, 9, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(1108, 30, 19, 5, 10, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(1109, 30, 19, 5, 11, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(1110, 30, 19, 5, 12, 2025, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(1111, 30, 19, 5, 1, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(1112, 30, 19, 5, 2, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(1113, 30, 19, 5, 3, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(1114, 30, 19, 5, 4, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(1115, 30, 19, 5, 5, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(1116, 30, 19, 5, 6, 2026, 1200000.00, 0.00, 0, 0.00, 'unpaid', NULL, NULL, '2026-01-01 06:32:02', '2026-01-01 08:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `student_graduation_results`
--

CREATE TABLE `student_graduation_results` (
  `id` bigint UNSIGNED NOT NULL,
  `graduation_announcement_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `status` enum('lulus','tidak_lulus','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `message` text COLLATE utf8mb4_unicode_ci,
  `skl_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_payment_settings`
--

CREATE TABLE `student_payment_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `payment_type_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_month` int DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_payment_settings`
--

INSERT INTO `student_payment_settings` (`id`, `student_id`, `payment_type_id`, `academic_year_id`, `month`, `due_month`, `amount`, `discount_amount`, `is_free`, `created_at`, `updated_at`) VALUES
(703, 31, 19, 5, '7', 7, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(704, 31, 19, 5, '8', 8, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(705, 31, 19, 5, '9', 9, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(706, 31, 19, 5, '10', 10, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(707, 31, 19, 5, '11', 11, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(708, 31, 19, 5, '12', 12, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(709, 31, 19, 5, '1', 1, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(710, 31, 19, 5, '2', 2, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(711, 31, 19, 5, '3', 3, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(712, 31, 19, 5, '4', 4, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(713, 31, 19, 5, '5', 5, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(714, 31, 19, 5, '6', 6, 1200000.00, 0.00, 0, '2025-12-29 02:47:32', '2026-01-01 08:30:03'),
(715, 30, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(716, 30, 18, 5, '8', 8, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(717, 30, 18, 5, '9', 9, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(718, 30, 18, 5, '10', 10, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(719, 30, 18, 5, '11', 11, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(720, 30, 18, 5, '12', 12, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(721, 30, 18, 5, '1', 1, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(722, 30, 18, 5, '2', 2, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(723, 30, 18, 5, '3', 3, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(724, 30, 18, 5, '4', 4, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(725, 30, 18, 5, '5', 5, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(726, 30, 18, 5, '6', 6, 0.00, 0.00, 1, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(727, 31, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(728, 31, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(729, 31, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(730, 31, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(731, 31, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(732, 31, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(733, 31, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:13'),
(734, 31, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(735, 31, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(736, 31, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(737, 31, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(738, 31, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(739, 32, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(740, 32, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(741, 32, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(742, 32, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(743, 32, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(744, 32, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(745, 32, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(746, 32, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(747, 32, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(748, 32, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(749, 32, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(750, 32, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(751, 33, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(752, 33, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(753, 33, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:05', '2026-01-01 06:29:14'),
(754, 33, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(755, 33, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(756, 33, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(757, 33, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(758, 33, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(759, 33, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(760, 33, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(761, 33, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(762, 33, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(763, 34, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(764, 34, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(765, 34, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(766, 34, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(767, 34, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(768, 34, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(769, 34, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(770, 34, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(771, 34, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(772, 34, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(773, 34, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(774, 34, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(775, 35, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(776, 35, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(777, 35, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(778, 35, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(779, 35, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(780, 35, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(781, 35, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(782, 35, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(783, 35, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(784, 35, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(785, 35, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(786, 35, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(787, 36, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(788, 36, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(789, 36, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(790, 36, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(791, 36, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(792, 36, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(793, 36, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(794, 36, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(795, 36, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(796, 36, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(797, 36, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(798, 36, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(799, 37, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(800, 37, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(801, 37, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(802, 37, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(803, 37, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(804, 37, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(805, 37, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(806, 37, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(807, 37, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:14'),
(808, 37, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(809, 37, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(810, 37, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(811, 38, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(812, 38, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(813, 38, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(814, 38, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(815, 38, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(816, 38, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(817, 38, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(818, 38, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(819, 38, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(820, 38, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(821, 38, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(822, 38, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(823, 39, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(824, 39, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(825, 39, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(826, 39, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(827, 39, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(828, 39, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(829, 39, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(830, 39, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(831, 39, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(832, 39, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(833, 39, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(834, 39, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(835, 40, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(836, 40, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(837, 40, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(838, 40, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(839, 40, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(840, 40, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(841, 40, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(842, 40, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(843, 40, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(844, 40, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(845, 40, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(846, 40, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(847, 41, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(848, 41, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(849, 41, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(850, 41, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(851, 41, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(852, 41, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(853, 41, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(854, 41, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(855, 41, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(856, 41, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(857, 41, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(858, 41, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(859, 42, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(860, 42, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(861, 42, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(862, 42, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(863, 42, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(864, 42, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:06', '2026-01-01 06:29:15'),
(865, 42, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(866, 42, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(867, 42, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(868, 42, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(869, 42, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(870, 42, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(871, 43, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(872, 43, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(873, 43, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(874, 43, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(875, 43, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(876, 43, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(877, 43, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(878, 43, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(879, 43, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(880, 43, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(881, 43, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(882, 43, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(883, 44, 18, 5, '7', 7, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(884, 44, 18, 5, '8', 8, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(885, 44, 18, 5, '9', 9, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(886, 44, 18, 5, '10', 10, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(887, 44, 18, 5, '11', 11, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(888, 44, 18, 5, '12', 12, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(889, 44, 18, 5, '1', 1, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(890, 44, 18, 5, '2', 2, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(891, 44, 18, 5, '3', 3, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:15'),
(892, 44, 18, 5, '4', 4, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:16'),
(893, 44, 18, 5, '5', 5, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:16'),
(894, 44, 18, 5, '6', 6, 370000.00, 0.00, 0, '2025-12-29 02:48:07', '2026-01-01 06:29:16'),
(895, 30, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(896, 31, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(897, 32, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(898, 33, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(899, 34, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(900, 35, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(901, 36, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(902, 37, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(903, 38, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(904, 39, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(905, 40, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(906, 41, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(907, 42, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(908, 43, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(909, 44, 20, 5, '12', 12, 1300000.00, 0.00, 0, '2025-12-29 02:54:08', '2025-12-30 14:35:14'),
(910, 31, 21, 5, '12', 12, 1000000.00, 0.00, 0, '2025-12-29 02:58:35', '2025-12-29 02:58:35'),
(911, 31, 18, 6, '7', 7, 0.00, 0.00, 1, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(912, 31, 18, 6, '8', 8, 0.00, 0.00, 1, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(913, 31, 18, 6, '9', 9, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(914, 31, 18, 6, '10', 10, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(915, 31, 18, 6, '11', 11, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(916, 31, 18, 6, '12', 12, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(917, 31, 18, 6, '1', 1, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(918, 31, 18, 6, '2', 2, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(919, 31, 18, 6, '3', 3, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(920, 31, 18, 6, '4', 4, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(921, 31, 18, 6, '5', 5, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(922, 31, 18, 6, '6', 6, 370000.00, 0.00, 0, '2025-12-29 06:00:30', '2026-01-01 08:30:25'),
(923, 30, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(924, 31, 22, 5, '7', 7, 900000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(925, 32, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(926, 33, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(927, 34, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(928, 35, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(929, 36, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(930, 37, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(931, 38, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(932, 39, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(933, 40, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(934, 41, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(935, 42, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(936, 43, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(937, 44, 22, 5, '7', 7, 1000000.00, 0.00, 0, '2025-12-30 02:39:08', '2025-12-30 02:39:08'),
(938, 30, 19, 5, '7', 7, 1200000.00, 0.00, 0, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(939, 30, 19, 5, '8', 8, 1200000.00, 0.00, 0, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(940, 30, 19, 5, '9', 9, 1200000.00, 0.00, 0, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(941, 30, 19, 5, '10', 10, 1200000.00, 0.00, 0, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(942, 30, 19, 5, '11', 11, 1200000.00, 0.00, 0, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(943, 30, 19, 5, '12', 12, 1200000.00, 0.00, 0, '2026-01-01 06:32:01', '2026-01-01 08:30:03'),
(944, 30, 19, 5, '1', 1, 1200000.00, 0.00, 0, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(945, 30, 19, 5, '2', 2, 1200000.00, 0.00, 0, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(946, 30, 19, 5, '3', 3, 1200000.00, 0.00, 0, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(947, 30, 19, 5, '4', 4, 1200000.00, 0.00, 0, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(948, 30, 19, 5, '5', 5, 1200000.00, 0.00, 0, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(949, 30, 19, 5, '6', 6, 1200000.00, 0.00, 0, '2026-01-01 06:32:02', '2026-01-01 08:30:03'),
(950, 31, 19, 6, '7', 7, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(951, 31, 19, 6, '8', 8, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(952, 31, 19, 6, '9', 9, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(953, 31, 19, 6, '10', 10, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(954, 31, 19, 6, '11', 11, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(955, 31, 19, 6, '12', 12, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(956, 31, 19, 6, '1', 1, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(957, 31, 19, 6, '2', 2, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(958, 31, 19, 6, '3', 3, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(959, 31, 19, 6, '4', 4, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(960, 31, 19, 6, '5', 5, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40'),
(961, 31, 19, 6, '6', 6, 1200000.00, 0.00, 0, '2026-01-01 08:30:40', '2026-01-01 08:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `student_violations`
--

CREATE TABLE `student_violations` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `violation_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follow_up` text COLLATE utf8mb4_unicode_ci,
  `follow_up_result` text COLLATE utf8mb4_unicode_ci,
  `follow_up_attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follow_up_status` enum('pending','process','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `recorded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `unit_id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(3, 2, 'BAHASA', 'BAHASA', '2025-12-15 20:23:40', '2025-12-15 20:23:40'),
(4, 1, 'MTK', 'MTK', '2025-12-15 20:23:54', '2025-12-15 20:23:54'),
(5, 1, '30155', 'sdfsdf', '2025-12-15 23:01:44', '2025-12-15 23:01:44'),
(6, 1, 'BAHASA INDONESIA', 'BAHASA INDO', '2025-12-15 23:24:16', '2025-12-15 23:24:16'),
(7, 1, 'SD -MAT13', 'Matematika', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(8, 1, 'SD -BAH43', 'Bahasa Indonesia', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(9, 1, 'SD -IPA49', 'IPA', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(10, 1, 'SD -IPS28', 'IPS', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(11, 1, 'SD -PPK90', 'PPKn', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(12, 1, 'SD -PAI75', 'PAI', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(13, 1, 'SD -PJO54', 'PJOK', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(14, 1, 'SD -SEN93', 'Seni Budaya', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(15, 3, 'SMP-MAT83', 'Matematika', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(16, 3, 'SMP-BAH24', 'Bahasa Indonesia', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(17, 3, 'SMP-BAH91', 'Bahasa Inggris', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(18, 3, 'SMP-IPA18', 'IPA', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(19, 3, 'SMP-IPS74', 'IPS', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(20, 3, 'SMP-PPK27', 'PPKn', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(21, 3, 'SMP-PAI72', 'PAI', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(22, 3, 'SMP-PJO39', 'PJOK', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(23, 3, 'SMP-SEN94', 'Seni Budaya', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(24, 3, 'SMP-TIK28', 'TIK', '2025-12-15 23:40:50', '2025-12-15 23:40:50'),
(38, 4, 'PAI_SMA', 'PAI dan Budi Pekerti', '2025-12-27 06:45:17', '2025-12-27 06:46:11'),
(39, 4, 'PPKn_SMA', 'Pendidikan Pancasila', '2025-12-27 06:45:17', '2025-12-27 06:46:28'),
(40, 4, 'BIND_SMA', 'Bahasa Indonesia', '2025-12-27 06:45:17', '2025-12-27 06:46:37'),
(41, 4, 'MTK_SMA', 'Matematika', '2025-12-27 06:45:17', '2025-12-27 06:46:43'),
(42, 4, 'FIS_SMA', 'Fisika', '2025-12-27 06:45:17', '2025-12-27 06:46:49'),
(43, 4, 'KIM_SMA', 'Kimia', '2025-12-27 06:45:17', '2025-12-27 06:46:56'),
(44, 4, 'BIO_SMA', 'Biologi', '2025-12-27 06:45:17', '2025-12-27 06:47:14'),
(45, 4, 'SOS_SMA', 'Sosiologi', '2025-12-27 06:45:17', '2025-12-27 06:47:05'),
(46, 4, 'EKO_SMA', 'Ekonomi', '2025-12-27 06:45:17', '2025-12-27 06:47:48'),
(47, 4, 'SEJ_SMA', 'Sejarah', '2025-12-27 06:45:17', '2025-12-27 06:47:57'),
(48, 4, 'GEO_SMA', 'Geografi', '2025-12-27 06:45:17', '2025-12-27 06:48:02'),
(49, 4, 'BING_SMA', 'B.Inggris', '2025-12-27 06:45:17', '2025-12-27 06:48:06'),
(50, 4, 'PJOK_SMA', 'PJOK', '2025-12-27 06:45:17', '2025-12-27 06:48:12'),
(51, 4, 'INF_SMA', 'Informatika', '2025-12-27 06:45:17', '2025-12-27 06:48:20'),
(52, 4, 'SBK_SMA', 'Seni Budaya', '2025-12-27 06:45:17', '2025-12-27 06:48:27'),
(53, 4, 'BARB_SMA', 'B.Arab', '2025-12-27 06:45:17', '2025-12-27 06:48:34'),
(54, 4, 'PKWU_SMA', 'PKWU', '2025-12-27 06:45:17', '2025-12-27 06:48:39'),
(55, 4, 'MULOK_SMA', 'Mulok Ketahan Pangan Lokal', '2025-12-27 06:45:17', '2025-12-27 06:48:44'),
(56, 4, 'KKA_SMA', 'Coding dan AI', '2025-12-27 06:45:17', '2025-12-27 06:48:50'),
(57, 4, 'TTQ_SMA', 'TTQ', '2025-12-27 06:45:17', '2025-12-27 06:48:55'),
(58, 4, 'PKB_SMA', 'Prakib', '2025-12-27 06:45:17', '2025-12-27 06:49:00'),
(59, 4, 'HDT_SMA', 'Hadits', '2025-12-27 06:45:17', '2025-12-27 06:49:08'),
(60, 4, 'SRH_SMA', 'Sirah', '2025-12-27 06:45:17', '2025-12-27 06:49:12'),
(61, 4, 'INS_BAHASA_SMA', 'INSENTIF BAHASA SMA', '2025-12-27 07:56:49', '2025-12-27 07:56:49'),
(62, 5, 'PAI_SMK', 'PAI', '2025-12-30 06:52:53', '2025-12-30 06:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `supervisions`
--

CREATE TABLE `supervisions` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `supervisor_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `supervisor_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `school_class_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisions`
--

INSERT INTO `supervisions` (`id`, `unit_id`, `academic_year_id`, `supervisor_id`, `teacher_id`, `date`, `time`, `status`, `notes`, `supervisor_document_path`, `teacher_document_path`, `document_status`, `created_at`, `updated_at`, `subject_id`, `school_class_id`) VALUES
(18, 4, 5, 19, 27, '2025-12-24', '05:53:00', 'scheduled', NULL, NULL, 'supervisions/teacher/v8X3CXXtMRFDARNwQSUCo1hIQ07zkc7kWiehusL1.pdf', 'approved', '2025-12-23 10:47:55', '2025-12-29 12:21:14', NULL, 39);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_document_requests`
--

CREATE TABLE `teacher_document_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `target_units` json DEFAULT NULL,
  `target_subjects` json DEFAULT NULL,
  `target_grades` json DEFAULT NULL,
  `target_users` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_document_submissions`
--

CREATE TABLE `teacher_document_submissions` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','validated','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teaching_assignments`
--

CREATE TABLE `teaching_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teaching_assignments`
--

INSERT INTO `teaching_assignments` (`id`, `user_id`, `subject_id`, `class_id`, `academic_year_id`, `created_at`, `updated_at`) VALUES
(113, 27, 38, 39, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(114, 27, 38, 40, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(115, 27, 38, 41, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(116, 27, 55, 39, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(121, 24, 42, 39, 5, '2025-12-27 07:17:21', '2025-12-27 07:17:21'),
(122, 24, 42, 40, 5, '2025-12-27 07:17:21', '2025-12-27 07:17:21'),
(123, 24, 42, 41, 5, '2025-12-27 07:17:21', '2025-12-27 07:17:21'),
(124, 24, 43, 40, 5, '2025-12-27 07:17:21', '2025-12-27 07:17:21'),
(125, 23, 47, 39, 5, '2025-12-27 07:19:49', '2025-12-27 07:19:49'),
(126, 23, 47, 40, 5, '2025-12-27 07:19:49', '2025-12-27 07:19:49'),
(127, 23, 47, 41, 5, '2025-12-27 07:19:49', '2025-12-27 07:19:49'),
(134, 26, 39, 39, 5, '2025-12-27 07:43:35', '2025-12-27 07:43:35'),
(135, 26, 39, 40, 5, '2025-12-27 07:43:35', '2025-12-27 07:43:35'),
(136, 26, 39, 41, 5, '2025-12-27 07:43:35', '2025-12-27 07:43:35'),
(137, 20, 55, 41, 5, '2025-12-27 07:44:37', '2025-12-27 07:44:37'),
(138, 20, 44, 39, 5, '2025-12-27 07:44:37', '2025-12-27 07:44:37'),
(139, 20, 44, 40, 5, '2025-12-27 07:44:37', '2025-12-27 07:44:37'),
(140, 20, 44, 41, 5, '2025-12-27 07:44:37', '2025-12-27 07:44:37'),
(141, 33, 52, 39, 5, '2025-12-27 07:46:15', '2025-12-27 07:46:15'),
(142, 33, 52, 40, 5, '2025-12-27 07:46:15', '2025-12-27 07:46:15'),
(143, 33, 52, 41, 5, '2025-12-27 07:46:15', '2025-12-27 07:46:15'),
(144, 34, 51, 39, 5, '2025-12-27 07:47:07', '2025-12-27 07:47:07'),
(145, 34, 51, 40, 5, '2025-12-27 07:47:07', '2025-12-27 07:47:07'),
(146, 34, 51, 41, 5, '2025-12-27 07:47:07', '2025-12-27 07:47:07'),
(147, 35, 46, 39, 5, '2025-12-27 07:49:03', '2025-12-27 07:49:03'),
(148, 35, 46, 40, 5, '2025-12-27 07:49:03', '2025-12-27 07:49:03'),
(152, 36, 49, 39, 5, '2025-12-27 07:50:23', '2025-12-27 07:50:23'),
(153, 36, 49, 40, 5, '2025-12-27 07:50:23', '2025-12-27 07:50:23'),
(154, 36, 49, 41, 5, '2025-12-27 07:50:23', '2025-12-27 07:50:23'),
(155, 38, 46, 41, 5, '2025-12-27 07:51:14', '2025-12-27 07:51:14'),
(156, 30, 57, 41, 5, '2025-12-27 07:53:11', '2025-12-27 07:53:11'),
(157, 40, 57, 40, 5, '2025-12-27 07:53:59', '2025-12-27 07:53:59'),
(159, 43, 40, 39, 5, '2025-12-27 07:55:13', '2025-12-27 07:55:13'),
(160, 43, 40, 40, 5, '2025-12-27 07:55:13', '2025-12-27 07:55:13'),
(161, 43, 40, 41, 5, '2025-12-27 07:55:13', '2025-12-27 07:55:13'),
(165, 29, 56, 39, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(166, 29, 56, 40, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(167, 29, 56, 41, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(168, 29, 61, 39, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(169, 29, 61, 40, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(170, 29, 61, 41, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(174, 41, 58, 39, 5, '2025-12-27 07:59:18', '2025-12-27 07:59:18'),
(175, 41, 58, 40, 5, '2025-12-27 07:59:18', '2025-12-27 07:59:18'),
(176, 41, 58, 41, 5, '2025-12-27 07:59:18', '2025-12-27 07:59:18'),
(177, 42, 60, 39, 5, '2025-12-27 08:00:03', '2025-12-27 08:00:03'),
(178, 42, 60, 40, 5, '2025-12-27 08:00:03', '2025-12-27 08:00:03'),
(179, 42, 60, 41, 5, '2025-12-27 08:00:03', '2025-12-27 08:00:03'),
(180, 46, 57, 39, 5, '2025-12-27 08:00:42', '2025-12-27 08:00:42'),
(181, 46, 59, 39, 5, '2025-12-27 08:00:42', '2025-12-27 08:00:42'),
(182, 46, 59, 40, 5, '2025-12-27 08:00:42', '2025-12-27 08:00:42'),
(183, 46, 59, 41, 5, '2025-12-27 08:00:42', '2025-12-27 08:00:42'),
(184, 45, 50, 39, 5, '2025-12-27 08:05:11', '2025-12-27 08:05:11'),
(185, 31, 48, 39, 5, '2025-12-27 08:35:10', '2025-12-27 08:35:10'),
(187, 19, 53, 39, 5, '2025-12-30 08:07:18', '2025-12-30 08:07:18'),
(188, 21, 41, 39, 5, '2025-12-30 08:07:45', '2025-12-30 08:07:45'),
(189, 21, 41, 40, 5, '2025-12-30 08:07:45', '2025-12-30 08:07:45'),
(190, 21, 41, 41, 5, '2025-12-30 08:07:45', '2025-12-30 08:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_break` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `unit_id`, `name`, `start_time`, `end_time`, `is_break`, `created_at`, `updated_at`) VALUES
(7, 2, 'jam1', '13:00:00', '17:00:00', 0, '2025-12-19 08:09:39', '2025-12-19 08:09:39'),
(9, 4, 'UPACARA', '06:45:00', '08:00:00', 1, '2025-12-27 06:56:05', '2025-12-27 06:56:25'),
(10, 4, 'SHOLAT DHUHA', '06:45:00', '07:30:00', 1, '2025-12-27 06:57:04', '2025-12-27 06:57:04'),
(11, 4, 'ISTIRAHAT 1', '10:20:00', '10:40:00', 1, '2025-12-27 06:58:07', '2025-12-27 06:58:07'),
(12, 4, 'ISOMA', '11:50:00', '13:00:00', 1, '2025-12-27 06:59:13', '2025-12-27 06:59:13'),
(13, 4, 'SHOLAT ASHAR', '15:15:00', '16:00:00', 1, '2025-12-27 08:09:06', '2025-12-27 08:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `payment_type_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `month_paid` int DEFAULT NULL,
  `year_paid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `is_void` tinyint(1) DEFAULT '0',
  `void_reason` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `payment_type_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `month_paid` int DEFAULT NULL,
  `year_paid` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_bill_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `black_book_points` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `black_book_points`, `created_at`, `updated_at`) VALUES
(1, 'SD NURUL ILMI', 10, '2025-12-15 03:28:53', '2025-12-15 03:28:53'),
(2, 'TK NURUL ILMI', 10, '2025-12-15 03:29:04', '2025-12-15 03:29:04'),
(3, 'SMP NURUL ILMI', 10, '2025-12-15 03:29:15', '2025-12-15 03:29:15'),
(4, 'SMA NURUL ILMI', 10, '2025-12-15 03:29:23', '2025-12-15 03:29:23'),
(5, 'SMK NURUL ILMI', 10, '2025-12-15 03:29:37', '2025-12-15 03:29:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('aktif','non-aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `security_pin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `birth_place`, `birth_date`, `gender`, `address`, `phone`, `nip`, `photo`, `email_verified_at`, `password`, `plain_password`, `role`, `status`, `remember_token`, `created_at`, `updated_at`, `unit_id`, `security_pin`) VALUES
(1, 'ADMINISTRATOR', 'riandi111', 'admin@admin.com', NULL, NULL, NULL, NULL, NULL, NULL, '1766987686.jpg', '2025-12-15 01:22:12', '$2y$12$LEcPAc8oTlZYpFiduXyaAeOH78/ArZ/EmRh5wcp50DWBI8c.JpQnm', NULL, 'administrator', 'aktif', 'tMcPLVD1ic9NIqaAaCTMGUhhlx6toBkQAAa1Tng2QIgtrCmc1o4ZM7fj9lF6', '2025-12-15 01:22:12', '2025-12-29 05:54:46', NULL, '$2y$12$BGsiKiFhTrkP5OqQKnzAEuV2KbezxCoDw4ujez8V3HCFmLtYSxg6u'),
(2, 'Hj. Aryanti Feriyenci,ST.,M.Pd', 'direktur1980', 'direktur@admin.com', NULL, NULL, NULL, NULL, NULL, NULL, '1767270159.jpg', '2025-12-15 01:22:12', '$2y$12$6FewqEet9iHmN2KlAxFJgeJPy6u8evWRyrFqLfCRZ0TLgsMwCdy1S', NULL, 'direktur', 'aktif', 'jcCveUP0JiDsabLHE7h9mOG9xxYgAXoa7eW4XsyzzM5qCbuNjAiqsW0yvJEo', '2025-12-15 01:22:12', '2026-01-01 12:57:41', NULL, '$2y$12$xya/Za5HAt4ISeZlr8gGxOd5GnLPfX4/80F8JIqQ5mkAV6tYMAlsS'),
(3, 'Kepala sekolah', NULL, 'kepalasekolah@admin.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-15 01:22:13', '$2y$12$ibBSklP20AMKIXpuqQlqJOrAx.bVRLdrdCf6j2bocwvq/Gc5DAVS2', NULL, 'kepala sekolah', 'aktif', 'gtP07s6dRd', '2025-12-15 01:22:13', '2025-12-15 01:22:13', NULL, NULL),
(4, 'Wakil kepala sekolah', NULL, 'wakilkepalasekolah@admin.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-15 01:22:13', '$2y$12$hWBqE1CEVwrajpZFTK2nsuRB7a/JzdCF.MbcSM1P0j7dwOCdr524u', NULL, 'wakil kepala sekolah', 'aktif', 'XcbD3NxJDb', '2025-12-15 01:22:13', '2025-12-15 01:22:13', NULL, NULL),
(7, 'ADMIN1', NULL, 'admin1@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$apI3RvnHxdex/liJE2f/rereVIdXCUxLLuqkVShvdaJd9TuVcYsNq', NULL, 'administrator', 'aktif', NULL, '2025-12-15 01:54:21', '2025-12-15 01:54:21', NULL, NULL),
(13, 'MADING SD', 'madingsd', 'madingsd@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$32EZoSBbngadGiMWEKwHzOYg0aBV4Ma3daTGKx/sussQNbIov8spm', NULL, 'mading', 'aktif', NULL, '2025-12-17 10:00:25', '2025-12-17 10:00:25', 1, NULL),
(14, 'MADING SMP', 'madingsmp', 'madingsmp@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$6QzeZKvcT4LWkwrV9bGI/uOjPCdr1H2YWvRn3sp4Q5CVhKZz5dt8i', NULL, 'mading', 'aktif', NULL, '2025-12-17 10:37:22', '2025-12-17 10:37:22', 3, NULL),
(15, 'MADING TK', 'madingtk', 'madingtk@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$ddpVQExPYkjoZ0bJ8FQ8leBPR0PYuCxCbWcxA8uG3GNaFefUsiumO', NULL, 'mading', 'aktif', NULL, '2025-12-19 08:18:39', '2025-12-19 08:18:39', 2, NULL),
(19, 'Aris Hidayat, M.Pd', 'aris123', 'aris@gmail.com', NULL, NULL, NULL, NULL, NULL, '225522', '1767082038.jpg', NULL, '$2y$12$eR1IZ4KFU5bLUxMCY2Xt6uqSMCxLS7YlYqL595LyHZSzfL8MKtQc2', 'andiapri123', 'guru', 'aktif', NULL, '2025-12-21 13:03:43', '2025-12-30 08:07:18', 4, NULL),
(20, 'AYU TRI UTAMI, S.Pd', 'guru441', 'guru001@gmail.com', NULL, NULL, NULL, NULL, NULL, '1607104303920002', NULL, NULL, '$2y$12$tdzg/mPcx/XGV6pIjktSueGeS9JNwRdXxoX8hvnP0Xadd.tTvSaHC', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:20', '2025-12-27 04:15:42', 4, NULL),
(21, 'CANDRA FITRIANSYAH,S.Pd', 'guru442', 'guru002@gmail.com', NULL, NULL, 'L', NULL, NULL, '1671070405890013', '1767082065.avif', NULL, '$2y$12$TLJdeCEW1gB8W4AuDwlc3u5Mf6vx80kjo1Mc0JlWoj7GJZtLWMVHi', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:20', '2025-12-30 08:12:59', 4, NULL),
(22, 'CATUR LESMONO,S.Pd', 'guru443', 'guru003@gmail.com', NULL, NULL, NULL, NULL, NULL, '1671040711920005', NULL, NULL, '$2y$12$A.vlOE5TKF7VGLfFhlz0d.aBUgfbdDsBonn/TFmk3CNRkBTwzFnhG', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:20', '2025-12-27 04:16:02', 4, NULL),
(23, 'DESI RATNA SARI,S.Pd', 'guru444', 'guru004@gmail.com', NULL, NULL, NULL, NULL, NULL, '1671155912920001', NULL, NULL, '$2y$12$j01r0BYkokSonOlN72PIgOlBsOTTWJyC7TOv.4kTNesYtybd8/o5i', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:20', '2025-12-27 04:16:11', 4, NULL),
(24, 'GENI PUSPITA,S.Pd', 'guru445', 'guru005@gmail.com', NULL, NULL, NULL, NULL, NULL, '1671106604830008', NULL, NULL, '$2y$12$9rNWFPCLXikqXZ72FD83s.Xthg6jgxP9WX6fd3/De6rws7DFz2HeW', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:21', '2025-12-27 04:16:20', 4, NULL),
(25, 'Hirma Kirana', 'guru446', 'guru006@gmail.com', NULL, NULL, NULL, NULL, '', '1607105908990006', NULL, NULL, '$2y$12$eqIFwtlFFLEhys1W9mHccu5we0zVlYMJpeomBIYU0IFU8R9jFPaL6', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:21', '2025-12-21 13:28:21', 4, NULL),
(26, 'IIS DAHLIA', 'guru447', 'guru007@gmail.com', NULL, NULL, NULL, NULL, NULL, '1611015001970001', NULL, NULL, '$2y$12$br/9TwVLEX3Dgwu908BY0.ejHWOqe00EdoRwZwr0sPsBUmJYi8yMy', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:21', '2025-12-23 05:07:25', 4, NULL),
(27, 'LIA LAILI ROSADAH,S.Pd', 'guru448', 'guru008@gmail.com', NULL, NULL, NULL, NULL, NULL, '1607037103900002', NULL, NULL, '$2y$12$GaisPfQRsmcAMr35jp3GS.hC83yZQcxOSaam2wLIT2wKm4wQ.5wkq', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:21', '2025-12-27 04:16:34', 4, NULL),
(28, 'M. ANGGA SUPRATMAN', 'guru449', 'guru009@gmail.com', NULL, NULL, NULL, NULL, NULL, '1602111010910004', NULL, NULL, '$2y$12$HXulAyX2RnYYdJdDJJL6WOlikyXxilH9O3h6k2.vj/fMPGO1D2g5C', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:22', '2025-12-21 13:44:25', 4, NULL),
(29, 'MUHAMMAD SALMAN ALFARISI', 'guru450', 'guru0010@gmail.com', NULL, NULL, NULL, NULL, NULL, '1671130309790007', NULL, NULL, '$2y$12$E6f59agZGjH/DdCuhEPnvu1Xc/gYoXBIpEx6x.5vfnMJGL4Ga/hSC', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:22', '2025-12-27 07:56:01', 4, NULL),
(30, 'MUSLIHIN,SS', 'guru451', 'guru0011@gmail.com', NULL, NULL, NULL, NULL, NULL, '1603176007870001', NULL, NULL, '$2y$12$iZe7mEkLtscmVanhlcyAz.dxDIW1yHxQCxu6JeHtF0tHStHFL5gNa', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:22', '2025-12-27 05:27:03', 4, NULL),
(31, 'RIMA WARDIANA,S.Pd', 'guru452', 'guru0012@gmail.com', NULL, NULL, NULL, NULL, NULL, '1607105508900006', NULL, NULL, '$2y$12$bEx2KUJ2HV7uVEC/uLQf0eWp6KHaGxpLkkYRmA3eSZbbPKDIbQyl2', 'guru123', 'guru', 'aktif', NULL, '2025-12-21 13:28:23', '2025-12-27 05:26:29', 4, NULL),
(32, 'MADING SMA', 'madingsma', 'madingsma@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$VsoMjfIeuF/xnJqsZsL7leeHPX8Q2YPV0.bHUtypy1ugllKpGUNfm', NULL, 'mading', 'aktif', NULL, '2025-12-23 05:28:52', '2025-12-23 05:28:52', 4, NULL),
(33, 'Yohana Febriningsih, S.Pd.', 'guru5551', 'guru51@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555551', NULL, NULL, '$2y$12$0v4KW.Qp8TViB84ZGx1SP.DjV2ZYY8oGA.6.GsptRdZ7JD/EFoJo6', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:16:59', '2025-12-27 05:18:15', 5, NULL),
(34, 'Herdi Yupika,M.Pd.', 'guru5552', 'guru52@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555552', NULL, NULL, '$2y$12$6iJEmvpXymR8wF1j4C.9yeMqkBeY.4cTkofYJ9/rBaFD/r0sZHU/S', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:16:59', '2025-12-27 05:18:30', 5, NULL),
(35, 'Elita Oktarini, S.E', 'guru5553', 'guru53@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555553', NULL, NULL, '$2y$12$pkGnJXyVMB00/6ZGipt9..DDLqW2I6HWctxAuWfw1sYYfFHFcuKsO', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:16:59', '2025-12-27 05:18:42', 5, NULL),
(36, 'Mr. Toyo Whydodo, M.Pd', 'guru5554', 'guru54@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555554', NULL, NULL, '$2y$12$O2gsJiYa8DIvpFhshAcYHOWd6y0FZeLq67CuG3uCUhcq1C8cH9NEq', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:16:59', '2025-12-27 07:49:11', 5, NULL),
(37, 'Rahmad Apriandi, S.T.', 'guru5555', 'guru55@gmail.com', NULL, NULL, NULL, NULL, NULL, '1901011104960006', NULL, NULL, '$2y$12$blkplEnAqFjrowlr/6vtX.foOmjsJ7p7CwXaY6ExA1fbTGjgOFdB6', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:00', '2025-12-27 05:19:07', 5, NULL),
(38, 'Wike Lestari, S.E', 'guru5556', 'guru56@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555555', NULL, NULL, '$2y$12$ZvuEnFfocjU..RLToi7reOk90X1mBeHS40tAFZLbmbav3IRBIwpmu', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:00', '2025-12-27 05:28:52', 5, NULL),
(39, 'Jayanti Kharisma, S.Pd.', 'guru5557', 'guru57@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555556', NULL, NULL, '$2y$12$K2htdwbiH8jE2MNPn2i08O9DvCDLL3yI4SAS5zsiTYSPHUqNkrs6K', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:00', '2025-12-27 05:21:04', 5, NULL),
(40, 'Piqolbi Nuron', 'guru5558', 'guru58@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555557', NULL, NULL, '$2y$12$ckOt.IcKipVky6ep2/VrS.nq5BNCe.U.dgXSDREGsZUcE/CENxHNe', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:00', '2025-12-27 05:21:32', 5, NULL),
(41, 'Aldi Nurfit Janazi', 'guru5559', 'guru59@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555558', NULL, NULL, '$2y$12$merr5Otolj/pH9UWi3BcIu5NQnCUZ.SV509byr2wSYWZy5Zf.0imC', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:01', '2025-12-27 05:21:51', 5, NULL),
(42, 'Suhari', 'guru5560', 'guru60@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555559', NULL, NULL, '$2y$12$18kMAomVp6NWrK/NCwkqve457c33McQUjsBhN8I9RiiRJ.wMgBnBy', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:01', '2025-12-27 05:22:07', 5, NULL),
(43, 'Ika Kurnia, S.Pd', 'guru5561', 'guru61@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555560', NULL, NULL, '$2y$12$b30GKsUSmCM2hEtzbwmriu2JmQv78QxaVZ0lCATRoNz854hPH6my.', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:01', '2025-12-27 05:22:27', 5, NULL),
(44, 'Fahmi Maulana, S.Pd.', 'guru5562', 'guru62@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555561', NULL, NULL, '$2y$12$HeCd6u2y8au.MG2IUWxKCOLS5n72i6RXjEhelOVJVmUd2gQYr/hu.', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:01', '2025-12-27 05:22:36', 5, NULL),
(45, 'Raud Latus,S.Pd', 'guru5563', 'guru63@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555562', NULL, NULL, '$2y$12$vktLzgrFrQxMYYWre.HxB.ndourwY7NrD16VJh9e882xPpd.If0V.', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:02', '2025-12-27 05:28:41', 5, NULL),
(46, 'Alen Erick', 'guru5564', 'guru64@gmail.com', NULL, NULL, NULL, NULL, NULL, '5555555555555563', NULL, NULL, '$2y$12$iXSRpOZs7pWzky.z0G5CrOA4X.il102hVm5eReSizHqApVarqB4H6', 'guru123', 'guru', 'aktif', NULL, '2025-12-27 05:17:02', '2025-12-27 05:22:55', 5, NULL),
(47, 'MADING SMK', 'madingsmk', 'madingsmk@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$hy.gYnpD1BzYWPY/Sh4Fh.7/R3o5ERidkAmImpMaAA.FYeXp79Bsm', NULL, 'mading', 'aktif', NULL, '2025-12-30 06:33:32', '2025-12-30 06:33:32', 5, NULL),
(49, 'desta,A.Md', 'desta123', 'Desta@gmail.com', NULL, NULL, NULL, NULL, NULL, '888222222222222', NULL, NULL, '$2y$12$sWwCROHpL6hD7RcurFo/k.u2fkel3ediPuni7Yn8bjKKBOt8s7wsO', NULL, 'admin_keuangan', 'aktif', NULL, '2025-12-31 10:33:58', '2025-12-31 18:05:53', NULL, '$2y$12$p/NUoKgF94UHlo6HExT2tOxeMaD0/Xt9pslNCqwRXllh8x1bsdIvy'),
(50, 'Diyan', 'diyan123', 'diyan@gmail.com', NULL, NULL, NULL, NULL, NULL, '123456789987', NULL, NULL, '$2y$12$/XzS0lpTq8g7/GkXDGCpUOn/B5ZyPSCMwj/R8xflmWy.4RfvGHhhW', NULL, 'kepala_keuangan', 'aktif', NULL, '2026-01-01 05:05:59', '2026-01-01 05:05:59', NULL, '$2y$12$Zvucog7TnJJSk/gTsV0Xu.yle7ee3Yit6BBFOgPMQo.NmDMKa6vku'),
(51, 'Irra Julia Saputri, S.Pd.', 'guru30001', 'guru30001111@gmail.com', NULL, NULL, NULL, NULL, NULL, '30001111', NULL, NULL, '$2y$12$0Ui9DTEKyJIymbC28LOiUeGkSav3YTdBjRBbmFx4AwziLjcyefVU2', 'guru123', 'guru', 'aktif', NULL, '2026-01-02 01:40:32', '2026-01-02 01:41:41', 3, NULL),
(52, 'Rizka Indriati, S.Sos.', 'guru30002', 'guru30001112@gmail.com', NULL, NULL, NULL, NULL, NULL, '30001112', NULL, NULL, '$2y$12$0j4INe9EovcT8TufrxmQjOWFzvwDnZuk6NM4Yp/2CjVTBq5ch2qFm', 'guru124', 'guru', 'aktif', NULL, '2026-01-02 01:40:33', '2026-01-02 01:41:58', 3, NULL),
(53, 'Mahfira, S.Pd.', 'guru30003', 'guru30001113@gmail.com', NULL, NULL, NULL, NULL, NULL, '30001113', NULL, NULL, '$2y$12$3Tj6/i1MS1xH9Mb6JNnXqetEImqqwyW9gwwU39YokITqLQdEq1Kg.', 'guru125', 'guru', 'aktif', NULL, '2026-01-02 01:40:33', '2026-01-02 01:43:26', 3, NULL),
(54, 'Yuni Nurtias Hapsari, S.Pd.', 'guru30004', 'guru30001114@gmail.com', NULL, NULL, NULL, NULL, NULL, '30001114', NULL, NULL, '$2y$12$TbiqvW2ONk9hgPBJI/hqo.bvX/0gdbjvZbgYKKT/rulHju3LQ3l5O', 'guru126', 'guru', 'aktif', NULL, '2026-01-02 01:40:33', '2026-01-02 01:42:19', 3, NULL),
(55, 'Anggun Harminia, S.Pd.', 'guru30005', 'guru30001115@gmail.com', NULL, NULL, NULL, NULL, '', '30001115', NULL, NULL, '$2y$12$HAN/iFGBjNHyAaV18GFvBuXfN7b8gMuLICIWtUgOeTmtP9Q5LDpW6', 'guru127', 'guru', 'aktif', NULL, '2026-01-02 01:40:33', '2026-01-02 01:40:33', 3, NULL),
(56, 'Leni Pratiwi Nur Aisyah, S.Sos.I.', 'guru30006', 'guru30001116@gmail.com', NULL, NULL, NULL, NULL, '', '30001116', NULL, NULL, '$2y$12$YBXfSnFukRoaFp2qbCXN0uTqW3vcAovYStkqfBmT/.zMI6bxU77Qi', 'guru128', 'guru', 'aktif', NULL, '2026-01-02 01:40:34', '2026-01-02 01:40:34', 3, NULL),
(57, 'Triwidya Ningsih, M.Pd.', 'guru30007', 'guru30001117@gmail.com', NULL, NULL, NULL, NULL, '', '30001117', NULL, NULL, '$2y$12$8lM6ZGjJke6yDExayvMIyOMOcji4pwuffytrVtBXzEiocm7tGLI8.', 'guru129', 'guru', 'aktif', NULL, '2026-01-02 01:40:34', '2026-01-02 01:40:34', 3, NULL),
(58, 'Fika Gustina, S.Pd.', 'guru30008', 'guru30001118@gmail.com', NULL, NULL, NULL, NULL, '', '30001118', NULL, NULL, '$2y$12$xrvv.MAFQBF3yx9Pb.4q0eSECIfISMaz/NVUudB3gYohUTWDkMfsu', 'guru130', 'guru', 'aktif', NULL, '2026-01-02 01:40:34', '2026-01-02 01:40:34', 3, NULL),
(59, 'Diah Putri Ramadani, S.Si.', 'guru30009', 'guru30001119@gmail.com', NULL, NULL, NULL, NULL, '', '30001119', NULL, NULL, '$2y$12$8/OYC03A/zJ85cGqUHgC2.O2BHygGZVW/SLCKNU02/gByQ9hbxeYi', 'guru131', 'guru', 'aktif', NULL, '2026-01-02 01:40:34', '2026-01-02 01:40:34', 3, NULL),
(60, 'Dzakia Fifi Mahardini, S.Pd.', 'guru30010', 'guru30001120@gmail.com', NULL, NULL, NULL, NULL, '', '30001120', NULL, NULL, '$2y$12$/yoQzJ8NFwtLNKANszwdJu5pFyvHi2GRKT4WI6vzSAuw4NbubMXJS', 'guru132', 'guru', 'aktif', NULL, '2026-01-02 01:40:35', '2026-01-02 01:40:35', 3, NULL),
(61, 'Nur Firmansyah', 'guru30011', 'guru30001121@gmail.com', NULL, NULL, NULL, NULL, '', '30001121', NULL, NULL, '$2y$12$P6H5lXt4kFnVB8b2ppX8XuTYROsrgaxd1yvZfZ0IjlHyvw.7Hb/2.', 'guru133', 'guru', 'aktif', NULL, '2026-01-02 01:40:35', '2026-01-02 01:40:35', 3, NULL),
(62, 'Dyah Ayu Pertiwi, S.Pd.', 'guru30012', 'guru30001122@gmail.com', NULL, NULL, NULL, NULL, '', '30001122', NULL, NULL, '$2y$12$M0du48DlPjNR9QdZ9UtMyedSnDpiTjCjX.y8q9F3dJad1g1Dln6Ea', 'guru134', 'guru', 'aktif', NULL, '2026-01-02 01:40:35', '2026-01-02 01:40:35', 3, NULL),
(63, 'Mira Desantika, S.Kom.', 'guru30013', 'guru30001123@gmail.com', NULL, NULL, NULL, NULL, '', '30001123', NULL, NULL, '$2y$12$vc0HK6cs18DDaRvufWp4KeCaxPEKRnvURI9rxtqeB1pyZWGDrfcRK', 'guru135', 'guru', 'aktif', NULL, '2026-01-02 01:40:35', '2026-01-02 01:40:35', 3, NULL),
(64, 'Lisa Ramadini, S.Ag.', 'guru30014', 'guru30001124@gmail.com', NULL, NULL, NULL, NULL, '', '30001124', NULL, NULL, '$2y$12$1h7plBMxrt5yXq5QGf80HOE0vda7rZhG9qJLYzTMATVEklDwke0Bi', 'guru136', 'guru', 'aktif', NULL, '2026-01-02 01:40:35', '2026-01-02 01:40:35', 3, NULL),
(65, 'Makomam Mahmuda, S.Sos.', 'guru30015', 'guru30001125@gmail.com', NULL, NULL, NULL, NULL, '', '30001125', NULL, NULL, '$2y$12$psyVsTH0.i3JyajcN78qRuJyvgKVHqpnxLEGqF.Erd2iX7O9kGxQG', 'guru137', 'guru', 'aktif', NULL, '2026-01-02 01:40:36', '2026-01-02 01:40:36', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_jabatan_units`
--

CREATE TABLE `user_jabatan_units` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jabatan_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_jabatan_units`
--

INSERT INTO `user_jabatan_units` (`id`, `user_id`, `jabatan_id`, `unit_id`, `academic_year_id`, `created_at`, `updated_at`) VALUES
(115, 28, 66, 4, 5, '2025-12-26 14:18:44', '2025-12-26 14:18:44'),
(130, 22, 67, 4, 5, '2025-12-27 04:16:02', '2025-12-27 04:16:02'),
(131, 22, 68, 5, 5, '2025-12-27 04:16:02', '2025-12-27 04:16:02'),
(161, 27, 64, 4, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(162, 27, 125, 5, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(163, 27, 66, 4, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(164, 27, 65, 4, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(165, 27, 72, 5, 5, '2025-12-27 07:05:20', '2025-12-27 07:05:20'),
(167, 24, 66, 4, 5, '2025-12-27 07:17:21', '2025-12-27 07:17:21'),
(168, 23, 69, 4, 5, '2025-12-27 07:19:49', '2025-12-27 07:19:49'),
(169, 23, 70, 5, 5, '2025-12-27 07:19:49', '2025-12-27 07:19:49'),
(170, 23, 128, 5, 5, '2025-12-27 07:19:49', '2025-12-27 07:19:49'),
(174, 26, 65, 4, 5, '2025-12-27 07:43:35', '2025-12-27 07:43:35'),
(175, 26, 66, 4, 5, '2025-12-27 07:43:35', '2025-12-27 07:43:35'),
(176, 20, 72, 5, 5, '2025-12-27 07:44:37', '2025-12-27 07:44:37'),
(177, 20, 65, 4, 5, '2025-12-27 07:44:37', '2025-12-27 07:44:37'),
(178, 33, 66, 4, 5, '2025-12-27 07:46:15', '2025-12-27 07:46:15'),
(179, 33, 72, 5, 5, '2025-12-27 07:46:15', '2025-12-27 07:46:15'),
(180, 34, 66, 4, 5, '2025-12-27 07:47:07', '2025-12-27 07:47:07'),
(181, 35, 66, 4, 5, '2025-12-27 07:49:03', '2025-12-27 07:49:03'),
(182, 36, 66, 4, 5, '2025-12-27 07:50:23', '2025-12-27 07:50:23'),
(183, 38, 128, 5, 5, '2025-12-27 07:51:14', '2025-12-27 07:51:14'),
(184, 38, 66, 4, 5, '2025-12-27 07:51:14', '2025-12-27 07:51:14'),
(185, 30, 66, 4, 5, '2025-12-27 07:53:11', '2025-12-27 07:53:11'),
(186, 40, 66, 4, 5, '2025-12-27 07:53:59', '2025-12-27 07:53:59'),
(188, 43, 66, 4, 5, '2025-12-27 07:55:13', '2025-12-27 07:55:13'),
(190, 29, 66, 4, 5, '2025-12-27 07:57:28', '2025-12-27 07:57:28'),
(191, 41, 66, 4, 5, '2025-12-27 07:59:18', '2025-12-27 07:59:18'),
(192, 42, 66, 4, 5, '2025-12-27 08:00:03', '2025-12-27 08:00:03'),
(193, 46, 66, 4, 5, '2025-12-27 08:00:42', '2025-12-27 08:00:42'),
(194, 45, 66, 4, 5, '2025-12-27 08:05:11', '2025-12-27 08:05:11'),
(195, 31, 71, 5, 5, '2025-12-27 08:35:10', '2025-12-27 08:35:10'),
(198, 19, 63, 4, 5, '2025-12-30 08:07:18', '2025-12-30 08:07:18'),
(199, 19, 66, 4, 5, '2025-12-30 08:07:18', '2025-12-30 08:07:18'),
(200, 21, 66, 4, 5, '2025-12-30 08:07:45', '2025-12-30 08:07:45'),
(204, 49, 60, 1, NULL, '2025-12-31 18:05:53', '2025-12-31 18:05:53'),
(205, 50, 60, NULL, NULL, '2026-01-01 05:05:59', '2026-01-01 05:05:59'),
(209, 54, 100, 3, 5, '2026-01-02 01:42:19', '2026-01-02 01:42:19'),
(210, 54, 104, 3, 5, '2026-01-02 01:42:19', '2026-01-02 01:42:19'),
(212, 51, 99, 3, 5, '2026-01-02 01:48:14', '2026-01-02 01:48:14'),
(213, 52, 102, 3, 5, '2026-01-02 01:48:23', '2026-01-02 01:48:23'),
(214, 52, 104, 3, 5, '2026-01-02 01:48:23', '2026-01-02 01:48:23'),
(215, 53, 101, 3, 5, '2026-01-02 01:48:29', '2026-01-02 01:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `user_siswa`
--

CREATE TABLE `user_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `login_attempts` tinyint NOT NULL DEFAULT '0',
  `locked_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_siswa`
--

INSERT INTO `user_siswa` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `plain_password`, `status`, `photo`, `remember_token`, `created_at`, `updated_at`, `login_attempts`, `locked_at`) VALUES
(35, 'Alfish Janky Candish', 'siswa4001', 'siswa4001@student.nurulilmi.id', NULL, '$2y$12$8bqe0okBOIEaADxncjCX0OhrF6QHGxjsFeBWulV/UWrdv1rZSJ56C', 'siswa123', 'aktif', '1766935076_2a76fd75-c413-4589-b2c2-b13a8fa973a2.jpg', NULL, '2025-12-21 13:00:38', '2025-12-28 15:17:56', 0, NULL),
(36, 'AMALLIA PUTRI PRATAMA', 'siswa4002', 'siswa4002@student.nurulilmi.id', NULL, '$2y$12$Famn8VHiO/inJmwwnR3eo.e2N/mB64H6IVSFb/70RqpEcY7fQPwb6', 'siswa123', 'aktif', '1766934594_1765860454.jpg', NULL, '2025-12-21 13:00:39', '2026-01-01 05:12:00', 0, NULL),
(37, 'ARIESKA PRIMA SARI', 'siswa4003', 'siswa4003@student.nurulilmi.id', NULL, '$2y$12$4L8wJOd3cpL.hjSrjwWHVOkc94Obew9aKz0SVOmsm39Z0bFynuw7u', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:39', '2025-12-21 13:00:39', 0, NULL),
(38, 'DINO RIANSYAH', 'siswa4004', 'siswa4004@student.nurulilmi.id', NULL, '$2y$12$aRSIVa75rMy9T94h1654j.tJjEr2TN0OI/hjWe.OkN7/PZAXTYxQ2', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:39', '2025-12-21 13:00:39', 0, NULL),
(39, 'DIVA THREE SUSANTO', 'siswa4005', 'siswa4005@student.nurulilmi.id', NULL, '$2y$12$x1AD.l22OWB.On2vljpBy.WCAAnFNx1x4YA807oVzsEQKlec8y8UK', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:39', '2025-12-21 13:00:39', 0, NULL),
(40, 'Fakhira Azri Shakila', 'siswa4006', 'siswa4006@student.nurulilmi.id', NULL, '$2y$12$9adMHlnUrNDxauHrzMN2XOPOG6rt46izXm/Dm8jtBpBQBEzA8pxVq', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:40', '2025-12-21 13:00:40', 0, NULL),
(41, 'Faris Onedri Wijaya', 'siswa4007', 'siswa4007@student.nurulilmi.id', NULL, '$2y$12$0KOkoXAAmoWrv0gmXgaG5..EiQi2EJ9l7T3iOTC.WqCEJ42qOxq8.', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:40', '2025-12-21 13:00:40', 0, NULL),
(42, 'MUHAMMAD ATTAYA ABYAN NAUFAL', 'siswa4008', 'siswa4008@student.nurulilmi.id', NULL, '$2y$12$4Hn0DxgErtIQEuVM4X06oeVVGp.ySdYLoB9OumTGiqoO/9DVfF9Ry', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:40', '2025-12-21 13:00:40', 0, NULL),
(43, 'Muhammad Dzaky Araffah', 'siswa4009', 'siswa4009@student.nurulilmi.id', NULL, '$2y$12$faTRhTY9IqosL3mNEeKjwegRCCK1QJe98qPZGKUGVIFU8xrEp.fya', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:40', '2025-12-21 13:00:40', 0, NULL),
(44, 'MUHAMMAD NAUFAL', 'siswa4010', 'siswa4010@student.nurulilmi.id', NULL, '$2y$12$BwSnWVL2hiCYAplZsDKqyeap2mf94JntjJ2jzpfJWU9EchJZ9TOc.', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:40', '2025-12-21 13:00:40', 0, NULL),
(45, 'MUHAMMAD RAKHA AL FAJRI', 'siswa4011', 'siswa4011@student.nurulilmi.id', NULL, '$2y$12$JPaDWiElEmGKO/fUOZrctuNWsmicvMfCxNba70VWZNiemUcUbO1jC', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:41', '2025-12-21 13:00:41', 0, NULL),
(46, 'MUHAMMAD RASYIID AL HAFIIZH', 'siswa4012', 'siswa4012@student.nurulilmi.id', NULL, '$2y$12$fuBhfRfNJ9ELhnM7nE5VueX40rbuyoNrU/IsU4RDU4wDN17ElRK0y', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:41', '2025-12-21 13:00:41', 0, NULL),
(47, 'Najid Atthalla Mirza', 'siswa4013', 'siswa4013@student.nurulilmi.id', NULL, '$2y$12$jbPqUnnARFOKq0S80.peYOonc2Z7M1wwRYNio9qEp5FILiCcUc5ru', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:41', '2025-12-21 13:00:41', 0, NULL),
(48, 'NUR AIN AIDIL ADHA', 'siswa4014', 'siswa4014@student.nurulilmi.id', NULL, '$2y$12$TpgcCcYiM/Pd0PVIyNABE.TQwKxddpAqNUukUoAis2evE3yOUZ51y', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:41', '2025-12-21 13:00:41', 0, NULL),
(49, 'REGINA PUTRI', 'siswa4015', 'siswa4015@student.nurulilmi.id', NULL, '$2y$12$C/RP2mcQo/FQDuAtrRwoL.b2I.JejoZ5RBTUjEPUtLfrQzs4dh0.G', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:42', '2025-12-21 13:00:42', 0, NULL),
(50, 'ADINDA CHAESYA PUTRI', 'siswa4016', 'siswa4016@student.nurulilmi.id', NULL, '$2y$12$kAFULveA8KMtVMcUWzL1rOgfjvAwCGt8pzuE4dRuscZhevdU3R7FW', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:42', '2025-12-21 13:00:42', 0, NULL),
(51, 'Athallah Rafialfalih Syear', 'siswa4017', 'siswa4017@student.nurulilmi.id', NULL, '$2y$12$CM0vZ/wcsq4HU7IyqBh6G.Vqo0OgcpzIWlUL5kZ2eIH4N0.pWkn62', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:42', '2025-12-21 13:00:42', 0, NULL),
(52, 'CHERYL CECILLYA YOSIOCTAVIA', 'siswa4018', 'siswa4018@student.nurulilmi.id', NULL, '$2y$12$9r3cRbGa6ce4ItObhgQzo.v/1qjY4uXKAmN3W0xCwH66k9gSgq8ay', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:42', '2025-12-21 13:00:42', 0, NULL),
(53, 'Daffa Agriansyah', 'siswa4019', 'siswa4019@student.nurulilmi.id', NULL, '$2y$12$s6fZZA.DpncUi.fpez125uRSSLlk/1UaPLi.hZhDFjSfN1bkpcZIi', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:43', '2025-12-21 13:00:43', 0, NULL),
(54, 'DEA INTAN PERMATA SARI', 'siswa4020', 'siswa4020@student.nurulilmi.id', NULL, '$2y$12$.d.exiUB5hM/NGurEvXzIOXBBB8uDL..6LVkAf3mhDHrN9hv38TMC', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:43', '2025-12-21 13:00:43', 0, NULL),
(55, 'DERLI AUSDISTIRA', 'siswa4021', 'siswa4021@student.nurulilmi.id', NULL, '$2y$12$2qsbujXsYyxDQ9l6ZQj7E.OQTKpS8pFRW8giuiNTC8TRbd9PL5LY2', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:43', '2025-12-21 13:00:43', 0, NULL),
(56, 'FIDELA NAIFAH DWIYANTI', 'siswa4022', 'siswa4022@student.nurulilmi.id', NULL, '$2y$12$BE8xSwKVUgHD8cqqrk1ND.PtAattnh.5P7dhQnAQ7GAlIpLPtuNnK', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:44', '2025-12-21 13:00:44', 0, NULL),
(57, 'Lisa Melia Oktavia', 'siswa4023', 'siswa4023@student.nurulilmi.id', NULL, '$2y$12$N3ANcxAXHjW7AsnXVYI0C.UKcm15TcuHhB66VClz6cwrYmLPsHzk.', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:44', '2025-12-21 13:00:44', 0, NULL),
(58, 'M. Faris Adila Ammar', 'siswa4024', 'siswa4024@student.nurulilmi.id', NULL, '$2y$12$4Rq6XL1zc47QOyJBQjEUDeAoZl8yaY42bIdhMCwEnlg2kjfO/Crye', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:44', '2025-12-21 13:00:44', 0, NULL),
(59, 'M. FASHAN EL-MUBAROK', 'siswa4025', 'siswa4025@student.nurulilmi.id', NULL, '$2y$12$CIyv4AmhslnGF8zQX4i0MeefdgMgwAn17NPzA4cPkbLM.XjmGJtqu', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:44', '2025-12-21 13:00:44', 0, NULL),
(60, 'M. RIDHO BONIYAGO', 'siswa4026', 'siswa4026@student.nurulilmi.id', NULL, '$2y$12$9v3y6AMT8xXLqdALlcbd6e3/r8sVt8YjXOUzIsgbnB6u5K1qHbjTq', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:45', '2025-12-21 13:00:45', 0, NULL),
(61, 'NABILAH SYAUQIYAH WIDAD', 'siswa4027', 'siswa4027@student.nurulilmi.id', NULL, '$2y$12$G.dgB6J4GP2s5LnWvnlf/eilfQbFf/KoqsJiwTsJrTxnqtIhFzTXu', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:45', '2025-12-21 13:00:45', 0, NULL),
(62, 'OASE FADHILA NUGRAHA', 'siswa4028', 'siswa4028@student.nurulilmi.id', NULL, '$2y$12$V7dRbcN8dz93DXgkTwYdmeuhc/VV5pACV.GPIaeB5pMaXpcqg1x3G', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:45', '2025-12-21 13:00:45', 0, NULL),
(63, 'RISKA AULIA', 'siswa4029', 'siswa4029@student.nurulilmi.id', NULL, '$2y$12$7udJveYriK3jEgXv9svTnO/VoRFFqlDaTLq1euOF4eyLYUQvj/AIG', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:45', '2025-12-21 13:00:45', 0, NULL),
(64, 'Siti Syifa Izza Pratiwi', 'siswa4030', 'siswa4030@student.nurulilmi.id', NULL, '$2y$12$tc8d3f6PlvEApKVOvE//peSFLKAkzNJQN3oD/5b3HvgCii77HZfeq', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:46', '2025-12-21 13:00:46', 0, NULL),
(65, 'Syarofati Dwi Athiyyah', 'siswa4031', 'siswa4031@student.nurulilmi.id', NULL, '$2y$12$LzO5yThy6et//wvW7sk2Wux8ddTfzRnqD/sUy0xJdYSassxVFNYGq', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:46', '2025-12-21 13:00:46', 0, NULL),
(66, 'SYIFA AZAHRO', 'siswa4032', 'siswa4032@student.nurulilmi.id', NULL, '$2y$12$0wh0lrhcNutljEHWlmcrFeytHB9m95DMU2ixQ5egD.iydmrU8/rL6', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:46', '2025-12-21 13:00:46', 0, NULL),
(67, 'Zidane Arya Arkadian', 'siswa4033', 'siswa4033@student.nurulilmi.id', NULL, '$2y$12$orjbSrCeMU86B33EKKgFq.LtGAuo1Tgeu5wZo87f.DN3ZBIG5wnCm', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:47', '2025-12-21 13:00:47', 0, NULL),
(68, 'AHMAD RIFQI', 'siswa4034', 'siswa4034@student.nurulilmi.id', NULL, '$2y$12$sMizOus31pab4STe1SNmUOt0sVdsFCBO8WeTiVuKf4etaZNYC3kP.', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:47', '2025-12-21 13:00:47', 0, NULL),
(69, 'Aisyah Azzahra', 'siswa4035', 'siswa4035@student.nurulilmi.id', NULL, '$2y$12$/h0D.xRQM0ZD9YK5jNrcCe3wjm2TN26b1Ymoku.Gc3V7Md7NEESHK', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:47', '2025-12-21 13:00:47', 0, NULL),
(70, 'DIMAS YOGA SAPUTRA', 'siswa4036', 'siswa4036@student.nurulilmi.id', NULL, '$2y$12$L55Wuk/FqGn2f1kxKr4EEe8Zd07QaV61MUnpdFyllXgKZmGLT8Cpy', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:47', '2025-12-21 13:00:47', 0, NULL),
(71, 'DITA LASTARI', 'siswa4037', 'siswa4037@student.nurulilmi.id', NULL, '$2y$12$WhCnvE6NS2wx2YbaQWFP7erAZwiYDONZqkZU62DnTAJ6oFSoHuvcO', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:48', '2025-12-21 13:00:48', 0, NULL),
(72, 'Faiz Muzakky Pramana', 'siswa4038', 'siswa4038@student.nurulilmi.id', NULL, '$2y$12$8DonFSX8qn5gNTpKFUf89uJE2.hLli5O0C/3hS9Ka4MlWuD6cfoky', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:48', '2025-12-21 13:00:48', 0, NULL),
(73, 'FAREL RAMADHANI', 'siswa4039', 'siswa4039@student.nurulilmi.id', NULL, '$2y$12$Y5ECT1ufg0MA5Z2hYSHMyOrZlfnPyGgBd4ue5Nmd1qlaep.bN/rJG', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:48', '2025-12-21 13:00:48', 0, NULL),
(74, 'FARHAN DWI SAPUTRA', 'siswa4040', 'siswa4040@student.nurulilmi.id', NULL, '$2y$12$mxcD8BoML3kwHFVLebBdlezxgQx7wMM1OF.VZSPN2zNK7L4pBzbRW', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:49', '2025-12-21 13:00:49', 0, NULL),
(75, 'ISMA RAHAYU', 'siswa4041', 'siswa4041@student.nurulilmi.id', NULL, '$2y$12$FUbHm/f5Ga4KGjNgd7xdEedi7WJiYhkVeSaAkbLu8XANpDf4KGtm6', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:49', '2025-12-21 13:00:49', 0, NULL),
(76, 'Khoirul Bariyah', 'siswa4042', 'siswa4042@student.nurulilmi.id', NULL, '$2y$12$/Kx24Mmq8MfBmP7DqAhEHePdIeAH4YETG90v6xqFLJ./Gf2ephQz.', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:49', '2025-12-26 13:14:46', 0, NULL),
(77, 'LIONEL GARCIA', 'siswa4043', 'siswa4043@student.nurulilmi.id', NULL, '$2y$12$4.9YIsIYbRnD.ylCxNee/els9JxphfwCxAuZVyfOkFHQ3dZwK9D0i', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:49', '2025-12-21 13:00:49', 0, NULL),
(78, 'M. ALFARIZI SYAH', 'siswa4044', 'siswa4044@student.nurulilmi.id', NULL, '$2y$12$KG9PftbsJGoTHwmL9C20Ze.BksQdqkVoWlsdtRQOzLwbGwzoNvH1a', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:50', '2025-12-21 13:00:50', 0, NULL),
(79, 'MEITA PUTRI PRATIWI', 'siswa4045', 'siswa4045@student.nurulilmi.id', NULL, '$2y$12$FKW5KkbB0wqOFyOERCxR1.WJxJJ23mJQRk8zt0RqsQTt4XH8vGqDK', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:50', '2025-12-21 13:00:50', 0, NULL),
(80, 'Muhammad Faza Nail Urridho', 'siswa4046', 'siswa4046@student.nurulilmi.id', NULL, '$2y$12$BaAliqg8dSnbRWjvif1V7u5.ZGtEUDU94Dh8C0jAgZrrgI5qgeA2W', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:50', '2025-12-21 13:00:50', 0, NULL),
(81, 'PUTRI HUMAIRAH LAILANI', 'siswa4047', 'siswa4047@student.nurulilmi.id', NULL, '$2y$12$HXEa9yQ3.YimpKDbAZ5gZ.NNL/QiLTnISX2/.9rH8EjCg9b/Nqe1y', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:50', '2025-12-21 13:00:50', 0, NULL),
(82, 'Reva Anisa Putri', 'siswa4048', 'siswa4048@student.nurulilmi.id', NULL, '$2y$12$B3fLZfYu0SIcqtpqF/FN.u.NnH/J1f2awzjyDKoqtmQJbC0hVzvGO', 'siswa123', 'aktif', '1766774480_1765860454.jpg', NULL, '2025-12-21 13:00:51', '2025-12-26 18:41:20', 0, NULL),
(83, 'SALWA ALIA PUTRI', 'siswa4049', 'siswa4049@student.nurulilmi.id', NULL, '$2y$12$uuH8aATut/7K7MJhe7FoUe2g4VgHGAWaZOBZ07JEEyTSCDrAE7xu6', 'siswa123', 'aktif', '1766774740_2.jpeg', NULL, '2025-12-21 13:00:51', '2025-12-26 18:45:40', 0, NULL),
(84, 'SEPTIA RAMADHANI', 'siswa4050', 'siswa4050@student.nurulilmi.id', NULL, '$2y$12$mhF1BCHEgyBWTiLomhIt1OEdB/NEdzl.sauoDOnt9VPVfQiqGYZ.2', 'siswa123', 'aktif', NULL, NULL, '2025-12-21 13:00:51', '2025-12-26 13:37:47', 0, NULL),
(85, 'AHMAD AKBAR', 'siswasmk551', 'siswasmk551@student.nurulilmi.id', NULL, '$2y$12$5icQSayeEBIoUbxyFGIKwOE.VdlomDSQQjoHDkBfV71oEh8Ox32cu', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:48', '2025-12-27 04:30:48', 0, NULL),
(86, 'ELVAN SUBASTIAN', 'siswasmk552', 'siswasmk552@student.nurulilmi.id', NULL, '$2y$12$iKhXutriLNi8My9xnOQtZuLUnwCEdMy9bebOzg.Pq/T/TzDr5MPee', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:48', '2025-12-27 04:30:48', 0, NULL),
(87, 'Indra Wijaya', 'siswasmk553', 'siswasmk553@student.nurulilmi.id', NULL, '$2y$12$ownMbKFRcn.JaYsZYOItaelhCJ9vTmJZ/.yLYFOklx6y/CIFr69cC', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:48', '2025-12-27 04:30:48', 0, NULL),
(88, 'M Dafa Assiddiq', 'siswasmk554', 'siswasmk554@student.nurulilmi.id', NULL, '$2y$12$77rQnvSHwTMzuE9RCOLh0uu8Clc7O.ynUTslGaJsEgzYYzTnyc/iq', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:48', '2025-12-27 04:30:48', 0, NULL),
(89, 'M. REYFAN', 'siswasmk555', 'siswasmk555@student.nurulilmi.id', NULL, '$2y$12$/lHRgv7suZvg5/ebvwsjkOwx1XBMey0JKHZQLMBpk81Drakg29HuC', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:48', '2025-12-27 04:30:48', 0, NULL),
(90, 'M. sultan faiz', 'siswasmk556', 'siswasmk556@student.nurulilmi.id', NULL, '$2y$12$cr2N748cD2UZ5wD4u7.oGOsA5oM51QX02phRxG.7nIanpUxHg/pNK', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:49', '2025-12-27 04:30:49', 0, NULL),
(91, 'M.SHOLIHIN', 'siswasmk557', 'siswasmk557@student.nurulilmi.id', NULL, '$2y$12$IeGdclzCk.1eXV2np4P7J.2iCbUqZrhGT/lAgm24y9/YANuFgKVoW', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:49', '2025-12-27 04:30:49', 0, NULL),
(92, 'Muhammad Danny Irawan', 'siswasmk558', 'siswasmk558@student.nurulilmi.id', NULL, '$2y$12$FQ1EWXVAUbSv8GJdD5xrA.Z1KJhxVk6nnK/xU.GUC0p3KbDxO9CHe', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:49', '2025-12-27 04:30:49', 0, NULL),
(93, 'Muhammad Hafizh Ridho', 'siswasmk559', 'siswasmk559@student.nurulilmi.id', NULL, '$2y$12$uaC06TnrWvfxwRYcHlk7f.OQTdZlaz1BiVDYubQTgulmyueM2o77G', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:49', '2025-12-27 04:30:49', 0, NULL),
(94, 'WIRA LESMANA AHMAD', 'siswasmk560', 'siswasmk560@student.nurulilmi.id', NULL, '$2y$12$v9FxPxFa3ui4e41VxrAjdO4ikORI7rPRJFYbA1Q52Rkfu1mv9oeqS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:50', '2025-12-27 04:30:50', 0, NULL),
(95, 'M.SHOBIRIN', 'siswasmk561', 'siswasmk561@student.nurulilmi.id', NULL, '$2y$12$kLZK9/NmDXi5uBBTfzLI/ujzSvy8.ZFj5bMrKEkpUMzxEl/hL2gGq', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:50', '2025-12-27 04:30:50', 0, NULL),
(96, 'Mufid Arbain', 'siswasmk562', 'siswasmk562@student.nurulilmi.id', NULL, '$2y$12$4b8HG7fPm3kT1ipzLjK9FeA2yShU86MmmdpsbP3Sb7Yw4TTcH7X/G', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:50', '2025-12-27 04:30:50', 0, NULL),
(97, 'NONI AYU NOVIANI', 'siswasmk563', 'siswasmk563@student.nurulilmi.id', NULL, '$2y$12$t1unnLFDp4dnV447u7FQneQ.FUeRNFmVeLqlo9nfCQy6hWG9o0UIS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:50', '2025-12-27 04:30:50', 0, NULL),
(98, 'ROMI FRIYANSAH', 'siswasmk564', 'siswasmk564@student.nurulilmi.id', NULL, '$2y$12$l2tjrotG.doMnErQd7OP1.W8nLvGRRtLgvbxX31bnzWzAommmmHS2', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:51', '2025-12-27 04:30:51', 0, NULL),
(99, 'SYAFA PRATAMI', 'siswasmk565', 'siswasmk565@student.nurulilmi.id', NULL, '$2y$12$uRcTS1pxtM2Yw01ILDaj0.McSJm.N5a/UfhvbeyncUlB923rBfUVa', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:51', '2025-12-27 04:30:51', 0, NULL),
(100, 'AHMAD ALMAHDI', 'siswasmk566', 'siswasmk566@student.nurulilmi.id', NULL, '$2y$12$NIhDQk/YdbBAdztxcUGKxujeJSZaBBO0Yh41aL7Qd9S0V3uj4WSlm', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:51', '2025-12-27 04:30:51', 0, NULL),
(101, 'AHMAD FAIZ FADILLAH', 'siswasmk567', 'siswasmk567@student.nurulilmi.id', NULL, '$2y$12$yUJIbkn34usIkVbuNzyp4OzHSLF6K3qusTE7Rn4tXCjPtH5slt4we', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:51', '2025-12-27 04:30:51', 0, NULL),
(102, 'Dimas Deri Kusuma', 'siswasmk568', 'siswasmk568@student.nurulilmi.id', NULL, '$2y$12$IsRmZpeAOMLZe2LUn9H47O0MIlFbJfNCdw.8zGwmFaWtdvJe5Gk9G', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:52', '2025-12-27 04:30:52', 0, NULL),
(103, 'FANNDY DONY ADITIA', 'siswasmk569', 'siswasmk569@student.nurulilmi.id', NULL, '$2y$12$cW2wc9ZrSXElGtyUvfxuZOnI6ds0MRwhRDCHncoLRAItUuN0OSRCq', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:52', '2025-12-27 04:30:52', 0, NULL),
(104, 'FAREL FAIQ AL KAFILA', 'siswasmk570', 'siswasmk570@student.nurulilmi.id', NULL, '$2y$12$pB/WGxXw36qop8e7f1NBtuNM7uXsM4ZqicJmxf.pNP5Y.8WR/TAHu', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:52', '2025-12-27 04:30:52', 0, NULL),
(105, 'ILHAM AL \'AZIZ', 'siswasmk571', 'siswasmk571@student.nurulilmi.id', NULL, '$2y$12$oxdXihTR5d2mWdOnYmfoiOg71cebArAvgQSb/DEzUali/M0bjVTGS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:52', '2025-12-27 04:30:52', 0, NULL),
(106, 'LAURA GISELA VANDA DITA', 'siswasmk572', 'siswasmk572@student.nurulilmi.id', NULL, '$2y$12$htEAIR.zBkKE3mj/pc77KeJYjgUWT7zBUksB2L2hgKA5dD346xfcK', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:53', '2025-12-27 04:30:53', 0, NULL),
(107, 'M. ANDREAN MUSLIMIN', 'siswasmk573', 'siswasmk573@student.nurulilmi.id', NULL, '$2y$12$SX0gA1HAsJtRXZmlM5g9xuzMt4PSuveFMj2febgQ/ROsE5aCBKQVa', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:53', '2025-12-27 04:30:53', 0, NULL),
(108, 'Muhammad Fairuz Ramadhan', 'siswasmk574', 'siswasmk574@student.nurulilmi.id', NULL, '$2y$12$p5OoDshf3LIulhKAF5L8M.nuQ8.hcQ6OKim.Zqrwahm9trfVrNNdO', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:53', '2025-12-27 04:30:53', 0, NULL),
(109, 'NURHAFIDZHA', 'siswasmk575', 'siswasmk575@student.nurulilmi.id', NULL, '$2y$12$qulqkY5RtFJWc8rV6pD4UuV1YhZuP5a3S/FBQmnQ4vJjoN4I4tmx6', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:53', '2025-12-27 04:30:53', 0, NULL),
(110, 'RIKA AMELIA', 'siswasmk576', 'siswasmk576@student.nurulilmi.id', NULL, '$2y$12$81rra7m5LnPs3wyXA.EieOLbYZBi8wxtnXqlKRVT0ij0Skz18.mzS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:54', '2025-12-27 04:30:54', 0, NULL),
(111, 'Tofiqur Rohim Harahap', 'siswasmk577', 'siswasmk577@student.nurulilmi.id', NULL, '$2y$12$6BZHaWIQaZQFtYUuR8eK2eRG7mwA48zNve/dvryIvY7me2SnuXxl2', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:54', '2025-12-27 04:30:54', 0, NULL),
(112, 'Toriqul Rahman Harahap', 'siswasmk578', 'siswasmk578@student.nurulilmi.id', NULL, '$2y$12$ht.zSVnw4FMAjasNJH2JZOB/vMw1WePwGy0cD6hTjgv46jM4mD4hS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:54', '2025-12-27 04:30:54', 0, NULL),
(113, 'ZAHRA LATIFATUL NUR AINI', 'siswasmk579', 'siswasmk579@student.nurulilmi.id', NULL, '$2y$12$2P2.paMkDIsDLW3W6WoHEuLFJwha9yH3Pqjj/xmO3U/irHdY1i7DS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:55', '2025-12-27 04:30:55', 0, NULL),
(114, 'ADENDA DARMAWAN', 'siswasmk580', 'siswasmk580@student.nurulilmi.id', NULL, '$2y$12$dRF2JgF7xjK3A80erAD5PO730uN2pHd9zZfwEojvv30PZkB0nJunG', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:55', '2025-12-27 04:30:55', 0, NULL),
(115, 'ALFHADILLAH', 'siswasmk581', 'siswasmk581@student.nurulilmi.id', NULL, '$2y$12$BdqEV6fah12yAIi9AEXTG.k15VrSvcsydJnfS9.vNTVPC6iqntTm2', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:55', '2025-12-27 04:30:55', 0, NULL),
(116, 'DIRGA HADITAMA', 'siswasmk582', 'siswasmk582@student.nurulilmi.id', NULL, '$2y$12$9FdV2npVIyHFvR1zwSUfdOsnq35/bRapg/GpA2zN8EpLRTQLWxT0O', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:55', '2025-12-27 04:30:55', 0, NULL),
(117, 'Dzikra Hafidz Farrasi', 'siswasmk583', 'siswasmk583@student.nurulilmi.id', NULL, '$2y$12$MZ3fw5X9v.u3hWK5yZdiXuuHFRzRaIrxJaCJoEeNNtnpDzLrfFmh.', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:56', '2025-12-27 04:30:56', 0, NULL),
(118, 'MEYZA MUTIARA KASIH', 'siswasmk584', 'siswasmk584@student.nurulilmi.id', NULL, '$2y$12$GMMMuG5ys4ldR7rG0lp/cuo1sRz0m98VqKGAELDzbR9bYSz9hYZWS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:56', '2025-12-27 04:30:56', 0, NULL),
(119, 'MICKY REVI SAPUTRA', 'siswasmk585', 'siswasmk585@student.nurulilmi.id', NULL, '$2y$12$xVG280z1dHS6sC75Fi33Ve8YUAX3K0bbjW5bLALE90i.Uzwo5PcjW', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:56', '2025-12-27 04:30:56', 0, NULL),
(120, 'NIRWANA', 'siswasmk586', 'siswasmk586@student.nurulilmi.id', NULL, '$2y$12$myB8CvGp9FmLIv4/smAsxuGCuDf5Sa0cEXqXAXz.bHePOMKZD/goW', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:56', '2025-12-27 04:30:56', 0, NULL),
(121, 'Rani Anggraini', 'siswasmk587', 'siswasmk587@student.nurulilmi.id', NULL, '$2y$12$pY7YA0Nl.QRu7uHvB8ENf.EHcjLMNXNYwHj.ImB1/I/6dNktI81/W', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:57', '2025-12-27 04:30:57', 0, NULL),
(122, 'RESTU WIDIAS TANTO', 'siswasmk588', 'siswasmk588@student.nurulilmi.id', NULL, '$2y$12$GZAURZbIZX/itAMKpkFNHuCpI.FY4esNkj.a3.RKFwd8Rap.FPh/W', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:57', '2025-12-27 04:30:57', 0, NULL),
(123, 'Talitha Sakhi Raniah', 'siswasmk589', 'siswasmk589@student.nurulilmi.id', NULL, '$2y$12$DsSZrwaysP4VAKq9OEZkKOHThb/ft81dajBb4Y8pMKDe2pMMiVofW', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:57', '2025-12-27 04:30:57', 0, NULL),
(124, 'Tegar Ady Pratama', 'siswasmk590', 'siswasmk590@student.nurulilmi.id', NULL, '$2y$12$aiY4jxzOhCqi22kdM2oDeuDlqjc5NNuHUPwSHreausA8S1Rk5Dsti', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:57', '2025-12-27 04:30:57', 0, NULL),
(125, 'DZULHI NOFELINDA', 'siswasmk591', 'siswasmk591@student.nurulilmi.id', NULL, '$2y$12$Xc01j5GL7FJM2gXKmHS/ieu/rjk.7rdE4QAumP5F/X185ZISPpxzS', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:58', '2025-12-27 04:30:58', 0, NULL),
(126, 'LENI OKTAFIYANI', 'siswasmk592', 'siswasmk592@student.nurulilmi.id', NULL, '$2y$12$k7DapGDnwGHB4zhrBuwEmerOV7XYog8b.gs240mTCEWnWx/be5FCm', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:58', '2025-12-27 04:30:58', 0, NULL),
(127, 'M. ROBIH FERLIANSYAH', 'siswasmk593', 'siswasmk593@student.nurulilmi.id', NULL, '$2y$12$icFkHQks06O6XzznudBTBODpLgZ3lu2R5NJsMvxL3VYTfVi.cZcJC', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:58', '2025-12-27 04:30:58', 0, NULL),
(128, 'MARISSA UTARI', 'siswasmk594', 'siswasmk594@student.nurulilmi.id', NULL, '$2y$12$A9VBgIrHMmfUR4K/CLMzQehKmpV2nF6E0.ZvKDaaG8BnHXwUXJ3hG', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:58', '2025-12-27 04:30:58', 0, NULL),
(129, 'MUHAMMAD RIDHO TRI SAPUTRA', 'siswasmk595', 'siswasmk595@student.nurulilmi.id', NULL, '$2y$12$B7mPy.iFNxyzaoi3UBIDjenxf9Mp3LyuQY4vWS4Yx0aHOBXnkWlbm', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:58', '2025-12-27 04:30:58', 0, NULL),
(130, 'RIYANI AWALIA PUTRI', 'siswasmk596', 'siswasmk596@student.nurulilmi.id', NULL, '$2y$12$/47Otz6W/EYvl6cu1gW80evpssB5NN1EFBU9ahNVwGtdS38pQY8UG', 'siswa123', 'aktif', NULL, NULL, '2025-12-27 04:30:59', '2025-12-27 04:30:59', 0, NULL),
(213, 'Aerilyn Bellvania Cintakirana', 'siswa00281', 'siswa00281@student.nurulilmi.id', NULL, '$2y$12$KUcRb8XVjDMQeO8ffHV39OOQQLxjkVKMoF2j8AGpVeZ1eV.gkZgQe', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:35', '2026-01-01 14:16:35', 0, NULL),
(214, 'Ahmad Faiz', 'siswa00282', 'siswa00282@student.nurulilmi.id', NULL, '$2y$12$MOHJ1lmi6X27OkZ1qOkJjOTj1c9lwiCLMhCIeH/jmNGl6kIBosjSO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:36', '2026-01-01 14:16:36', 0, NULL),
(215, 'Aliyu Ratu Jaya', 'siswa00284', 'siswa00284@student.nurulilmi.id', NULL, '$2y$12$1QU8AJVPWZkuunQyf0ugWeVyXCaQJYJ6/Jc6gy6UH9A1y2CfnI5oK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:36', '2026-01-01 14:16:36', 0, NULL),
(216, 'Annisya Al-Zha Hirah', 'siswa00285', 'siswa00285@student.nurulilmi.id', NULL, '$2y$12$t/uI06Xw5TO3sRanE0yqL.RQ6gzBLjwmt.3cE3JrHyCK4lG3VJs8u', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:37', '2026-01-01 14:16:37', 0, NULL),
(217, 'Aprilia Nurul Madina', 'siswa00286', 'siswa00286@student.nurulilmi.id', NULL, '$2y$12$7EDq6w23L.dJRFkAKnS4putMYT/lzCjubnme1tBkvyTlfEVusDZEK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:37', '2026-01-01 14:16:37', 0, NULL),
(218, 'Aqilah Andero Putri', 'siswa00287', 'siswa00287@student.nurulilmi.id', NULL, '$2y$12$WWiscxtA4PgIdN6cnVf5ReFkvHPOrcDTn8HG6RBEOIneAe5OPeQYS', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:38', '2026-01-01 14:16:38', 0, NULL),
(219, 'Arif Adi Saputra', 'siswa00288', 'siswa00288@student.nurulilmi.id', NULL, '$2y$12$FDK/FdsN16yX4ngMfqqlfe.Esn6D5EXJpZ.5a8TZUZVSlRXn4DcD2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:38', '2026-01-01 14:16:38', 0, NULL),
(220, 'ASIFA SYAHIRANI', 'siswa00289', 'siswa00289@student.nurulilmi.id', NULL, '$2y$12$VDqv1GoN8AUi9UV/U9jS1eprff7jX5nXrl3/QlIHuoPOwu4b74gP2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:39', '2026-01-01 14:16:39', 0, NULL),
(221, 'ASSYIFA KHAIRUNNISA', 'siswa00290', 'siswa00290@student.nurulilmi.id', NULL, '$2y$12$dEl5rMFprC.vQBumI3VTBuEwp5MDxdrcpqtv2h8CLch8Ez26lqdke', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:39', '2026-01-01 14:16:39', 0, NULL),
(222, 'AUREL AQILAH PRARIA PUTRI', 'siswa00291', 'siswa00291@student.nurulilmi.id', NULL, '$2y$12$LTYPs1qUdBlH879jmrYrs.hHV8eY5RTF.BT19HqWMfDtUP4DhyAiG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:40', '2026-01-01 14:16:40', 0, NULL),
(223, 'CLAIRINE AYU SARASWATI', 'siswa00292', 'siswa00292@student.nurulilmi.id', NULL, '$2y$12$/7xz8pzsuoJOizL9VIv5IO8ltSeqRCUa6Wvo0XvpvXdviD/NHl.6C', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:40', '2026-01-01 14:16:40', 0, NULL),
(224, 'Delvin Agustinus', 'siswa00294', 'siswa00294@student.nurulilmi.id', NULL, '$2y$12$l.JHWxCpIWDugswVVck9D.FvZ0tMOwFIJNJO9sqSechc9RstEOug6', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:41', '2026-01-01 14:16:41', 0, NULL),
(225, 'Dewa Aprilian Caesar', 'siswa00295', 'siswa00295@student.nurulilmi.id', NULL, '$2y$12$Xeaao0dNZuQ38lWTzwBujuHQn9C7OO0AMUUXegFjYHwRSikcSPSoq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:41', '2026-01-01 14:16:41', 0, NULL),
(226, 'DINARA AULIA ASTREILA', 'siswa00296', 'siswa00296@student.nurulilmi.id', NULL, '$2y$12$U4VN2JNpD/WZ9Ll2Jg5Bo.FfA8enl.fD4egdagKyHVgvuJo/XEZbm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:42', '2026-01-01 14:16:42', 0, NULL),
(227, 'DIVA AZZAHRA', 'siswa00297', 'siswa00297@student.nurulilmi.id', NULL, '$2y$12$k8jjrx0piUe.Kl.Gagg3Se4mN7nYiBi3O//eUY//gIrOd.G1Fy45a', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:42', '2026-01-01 14:16:42', 0, NULL),
(228, 'Dzakiyah Nayla Deandy', 'siswa00298', 'siswa00298@student.nurulilmi.id', NULL, '$2y$12$WEF/In/9PYhAY44R8HsMEOEx50o8Niu7v2PmpivEifFUCoPD7AEYa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:43', '2026-01-01 14:16:43', 0, NULL),
(229, 'Fagan Fabian Altair', 'siswa00299', 'siswa00299@student.nurulilmi.id', NULL, '$2y$12$srnvL2hemd7u5xUD8n5jeenFz8Z7mezMou9GzIF/uErw2Ldl8Hn4C', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:43', '2026-01-01 14:16:43', 0, NULL),
(230, 'FAHRI DERMAWAN', 'siswa00300', 'siswa00300@student.nurulilmi.id', NULL, '$2y$12$6NfBu6mj/ttgG8lKz5qUq.LAK8A1l5snkH6yyLV2KqE1.Jkn94NVu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:44', '2026-01-01 14:16:44', 0, NULL),
(231, 'Faza Hakim Firdaus', 'siswa00301', 'siswa00301@student.nurulilmi.id', NULL, '$2y$12$/Nqv397bWlHqZcpA.WPLiOeNZxSWwQmV3xEC2eRY34AHmN9V0vpEq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:44', '2026-01-01 14:16:44', 0, NULL),
(232, 'Fransaid Almuizz', 'siswa00302', 'siswa00302@student.nurulilmi.id', NULL, '$2y$12$I5ijGAhreCFUsZ9mc4EZXOLAJDpltG1hZMaI2dM7LPxEERHoZe9AG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:44', '2026-01-01 14:16:44', 0, NULL),
(233, 'Hafizah Sadira', 'siswa00303', 'siswa00303@student.nurulilmi.id', NULL, '$2y$12$Ok5Q8oF9Vefl8w06pSainuEx41MV5uYGPrhx4t7owNASYahOUJpSO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:45', '2026-01-01 14:16:45', 0, NULL),
(234, 'Ichwan Taufiq', 'siswa00304', 'siswa00304@student.nurulilmi.id', NULL, '$2y$12$EQtwCYJcuwwqnbmQaY92l.kvPhuK6e39wWXc37Y.7XkzlTo0fO7s.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:45', '2026-01-01 14:16:45', 0, NULL),
(235, 'Iinas Firyaal Fakhriyyah', 'siswa00305', 'siswa00305@student.nurulilmi.id', NULL, '$2y$12$0ChswiJw8yOfw4AowMIv0uKXa34NrWKy842NTfvp/oXntHORGicn.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:46', '2026-01-01 14:16:46', 0, NULL),
(236, 'Ilham Mustapa', 'siswa00306', 'siswa00306@student.nurulilmi.id', NULL, '$2y$12$CHAJF8T6OQacpUM0RMBbyO0PH0lwAIQ6cGM26mdK2P7.gbiq3knAW', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:46', '2026-01-01 14:16:46', 0, NULL),
(237, 'Kenara Yohanda', 'siswa00307', 'siswa00307@student.nurulilmi.id', NULL, '$2y$12$XeFCmUy0bEN2ot6y7hxDkezqyhRSNXONKVrfvF7CFgim9.ecTUuF2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:47', '2026-01-01 14:16:47', 0, NULL),
(238, 'Keyzia Morella', 'siswa00308', 'siswa00308@student.nurulilmi.id', NULL, '$2y$12$3D/KF0Ictv75RSy3RA.T7uvV20hMzA/xYipDPHqvWTihky.VvFX5G', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:47', '2026-01-01 14:16:47', 0, NULL),
(239, 'Khafka Fadhilul Afif Alfri', 'siswa00309', 'siswa00309@student.nurulilmi.id', NULL, '$2y$12$VjL1afjLk7dncnqOA9LGqOEWQ/ugBJ3pDqmCu9gUlPnLJelKfSPdK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:48', '2026-01-01 14:16:48', 0, NULL),
(240, 'Kiagus Muhammad Azzikri', 'siswa00310', 'siswa00310@student.nurulilmi.id', NULL, '$2y$12$dhT7nsIJO4rO6x0OG8AS5u0Kxq6O7vDOBPBb5RsfWTYZbbdsfqxwu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:48', '2026-01-01 14:16:48', 0, NULL),
(241, 'Luckye Alpari', 'siswa00311', 'siswa00311@student.nurulilmi.id', NULL, '$2y$12$.8V6g0foKd9QwJFQsGJ/zuro0poZaQVHXY2OcwhgqHcS5NsltLt1K', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:49', '2026-01-01 14:16:49', 0, NULL),
(242, 'M. Fahruddin', 'siswa00312', 'siswa00312@student.nurulilmi.id', NULL, '$2y$12$19GExGBpfRAIWMPaH5tfR.3QxBRIDAIpKBeV01vEnQxKaNNWR53z.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:49', '2026-01-01 14:16:49', 0, NULL),
(243, 'M. Azzafha Alkhatirta Syaher', 'siswa00314', 'siswa00314@student.nurulilmi.id', NULL, '$2y$12$c/VrAs4Hbj.xTbJCjBycxOKuEDavKlwccMNLil06t/XUdLgCKDZNS', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:50', '2026-01-01 14:16:50', 0, NULL),
(244, 'M. Hafiz At-Taqiy', 'siswa00315', 'siswa00315@student.nurulilmi.id', NULL, '$2y$12$nICQfpIPOz3UXbhSWwEx/uFZONfs2rX9hDXlrgGVuL1QPylgrbdwe', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:50', '2026-01-01 14:16:50', 0, NULL),
(245, 'M. Nabil Yeishaq', 'siswa00316', 'siswa00316@student.nurulilmi.id', NULL, '$2y$12$vsvOfyT9s7Hw0/K3hkrDl.sMnddLzeGJ3KD/j8yEaJJ./dC1nD6G6', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:51', '2026-01-01 14:16:51', 0, NULL),
(246, 'M. VARID AMIN KATHABI', 'siswa00317', 'siswa00317@student.nurulilmi.id', NULL, '$2y$12$Qsm.NKwX5b8qaC/13oHf9OyP.MRs/IKN.CmQM3/ZG/hdI2hPkyFuK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:51', '2026-01-01 14:16:51', 0, NULL),
(247, 'M. Zakiy Al Balkis', 'siswa00318', 'siswa00318@student.nurulilmi.id', NULL, '$2y$12$8PAf1RUXRWYv8MiebyIN1u3HLtc1L.nYPF4o3RcNgxZiaZTBoVQsm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:52', '2026-01-01 14:16:52', 0, NULL),
(248, 'M. Ikhsan Febriansyah', 'siswa00319', 'siswa00319@student.nurulilmi.id', NULL, '$2y$12$itw8HDT1/n0V9SMo6Hkw4eyEDQRmACWkuiYXZrA3vTyyYgDURBJoW', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:52', '2026-01-01 14:16:52', 0, NULL),
(249, 'M. Nizam Ramadhan', 'siswa00320', 'siswa00320@student.nurulilmi.id', NULL, '$2y$12$WDI2m46.Dq6Pd3687jHZYeUSLMR2rxAVRiYDLHwM/U6DEsjkWRXRC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:53', '2026-01-01 14:16:53', 0, NULL),
(250, 'Maheza Aldippa Rifasyanki', 'siswa00321', 'siswa00321@student.nurulilmi.id', NULL, '$2y$12$VqeJogvMKtzXyZHzQU.Eyelaja76Hy2eQ1d11tQKt2rTOd139Yqcm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:53', '2026-01-01 14:16:53', 0, NULL),
(251, 'Muazara Ulfa Rohmad', 'siswa00322', 'siswa00322@student.nurulilmi.id', NULL, '$2y$12$h3BsRY0unyEbRzQ5nMFOfuv76VT.Nt1D4uEmfVXCGXCQHCiuQ5GYy', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:53', '2026-01-01 14:16:53', 0, NULL),
(252, 'Muhammad Alif Junaidi Rasyied', 'siswa00323', 'siswa00323@student.nurulilmi.id', NULL, '$2y$12$7Xs65EY9X1vzKkq6dQI/ceFZjP3.UCYLjEj7G3aEWiSl5/xGfg4Tm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:54', '2026-01-01 14:16:54', 0, NULL),
(253, 'MUHAMMAD FHAIZ MAULANA SUSILO', 'siswa00324', 'siswa00324@student.nurulilmi.id', NULL, '$2y$12$pdBPalZESJaCNIq8dX09R.FX3ApGKLZ53GuAoMcEljr8oKctxpXRS', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:54', '2026-01-01 14:16:54', 0, NULL),
(254, 'Muhammad Mahvin Gustiandi', 'siswa00325', 'siswa00325@student.nurulilmi.id', NULL, '$2y$12$lEc04VxlKtMOXp1tw/LRieMy0oVDMSeOhQuD8rVIpFjy0nnBLYXcq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:55', '2026-01-01 14:16:55', 0, NULL),
(255, 'Mulla Reza', 'siswa00326', 'siswa00326@student.nurulilmi.id', NULL, '$2y$12$hEh8G3Pt9M0A6AQheFOwrewdxQCJazzIBuI69U3Sm.JavIh3yz9e2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:55', '2026-01-01 14:16:55', 0, NULL),
(256, 'Nafisha Ananda', 'siswa00328', 'siswa00328@student.nurulilmi.id', NULL, '$2y$12$1Iix2gjVT3ti0uU/VSio.urhiQh2FZzgjYFJtR7SWd8F1BjP4inrm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:56', '2026-01-01 14:16:56', 0, NULL),
(257, 'Nasywa Aqila Khairani', 'siswa00329', 'siswa00329@student.nurulilmi.id', NULL, '$2y$12$RmigvUrvoSWPWkcxXVTiyePDWM62xVuzVoKg1rrtX5GsNFiXuroYC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:56', '2026-01-01 14:16:56', 0, NULL),
(258, 'Prenzi Founjalian', 'siswa00330', 'siswa00330@student.nurulilmi.id', NULL, '$2y$12$J0mZYGBGEQWhMfr6bwIAu.NatQ2/Xm6gowa1sLjUqGwEGmeF5qlxa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:57', '2026-01-01 14:16:57', 0, NULL),
(259, 'Putra Romadon', 'siswa00331', 'siswa00331@student.nurulilmi.id', NULL, '$2y$12$wEksdUn6ZsX2f0SDKLdJuOsYh0/yvs2fqZF1eXIOHSDtBGpMuiHvm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:57', '2026-01-01 14:16:57', 0, NULL),
(260, 'Putri Jasmine Harahap', 'siswa00332', 'siswa00332@student.nurulilmi.id', NULL, '$2y$12$rUpLuwu/qzgvU9HvlKte6ecpgkC.4D0Q/Z8Leq8oxGfCGelBpDPk6', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:58', '2026-01-01 14:16:58', 0, NULL),
(261, 'Rangga Dwi Ariyansyah', 'siswa00333', 'siswa00333@student.nurulilmi.id', NULL, '$2y$12$z/PzkWHNgsaqPeaWuk/xw.E/syQ7XO0Wp8dphzVDjEfB/QtyUCY/m', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:58', '2026-01-01 14:16:58', 0, NULL),
(262, 'Ruli Malik Rasyid Asshidiq', 'siswa00334', 'siswa00334@student.nurulilmi.id', NULL, '$2y$12$LSX.3Z.IlaINMnTEdTOPD.giYS2ZjqpQisAk/ZO9UkaNyyJBGYnUq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:59', '2026-01-01 14:16:59', 0, NULL),
(263, 'SHELLY AULIA PUTRI', 'siswa00335', 'siswa00335@student.nurulilmi.id', NULL, '$2y$12$R6VbzFv7dJVnDZChOKw52.1cSOJpoqPBxc76v3b6YIG/osbHmwb7G', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:16:59', '2026-01-01 14:16:59', 0, NULL),
(264, 'Syahrendra Rizla Rayend Utomo', 'siswa00336', 'siswa00336@student.nurulilmi.id', NULL, '$2y$12$y00gCYajCI7O.2f8IMHUO.6nwnEZzoxWnfQO2RPzpMN9abohe9ybK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:00', '2026-01-01 14:17:00', 0, NULL),
(265, 'Syarif Abdurrahman', 'siswa00337', 'siswa00337@student.nurulilmi.id', NULL, '$2y$12$toHwhJkr89rP3xBA1s5Xae7pBzKA1ClMY7NTWbHeTRN2f68aCnz/G', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:00', '2026-01-01 14:17:00', 0, NULL),
(266, 'SYAVIRA MARTHA RISKI SAPUTRI', 'siswa00338', 'siswa00338@student.nurulilmi.id', NULL, '$2y$12$RCuR0bgS1VsQfhGDzl6WQ.SrQ0efWXx0kCtYx5.5Ep1cX2JMkcVc6', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:01', '2026-01-01 14:17:01', 0, NULL),
(267, 'Syifa Aira Mutiara Taruni', 'siswa00339', 'siswa00339@student.nurulilmi.id', NULL, '$2y$12$tejdM6vDfSKHqAYl9o8FVe9JpiZzGDiHkGHxsccWlTA5aR4.BXV/y', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:01', '2026-01-01 14:17:01', 0, NULL),
(268, 'TIARA AMANDA', 'siswa00340', 'siswa00340@student.nurulilmi.id', NULL, '$2y$12$xWTtpl/E8m//MPlTUCw.zuIkbzMDSbhXOnu5sl/KrD7XHTi5Sbj6W', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:02', '2026-01-01 14:17:02', 0, NULL),
(269, 'Tiara Fitriansyah', 'siswa00341', 'siswa00341@student.nurulilmi.id', NULL, '$2y$12$P2qKzvya4XN03LnayP0KHO.bNRH9xaWOm1YNeOInVYltljbtpLg6C', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:02', '2026-01-01 14:17:02', 0, NULL),
(270, 'Tyandra Abbra Ganesha', 'siswa00342', 'siswa00342@student.nurulilmi.id', NULL, '$2y$12$jfhTM0ucxIXpS7F4ZeUg.eVLGNxmaCyDKExi9DFmb0l8kli4eOJGO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:03', '2026-01-01 14:17:03', 0, NULL),
(271, 'Viona Pramayshella', 'siswa00344', 'siswa00344@student.nurulilmi.id', NULL, '$2y$12$FgT0EJh9.GGM6jDlEglxNeQeNDho0TtlIqJ./p1lYtuPrM242lFW.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:03', '2026-01-01 14:17:03', 0, NULL),
(272, 'Wira Imanda Gusti', 'siswa00345', 'siswa00345@student.nurulilmi.id', NULL, '$2y$12$81BwL0TEoe2mXdw6I9JetO.hXkCFwKkhTUoVMIPWdpNuvrpvwbQFu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:04', '2026-01-01 14:17:04', 0, NULL),
(273, 'Zanira Hayu Hafidzah', 'siswa00346', 'siswa00346@student.nurulilmi.id', NULL, '$2y$12$2yv6OYpMsNghH3EqH4iJweOlxLTxp3zGs3AEDkD1MXeBbSPyTBNJK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:04', '2026-01-01 14:17:04', 0, NULL),
(274, 'ZASKIA CAESARITA DIAN', 'siswa00347', 'siswa00347@student.nurulilmi.id', NULL, '$2y$12$zKjoFoIBgf8.rv/dJfMc1.9tBpDaJliLzkKGt4MQwGU90QHNvDa.S', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:05', '2026-01-01 14:17:05', 0, NULL),
(275, 'ZULFA HANIYYA AZZAHRA', 'siswa00348', 'siswa00348@student.nurulilmi.id', NULL, '$2y$12$GzK8Ruu3aeVP899gJ9MV/uxE5xaKImQVqhZf9AUumM1/kcvi.T9Aa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:05', '2026-01-01 14:17:05', 0, NULL),
(276, 'Aura Putri Divia', 'siswa00351', 'siswa00351@student.nurulilmi.id', NULL, '$2y$12$o9uvsWJ8MJy0m8/68lBa5.scfSwttvdyDjG9sVCPJMWo/MwTfeSE2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:05', '2026-01-01 14:17:05', 0, NULL),
(277, 'Azza Puspita Ayu', 'siswa00352', 'siswa00352@student.nurulilmi.id', NULL, '$2y$12$gi.H0FhLBNqpSabmJjZ.BuXLdjkqspEJqo85snnAwR8lTIKPzTjqq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:06', '2026-01-01 14:17:06', 0, NULL),
(278, 'Khanza Nafisah Hadiwijaya', 'siswa00355', 'siswa00355@student.nurulilmi.id', NULL, '$2y$12$uS8YVW/0Rlj7vAE7wYKNj.yKE4EOBVwda1z26x7.rLSTqHxzpvw..', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:06', '2026-01-01 14:17:06', 0, NULL),
(279, 'HANIIFAH SYAHFIRA', 'siswa00358', 'siswa00358@student.nurulilmi.id', NULL, '$2y$12$ggdmNIiOn6uM1/boZBfin.TZWE70Fllgkb9WCTLvZOu6t7fia7l76', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:07', '2026-01-01 14:17:07', 0, NULL),
(280, 'MUHAMMAD FATHAN FARIS', 'siswa00361', 'siswa00361@student.nurulilmi.id', NULL, '$2y$12$DaqbB3LVlIqaGDGRWiSWi.jxj7EVS9R9D84C3OatHpbCrwnJtr5ta', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:07', '2026-01-01 14:17:07', 0, NULL),
(281, 'Ahmad Fairuz', 'siswa00362', 'siswa00362@student.nurulilmi.id', NULL, '$2y$12$ClkKIKLcsEDofno2IuCCKu5egNBOQngibkUb9lDeBkrFsAhzAE38O', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:08', '2026-01-01 14:17:08', 0, NULL),
(282, 'Alia Renada', 'siswa00363', 'siswa00363@student.nurulilmi.id', NULL, '$2y$12$1c65TimDLAChy2SnFoZS0emoVpZ51dwOfLyvnWo.zP/7g7RFZv2gq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:08', '2026-01-01 14:17:08', 0, NULL),
(283, 'Alikha Al Zahra', 'siswa00364', 'siswa00364@student.nurulilmi.id', NULL, '$2y$12$j/tKH8UKHtR5oU.rMRn.JusfYacYtMMsuPZgxs1RDIK92130CksXW', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:09', '2026-01-01 14:17:09', 0, NULL),
(284, 'Aliyah Dzakiyah Uzma', 'siswa00365', 'siswa00365@student.nurulilmi.id', NULL, '$2y$12$1Moh7XNHcVq1C3aPJ4pufOl.kuvyvBuMVJYy.WSYYpGj4ZGx.kWyK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:09', '2026-01-01 14:17:09', 0, NULL),
(285, 'Apta Arsenio Baskoro', 'siswa00367', 'siswa00367@student.nurulilmi.id', NULL, '$2y$12$M1fWUPnIsvTuMXE8m5kMjeuMfdiFvObfLT9HDEuUeLage5DcE5Gke', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:10', '2026-01-01 14:17:10', 0, NULL),
(286, 'Aqilla Zhafirah Arrayan', 'siswa00368', 'siswa00368@student.nurulilmi.id', NULL, '$2y$12$/g2/.f4QI7GCkondpgEv0eMq9O1nbFDMy2cp10TyPbLkZsY2u6IHu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:10', '2026-01-01 14:17:10', 0, NULL),
(287, 'Balqis Diandratiqah Widuri', 'siswa00369', 'siswa00369@student.nurulilmi.id', NULL, '$2y$12$JBGolbI3hKj.s8nzM5d/Z.i.B9GafR9jHX/pRnIi8N5qAoryOwqNq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:11', '2026-01-01 14:17:11', 0, NULL),
(288, 'Deki Apriansyah', 'siswa00370', 'siswa00370@student.nurulilmi.id', NULL, '$2y$12$sU.FQ8JJqEBU91KNC0LExelyNR4O79eeaGUX4qRyKOrL/1E/v4OGu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:11', '2026-01-01 14:17:11', 0, NULL),
(289, 'Donita Queenzha Salsabilah', 'siswa00372', 'siswa00372@student.nurulilmi.id', NULL, '$2y$12$0D4bHpa7EdZemS9M6.SEcOHg2jDqkFstiyufSQQ7S6KKFPW8Nzfny', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:12', '2026-01-01 14:17:12', 0, NULL),
(290, 'Ferina Luthfia Balqis', 'siswa00373', 'siswa00373@student.nurulilmi.id', NULL, '$2y$12$B/uPJClh2uJv.KVuxUUMcOYpE.BBfvaWw638a/WUF/a2X/3z7c9PS', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:12', '2026-01-01 14:17:12', 0, NULL),
(291, 'Ghali Azka Abdillah', 'siswa00374', 'siswa00374@student.nurulilmi.id', NULL, '$2y$12$x79f.J5i4IboyBByfEsKse/btkiDbQqQuxsp4uTUSPtyGEXart3hy', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:13', '2026-01-01 14:17:13', 0, NULL),
(292, 'Gilang Adhyastha Susanto', 'siswa00375', 'siswa00375@student.nurulilmi.id', NULL, '$2y$12$s2Yd8LybpHO4yFMwdmi90uqii8CxKZgwx3WQer1nCzPGlin7HVkXK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:13', '2026-01-01 14:17:13', 0, NULL),
(293, 'Hanun Khairunnisa', 'siswa00376', 'siswa00376@student.nurulilmi.id', NULL, '$2y$12$jQZxIDlFgejoWuOafHp8S.R8vR50/7R8dlY4rofTvl2T/zHiz6vJy', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:14', '2026-01-01 14:17:14', 0, NULL),
(294, 'Harwanto', 'siswa00377', 'siswa00377@student.nurulilmi.id', NULL, '$2y$12$fm7Z2plZdxPY2dbAjfF2w.wjYG5pQRpcYBAaZ2b1XLib3rx.22NRe', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:14', '2026-01-01 14:17:14', 0, NULL),
(295, 'Husna Najwa Kinara', 'siswa00378', 'siswa00378@student.nurulilmi.id', NULL, '$2y$12$VPZ/Gw9BayVAa/2QlgEn/OEmqxx5oAaZZqvjTH02Buq8k0iyLEVFC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:14', '2026-01-01 14:17:14', 0, NULL),
(296, 'Intan Sabrina', 'siswa00379', 'siswa00379@student.nurulilmi.id', NULL, '$2y$12$NiYqdbnq7bEdTbIswOiXbO/J0cpZ3P5JRFL3jHJ5qL8yBvjq3lVhu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:15', '2026-01-01 14:17:15', 0, NULL),
(297, 'Keyla Assyifa Maharani', 'siswa00380', 'siswa00380@student.nurulilmi.id', NULL, '$2y$12$V9ozdswUrUQ/U1EYurhS1uT5IZpW/BeSIsf1pLOd2.fvbMkhT089u', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:15', '2026-01-01 14:17:15', 0, NULL),
(298, 'Khansaa Zakiy Nabiilah', 'siswa00381', 'siswa00381@student.nurulilmi.id', NULL, '$2y$12$eFt2eUbYH98fBRCPCgGO0.wgUoZj0dQ7bCkScn0IshtIE6ieGpYYC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:16', '2026-01-01 14:17:16', 0, NULL),
(299, 'Kiandra Anaia Putri Wardani', 'siswa00382', 'siswa00382@student.nurulilmi.id', NULL, '$2y$12$qabR2OjFrFfZmfFX2sNcSO8RXOwnmVZeHh6Bt93JKih/weV5bxr.y', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:16', '2026-01-01 14:17:16', 0, NULL),
(300, 'Kinanti Auerelia Putri', 'siswa00383', 'siswa00383@student.nurulilmi.id', NULL, '$2y$12$ktH2bp4MMdOstHidPXRG/uJlHAAo2ditb1K8XR2YBW9AXAnyWic5q', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:17', '2026-01-01 14:17:17', 0, NULL),
(301, 'M. Iqbal Alfiqi', 'siswa00384', 'siswa00384@student.nurulilmi.id', NULL, '$2y$12$Z6C/DR9C3VwyBtQrQK.G9erkqzhxVzn72PPKkUVq1ydcB87sUPXgm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:17', '2026-01-01 14:17:17', 0, NULL),
(302, 'M. Raihan Alfarizqi', 'siswa00385', 'siswa00385@student.nurulilmi.id', NULL, '$2y$12$6CgMsaHsK2MaGIdKpT7i3u6RooHiMiTd1uMMlRlQXLBkVGGKSFzZC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:18', '2026-01-01 14:17:18', 0, NULL),
(303, 'M. Tristan Alief Alrummi AD', 'siswa00386', 'siswa00386@student.nurulilmi.id', NULL, '$2y$12$Bo.Yf9AIeStQcPGnzPWyve7vRJn/qq8hYn.1D8pvIUi/M9uAqIGy.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:18', '2026-01-01 14:17:18', 0, NULL),
(304, 'Mahdiyah Rafika', 'siswa00387', 'siswa00387@student.nurulilmi.id', NULL, '$2y$12$VLpnij/5eHO9tfducWL3dumWA8fD9FMDwCnXQ10bvojrxB3SmF142', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:19', '2026-01-01 14:17:19', 0, NULL),
(305, 'M. Rizky Al-Ghazali', 'siswa00388', 'siswa00388@student.nurulilmi.id', NULL, '$2y$12$BXfgkZ6n65I.wGylKc1uwuSl9gHczYgh8L2beVM3TxBmA6ojVvNUi', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:19', '2026-01-01 14:17:19', 0, NULL),
(306, 'Melinda Juniaty', 'siswa00389', 'siswa00389@student.nurulilmi.id', NULL, '$2y$12$wum3BJvgjdrP3.u2UDjMROX9aU7XKpI7SK6uIptcOZ4vxkjQEpAkq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:20', '2026-01-01 14:17:20', 0, NULL),
(307, 'Michelle Anatacia', 'siswa00390', 'siswa00390@student.nurulilmi.id', NULL, '$2y$12$JgSpXuywp4KxUR9TBDDlPuplb5ahnRq4fbnkpT9IpezcUwQYAo99m', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:20', '2026-01-01 14:17:20', 0, NULL),
(308, 'Mubdi Baskoro', 'siswa00391', 'siswa00391@student.nurulilmi.id', NULL, '$2y$12$yxil.0NDtrkZtdXJJ7fsmO/0uj7BqRaJ1eGcMCx/FTESOzu2ioHhe', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:21', '2026-01-01 14:17:21', 0, NULL),
(309, 'Muhammad Azzam Dyas Adhipramana', 'siswa00392', 'siswa00392@student.nurulilmi.id', NULL, '$2y$12$WoB.wjgjamxmh2YHhw9dEeFQodpKpvNcgdk8tMdZ0aS5a6VkvaRNC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:21', '2026-01-01 14:17:21', 0, NULL),
(310, 'Muhammad Fadhil Alfahrizi', 'siswa00393', 'siswa00393@student.nurulilmi.id', NULL, '$2y$12$adcm6ALylGi.JvEDIuF1W.aeZKWQq.mxPXtoHju2Wytq7D.dyvdCq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:22', '2026-01-01 14:17:22', 0, NULL),
(311, 'Muhammad Izaan Althaf', 'siswa00394', 'siswa00394@student.nurulilmi.id', NULL, '$2y$12$BgGzGbYpyxHxGdDT4kufc.kBf0D1GOTJQvj8wPSFlqey5kEh0HtOW', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:22', '2026-01-01 14:17:22', 0, NULL),
(312, 'Mikhayla Dwi Ayumi', 'siswa00395', 'siswa00395@student.nurulilmi.id', NULL, '$2y$12$5k9AVATMlHpi6J7QrtnJ4uRzYYIUzPE/LIg6OwhA4uBAePSs8tDOy', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:23', '2026-01-01 14:17:23', 0, NULL),
(313, 'Muhammad Kevin Junior', 'siswa00396', 'siswa00396@student.nurulilmi.id', NULL, '$2y$12$XX6wkHYYXKYMFJl06zbN8e1vxH8scmw8bCuOokTxTe3y.rJJcpoJ6', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:23', '2026-01-01 14:17:23', 0, NULL),
(314, 'Muhammad Malka Haidar Firdaus', 'siswa00397', 'siswa00397@student.nurulilmi.id', NULL, '$2y$12$1CvwXmDw8mQOpSHTymKQCudNeYosmZnKlbDIFrBk2pKK3iB9nedhu', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:23', '2026-01-01 14:17:23', 0, NULL),
(315, 'Muhammad Syafiq Aesar', 'siswa00398', 'siswa00398@student.nurulilmi.id', NULL, '$2y$12$PkcA6dOA3djMf4NCOOhQKOi/Of10onWqaxHqhxkMbIq2QIHwT/XZ.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:24', '2026-01-01 14:17:24', 0, NULL),
(316, 'Muhammad Tuhfatul Azka', 'siswa00399', 'siswa00399@student.nurulilmi.id', NULL, '$2y$12$V3x2lOIJ2DCx.FCLvkHgueP2MzHBhwvCU4CEuRdVTit.5Oj.i7kpa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:24', '2026-01-01 14:17:24', 0, NULL),
(317, 'Nabila Aprilia', 'siswa00400', 'siswa00400@student.nurulilmi.id', NULL, '$2y$12$ppRXvlgQ2P3yMjY1TprnCOygYUuapdxDEfDVFR85BbpSk2t0bBqO2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:25', '2026-01-01 14:17:25', 0, NULL),
(318, 'Naila Kasi', 'siswa00401', 'siswa00401@student.nurulilmi.id', NULL, '$2y$12$M6dLhD2ukX6sOqpZ8vn8G.aSbRcqKso1ed70ntmfhWvBtAxvXQHlG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:25', '2026-01-01 14:17:25', 0, NULL),
(319, 'Najla Nur Qisya', 'siswa00402', 'siswa00402@student.nurulilmi.id', NULL, '$2y$12$Xs8VVtN48koQ7GGmqT3Tfuhv8iqzVHsIckFKlHKTDafl5FE.OO0rG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:26', '2026-01-01 14:17:26', 0, NULL),
(320, 'Neneng Nadya Herawati', 'siswa00403', 'siswa00403@student.nurulilmi.id', NULL, '$2y$12$ci2PbuCWuidEgwoJ0BU4MOPX9bOD018TFZPI06xQHampsKWX5Fzpi', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:26', '2026-01-01 14:17:26', 0, NULL),
(321, 'Nizham Dzaki Fatahillah', 'siswa00404', 'siswa00404@student.nurulilmi.id', NULL, '$2y$12$Q8B3TaGhQ3WA4y/7SN0KIu1Xcui7qCFxIKZt2DJ6FdJ0Lc7VC1WBW', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:27', '2026-01-01 14:17:27', 0, NULL),
(322, 'Nur Alif Zahran', 'siswa00405', 'siswa00405@student.nurulilmi.id', NULL, '$2y$12$Sr46aCgWhCWYurZQIgUB1uOqvdwrV1/9x7GafpkeF/DgcNHPlKAha', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:27', '2026-01-01 14:17:27', 0, NULL),
(323, 'Nur Rahman', 'siswa00406', 'siswa00406@student.nurulilmi.id', NULL, '$2y$12$K.OH3SjuQSIu55n08wJYO.zs3JR8/zYl6sqr72vGGPw98tFD79/gK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:28', '2026-01-01 14:17:28', 0, NULL),
(324, 'Rafa Prasetia', 'siswa00407', 'siswa00407@student.nurulilmi.id', NULL, '$2y$12$MxsSPEuk7lih5kdlkBKuMe1fGly.MSeUnjKGsyf/QtF8Beh5gtK/e', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:28', '2026-01-01 14:17:28', 0, NULL),
(325, 'Raina Fazila', 'siswa00408', 'siswa00408@student.nurulilmi.id', NULL, '$2y$12$jonSyi7zJ8zbzsjKXmr3te59If.izFn62uN9XgCKz1wb.2Xk3XEWK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:29', '2026-01-01 14:17:29', 0, NULL),
(326, 'Roro Ayu Safira Arini', 'siswa00409', 'siswa00409@student.nurulilmi.id', NULL, '$2y$12$2lJQ8A7fwU/elSEe1QTF4.GBFkocrEaEeDgaWUTghRKsGhkoDW.8m', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:29', '2026-01-01 14:17:29', 0, NULL),
(327, 'Seldi Putra Prayetno', 'siswa00410', 'siswa00410@student.nurulilmi.id', NULL, '$2y$12$leTbA6JdsJWZrQFT59pOhe9oGKMOyQtgtsACfl51.K53eABKH2YbK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:30', '2026-01-01 14:17:30', 0, NULL),
(328, 'Wahyu Putra Samudra', 'siswa00412', 'siswa00412@student.nurulilmi.id', NULL, '$2y$12$GYOfSuwzx8jjcLRTBkh8SuL6bQnX.VQbyG6Pdw476w98UgtJ0thLW', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:30', '2026-01-01 14:17:30', 0, NULL);
INSERT INTO `user_siswa` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `plain_password`, `status`, `photo`, `remember_token`, `created_at`, `updated_at`, `login_attempts`, `locked_at`) VALUES
(329, 'Zahid Raes Al Hidayat', 'siswa00413', 'siswa00413@student.nurulilmi.id', NULL, '$2y$12$6Jdvo0i04XwytY3zlXN84udlUP0m4jq68D4xG1DUNFK0jYoCcVn7.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:30', '2026-01-01 14:17:30', 0, NULL),
(330, 'Zaki Pratama', 'siswa00414', 'siswa00414@student.nurulilmi.id', NULL, '$2y$12$j0gbvF8gRDwVH6xwFaZkj.VGHfeWOeWiUGpadIJY49PE4F/e1NEIa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:31', '2026-01-01 14:17:31', 0, NULL),
(331, 'Hafizh Fadli Zharifa', 'siswa00415', 'siswa00415@student.nurulilmi.id', NULL, '$2y$12$ycrSwPgCYBB5gRPmoLEx9uSb0MBxx8G2rr.6qp1QrDVaagjvC8Iuq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:31', '2026-01-01 14:17:31', 0, NULL),
(332, 'Azrul Rizki Parindra', 'siswa00416', 'siswa00416@student.nurulilmi.id', NULL, '$2y$12$zDXN8oJCMeIctwSD5S5QveTgEN3O44xpqmVA7xNQ5JCdMqGzJESIK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:32', '2026-01-01 14:17:32', 0, NULL),
(333, 'Intan Zhafira Kalista', 'siswa00417', 'siswa00417@student.nurulilmi.id', NULL, '$2y$12$prV80j6iPswsBkXc/8uimuiz6kBkPe3.5W7xzql8I7cTLl2xFAxVC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:32', '2026-01-01 14:17:32', 0, NULL),
(334, 'Dea Bilqis Anisa Firly', 'siswa00419', 'siswa00419@student.nurulilmi.id', NULL, '$2y$12$6l6yTJ.QMcmIBGYKyn/Qq.3tpe685rpv4AOKfWSp7MQDIkWZZ6nUC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:33', '2026-01-01 14:17:33', 0, NULL),
(335, 'NAILA AYU PRISA', 'siswa00420', 'siswa00420@student.nurulilmi.id', NULL, '$2y$12$mXiZcjA5QYCPslhUKyBqVeBggvZQ9JHrRtrqzkUdnp3FUx.bChlVi', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:33', '2026-01-01 14:17:33', 0, NULL),
(336, 'Abdul Aziiz Saputra', 'siswa00421', 'siswa00421@student.nurulilmi.id', NULL, '$2y$12$CZxCG8zqVZe4OL4IzFtYaOFKbAxdzjmsBu4o4LnZmHLOBeM97Luga', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:34', '2026-01-01 14:17:34', 0, NULL),
(337, 'Aditya Nukman', 'siswa00422', 'siswa00422@student.nurulilmi.id', NULL, '$2y$12$B0OYJOWPKUkUilN1QsTW5egHWmq8cTTT3PeTpeK46OX6KwSEUpqeq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:34', '2026-01-01 14:17:34', 0, NULL),
(338, 'Afiqa Deya Nazhwa', 'siswa00423', 'siswa00423@student.nurulilmi.id', NULL, '$2y$12$EDD4AA6a5HkgueG89AEsDe/TNgd49W0X7Iq9jC586L47N.4k0PrJa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:35', '2026-01-01 14:17:35', 0, NULL),
(339, 'Aga Aglab Ibrahim Adinata AF', 'siswa00424', 'siswa00424@student.nurulilmi.id', NULL, '$2y$12$oJh4cRqVtlIiuNvjDtx6Zuz0phPLckQImZv6IuWCRN55RjhqfUou.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:35', '2026-01-01 14:17:35', 0, NULL),
(340, 'Aisyah Nur Zakiyah', 'siswa00425', 'siswa00425@student.nurulilmi.id', NULL, '$2y$12$BPytr1HWIJQm8tYOiikXX.8Yo1dBp/cwbieORnzFkCnKA9QeQjCAe', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:36', '2026-01-01 14:17:36', 0, NULL),
(341, 'Alif Ridwan', 'siswa00426', 'siswa00426@student.nurulilmi.id', NULL, '$2y$12$CFELiA8ur9avHUDEo9p9Jee1MNehUMR3Vs0ZGRXTgqqQaIeH2atWa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:36', '2026-01-01 14:17:36', 0, NULL),
(342, 'Alika Ainurohima', 'siswa00427', 'siswa00427@student.nurulilmi.id', NULL, '$2y$12$Pf0VVxrIRujFpqhagwAHTuXEo//3MmUr4K3m1deYgAEAFW36miEWO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:37', '2026-01-01 14:17:37', 0, NULL),
(343, 'Alya Salsabillah', 'siswa00428', 'siswa00428@student.nurulilmi.id', NULL, '$2y$12$zzLkiuKrG01Efv3ltcu5kOC4KcALouJ0fAZgiIlmV3JPYcnkHeJJa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:37', '2026-01-01 14:17:37', 0, NULL),
(344, 'Aqila Putri Rahmadani', 'siswa00429', 'siswa00429@student.nurulilmi.id', NULL, '$2y$12$xoWwsVUzNaTfr3.1uHiTDu/Gs5ogiqI4AYMfm.vPm8Oyb3kKHj3Ne', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:38', '2026-01-01 14:17:38', 0, NULL),
(345, 'Aqilah Nuri Khoirunnisa', 'siswa00430', 'siswa00430@student.nurulilmi.id', NULL, '$2y$12$fRhvWJeXRSL.qOl9cdhShOM56YvxsWnMZaaac8AiYg9sWQlox89aq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:38', '2026-01-01 14:17:38', 0, NULL),
(346, 'Aril Mahesa', 'siswa00431', 'siswa00431@student.nurulilmi.id', NULL, '$2y$12$x0AOz80hiWogfOYarg/Ig.dg67leOo76594oRQBMIgpmo4OZIJcla', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:38', '2026-01-01 14:17:38', 0, NULL),
(347, 'Assyifa Niswah', 'siswa00432', 'siswa00432@student.nurulilmi.id', NULL, '$2y$12$6XTZvNI3vmJQ2SG6ZLEnhuGZAZk6FKQc9hMe0s.IPj7x1RBPm9way', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:39', '2026-01-01 14:17:39', 0, NULL),
(348, 'Azhara Khairunnisa', 'siswa00433', 'siswa00433@student.nurulilmi.id', NULL, '$2y$12$MXtVdmbINvHmo7ooPcEAHuU9KS4gMWpwh2HJ0/qvt1g0TklRvcZPa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:39', '2026-01-01 14:17:39', 0, NULL),
(349, 'Belva Yuda Pratiwi', 'siswa00434', 'siswa00434@student.nurulilmi.id', NULL, '$2y$12$GCAx8mhI0ITHy2rsy2wFKuZF0elXrlIcAqf29r/8oi/E5OOvcxv6m', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:40', '2026-01-01 14:17:40', 0, NULL),
(350, 'David Villa Kesuma', 'siswa00435', 'siswa00435@student.nurulilmi.id', NULL, '$2y$12$i0zI0A83t4BF4k7iT.wCfegSnD52RF6RvRlH7VdKfBceqca2qQyPK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:40', '2026-01-01 14:17:40', 0, NULL),
(351, 'Delfia Say Denay', 'siswa00436', 'siswa00436@student.nurulilmi.id', NULL, '$2y$12$Hsz9QwRnEZQ9Ah6u2AdhYOLySZENp7GMoQLsxc1KvFUL6u2L0LAkq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:41', '2026-01-01 14:17:41', 0, NULL),
(352, 'Faizah Majida', 'siswa00437', 'siswa00437@student.nurulilmi.id', NULL, '$2y$12$MNor7MkT7wADqonL6DfY0.zmrs68LYN4ohs2Ffmhv6uW825FVwqxO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:41', '2026-01-01 14:17:41', 0, NULL),
(353, 'Hafidzah Azzahrah', 'siswa00438', 'siswa00438@student.nurulilmi.id', NULL, '$2y$12$C1yTL0CbCM2q9loEfF3.4uSJ.7czKYx5RWqseUm2XMb/sqEAzVpZq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:42', '2026-01-01 14:17:42', 0, NULL),
(354, 'Hakim Annabil', 'siswa00439', 'siswa00439@student.nurulilmi.id', NULL, '$2y$12$.CnGsNeMiwaspnn/edxu2u/sC7.Tgpn6wz2y.7rkyo1wYbVRGWfWO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:42', '2026-01-01 14:17:42', 0, NULL),
(355, 'Keysah Aqila Azzahra', 'siswa00440', 'siswa00440@student.nurulilmi.id', NULL, '$2y$12$W9CpDajUUjVPuV8wgNnQRefLtfz4aenGu4zpPL0I5KYWRsK0ThF5O', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:43', '2026-01-01 14:17:43', 0, NULL),
(356, 'Kia Florisa', 'siswa00441', 'siswa00441@student.nurulilmi.id', NULL, '$2y$12$u68JxKms0yzdgsYZR2PxE.weKjUhBO46ifcj2t2Pfkkswrac5ErTS', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:43', '2026-01-01 14:17:43', 0, NULL),
(357, 'M. Zain Al Rasyid', 'siswa00442', 'siswa00442@student.nurulilmi.id', NULL, '$2y$12$doTQHWy3xgxME2yO/4Alg.sao9ZnVzkaidCdVBvFV91HLMyBcKojq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:43', '2026-01-01 14:17:43', 0, NULL),
(358, 'Muhammad Rafie Wijaya', 'siswa00443', 'siswa00443@student.nurulilmi.id', NULL, '$2y$12$EWepeaycWyGL6CnZEGJNpe.0s3cpuB6DWMxuEka/XehhRIDbZVPl2', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:44', '2026-01-01 14:17:44', 0, NULL),
(359, 'Muhammad Abizam', 'siswa00444', 'siswa00444@student.nurulilmi.id', NULL, '$2y$12$QlzxU.qoP3ogbppOKc1rZOVX0YFWBSNMeUtKzmPxuRlD.YzMliIxe', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:44', '2026-01-01 14:17:44', 0, NULL),
(360, 'Muhammad Indra Saputra', 'siswa00445', 'siswa00445@student.nurulilmi.id', NULL, '$2y$12$EBPLlc9TX6xXPJHvzmpsT.80DRwEFK2M99uUV7CM.rnF66STd5Hd.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:45', '2026-01-01 14:17:45', 0, NULL),
(361, 'Muhammad Naufal', 'siswa00446', 'siswa00446@student.nurulilmi.id', NULL, '$2y$12$Wj5bKTSLBh3coSoEVPbeV.6Wn.Ip5wiChpEmeckzZ97.RtmzYcHmK', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:45', '2026-01-01 14:17:45', 0, NULL),
(362, 'Muhammad Naufal Arifqi', 'siswa00447', 'siswa00447@student.nurulilmi.id', NULL, '$2y$12$34sbpWlCW.fClHR0lVso8OOVn4nEzCuxo2j5.bnjVJIpdvmw9rxyG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:46', '2026-01-01 14:17:46', 0, NULL),
(363, 'Muhammad Padlan Paseka', 'siswa00448', 'siswa00448@student.nurulilmi.id', NULL, '$2y$12$mNugENw.Xe/1ihs5TfkQMOwFOIGUycTXeNE4aE3Z7kQPXU4ElteeG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:46', '2026-01-01 14:17:46', 0, NULL),
(364, 'Nadhifah Shaki Kenedy', 'siswa00449', 'siswa00449@student.nurulilmi.id', NULL, '$2y$12$3Y6Y..YUXW5vsb0dNLNJHeVaXrEv/nDiOXOgfptOT5J1co.9W7yWG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:47', '2026-01-01 14:17:47', 0, NULL),
(365, 'Najwa Khaira Nadhifa', 'siswa00450', 'siswa00450@student.nurulilmi.id', NULL, '$2y$12$fllqbVMNnq//0n1LdhRc4.W7NpoEJCjjchuW16CRdNJltACu8Tw7m', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:47', '2026-01-01 14:17:47', 0, NULL),
(366, 'Nanda Dzakiyah Aftani', 'siswa00451', 'siswa00451@student.nurulilmi.id', NULL, '$2y$12$pd4V/10ybu940tNm/1TMsOHlcHIllOuLpfHh6.V1sorevLvcL.Dhq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:48', '2026-01-01 14:17:48', 0, NULL),
(367, 'Nila Shofiyatul Awaliyah', 'siswa00452', 'siswa00452@student.nurulilmi.id', NULL, '$2y$12$KNiu5TXtjLjxgz3W.dD/0uDHAns.VqsKhmqBGkD2Uotstws/Nq2ly', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:48', '2026-01-01 14:17:48', 0, NULL),
(368, 'Nur\'aini', 'siswa00453', 'siswa00453@student.nurulilmi.id', NULL, '$2y$12$AdQuDVG8CXKN3IPqpR6Oye9xkxRi2cAfrHd4PMPxruEqJ1TlTZKCm', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:49', '2026-01-01 14:17:49', 0, NULL),
(369, 'Putri Lucingga', 'siswa00454', 'siswa00454@student.nurulilmi.id', NULL, '$2y$12$uFW0lLUiNh8IYgDAPMvFL.S/yRLf4DwDEiSMv4FdZc8wAj/55wLUG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:49', '2026-01-01 14:17:49', 0, NULL),
(370, 'Ramadhan Khairu Nugraha', 'siswa00455', 'siswa00455@student.nurulilmi.id', NULL, '$2y$12$m4lDeqgGKWausq1GjXzjk.KD457wrVB/p0436itHShAi1e/y3Wj8.', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:50', '2026-01-01 14:17:50', 0, NULL),
(371, 'Reyvan Daffa Saputra', 'siswa00456', 'siswa00456@student.nurulilmi.id', NULL, '$2y$12$VpHmiPi6IIANh6INVvcTcul70eKSvPzegLN.TTeouPMnozK44w0ba', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:50', '2026-01-01 14:17:50', 0, NULL),
(372, 'Shifa Shauqiyah', 'siswa00457', 'siswa00457@student.nurulilmi.id', NULL, '$2y$12$EoHwacBmH1gW2PYcmqi10uX4BK4WXWhAv.aq4MZyv2zKsyj68NBFO', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:51', '2026-01-01 14:17:51', 0, NULL),
(373, 'Syifatun Jannah', 'siswa00458', 'siswa00458@student.nurulilmi.id', NULL, '$2y$12$qWphOfOY0y8AkvIcfXhjYuU777b4C.ak9trc1fRIiEh1w1JysVl1u', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:51', '2026-01-01 14:17:51', 0, NULL),
(374, 'Wira Ananta Rudira Karos', 'siswa00459', 'siswa00459@student.nurulilmi.id', NULL, '$2y$12$n8ezEnUob7matZleTRzXj.rTCcjmK4xPd97Opc0VjXDzd966bRA9q', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:51', '2026-01-01 14:17:51', 0, NULL),
(375, 'Yafiza Fitria Azzahra', 'siswa00460', 'siswa00460@student.nurulilmi.id', NULL, '$2y$12$/50eIWhyN38oh9Gba8WAdO5aHjJZOiTg9zpmvXYIgOafmTVtwGP2K', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:52', '2026-01-01 14:17:52', 0, NULL),
(376, 'Yusuf Efendi Harahap', 'siswa00461', 'siswa00461@student.nurulilmi.id', NULL, '$2y$12$kaMUtU8Ti9pvSgEZbYx4HuCpRze2kkUzwFANjUNHiRMRJI.m0NqXG', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:52', '2026-01-01 14:17:52', 0, NULL),
(377, 'Zahra Alya Naima', 'siswa00462', 'siswa00462@student.nurulilmi.id', NULL, '$2y$12$IulvKJhy1zx0X3j7aW6WhukS2MpFfByDnbuwaNPFLoF/XuENMjIt6', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:53', '2026-01-01 14:17:53', 0, NULL),
(378, 'Zaskia Salsabila', 'siswa00463', 'siswa00463@student.nurulilmi.id', NULL, '$2y$12$Uy8/c4lTX8oTW8Glm8p0uOe7dQvWvBZkV6iYQ3uDEH82nMnxFzoYq', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:53', '2026-01-01 14:17:53', 0, NULL),
(379, 'Zio Rizky Alvaro', 'siswa00464', 'siswa00464@student.nurulilmi.id', NULL, '$2y$12$k72937KmsbIkjiUwozSjS.e7UxwsfH7gJ0Mwv8k7UaYnIQy/VaqUa', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:54', '2026-01-01 14:17:54', 0, NULL),
(380, 'Zulaikha Zhafira', 'siswa00465', 'siswa00465@student.nurulilmi.id', NULL, '$2y$12$ZafolzzX.qd4igd4ON1U7OmRyOZE8p69AgSebgiuk7GdbljLdHsni', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:54', '2026-01-01 14:17:54', 0, NULL),
(381, 'Reyhan Saputra', 'siswa00466', 'siswa00466@student.nurulilmi.id', NULL, '$2y$12$AJ.1ws92ZqUzEZF1aCqU4usAlOp3rLrnTVYcAbcfDmgqkFwqxuDtC', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:55', '2026-01-01 14:17:55', 0, NULL),
(382, 'Alverina Zahra Kirana', 'siswa0418', 'siswa0418@student.nurulilmi.id', NULL, '$2y$12$BtbpnC9Vxz.LRmf8m.mWXuLru2Oi9iBU8SgPN8Xly5XuNV.PSCy06', 'siswa123', 'aktif', NULL, NULL, '2026-01-01 14:17:55', '2026-01-01 14:17:55', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_calendars`
--
ALTER TABLE `academic_calendars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_calendars_date_unit_id_unique` (`date`,`unit_id`),
  ADD KEY `academic_calendars_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_years_start_year_end_year_unique` (`start_year`,`end_year`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_unit_id_foreign` (`unit_id`),
  ADD KEY `classes_teacher_id_foreign` (`teacher_id`),
  ADD KEY `classes_student_leader_id_foreign` (`student_leader_id`),
  ADD KEY `classes_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `class_announcements`
--
ALTER TABLE `class_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_announcements_class_id_foreign` (`class_id`),
  ADD KEY `class_announcements_user_id_foreign` (`user_id`);

--
-- Indexes for table `class_checkins`
--
ALTER TABLE `class_checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_checkins_schedule_id_foreign` (`schedule_id`),
  ADD KEY `class_checkins_user_id_foreign` (`user_id`);

--
-- Indexes for table `class_student`
--
ALTER TABLE `class_student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_student_student_id_class_id_unique` (`student_id`,`class_id`),
  ADD UNIQUE KEY `student_academic_year_unique` (`student_id`,`academic_year_id`),
  ADD KEY `class_student_class_id_foreign` (`class_id`),
  ADD KEY `class_student_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `consumables`
--
ALTER TABLE `consumables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consumables_inventory_category_id_foreign` (`inventory_category_id`),
  ADD KEY `consumables_unit_id_foreign` (`unit_id`),
  ADD KEY `consumables_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `consumable_transactions`
--
ALTER TABLE `consumable_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consumable_transactions_consumable_id_foreign` (`consumable_id`),
  ADD KEY `consumable_transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `damage_reports`
--
ALTER TABLE `damage_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `damage_reports_inventory_id_foreign` (`inventory_id`),
  ADD KEY `damage_reports_user_id_foreign` (`user_id`),
  ADD KEY `damage_reports_principal_id_foreign` (`principal_id`),
  ADD KEY `damage_reports_director_id_foreign` (`director_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_categories_name_unique` (`name`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_items_income_expense_id_foreign` (`income_expense_id`);

--
-- Indexes for table `extracurriculars`
--
ALTER TABLE `extracurriculars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extracurriculars_unit_id_foreign` (`unit_id`),
  ADD KEY `extracurriculars_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `extracurricular_members`
--
ALTER TABLE `extracurricular_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extracurricular_members_extracurricular_id_foreign` (`extracurricular_id`),
  ADD KEY `extracurricular_members_student_id_foreign` (`student_id`),
  ADD KEY `extracurricular_members_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `extracurricular_reports`
--
ALTER TABLE `extracurricular_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extracurricular_reports_extracurricular_id_foreign` (`extracurricular_id`),
  ADD KEY `extracurricular_reports_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `graduation_announcements`
--
ALTER TABLE `graduation_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `graduation_announcements_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `graduation_announcements_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `income_categories_name_unique` (`name`);

--
-- Indexes for table `income_expenses`
--
ALTER TABLE `income_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_expenses_unit_id_foreign` (`unit_id`),
  ADD KEY `income_expenses_user_id_foreign` (`user_id`),
  ADD KEY `income_expenses_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventories_code_unique` (`code`),
  ADD KEY `inventories_inventory_category_id_foreign` (`inventory_category_id`),
  ADD KEY `inventories_room_id_foreign` (`room_id`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_categories_unit_id_foreign` (`unit_id`),
  ADD KEY `inventory_categories_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_logs_inventory_id_foreign` (`inventory_id`),
  ADD KEY `inventory_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jabatans_unit_id_kode_jabatan_unique` (`unit_id`,`kode_jabatan`);

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
-- Indexes for table `login_histories`
--
ALTER TABLE `login_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_histories_user_id_foreign` (`user_id`),
  ADD KEY `login_histories_user_siswa_id_foreign` (`user_siswa_id`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_unit_id_foreign` (`unit_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_requests_reference_code_unique` (`reference_code`),
  ADD KEY `payment_requests_student_id_foreign` (`student_id`),
  ADD KEY `payment_requests_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `payment_requests_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `payment_request_items`
--
ALTER TABLE `payment_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_request_items_payment_request_id_foreign` (`payment_request_id`),
  ADD KEY `payment_request_items_student_bill_id_foreign` (`student_bill_id`);

--
-- Indexes for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_types_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `procurement_requests`
--
ALTER TABLE `procurement_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procurement_requests_unit_id_foreign` (`unit_id`),
  ADD KEY `procurement_requests_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `procurement_requests_user_id_foreign` (`user_id`),
  ADD KEY `procurement_requests_inventory_category_id_foreign` (`inventory_category_id`),
  ADD KEY `procurement_requests_request_code_index` (`request_code`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipts_unit_id_foreign` (`unit_id`),
  ADD KEY `receipts_user_id_foreign` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_unit_id_foreign` (`unit_id`),
  ADD KEY `rooms_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_types_unit_id_name_unique` (`unit_id`,`name`),
  ADD KEY `room_types_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_unit_id_foreign` (`unit_id`),
  ADD KEY `schedules_subject_id_foreign` (`subject_id`),
  ADD KEY `schedules_class_id_day_index` (`class_id`,`day`),
  ADD KEY `schedules_user_id_day_index` (`user_id`,`day`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semesters_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_nis_unique` (`nis`),
  ADD UNIQUE KEY `students_nisn_unique` (`nisn`),
  ADD KEY `students_unit_id_foreign` (`unit_id`),
  ADD KEY `students_user_siswa_id_foreign` (`user_siswa_id`),
  ADD KEY `students_class_id_foreign` (`class_id`);

--
-- Indexes for table `student_achievements`
--
ALTER TABLE `student_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_achievements_student_id_foreign` (`student_id`),
  ADD KEY `student_achievements_recorded_by_foreign` (`recorded_by`),
  ADD KEY `student_achievements_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_attendances_student_id_date_unique` (`student_id`,`date`),
  ADD KEY `student_attendances_class_id_foreign` (`class_id`),
  ADD KEY `student_attendances_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `student_attendances_created_by_foreign` (`created_by`);

--
-- Indexes for table `student_bills`
--
ALTER TABLE `student_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_bill_unique` (`student_id`,`payment_type_id`,`academic_year_id`,`month`),
  ADD KEY `student_bills_payment_type_id_foreign` (`payment_type_id`),
  ADD KEY `student_bills_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `student_graduation_results`
--
ALTER TABLE `student_graduation_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_announcement_student` (`graduation_announcement_id`,`student_id`),
  ADD KEY `student_graduation_results_student_id_foreign` (`student_id`);

--
-- Indexes for table `student_payment_settings`
--
ALTER TABLE `student_payment_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stud_pay_set_month_unique` (`student_id`,`payment_type_id`,`academic_year_id`,`month`),
  ADD KEY `student_payment_settings_payment_type_id_foreign` (`payment_type_id`),
  ADD KEY `student_payment_settings_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `student_violations`
--
ALTER TABLE `student_violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_violations_student_id_foreign` (`student_id`),
  ADD KEY `student_violations_recorded_by_foreign` (`recorded_by`),
  ADD KEY `student_violations_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_unit_id_code_unique` (`unit_id`,`code`);

--
-- Indexes for table `supervisions`
--
ALTER TABLE `supervisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supervisions_unit_id_foreign` (`unit_id`),
  ADD KEY `supervisions_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `supervisions_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `supervisions_teacher_id_foreign` (`teacher_id`),
  ADD KEY `supervisions_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `teacher_document_requests`
--
ALTER TABLE `teacher_document_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_document_requests_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `teacher_document_requests_created_by_foreign` (`created_by`);

--
-- Indexes for table `teacher_document_submissions`
--
ALTER TABLE `teacher_document_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_document_submissions_request_id_foreign` (`request_id`),
  ADD KEY `teacher_document_submissions_user_id_foreign` (`user_id`),
  ADD KEY `teacher_document_submissions_validated_by_foreign` (`validated_by`),
  ADD KEY `teacher_document_submissions_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `teaching_assignments`
--
ALTER TABLE `teaching_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment_year` (`user_id`,`subject_id`,`class_id`,`academic_year_id`),
  ADD KEY `teaching_assignments_subject_id_foreign` (`subject_id`),
  ADD KEY `teaching_assignments_class_id_foreign` (`class_id`),
  ADD KEY `teaching_assignments_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_slots_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_unit_id_foreign` (`unit_id`),
  ADD KEY `transactions_student_id_foreign` (`student_id`),
  ADD KEY `transactions_payment_type_id_foreign` (`payment_type_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_items_payment_type_id_foreign` (`payment_type_id`),
  ADD KEY `transaction_items_student_bill_id_foreign` (`student_bill_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `user_jabatan_units`
--
ALTER TABLE `user_jabatan_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_jab_unit_unique` (`user_id`,`jabatan_id`,`unit_id`),
  ADD KEY `user_jabatan_units_jabatan_id_foreign` (`jabatan_id`),
  ADD KEY `user_jabatan_units_unit_id_foreign` (`unit_id`),
  ADD KEY `user_jabatan_units_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `user_siswa`
--
ALTER TABLE `user_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_siswa_email_unique` (`email`),
  ADD UNIQUE KEY `user_siswa_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_calendars`
--
ALTER TABLE `academic_calendars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `class_announcements`
--
ALTER TABLE `class_announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `class_checkins`
--
ALTER TABLE `class_checkins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `class_student`
--
ALTER TABLE `class_student`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT for table `consumables`
--
ALTER TABLE `consumables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `consumable_transactions`
--
ALTER TABLE `consumable_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `damage_reports`
--
ALTER TABLE `damage_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `extracurriculars`
--
ALTER TABLE `extracurriculars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `extracurricular_members`
--
ALTER TABLE `extracurricular_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `extracurricular_reports`
--
ALTER TABLE `extracurricular_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `graduation_announcements`
--
ALTER TABLE `graduation_announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `income_expenses`
--
ALTER TABLE `income_expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_histories`
--
ALTER TABLE `login_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_requests`
--
ALTER TABLE `payment_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment_request_items`
--
ALTER TABLE `payment_request_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payment_types`
--
ALTER TABLE `payment_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `procurement_requests`
--
ALTER TABLE `procurement_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=374;

--
-- AUTO_INCREMENT for table `student_achievements`
--
ALTER TABLE `student_achievements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_attendances`
--
ALTER TABLE `student_attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `student_bills`
--
ALTER TABLE `student_bills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1141;

--
-- AUTO_INCREMENT for table `student_graduation_results`
--
ALTER TABLE `student_graduation_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `student_payment_settings`
--
ALTER TABLE `student_payment_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=962;

--
-- AUTO_INCREMENT for table `student_violations`
--
ALTER TABLE `student_violations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `supervisions`
--
ALTER TABLE `supervisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `teacher_document_requests`
--
ALTER TABLE `teacher_document_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `teacher_document_submissions`
--
ALTER TABLE `teacher_document_submissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `teaching_assignments`
--
ALTER TABLE `teaching_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `user_jabatan_units`
--
ALTER TABLE `user_jabatan_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `user_siswa`
--
ALTER TABLE `user_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=383;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_calendars`
--
ALTER TABLE `academic_calendars`
  ADD CONSTRAINT `academic_calendars_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_student_leader_id_foreign` FOREIGN KEY (`student_leader_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_announcements`
--
ALTER TABLE `class_announcements`
  ADD CONSTRAINT `class_announcements_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_checkins`
--
ALTER TABLE `class_checkins`
  ADD CONSTRAINT `class_checkins_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_checkins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_student`
--
ALTER TABLE `class_student`
  ADD CONSTRAINT `class_student_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_student_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `consumables`
--
ALTER TABLE `consumables`
  ADD CONSTRAINT `consumables_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consumables_inventory_category_id_foreign` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consumables_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `consumable_transactions`
--
ALTER TABLE `consumable_transactions`
  ADD CONSTRAINT `consumable_transactions_consumable_id_foreign` FOREIGN KEY (`consumable_id`) REFERENCES `consumables` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consumable_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `damage_reports`
--
ALTER TABLE `damage_reports`
  ADD CONSTRAINT `damage_reports_director_id_foreign` FOREIGN KEY (`director_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `damage_reports_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `damage_reports_principal_id_foreign` FOREIGN KEY (`principal_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `damage_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD CONSTRAINT `expense_items_income_expense_id_foreign` FOREIGN KEY (`income_expense_id`) REFERENCES `income_expenses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `extracurriculars`
--
ALTER TABLE `extracurriculars`
  ADD CONSTRAINT `extracurriculars_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extracurriculars_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `extracurricular_members`
--
ALTER TABLE `extracurricular_members`
  ADD CONSTRAINT `extracurricular_members_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extracurricular_members_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extracurricular_members_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `extracurricular_reports`
--
ALTER TABLE `extracurricular_reports`
  ADD CONSTRAINT `extracurricular_reports_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extracurricular_reports_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `graduation_announcements`
--
ALTER TABLE `graduation_announcements`
  ADD CONSTRAINT `graduation_announcements_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `graduation_announcements_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `income_expenses`
--
ALTER TABLE `income_expenses`
  ADD CONSTRAINT `income_expenses_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `income_expenses_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_inventory_category_id_foreign` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventories_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD CONSTRAINT `inventory_categories_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_categories_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD CONSTRAINT `jabatans_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_histories`
--
ALTER TABLE `login_histories`
  ADD CONSTRAINT `login_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `login_histories_user_siswa_id_foreign` FOREIGN KEY (`user_siswa_id`) REFERENCES `user_siswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD CONSTRAINT `payment_requests_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_requests_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_request_items`
--
ALTER TABLE `payment_request_items`
  ADD CONSTRAINT `payment_request_items_payment_request_id_foreign` FOREIGN KEY (`payment_request_id`) REFERENCES `payment_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_request_items_student_bill_id_foreign` FOREIGN KEY (`student_bill_id`) REFERENCES `student_bills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD CONSTRAINT `payment_types_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `procurement_requests`
--
ALTER TABLE `procurement_requests`
  ADD CONSTRAINT `procurement_requests_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `procurement_requests_inventory_category_id_foreign` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories` (`id`),
  ADD CONSTRAINT `procurement_requests_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `procurement_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rooms_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_types`
--
ALTER TABLE `room_types`
  ADD CONSTRAINT `room_types_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_types_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_user_siswa_id_foreign` FOREIGN KEY (`user_siswa_id`) REFERENCES `user_siswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_achievements`
--
ALTER TABLE `student_achievements`
  ADD CONSTRAINT `student_achievements_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_achievements_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD CONSTRAINT `student_attendances_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_attendances_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_attendances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_bills`
--
ALTER TABLE `student_bills`
  ADD CONSTRAINT `student_bills_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_bills_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_bills_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_graduation_results`
--
ALTER TABLE `student_graduation_results`
  ADD CONSTRAINT `student_graduation_results_graduation_announcement_id_foreign` FOREIGN KEY (`graduation_announcement_id`) REFERENCES `graduation_announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_graduation_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_payment_settings`
--
ALTER TABLE `student_payment_settings`
  ADD CONSTRAINT `student_payment_settings_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_payment_settings_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_payment_settings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_violations`
--
ALTER TABLE `student_violations`
  ADD CONSTRAINT `student_violations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_violations_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_violations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisions`
--
ALTER TABLE `supervisions`
  ADD CONSTRAINT `supervisions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supervisions_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_document_requests`
--
ALTER TABLE `teacher_document_requests`
  ADD CONSTRAINT `teacher_document_requests_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teacher_document_requests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `teacher_document_submissions`
--
ALTER TABLE `teacher_document_submissions`
  ADD CONSTRAINT `teacher_document_submissions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teacher_document_submissions_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `teacher_document_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_document_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teacher_document_submissions_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teaching_assignments`
--
ALTER TABLE `teaching_assignments`
  ADD CONSTRAINT `teaching_assignments_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teaching_assignments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teaching_assignments_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teaching_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD CONSTRAINT `time_slots_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_student_bill_id_foreign` FOREIGN KEY (`student_bill_id`) REFERENCES `student_bills` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_jabatan_units`
--
ALTER TABLE `user_jabatan_units`
  ADD CONSTRAINT `user_jabatan_units_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_jabatan_units_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_jabatan_units_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_jabatan_units_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
