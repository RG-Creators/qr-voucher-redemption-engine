-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 25, 2026 at 03:41 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scanner`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `last_login`, `created_at`) VALUES
(1, 'tanush_admin', '$2y$10$zIPkvxVndQ6UTqlWVSMaLuXHuk5Uvi.Jo8xCff8x5rQtB/uDMNHaG', '2026-02-25 09:10:30', '2026-02-23 17:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `aadhaar` char(12) NOT NULL,
  `qr_token` char(64) NOT NULL,
  `qr_image` varchar(255) DEFAULT NULL,
  `is_redeemed` tinyint(1) NOT NULL DEFAULT '0',
  `redeemed_at` datetime DEFAULT NULL,
  `redeemed_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_token` (`qr_token`),
  UNIQUE KEY `aadhaar` (`aadhaar`),
  KEY `idx_qr_token` (`qr_token`),
  KEY `idx_phone` (`phone`),
  KEY `idx_redeemed` (`is_redeemed`),
  KEY `fk_redeemed_admin` (`redeemed_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `aadhaar`, `qr_token`, `qr_image`, `is_redeemed`, `redeemed_at`, `redeemed_by`, `created_at`) VALUES
(1, 'Dummy Data', '9876543210', '98765432100', '39dbe5431c12953e00ae0aa64aa183da70a83767c8419e5239a1a706d2f53386', 'qr_39dbe5431c12953e00ae0aa64aa183da70a83767c8419e5239a1a706d2f53386.png', 1, NULL, NULL, '2026-02-24 15:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `redemption_logs`
--

DROP TABLE IF EXISTS `redemption_logs`;
CREATE TABLE IF NOT EXISTS `redemption_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int UNSIGNED NOT NULL,
  `admin_id` int UNSIGNED NOT NULL,
  `redeemed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_customer` (`customer_id`),
  KEY `idx_admin` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_redeemed_admin` FOREIGN KEY (`redeemed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `redemption_logs`
--
ALTER TABLE `redemption_logs`
  ADD CONSTRAINT `fk_log_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_log_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
