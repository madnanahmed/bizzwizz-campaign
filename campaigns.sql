-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 21, 2022 at 08:07 AM
-- Server version: 8.0.27
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `campaigns`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

DROP TABLE IF EXISTS `campaign`;
CREATE TABLE IF NOT EXISTS `campaign` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_ids` varchar(199) DEFAULT NULL,
  `title` varchar(199) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `email_body` text,
  `type` varchar(199) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`id`, `user_id`, `category_ids`, `title`, `email_subject`, `email_body`, `type`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, '1,2', 'Send Bulk Messages', 'Send Bulk Messages', '<p>Send Bulk Messages</p>', '2', 0, '2022-06-21 07:25:45', '2022-06-21 02:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_logs`
--

DROP TABLE IF EXISTS `campaign_logs`;
CREATE TABLE IF NOT EXISTS `campaign_logs` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `code` varchar(199) DEFAULT NULL,
  `user_id` int NOT NULL,
  `campaign_id` varchar(199) DEFAULT NULL,
  `contact_id` varchar(199) NOT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1=sms,2=email',
  `response` longtext,
  `is_open` tinyint DEFAULT NULL,
  `bitly_id` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(199) NOT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `location` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_list`
--

DROP TABLE IF EXISTS `contacts_list`;
CREATE TABLE IF NOT EXISTS `contacts_list` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts_list`
--

INSERT INTO `contacts_list` (`id`, `user_id`, `title`, `status`, `created_at`) VALUES
(1, 4, 'Roofing', '', '2019-12-30 19:10:12'),
(63, 4, 'general construction employers union', '1', '2020-01-10 19:47:13'),
(62, 4, 'RBQ', '1', '2020-01-08 18:48:19'),
(64, 4, 'CRIMINAL LAWYER MONTREAL LIST', '1', '2020-01-15 17:22:59'),
(61, 4, 'Adnan Test', '1', '2020-01-05 06:20:02'),
(65, 4, 'test 5', '1', '2022-06-19 21:03:21');

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
CREATE TABLE IF NOT EXISTS `email_logs` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email_id` int DEFAULT NULL,
  `to_email` varchar(199) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `body` longtext,
  `list_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

DROP TABLE IF EXISTS `email_settings`;
CREATE TABLE IF NOT EXISTS `email_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from_name` varchar(100) DEFAULT NULL,
  `from_email` varchar(100) DEFAULT NULL,
  `reply_to` varchar(100) DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_email_log`
--

DROP TABLE IF EXISTS `feedback_email_log`;
CREATE TABLE IF NOT EXISTS `feedback_email_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sheet_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `from` varchar(199) DEFAULT NULL,
  `to` varchar(199) DEFAULT NULL,
  `data` text,
  `type` tinyint DEFAULT NULL COMMENT '1 for agent, 2 for lead',
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incomming_emails`
--

DROP TABLE IF EXISTS `incomming_emails`;
CREATE TABLE IF NOT EXISTS `incomming_emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `list_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invalid_contacts`
--

DROP TABLE IF EXISTS `invalid_contacts`;
CREATE TABLE IF NOT EXISTS `invalid_contacts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_feedback`
--

DROP TABLE IF EXISTS `lead_feedback`;
CREATE TABLE IF NOT EXISTS `lead_feedback` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `is_sms` tinyint(1) DEFAULT NULL,
  `sheet_id` int DEFAULT NULL,
  `sms` text,
  `is_email` tinyint(1) DEFAULT NULL COMMENT '1',
  `subject` varchar(150) DEFAULT NULL,
  `page_id` varchar(100) DEFAULT NULL,
  `email_body` longtext,
  `status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lead_response`
--

DROP TABLE IF EXISTS `lead_response`;
CREATE TABLE IF NOT EXISTS `lead_response` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `from` varchar(150) DEFAULT NULL,
  `to` varchar(150) DEFAULT NULL,
  `body` text,
  `sms_message_sid` varchar(150) DEFAULT NULL,
  `sms_sid` varchar(150) DEFAULT NULL,
  `message_sid` varchar(150) DEFAULT NULL,
  `account_sid` varchar(150) DEFAULT NULL,
  `data` text,
  `status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(17, '2015_07_22_115516_create_ticketit_tables', 2),
(18, '2015_07_22_123254_alter_users_table', 2),
(19, '2015_09_29_123456_add_completed_at_column_to_ticketit_table', 2),
(20, '2015_10_08_123457_create_settings_table', 2),
(21, '2016_01_15_002617_add_htmlcontent_to_ticketit_and_comments', 2),
(22, '2016_01_15_040207_enlarge_settings_columns', 2),
(23, '2016_01_15_120557_add_indexes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `twilio_sid` varchar(199) NOT NULL,
  `twilio_token` text NOT NULL,
  `from_number` varchar(199) NOT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `twilio_sid`, `twilio_token`, `from_number`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, 'AC325bb01ec48a594eba663a55380sc84c1', 'af7e6741f66907682548d5025b8c06097', '+14804281779', NULL, '2022-06-21 08:02:16', '2022-06-21 08:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `sheets_contacts`
--

DROP TABLE IF EXISTS `sheets_contacts`;
CREATE TABLE IF NOT EXISTS `sheets_contacts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `lead_id` int DEFAULT NULL,
  `sheet_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pin_type` varchar(100) DEFAULT NULL,
  `is_pin` tinyint(1) DEFAULT NULL,
  `pin_code` varchar(100) DEFAULT NULL,
  `pin_count` int DEFAULT '0',
  `phone` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sheets_history`
--

DROP TABLE IF EXISTS `sheets_history`;
CREATE TABLE IF NOT EXISTS `sheets_history` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sheet_id` int DEFAULT NULL,
  `total_records` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sheets_history`
--

INSERT INTO `sheets_history` (`id`, `sheet_id`, `total_records`, `updated_at`) VALUES
(1, 3, 2, '2019-11-24 09:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `time_zones`
--

DROP TABLE IF EXISTS `time_zones`;
CREATE TABLE IF NOT EXISTS `time_zones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(255) DEFAULT NULL,
  `zone_value` varchar(255) DEFAULT NULL,
  `field_status` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `time_zones`
--

INSERT INTO `time_zones` (`id`, `zone_name`, `zone_value`, `field_status`, `created_at`, `updated_at`) VALUES
(1, 'Pacific/Wake', 'Pacific/Wake', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(3, 'Pacific/Apia', 'Pacific/Apia', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(4, 'Pacific/Honolulu', 'Pacific/Honolulu', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(5, 'America/Anchorage', 'America/Anchorage', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(6, 'America/Los_Angeles', 'America/Los_Angeles', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(7, 'America/Phoenix', 'America/Phoenix', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(8, 'America/Chihuahua', 'America/Chihuahua', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(11, 'America/Denver', 'America/Denver', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(12, 'America/Managua', 'America/Managua', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(13, 'America/Chicago', 'America/Chicago', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(16, 'America/Mexico_City', 'America/Mexico_City', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(17, 'America/Regina', 'America/Regina', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(18, 'America/Bogota', 'America/Bogota', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(19, 'America/New_York', 'America/New_York', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(20, 'America/Indiana/Indianapolis', 'America/Indiana/Indianapolis', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(23, 'America/Halifax', 'America/Halifax', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(24, 'America/Caracas', 'America/Caracas', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(26, 'America/Santiago', 'America/Santiago', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(27, 'America/St_Johns', 'America/St_Johns', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(28, 'America/Sao_Paulo', 'America/Sao_Paulo', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(29, 'America/Argentina/Buenos_Aires', 'America/Argentina/Buenos_Aires', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(31, 'America/Godthab', 'America/Godthab', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(32, 'America/Noronha', 'America/Noronha', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(33, 'Atlantic/Azores', 'Atlantic/Azores', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(34, 'Atlantic/Cape_Verde', 'Atlantic/Cape_Verde', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(35, 'Africa/Casablanca', 'Africa/Casablanca', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(39, 'Europe/London', 'Europe/London', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(51, 'Europe/Paris', 'Europe/Paris', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(52, 'Europe/Belgrade', 'Europe/Belgrade', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(57, 'Europe/Berlin', 'Europe/Berlin', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(59, 'Africa/Lagos', 'Africa/Lagos', NULL, '2019-03-26 15:44:01', '2019-03-26 15:44:01'),
(60, 'Europe/Sarajevo', 'Europe/Sarajevo', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(62, 'Europe/Bucharest', 'Europe/Bucharest', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(63, 'Africa/Cairo', 'Africa/Cairo', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(64, 'Africa/Johannesburg', 'Africa/Johannesburg', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(67, 'Asia/Jerusalem', 'Asia/Jerusalem', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(69, 'Europe/Istanbul', 'Europe/Istanbul', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(74, 'Europe/Helsinki', 'Europe/Helsinki', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(75, 'Asia/Baghdad', 'Asia/Baghdad', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(78, 'Africa/Nairobi', 'Africa/Nairobi', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(79, 'Asia/Riyadh', 'Asia/Riyadh', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(81, 'Europe/Moscow', 'Europe/Moscow', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(82, 'Asia/Tehran', 'Asia/Tehran', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(83, 'Asia/Muscat', 'Asia/Muscat', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(87, 'Asia/Tbilisi', 'Asia/Tbilisi', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(88, 'Asia/Kabul', 'Asia/Kabul', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(89, 'Asia/Yekaterinburg', 'Asia/Yekaterinburg', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(90, 'Asia/Karachi', 'Asia/Karachi', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(93, 'Asia/Calcutta', 'Asia/Calcutta', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(97, 'Asia/Katmandu', 'Asia/Katmandu', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(99, 'Asia/Dhaka', 'Asia/Dhaka', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(101, 'Asia/Novosibirsk', 'Asia/Novosibirsk', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(102, 'Asia/Colombo', 'Asia/Colombo', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(103, 'Asia/Rangoon', 'Asia/Rangoon', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(106, 'Asia/Bangkok', 'Asia/Bangkok', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(107, 'Asia/Krasnoyarsk', 'Asia/Krasnoyarsk', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(108, 'Asia/Hong_Kong', 'Asia/Hong_Kong', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(111, 'Asia/Irkutsk', 'Asia/Irkutsk', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(113, 'Australia/Perth', 'Australia/Perth', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(114, 'Asia/Singapore', 'Asia/Singapore', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(118, 'Asia/Tokyo', 'Asia/Tokyo', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(120, 'Asia/Seoul', 'Asia/Seoul', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(122, 'Asia/Yakutsk', 'Asia/Yakutsk', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(123, 'Australia/Adelaide', 'Australia/Adelaide', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(124, 'Australia/Darwin', 'Australia/Darwin', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(125, 'Australia/Brisbane', 'Australia/Brisbane', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(126, 'Australia/Sydney', 'Australia/Sydney', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(128, 'Australia/Hobart', 'Australia/Hobart', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(130, 'Pacific/Guam', 'Pacific/Guam', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(132, 'Asia/Vladivostok', 'Asia/Vladivostok', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(133, 'Asia/Magadan', 'Asia/Magadan', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(139, 'Pacific/Fiji', 'Pacific/Fiji', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(140, 'Pacific/Auckland', 'Pacific/Auckland', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02'),
(141, 'Pacific/Tongatapu', 'Pacific/Tongatapu', NULL, '2019-03-26 15:44:02', '2019-03-26 15:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_pass` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ag',
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'user-placeholder.png',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twilio_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ticketit_admin` tinyint(1) NOT NULL DEFAULT '0',
  `ticketit_agent` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `raw_pass`, `user_type`, `avatar`, `phone`, `twilio_number`, `ip_address`, `remember_token`, `status`, `created_at`, `updated_at`, `ticketit_admin`, `ticketit_agent`, `timezone`) VALUES
(4, 'muhammad adnan', 'admin@gmail.com', '2019-07-25 20:40:22', '$2y$10$m1dQ086GfL8WA7eduYRlGupHSFddaxOWhJYUboGQ8zHagboJc138S', NULL, 'a', '1580650266.jpg', '+923437617288', NULL, NULL, 'fMNHG4ZCSefiP8c9aJrIQocqAPoltAPyl9y06QrGLlZYUZ9GZrhT97TCepQ2', 1, '2019-07-25 20:37:13', '2020-02-02 08:48:54', 1, 1, 'Asia/Karachi');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
