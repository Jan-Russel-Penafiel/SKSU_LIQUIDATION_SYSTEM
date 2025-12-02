-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 09:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sksu_liquid`
--

-- --------------------------------------------------------

--
-- Table structure for table `atm_liquidations`
--

CREATE TABLE `atm_liquidations` (
  `id` int(11) UNSIGNED NOT NULL,
  `batch_name` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('csv','excel') NOT NULL,
  `uploaded_by` int(11) UNSIGNED NOT NULL,
  `total_records` int(11) DEFAULT 0,
  `processed_records` int(11) DEFAULT 0,
  `semester` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `status` enum('uploaded','processing','processed','sent_to_chairman','approved','sent_to_accounting','completed','rejected','verified') DEFAULT 'uploaded',
  `chairman_approval_date` datetime DEFAULT NULL,
  `accounting_received_date` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atm_liquidations`
--

INSERT INTO `atm_liquidations` (`id`, `batch_name`, `file_path`, `file_type`, `uploaded_by`, `total_records`, `processed_records`, `semester`, `academic_year`, `status`, `chairman_approval_date`, `accounting_received_date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'asda', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598985_dd88b4f59e76479fa7c2.csv', 'csv', 1, 0, 0, '2nd Semester', '2023-2024', 'uploaded', NULL, NULL, NULL, '2025-12-01 14:23:05', '2025-12-01 14:23:05'),
(2, 'asda', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764642502_a1cebe659c1cf683aea7.csv', 'csv', 1, 3, 3, '1st Semester', '2023-2024', 'completed', NULL, '2025-12-02 05:42:53', 'asda', '2025-12-02 02:28:22', '2025-12-02 05:43:25'),
(3, 'mvjhvj', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657609_eb5050152b7351d6fa8b.csv', 'csv', 1, 3, 3, '2nd Semester', '2024-2025', 'verified', NULL, NULL, 'bm', '2025-12-02 06:40:09', '2025-12-02 06:40:10'),
(4, 'asdadaa ngfnfgnf', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657646_89368e706d36e77ef85b.csv', 'csv', 1, 3, 3, '2nd Semester', '2023-2024', 'completed', NULL, '2025-12-02 06:41:37', '', '2025-12-02 06:40:46', '2025-12-02 06:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `atm_liquidation_details`
--

CREATE TABLE `atm_liquidation_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `atm_liquidation_id` int(11) UNSIGNED DEFAULT NULL,
  `recipient_id` int(11) UNSIGNED NOT NULL,
  `transaction_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` enum('csv','excel','pdf') DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `verified_by` int(11) UNSIGNED DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `approved_by` int(11) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `accounting_received_date` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending','verified','approved','rejected','sent_to_accounting','completed') DEFAULT 'pending',
  `semester` varchar(20) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atm_liquidation_details`
--

INSERT INTO `atm_liquidation_details` (`id`, `atm_liquidation_id`, `recipient_id`, `transaction_date`, `amount`, `reference_number`, `file_path`, `file_type`, `created_by`, `verified_by`, `verified_at`, `approved_by`, `approved_at`, `accounting_received_date`, `completed_at`, `remarks`, `status`, `semester`, `academic_year`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '2025-12-01', 21313131.00, '123131', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764597582_13927bd3801c6cfc7dd6.pdf', 'pdf', 1, 1, '2025-12-01 22:05:45', NULL, NULL, NULL, NULL, 'asda', 'verified', '1st Semester', '2023-2024', '2025-12-01 13:59:42', '2025-12-01 13:59:42'),
(2, NULL, 1, '2025-12-01', 5000.00, 'REF-12345', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598523_5b1fd9f8e254e3bb4446.csv', 'csv', 1, 1, '2025-12-01 14:15:23', NULL, NULL, NULL, NULL, '', 'verified', '1st Semester', '2023-2024', '2025-12-01 14:15:23', '2025-12-01 14:15:23'),
(3, NULL, 2, '2025-12-01', 5000.00, 'REF-12346', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598523_5b1fd9f8e254e3bb4446.csv', 'csv', 1, 1, '2025-12-01 14:15:23', NULL, NULL, NULL, NULL, '', 'verified', '1st Semester', '2023-2024', '2025-12-01 14:15:23', '2025-12-01 14:15:23'),
(4, NULL, 3, '2025-12-01', 5000.00, 'REF-12347', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598523_5b1fd9f8e254e3bb4446.csv', 'csv', 1, 1, '2025-12-01 14:15:23', NULL, NULL, NULL, NULL, '', 'verified', '1st Semester', '2023-2024', '2025-12-01 14:15:23', '2025-12-01 14:15:23'),
(5, NULL, 1, '2025-12-01', 5000.00, 'REF-12345', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598847_8b9e353e0e0f68e20ff4.csv', 'csv', 1, 1, '2025-12-01 14:20:48', NULL, NULL, NULL, NULL, 'asda', 'verified', '1st Semester', '2024-2025', '2025-12-01 14:20:48', '2025-12-01 14:20:48'),
(6, NULL, 2, '2025-12-01', 5000.00, 'REF-12346', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598847_8b9e353e0e0f68e20ff4.csv', 'csv', 1, 1, '2025-12-01 14:20:48', NULL, NULL, NULL, NULL, 'asda', 'verified', '1st Semester', '2024-2025', '2025-12-01 14:20:48', '2025-12-01 14:20:48'),
(7, NULL, 3, '2025-12-01', 5000.00, 'REF-12347', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598847_8b9e353e0e0f68e20ff4.csv', 'csv', 1, 1, '2025-12-01 14:20:48', 2, '2025-12-02 02:46:24', '2025-12-02 05:57:38', NULL, 'asda', 'sent_to_accounting', '1st Semester', '2024-2025', '2025-12-01 14:20:48', '2025-12-02 05:57:38'),
(8, 1, 1, '2025-12-01', 5000.00, 'REF-12345', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598985_dd88b4f59e76479fa7c2.csv', 'csv', 1, 1, '2025-12-01 14:23:05', NULL, NULL, NULL, NULL, 'sad', 'verified', '2nd Semester', '2023-2024', '2025-12-01 14:23:05', '2025-12-01 14:23:05'),
(9, 1, 2, '2025-12-01', 5000.00, 'REF-12346', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598985_dd88b4f59e76479fa7c2.csv', 'csv', 1, 1, '2025-12-01 14:23:05', NULL, NULL, NULL, NULL, 'sad', 'verified', '2nd Semester', '2023-2024', '2025-12-01 14:23:05', '2025-12-01 14:23:05'),
(10, 1, 3, '2025-12-01', 5000.00, 'REF-12347', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764598985_dd88b4f59e76479fa7c2.csv', 'csv', 1, 1, '2025-12-01 14:23:05', NULL, NULL, NULL, NULL, 'sad', 'verified', '2nd Semester', '2023-2024', '2025-12-01 14:23:05', '2025-12-01 14:23:05'),
(11, 2, 1, '2025-12-01', 5000.00, 'REF-12345', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764642502_a1cebe659c1cf683aea7.csv', 'csv', 1, 1, '2025-12-02 02:28:22', 2, '2025-12-02 05:38:56', NULL, NULL, 'asda', 'approved', '1st Semester', '2023-2024', '2025-12-02 02:28:22', '2025-12-02 05:38:56'),
(12, 2, 2, '2025-12-01', 5000.00, 'REF-12346', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764642502_a1cebe659c1cf683aea7.csv', 'csv', 1, 1, '2025-12-02 02:28:22', 2, '2025-12-02 05:38:56', NULL, NULL, 'asda', 'approved', '1st Semester', '2023-2024', '2025-12-02 02:28:22', '2025-12-02 05:38:56'),
(13, 2, 3, '2025-12-01', 5000.00, 'REF-12347', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764642502_a1cebe659c1cf683aea7.csv', 'csv', 1, 1, '2025-12-02 02:28:22', 2, '2025-12-02 05:38:56', NULL, NULL, 'asda', 'approved', '1st Semester', '2023-2024', '2025-12-02 02:28:22', '2025-12-02 05:38:56'),
(14, NULL, 1, '2025-12-02', 2131.00, '123131', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764656653_d5c7d88218c0bdd5151c.pdf', 'pdf', 1, 1, '2025-12-02 06:24:13', NULL, NULL, NULL, NULL, 'asda', 'verified', '1st Semester', '2023-2024', '2025-12-02 06:24:13', '2025-12-02 06:24:13'),
(15, 3, 1, '2025-12-01', 5000.00, 'REF-12345', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657609_eb5050152b7351d6fa8b.csv', 'csv', 1, 1, '2025-12-02 06:40:10', NULL, NULL, NULL, NULL, 'bm', 'verified', '2nd Semester', '2024-2025', '2025-12-02 06:40:10', '2025-12-02 06:40:10'),
(16, 3, 2, '2025-12-01', 5000.00, 'REF-12346', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657609_eb5050152b7351d6fa8b.csv', 'csv', 1, 1, '2025-12-02 06:40:10', NULL, NULL, NULL, NULL, 'bm', 'verified', '2nd Semester', '2024-2025', '2025-12-02 06:40:10', '2025-12-02 06:40:10'),
(17, 3, 3, '2025-12-01', 5000.00, 'REF-12347', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657609_eb5050152b7351d6fa8b.csv', 'csv', 1, 1, '2025-12-02 06:40:10', NULL, NULL, NULL, NULL, 'bm', 'verified', '2nd Semester', '2024-2025', '2025-12-02 06:40:10', '2025-12-02 06:40:10'),
(18, 4, 1, '2025-12-01', 5000.00, 'REF-12345', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657646_89368e706d36e77ef85b.csv', 'csv', 1, 1, '2025-12-02 06:40:46', 2, '2025-12-02 06:41:14', '2025-12-02 06:41:37', '2025-12-02 06:44:30', 'sada', 'completed', '2nd Semester', '2023-2024', '2025-12-02 06:40:46', '2025-12-02 06:44:30'),
(19, 4, 2, '2025-12-01', 5000.00, 'REF-12346', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657646_89368e706d36e77ef85b.csv', 'csv', 1, 1, '2025-12-02 06:40:46', 2, '2025-12-02 06:41:14', '2025-12-02 06:41:37', '2025-12-02 06:44:30', 'sada', 'completed', '2nd Semester', '2023-2024', '2025-12-02 06:40:46', '2025-12-02 06:44:30'),
(20, 4, 3, '2025-12-01', 5000.00, 'REF-12347', 'C:\\xampp\\htdocs\\sksu_liquid\\writable\\uploads/atm_liquidations/1764657646_89368e706d36e77ef85b.csv', 'csv', 1, 1, '2025-12-02 06:40:46', 2, '2025-12-02 06:41:14', '2025-12-02 06:41:37', '2025-12-02 06:44:30', 'sada', 'completed', '2nd Semester', '2023-2024', '2025-12-02 06:40:46', '2025-12-02 06:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) UNSIGNED NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disbursements`
--

CREATE TABLE `disbursements` (
  `id` int(11) UNSIGNED NOT NULL,
  `disbursement_date` date NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_id` varchar(100) NOT NULL COMMENT 'Student ID or Recipient Identifier',
  `course_program` varchar(200) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `semester` enum('1st Semester','2nd Semester','Summer') NOT NULL,
  `academic_year` varchar(20) NOT NULL COMMENT 'Format: 2024-2025',
  `scholarship_type` varchar(150) NOT NULL COMMENT 'Type of scholarship',
  `amount` decimal(15,2) NOT NULL,
  `disbursement_method` enum('Cash','Check','Bank_Transfer','ATM') NOT NULL DEFAULT 'Cash',
  `campus` varchar(100) NOT NULL,
  `disbursing_officer_id` int(11) UNSIGNED NOT NULL,
  `status` enum('pending','verified','approved','rejected','disbursed') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `verification_date` datetime DEFAULT NULL,
  `verified_by` int(11) UNSIGNED DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approved_by` int(11) UNSIGNED DEFAULT NULL,
  `disbursed_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disbursements`
--

INSERT INTO `disbursements` (`id`, `disbursement_date`, `recipient_name`, `recipient_id`, `course_program`, `year_level`, `semester`, `academic_year`, `scholarship_type`, `amount`, `disbursement_method`, `campus`, `disbursing_officer_id`, `status`, `remarks`, `verification_date`, `verified_by`, `approval_date`, `approved_by`, `disbursed_date`, `created_at`, `updated_at`) VALUES
(1, '2025-12-01', 'asdadada', '123131', 'asda sdgdgds', '1st Year', '1st Semester', '2025-2026', 'TESDA Scholarship', 2131.00, 'Bank_Transfer', 'Kalamansig Campus', 4, 'approved', 'asda', '2025-12-01 08:36:56', 4, '2025-12-01 08:37:00', 4, NULL, '2025-12-01 08:36:30', '2025-12-01 08:37:00'),
(2, '2025-12-01', 'asadadadadada', '2131321', 'sdada', '1st Year', '1st Semester', '2025-2026', 'TESDA Scholarship', 23131.00, 'Cash', 'Kalamansig Campus', 4, 'approved', 'sadada', '2025-12-01 08:49:02', 4, '2025-12-01 08:49:07', 4, NULL, '2025-12-01 08:48:11', '2025-12-01 08:49:07'),
(3, '2025-12-01', 'asdadadaasdadadhnuter', '2131313', 'asda', '2nd Year', '1st Semester', '2025-2026', 'TESDA Scholarship', 123123.00, 'Bank_Transfer', 'Kalamansig Campus', 4, 'approved', 'asdada', '2025-12-01 09:00:20', 4, '2025-12-01 09:00:25', 4, NULL, '2025-12-01 09:00:16', '2025-12-01 09:00:25'),
(4, '2025-12-01', 'asdadadaqweqasda dasfgfgjretrhetgrhtrge', '213131', 'asd sa dad', '2nd Year', '2nd Semester', '2025-2026', 'TESDA Scholarship', 223213321133.00, 'Bank_Transfer', 'Main Campus', 4, 'approved', 'asda', NULL, NULL, '2025-12-01 09:14:12', 4, NULL, '2025-12-01 09:14:12', '2025-12-01 09:14:12'),
(5, '2025-12-01', 'asdadadasd asfsfsd', '21313131', 'asdad a', '3rd Year', '2nd Semester', '2025-2026', 'Local Government Scholarship', 132131.00, 'Check', 'Kalamansig Campus', 4, 'approved', 'asda', '2025-12-01 11:14:48', 4, '2025-12-01 11:14:55', 4, NULL, '2025-12-01 11:08:07', '2025-12-01 11:14:55');

-- --------------------------------------------------------

--
-- Table structure for table `manual_liquidations`
--

CREATE TABLE `manual_liquidations` (
  `id` int(11) UNSIGNED NOT NULL,
  `recipient_id` int(11) UNSIGNED NOT NULL,
  `disbursing_officer_id` int(11) UNSIGNED NOT NULL,
  `scholarship_coordinator_id` int(11) UNSIGNED DEFAULT NULL,
  `voucher_number` varchar(50) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `liquidation_date` date NOT NULL,
  `semester` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `campus` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','verified','approved','rejected') DEFAULT 'pending',
  `type` varchar(50) DEFAULT 'manual',
  `source_type` varchar(50) DEFAULT NULL,
  `disbursement_id` int(11) UNSIGNED DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(11) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manual_liquidations`
--

INSERT INTO `manual_liquidations` (`id`, `recipient_id`, `disbursing_officer_id`, `scholarship_coordinator_id`, `voucher_number`, `amount`, `liquidation_date`, `semester`, `academic_year`, `campus`, `description`, `status`, `type`, `source_type`, `disbursement_id`, `approved_date`, `approved_by`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 3, 'VO-2024-001', 5000.00, '2024-11-01', '1st Semester', '2024-2025', 'Main Campus', 'Academic merit scholarship liquidation for tuition fee', 'approved', 'manual', NULL, NULL, NULL, NULL, NULL, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(2, 2, 4, 3, 'VO-2024-002', 7500.00, '2024-11-05', '1st Semester', '2024-2025', 'Main Campus', 'CHED scholarship allowance', 'approved', 'manual', NULL, NULL, NULL, NULL, 'asda', '2025-12-01 16:26:43', '2025-12-01 10:59:31'),
(3, 3, 4, 3, 'VO-2024-003', 6000.00, '2024-11-10', '1st Semester', '2024-2025', 'Kalamansig Campus', 'DOST scholarship stipend', 'approved', 'manual', NULL, NULL, NULL, NULL, 'asda', '2025-12-01 16:26:43', '2025-12-01 10:59:26'),
(4, 6, 4, NULL, 'DV-2025-000001', 2131.00, '2025-12-01', '1st Semester', '2025-2026', 'Kalamansig Campus', 'TESDA Scholarship - asdadada (From Disbursement)', 'verified', 'disbursement', 'disbursement', 1, NULL, NULL, 'Transformed from approved disbursement - awaiting liquidation', NULL, '2025-12-02 02:37:05'),
(5, 7, 4, NULL, 'DV-2025-000002', 23131.00, '2025-12-01', '1st Semester', '2025-2026', 'Kalamansig Campus', 'TESDA Scholarship - asadadadadada (From Disbursement)', 'verified', 'disbursement', 'disbursement', 2, NULL, NULL, 'Transformed from approved disbursement - awaiting liquidation', NULL, '2025-12-02 02:37:05'),
(6, 8, 4, NULL, 'DV-2025-000003', 123123.00, '2025-12-01', '1st Semester', '2025-2026', 'Kalamansig Campus', 'TESDA Scholarship - asdadadaasdadadhnuter (From Disbursement)', 'verified', 'disbursement', 'disbursement', 3, NULL, NULL, 'Transformed from approved disbursement - awaiting liquidation', NULL, '2025-12-02 02:37:05'),
(7, 9, 4, NULL, 'DV-2025-000004', 223213321133.00, '2025-12-01', '2nd Semester', '2025-2026', 'Main Campus', 'TESDA Scholarship - asdadadaqweqasda dasfgfgjretrhetgrhtrge (From Disbursement)', 'approved', 'disbursement', 'disbursement', 4, '2025-12-01 11:35:51', 1, 'Automatically created from approved disbursement', '2025-12-01 09:14:12', '2025-12-01 11:35:51'),
(8, 10, 4, NULL, 'DV-2025-000005', 132131.00, '2025-12-01', '2nd Semester', '2025-2026', 'Kalamansig Campus', 'Local Government Scholarship - asdadadasd asfsfsd (From Disbursement)', 'approved', 'disbursement', 'disbursement', 5, '2025-12-01 11:35:51', 1, 'Transformed from approved disbursement - awaiting liquidation', '2025-12-01 11:14:55', '2025-12-01 11:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-11-28-000001', 'App\\Database\\Migrations\\CreateDisbursementTable', 'default', 'App', 1764577770, 1),
(10, '2025-11-28-022944', 'AppDatabaseMigrationsCreateScholarshipTables', 'default', 'App', 1764577771, 2),
(11, '2025-11-28-023000', 'AppDatabaseMigrationsAddDisbursementForeignKeys', 'default', 'App', 1764577772, 2),
(12, '2025-12-01-021559', 'AppDatabaseMigrationsAddTypeFieldsToManualLiquidations', 'default', 'App', 1764577773, 2),
(13, '2025-12-01-021645', 'AppDatabaseMigrationsUpdateExistingManualLiquidationTypes', 'default', 'App', 1764577774, 2);

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_recipients`
--

CREATE TABLE `scholarship_recipients` (
  `id` int(11) UNSIGNED NOT NULL,
  `recipient_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `campus` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `scholarship_type` varchar(100) NOT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarship_recipients`
--

INSERT INTO `scholarship_recipients` (`id`, `recipient_id`, `first_name`, `last_name`, `middle_name`, `email`, `phone`, `campus`, `course`, `year_level`, `scholarship_type`, `bank_account`, `bank_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SKSU-2024-001', 'Juan', 'Dela Cruz', 'Santos', 'juan.delacruz@student.sksu.edu.ph', '09171234567', 'Main Campus', 'Bachelor of Science in Information Technology', '4th Year', 'Academic Merit Scholarship', '1234567890', 'Land Bank of the Philippines', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(2, 'SKSU-2024-002', 'Maria', 'Santos', 'Garcia', 'maria.santos@student.sksu.edu.ph', '09181234567', 'Main Campus', 'Bachelor of Science in Education', '3rd Year', 'CHED Scholarship', '2345678901', 'Development Bank of the Philippines', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(3, 'SKSU-2024-003', 'Pedro', 'Gonzales', 'Reyes', 'pedro.gonzales@student.sksu.edu.ph', '09191234567', 'Kalamansig Campus', 'Bachelor of Science in Agriculture', '2nd Year', 'DOST Scholarship', '3456789012', 'Philippine National Bank', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(4, 'SKSU-2024-004', 'Ana', 'Rodriguez', 'Cruz', 'ana.rodriguez@student.sksu.edu.ph', '09201234567', 'Palimbang Campus', 'Bachelor of Science in Business Administration', '1st Year', 'TES Scholarship', '4567890123', 'Banco de Oro', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(5, 'SKSU-2024-005', 'Jose', 'Mendoza', 'Lopez', 'jose.mendoza@student.sksu.edu.ph', '09211234567', 'Isulan Campus', 'Bachelor of Science in Engineering', '4th Year', 'Engineering Scholarship', '5678901234', 'Metrobank', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(6, '123131', 'asdadada', '', '', '123131@temp.edu', NULL, 'Kalamansig Campus', 'asda sdgdgds', '1st Year', 'TESDA Scholarship', NULL, NULL, 1, NULL, NULL),
(7, '2131321', 'asadadadadada', '', '', '2131321@temp.edu', NULL, 'Kalamansig Campus', 'sdada', '1st Year', 'TESDA Scholarship', NULL, NULL, 1, NULL, NULL),
(8, '2131313', 'asdadadaasdadadhnuter', '', '', '2131313@temp.edu', NULL, 'Kalamansig Campus', 'asda', '2nd Year', 'TESDA Scholarship', NULL, NULL, 1, NULL, NULL),
(9, '213131', 'asdadadaqweqasda', 'dasfgfgjretrhetgrhtrge', '', '213131@temp.edu', NULL, 'Main Campus', 'asd sa dad', '2nd Year', 'TESDA Scholarship', NULL, NULL, 1, '2025-12-01 09:14:12', '2025-12-01 09:14:12'),
(10, '21313131', 'asdadadasd', 'asfsfsd', '', '21313131@temp.edu', NULL, 'Kalamansig Campus', 'asdad a', '3rd Year', 'Local Government Scholarship', NULL, NULL, 1, '2025-12-01 11:08:07', '2025-12-01 11:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','scholarship_coordinator','disbursing_officer','scholarship_chairman','accounting_officer') DEFAULT 'disbursing_officer',
  `campus` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `campus`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Main Campus', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(2, 'chairman', 'chairman@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'scholarship_chairman', 'Main Campus', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(3, 'coordinator', 'coordinator@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'scholarship_coordinator', 'Main Campus', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(4, 'officer1', 'officer1@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'disbursing_officer', 'Main Campus', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43'),
(5, 'accounting', 'accounting@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'accounting_officer', 'Main Campus', 1, '2025-12-01 16:26:43', '2025-12-01 16:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_approvals`
--

CREATE TABLE `workflow_approvals` (
  `id` int(11) UNSIGNED NOT NULL,
  `liquidation_type` enum('manual','atm') NOT NULL,
  `liquidation_id` int(11) UNSIGNED NOT NULL,
  `approver_id` int(11) UNSIGNED NOT NULL,
  `approval_level` enum('coordinator','chairman','accounting') NOT NULL,
  `action` enum('approved','rejected','returned') NOT NULL,
  `remarks` text DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atm_liquidations`
--
ALTER TABLE `atm_liquidations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atm_liquidation_details`
--
ALTER TABLE `atm_liquidation_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursements`
--
ALTER TABLE `disbursements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disbursing_officer_id` (`disbursing_officer_id`),
  ADD KEY `verified_by` (`verified_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `campus` (`campus`),
  ADD KEY `status` (`status`),
  ADD KEY `disbursement_date` (`disbursement_date`);

--
-- Indexes for table `manual_liquidations`
--
ALTER TABLE `manual_liquidations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scholarship_recipients`
--
ALTER TABLE `scholarship_recipients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `workflow_approvals`
--
ALTER TABLE `workflow_approvals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atm_liquidations`
--
ALTER TABLE `atm_liquidations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `atm_liquidation_details`
--
ALTER TABLE `atm_liquidation_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disbursements`
--
ALTER TABLE `disbursements`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manual_liquidations`
--
ALTER TABLE `manual_liquidations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `scholarship_recipients`
--
ALTER TABLE `scholarship_recipients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `workflow_approvals`
--
ALTER TABLE `workflow_approvals`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
