-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2020 at 11:34 PM
-- Server version: 10.3.22-MariaDB-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staffconnex_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accept_estimates`
--

CREATE TABLE `accept_estimates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `estimate_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `signature` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `clock_in_time` datetime NOT NULL,
  `clock_out_time` datetime DEFAULT NULL,
  `clock_in_ip` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `clock_out_ip` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `working_from` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'office',
  `late` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `half_day` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `company_id`, `user_id`, `clock_in_time`, `clock_out_time`, `clock_in_ip`, `clock_out_ip`, `working_from`, `late`, `half_day`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-04-09 02:07:00', NULL, '::1', '::1', 'home', 'no', 'no', '2020-04-09 22:08:08', '2020-04-09 22:08:08'),
(2, 1, 4, '2020-04-09 05:08:00', NULL, '::1', '::1', 'office', 'no', 'no', '2020-04-09 22:08:15', '2020-04-09 22:08:15'),
(3, 1, 5, '2020-04-09 05:08:00', NULL, '::1', '::1', 'office', 'yes', 'yes', '2020-04-09 22:08:20', '2020-04-09 22:08:25'),
(4, 1, 1, '2020-04-10 05:08:00', NULL, '::1', '::1', 'office', 'no', 'no', '2020-04-09 22:08:45', '2020-04-09 22:08:45');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_settings`
--

CREATE TABLE `attendance_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `office_start_time` time NOT NULL,
  `office_end_time` time NOT NULL,
  `halfday_mark_time` time DEFAULT NULL,
  `late_mark_duration` tinyint(4) NOT NULL,
  `clockin_in_day` int(11) NOT NULL DEFAULT 1,
  `employee_clock_in_out` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `office_open_days` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[1,2,3,4,5]',
  `ip_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `radius` int(11) DEFAULT NULL,
  `radius_check` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `ip_check` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `attendance_settings`
--

INSERT INTO `attendance_settings` (`id`, `company_id`, `office_start_time`, `office_end_time`, `halfday_mark_time`, `late_mark_duration`, `clockin_in_day`, `employee_clock_in_out`, `office_open_days`, `ip_address`, `radius`, `radius_check`, `ip_check`, `created_at`, `updated_at`) VALUES
(1, NULL, '09:00:00', '18:00:00', NULL, 20, 2, 'yes', '[1,2,3,4,5]', NULL, NULL, 'no', 'no', '2020-04-09 11:23:54', '2020-04-09 11:23:54'),
(2, 1, '09:00:00', '18:00:00', NULL, 20, 1, 'yes', '[1,2,3,4,5]', NULL, NULL, 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `client_contacts`
--

CREATE TABLE `client_contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `contact_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_details`
--

CREATE TABLE `client_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gst_number` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_phone` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_background` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `package_id` int(10) UNSIGNED DEFAULT NULL,
  `package_type` enum('monthly','annual') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'monthly',
  `timezone` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Asia/Kolkata',
  `date_format` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'd-m-Y',
  `date_picker_format` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_format` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'h:i a',
  `week_start` int(11) NOT NULL DEFAULT 1,
  `locale` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `leaves_start_from` enum('joining_date','year_start') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'joining_date',
  `active_theme` enum('default','custom') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `status` enum('active','inactive','license_expired') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `task_self` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `last_updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_brand` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_last_four` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `licence_expire_on` date DEFAULT NULL,
  `rounded_theme` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `default_task_status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `company_email`, `company_phone`, `logo`, `login_background`, `address`, `website`, `currency_id`, `package_id`, `package_type`, `timezone`, `date_format`, `date_picker_format`, `time_format`, `week_start`, `locale`, `latitude`, `longitude`, `leaves_start_from`, `active_theme`, `status`, `task_self`, `last_updated_by`, `created_at`, `updated_at`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`, `licence_expire_on`, `rounded_theme`, `last_login`, `default_task_status`) VALUES
(1, 'Testing Company', 'admin@example.com', '1212121212', NULL, NULL, 'Company address', NULL, 1, 1, 'annual', 'Asia/Jakarta', 'd-m-Y', 'dd-mm-yyyy', 'h:i a', 1, 'en', NULL, NULL, 'joining_date', 'default', 'active', 'yes', 3, '2020-04-09 11:24:06', '2020-04-14 02:30:50', NULL, NULL, NULL, NULL, '2021-04-14', 1, '2020-04-14 09:30:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `original_amount` decimal(15,2) NOT NULL,
  `contract_type_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `original_start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `original_end_date` date NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_detail` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_discussions`
--

CREATE TABLE `contract_discussions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `from` int(10) UNSIGNED NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_renews`
--

CREATE TABLE `contract_renews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `renewed_by` int(10) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_signs`
--

CREATE TABLE `contract_signs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `signature` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_types`
--

CREATE TABLE `contract_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_one` int(10) UNSIGNED NOT NULL,
  `user_two` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_reply`
--

CREATE TABLE `conversation_reply` (
  `id` int(10) UNSIGNED NOT NULL,
  `conversation_id` int(10) UNSIGNED NOT NULL,
  `reply` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `is_visible` tinyint(4) NOT NULL,
  `iso_alpha2` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso_alpha3` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso_numeric` int(10) UNSIGNED NOT NULL,
  `currency_code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `currency_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `flag` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `is_visible`, `iso_alpha2`, `iso_alpha3`, `iso_numeric`, `currency_code`, `currency_name`, `currency_symbol`, `flag`) VALUES
(1, 'Aruba', 1, 'AW', 'AB', 0, 'AWG', 'Guilder', 'ƒ', 'aw.png'),
(2, 'Afghanistan', 1, 'AF', 'AF', 0, 'AFN', 'Afghani', '؋', 'af.png'),
(3, 'Angola', 1, 'AO', 'AG', 0, 'AOA', 'Kwanza', 'Kz', 'ao.png'),
(4, 'Anguilla', 1, 'AI', 'AI', 0, 'XCD', 'Dollar', '$', 'ai.png'),
(5, 'Albania', 1, 'AL', 'AL', 0, 'ALL', 'Lek', 'Lek', 'al.png'),
(6, 'Andorra', 1, 'AD', 'AN', 0, 'EUR', 'Euro', '€', 'ad.png'),
(7, 'Netherlands Antilles', 1, 'AN', 'AN', 0, 'ANG', 'Guilder', 'ƒ', 'an.png'),
(8, 'United Arab Emirates', 1, 'AE', 'AR', 0, 'AED', 'Dirham', '', 'ae.png'),
(9, 'Argentina', 1, 'AR', 'AR', 0, 'ARS', 'Peso', '$', 'ar.png'),
(10, 'Armenia', 1, 'AM', 'AR', 0, 'AMD', 'Dram', '', 'am.png'),
(11, 'American Samoa', 1, 'AS', 'AS', 0, 'USD', 'Dollar', '$', 'as.png'),
(12, 'Antarctica', 1, 'AQ', 'AT', 0, '', '', '', 'aq.png'),
(13, 'French Southern territories', 1, 'TF', 'AT', 0, 'EUR', 'Euro  ', '€', 'tf.png'),
(14, 'Antigua and Barbuda', 1, 'AG', 'AT', 0, 'XCD', 'Dollar', '$', 'ag.png'),
(15, 'Australia', 1, 'AU', 'AU', 0, 'AUD', 'Dollar', '$', 'au.png'),
(16, 'Austria', 1, 'AT', 'AU', 0, 'EUR', 'Euro', '€', 'at.png'),
(17, 'Azerbaijan', 1, 'AZ', 'AZ', 0, 'AZN', 'Manat', 'ман', 'az.png'),
(18, 'Burundi', 1, 'BI', 'BD', 0, 'BIF', 'Franc', '', 'bi.png'),
(19, 'Belgium', 1, 'BE', 'BE', 0, 'EUR', 'Euro', '€', 'be.png'),
(20, 'Benin', 1, 'BJ', 'BE', 0, 'XOF', 'Franc', '', 'bj.png'),
(21, 'Burkina Faso', 1, 'BF', 'BF', 0, 'XOF', 'Franc', '', 'bf.png'),
(22, 'Bangladesh', 1, 'BD', 'BG', 0, 'BDT', 'Taka', '', 'bd.png'),
(23, 'Bulgaria', 1, 'BG', 'BG', 0, 'BGN', 'Lev', 'лв', 'bg.png'),
(24, 'Bahrain', 1, 'BH', 'BH', 0, 'BHD', 'Dinar', '', 'bh.png'),
(25, 'Bahamas', 1, 'BS', 'BH', 0, 'BSD', 'Dollar', '$', 'bs.png'),
(26, 'Bosnia and Herzegovina', 1, 'BA', 'BI', 0, 'BAM', 'Marka', 'KM', 'ba.png'),
(27, 'Belarus', 1, 'BY', 'BL', 0, 'BYR', 'Ruble', 'p.', 'by.png'),
(28, 'Belize', 1, 'BZ', 'BL', 0, 'BZD', 'Dollar', 'BZ$', 'bz.png'),
(29, 'Bermuda', 1, 'BM', 'BM', 0, 'BMD', 'Dollar', '$', 'bm.png'),
(30, 'Bolivia', 1, 'BO', 'BO', 0, 'BOB', 'Boliviano', '$b', 'bo.png'),
(31, 'Brazil', 1, 'BR', 'BR', 0, 'BRL', 'Real', 'R$', 'br.png'),
(32, 'Barbados', 1, 'BB', 'BR', 0, 'BBD', 'Dollar', '$', 'bb.png'),
(33, 'Brunei', 1, 'BN', 'BR', 0, 'BND', 'Dollar', '$', 'bn.png'),
(34, 'Bhutan', 1, 'BT', 'BT', 0, 'BTN', 'Ngultrum', '', 'bt.png'),
(35, 'Bouvet Island', 1, 'BV', 'BV', 0, 'NOK', 'Krone', 'kr', 'bv.png'),
(36, 'Botswana', 1, 'BW', 'BW', 0, 'BWP', 'Pula', 'P', 'bw.png'),
(37, 'Central African Republic', 1, 'CF', 'CA', 0, 'XAF', 'Franc', 'FCF', 'cf.png'),
(38, 'Canada', 1, 'CA', 'CA', 0, 'CAD', 'Dollar', '$', 'ca.png'),
(40, 'Switzerland', 1, 'CH', 'CH', 0, 'CHF', 'Franc', 'CHF', 'ch.png'),
(41, 'Chile', 1, 'CL', 'CH', 0, 'CLP', 'Peso', '', 'cl.png'),
(42, 'China', 1, 'CN', 'CH', 0, 'CNY', 'Yuan Renminbi', '¥', 'cn.png'),
(44, 'Cameroon', 1, 'CM', 'CM', 0, 'XAF', 'Franc', 'FCF', 'cm.png'),
(47, 'Cook Islands', 1, 'CK', 'CO', 0, 'NZD', 'Dollar', '$', 'ck.png'),
(48, 'Colombia', 1, 'CO', 'CO', 0, 'COP', 'Peso', '$', 'co.png'),
(49, 'Comoros', 1, 'KM', 'CO', 0, 'KMF', 'Franc', '', 'km.png'),
(50, 'Cape Verde', 1, 'CV', 'CP', 0, 'CVE', 'Escudo', '', 'cv.png'),
(51, 'Costa Rica', 1, 'CR', 'CR', 0, 'CRC', 'Colon', '₡', 'cr.png'),
(52, 'Cuba', 1, 'CU', 'CU', 0, 'CUP', 'Peso', '₱', 'cu.png'),
(53, 'Christmas Island', 1, 'CX', 'CX', 0, 'AUD', 'Dollar', '$', 'cx.png'),
(54, 'Cayman Islands', 1, 'KY', 'CY', 0, 'KYD', 'Dollar', '$', 'ky.png'),
(55, 'Cyprus', 1, 'CY', 'CY', 0, 'CYP', 'Pound', '', 'cy.png'),
(56, 'Czech Republic', 1, 'CZ', 'CZ', 0, 'CZK', 'Koruna', 'Kč', 'cz.png'),
(57, 'Germany', 1, 'DE', 'DE', 0, 'EUR', 'Euro', '€', 'de.png'),
(58, 'Djibouti', 1, 'DJ', 'DJ', 0, 'DJF', 'Franc', '', 'dj.png'),
(59, 'Dominica', 1, 'DM', 'DM', 0, 'XCD', 'Dollar', '$', 'dm.png'),
(60, 'Denmark', 1, 'DK', 'DN', 0, 'DKK', 'Krone', 'kr', 'dk.png'),
(61, 'Dominican Republic', 1, 'DO', 'DO', 0, 'DOP', 'Peso', 'RD$', 'do.png'),
(62, 'Algeria', 1, 'DZ', 'DZ', 0, 'DZD', 'Dinar', '', 'dz.png'),
(63, 'Ecuador', 1, 'EC', 'EC', 0, 'USD', 'Dollar', '$', 'ec.png'),
(64, 'Egypt', 1, 'EG', 'EG', 0, 'EGP', 'Pound', '£', 'eg.png'),
(65, 'Eritrea', 1, 'ER', 'ER', 0, 'ERN', 'Nakfa', 'Nfk', 'er.png'),
(66, 'Western Sahara', 1, 'EH', 'ES', 0, 'MAD', 'Dirham', '', 'eh.png'),
(67, 'Spain', 1, 'ES', 'ES', 0, 'EUR', 'Euro', '€', 'es.png'),
(68, 'Estonia', 1, 'EE', 'ES', 0, 'EEK', 'Kroon', 'kr', 'ee.png'),
(69, 'Ethiopia', 1, 'ET', 'ET', 0, 'ETB', 'Birr', '', 'et.png'),
(70, 'Finland', 1, 'FI', 'FI', 0, 'EUR', 'Euro', '€', 'fi.png'),
(72, 'Falkland Islands', 1, 'FK', 'FL', 0, 'FKP', 'Pound', '£', 'fk.png'),
(73, 'France', 1, 'FR', 'FR', 0, 'EUR', 'Euro', '€', 'fr.png'),
(74, 'Faroe Islands', 1, 'FO', 'FR', 0, 'DKK', 'Krone', 'kr', 'fo.png'),
(76, 'Gabon', 1, 'GA', 'GA', 0, 'XAF', 'Franc', 'FCF', 'ga.png'),
(77, 'United Kingdom', 1, 'GB', 'GB', 0, 'GBP', 'Pound', '£', 'gb.png'),
(78, 'Georgia', 1, 'GE', 'GE', 0, 'GEL', 'Lari', '', 'ge.png'),
(79, 'Ghana', 1, 'GH', 'GH', 0, 'GHC', 'Cedi', '¢', 'gh.png'),
(80, 'Gibraltar', 1, 'GI', 'GI', 0, 'GIP', 'Pound', '£', 'gi.png'),
(81, 'Guinea', 1, 'GN', 'GI', 0, 'GNF', 'Franc', '', 'gn.png'),
(82, 'Guadeloupe', 1, 'GP', 'GL', 0, 'EUR', 'Euro', '€', 'gp.png'),
(83, 'Gambia', 1, 'GM', 'GM', 0, 'GMD', 'Dalasi', 'D', 'gm.png'),
(84, 'Guinea-Bissau', 1, 'GW', 'GN', 0, 'XOF', 'Franc', '', 'gw.png'),
(85, 'Equatorial Guinea', 1, 'GQ', 'GN', 0, 'XAF', 'Franc', 'FCF', 'gq.png'),
(86, 'Greece', 1, 'GR', 'GR', 0, 'EUR', 'Euro', '€', 'gr.png'),
(87, 'Grenada', 1, 'GD', 'GR', 0, 'XCD', 'Dollar', '$', 'gd.png'),
(88, 'Greenland', 1, 'GL', 'GR', 0, 'DKK', 'Krone', 'kr', 'gl.png'),
(89, 'Guatemala', 1, 'GT', 'GT', 0, 'GTQ', 'Quetzal', 'Q', 'gt.png'),
(90, 'French Guiana', 1, 'GF', 'GU', 0, 'EUR', 'Euro', '€', 'gf.png'),
(91, 'Guam', 1, 'GU', 'GU', 0, 'USD', 'Dollar', '$', 'gu.png'),
(92, 'Guyana', 1, 'GY', 'GU', 0, 'GYD', 'Dollar', '$', 'gy.png'),
(93, 'Hong Kong', 1, 'HK', 'HK', 0, 'HKD', 'Dollar', '$', 'hk.png'),
(95, 'Honduras', 1, 'HN', 'HN', 0, 'HNL', 'Lempira', 'L', 'hn.png'),
(96, 'Croatia', 1, 'HR', 'HR', 0, 'HRK', 'Kuna', 'kn', 'hr.png'),
(97, 'Haiti', 1, 'HT', 'HT', 0, 'HTG', 'Gourde', 'G', 'ht.png'),
(98, 'Hungary', 1, 'HU', 'HU', 0, 'HUF', 'Forint', 'Ft', 'hu.png'),
(99, 'Indonesia', 1, 'ID', 'ID', 0, 'IDR', 'Rupiah', 'Rp', 'id.png'),
(100, 'India', 1, 'IN', 'IN', 0, 'INR', 'Rupee', '₹', 'in.png'),
(101, 'British Indian Ocean Territory', 1, 'IO', 'IO', 0, 'USD', 'Dollar', '$', 'io.png'),
(102, 'Ireland', 1, 'IE', 'IR', 0, 'EUR', 'Euro', '€', 'ie.png'),
(103, 'Iran', 1, 'IR', 'IR', 0, 'IRR', 'Rial', '﷼', 'ir.png'),
(104, 'Iraq', 1, 'IQ', 'IR', 0, 'IQD', 'Dinar', '', 'iq.png'),
(105, 'Iceland', 1, 'IS', 'IS', 0, 'ISK', 'Krona', 'kr', 'is.png'),
(106, 'Israel', 1, 'IL', 'IS', 0, 'ILS', 'Shekel', '₪', 'il.png'),
(107, 'Italy', 1, 'IT', 'IT', 0, 'EUR', 'Euro', '€', 'it.png'),
(108, 'Jamaica', 1, 'JM', 'JA', 0, 'JMD', 'Dollar', '$', 'jm.png'),
(109, 'Jordan', 1, 'JO', 'JO', 0, 'JOD', 'Dinar', '', 'jo.png'),
(110, 'Japan', 1, 'JP', 'JP', 0, 'JPY', 'Yen', '¥', 'jp.png'),
(112, 'Kenya', 1, 'KE', 'KE', 0, 'KES', 'Shilling', '', 'ke.png'),
(113, 'Kyrgyzstan', 1, 'KG', 'KG', 0, 'KGS', 'Som', 'лв', 'kg.png'),
(114, 'Cambodia', 1, 'KH', 'KH', 0, 'KHR', 'Riels', '៛', 'kh.png'),
(115, 'Kiribati', 1, 'KI', 'KI', 0, 'AUD', 'Dollar', '$', 'ki.png'),
(116, 'Saint Kitts and Nevis', 1, 'KN', 'KN', 0, 'XCD', 'Dollar', '$', 'kn.png'),
(117, 'South Korea', 1, 'KR', 'KO', 0, 'KRW', 'Won', '₩', 'kr.png'),
(118, 'Kuwait', 1, 'KW', 'KW', 0, 'KWD', 'Dinar', '', 'kw.png'),
(119, 'Laos', 1, 'LA', 'LA', 0, 'LAK', 'Kip', '₭', 'la.png'),
(120, 'Lebanon', 1, 'LB', 'LB', 0, 'LBP', 'Pound', '£', 'lb.png'),
(121, 'Liberia', 1, 'LR', 'LB', 0, 'LRD', 'Dollar', '$', 'lr.png'),
(123, 'Saint Lucia', 1, 'LC', 'LC', 0, 'XCD', 'Dollar', '$', 'lc.png'),
(124, 'Liechtenstein', 1, 'LI', 'LI', 0, 'CHF', 'Franc', 'CHF', 'li.png'),
(125, 'Sri Lanka', 1, 'LK', 'LK', 0, 'LKR', 'Rupee', '₨', 'lk.png'),
(126, 'Lesotho', 1, 'LS', 'LS', 0, 'LSL', 'Loti', 'L', 'ls.png'),
(127, 'Lithuania', 1, 'LT', 'LT', 0, 'LTL', 'Litas', 'Lt', 'lt.png'),
(128, 'Luxembourg', 1, 'LU', 'LU', 0, 'EUR', 'Euro', '€', 'lu.png'),
(129, 'Latvia', 1, 'LV', 'LV', 0, 'LVL', 'Lat', 'Ls', 'lv.png'),
(130, 'Macao', 1, 'MO', 'MA', 0, 'MOP', 'Pataca', 'MOP', 'mo.png'),
(131, 'Morocco', 1, 'MA', 'MA', 0, 'MAD', 'Dirham', '', 'ma.png'),
(132, 'Monaco', 1, 'MC', 'MC', 0, 'EUR', 'Euro', '€', 'mc.png'),
(133, 'Moldova', 1, 'MD', 'MD', 0, 'MDL', 'Leu', '', 'md.png'),
(134, 'Madagascar', 1, 'MG', 'MD', 0, 'MGA', 'Ariary', '', 'mg.png'),
(135, 'Maldives', 1, 'MV', 'MD', 0, 'MVR', 'Rufiyaa', 'Rf', 'mv.png'),
(136, 'Mexico', 1, 'MX', 'ME', 0, 'MXN', 'Peso', '$', 'mx.png'),
(137, 'Marshall Islands', 1, 'MH', 'MH', 0, 'USD', 'Dollar', '$', 'mh.png'),
(138, 'Macedonia', 1, 'MK', 'MK', 0, 'MKD', 'Denar', 'ден', 'mk.png'),
(139, 'Mali', 1, 'ML', 'ML', 0, 'XOF', 'Franc', '', 'ml.png'),
(140, 'Malta', 1, 'MT', 'ML', 0, 'MTL', 'Lira', '', 'mt.png'),
(141, 'Myanmar', 1, 'MM', 'MM', 0, 'MMK', 'Kyat', 'K', 'mm.png'),
(142, 'Mongolia', 1, 'MN', 'MN', 0, 'MNT', 'Tugrik', '₮', 'mn.png'),
(143, 'Northern Mariana Islands', 1, 'MP', 'MN', 0, 'USD', 'Dollar', '$', 'mp.png'),
(144, 'Mozambique', 1, 'MZ', 'MO', 0, 'MZN', 'Meticail', 'MT', 'mz.png'),
(145, 'Mauritania', 1, 'MR', 'MR', 0, 'MRO', 'Ouguiya', 'UM', 'mr.png'),
(146, 'Montserrat', 1, 'MS', 'MS', 0, 'XCD', 'Dollar', '$', 'ms.png'),
(147, 'Martinique', 1, 'MQ', 'MT', 0, 'EUR', 'Euro', '€', 'mq.png'),
(148, 'Mauritius', 1, 'MU', 'MU', 0, 'MUR', 'Rupee', '₨', 'mu.png'),
(149, 'Malawi', 1, 'MW', 'MW', 0, 'MWK', 'Kwacha', 'MK', 'mw.png'),
(150, 'Malaysia', 1, 'MY', 'MY', 0, 'MYR', 'Ringgit', 'RM', 'my.png'),
(151, 'Mayotte', 1, 'YT', 'MY', 0, 'EUR', 'Euro', '€', 'yt.png'),
(152, 'Namibia', 1, 'NA', 'NA', 0, 'NAD', 'Dollar', '$', 'na.png'),
(153, 'New Caledonia', 1, 'NC', 'NC', 0, 'XPF', 'Franc', '', 'nc.png'),
(154, 'Niger', 1, 'NE', 'NE', 0, 'XOF', 'Franc', '', 'ne.png'),
(155, 'Norfolk Island', 1, 'NF', 'NF', 0, 'AUD', 'Dollar', '$', 'nf.png'),
(156, 'Nigeria', 1, 'NG', 'NG', 0, 'NGN', 'Naira', '₦', 'ng.png'),
(157, 'Nicaragua', 1, 'NI', 'NI', 0, 'NIO', 'Cordoba', 'C$', 'ni.png'),
(158, 'Niue', 1, 'NU', 'NI', 0, 'NZD', 'Dollar', '$', 'nu.png'),
(159, 'Netherlands', 1, 'NL', 'NL', 0, 'EUR', 'Euro', '€', 'nl.png'),
(160, 'Norway', 1, 'NO', 'NO', 0, 'NOK', 'Krone', 'kr', 'no.png'),
(161, 'Nepal', 1, 'NP', 'NP', 0, 'NPR', 'Rupee', '₨', 'np.png'),
(162, 'Nauru', 1, 'NR', 'NR', 0, 'AUD', 'Dollar', '$', 'nr.png'),
(163, 'New Zealand', 1, 'NZ', 'NZ', 0, 'NZD', 'Dollar', '$', 'nz.png'),
(164, 'Oman', 1, 'OM', 'OM', 0, 'OMR', 'Rial', '﷼', 'om.png'),
(165, 'Pakistan', 1, 'PK', 'PA', 0, 'PKR', 'Rupee', '₨', 'pk.png'),
(166, 'Panama', 1, 'PA', 'PA', 0, 'PAB', 'Balboa', 'B/.', 'pa.png'),
(167, 'Pitcairn', 1, 'PN', 'PC', 0, 'NZD', 'Dollar', '$', 'pn.png'),
(168, 'Peru', 1, 'PE', 'PE', 0, 'PEN', 'Sol', 'S/.', 'pe.png'),
(169, 'Philippines', 1, 'PH', 'PH', 0, 'PHP', 'Peso', 'Php', 'ph.png'),
(170, 'Palau', 1, 'PW', 'PL', 0, 'USD', 'Dollar', '$', 'pw.png'),
(171, 'Papua New Guinea', 1, 'PG', 'PN', 0, 'PGK', 'Kina', '', 'pg.png'),
(172, 'Poland', 1, 'PL', 'PO', 0, 'PLN', 'Zloty', 'zł', 'pl.png'),
(173, 'Puerto Rico', 1, 'PR', 'PR', 0, 'USD', 'Dollar', '$', 'pr.png'),
(174, 'North Korea', 1, 'KP', 'PR', 0, 'KPW', 'Won', '₩', 'kp.png'),
(175, 'Portugal', 1, 'PT', 'PR', 0, 'EUR', 'Euro', '€', 'pt.png'),
(176, 'Paraguay', 1, 'PY', 'PR', 0, 'PYG', 'Guarani', 'Gs', 'py.png'),
(178, 'French Polynesia', 1, 'PF', 'PY', 0, 'XPF', 'Franc', '', 'pf.png'),
(179, 'Qatar', 1, 'QA', 'QA', 0, 'QAR', 'Rial', '﷼', 'qa.png'),
(181, 'Romania', 1, 'RO', 'RO', 0, 'RON', 'Leu', 'lei', 'ro.png'),
(183, 'Rwanda', 1, 'RW', 'RW', 0, 'RWF', 'Franc', '', 'rw.png'),
(184, 'Saudi Arabia', 1, 'SA', 'SA', 0, 'SAR', 'Rial', '﷼', 'sa.png'),
(185, 'Sudan', 1, 'SD', 'SD', 0, 'SDD', 'Dinar', '', 'sd.png'),
(186, 'Senegal', 1, 'SN', 'SE', 0, 'XOF', 'Franc', '', 'sn.png'),
(187, 'Singapore', 1, 'SG', 'SG', 0, 'SGD', 'Dollar', '$', 'sg.png'),
(189, 'Saint Helena', 1, 'SH', 'SH', 0, 'SHP', 'Pound', '£', 'sh.png'),
(190, 'Svalbard and Jan Mayen', 1, 'SJ', 'SJ', 0, 'NOK', 'Krone', 'kr', 'sj.png'),
(191, 'Solomon Islands', 1, 'SB', 'SL', 0, 'SBD', 'Dollar', '$', 'sb.png'),
(192, 'Sierra Leone', 1, 'SL', 'SL', 0, 'SLL', 'Leone', 'Le', 'sl.png'),
(193, 'El Salvador', 1, 'SV', 'SL', 0, 'SVC', 'Colone', '$', 'sv.png'),
(194, 'San Marino', 1, 'SM', 'SM', 0, 'EUR', 'Euro', '€', 'sm.png'),
(195, 'Somalia', 1, 'SO', 'SO', 0, 'SOS', 'Shilling', 'S', 'so.png'),
(196, 'Saint Pierre and Miquelon', 1, 'PM', 'SP', 0, 'EUR', 'Euro', '€', 'pm.png'),
(197, 'Sao Tome and Principe', 1, 'ST', 'ST', 0, 'STD', 'Dobra', 'Db', 'st.png'),
(198, 'Suriname', 1, 'SR', 'SU', 0, 'SRD', 'Dollar', '$', 'sr.png'),
(199, 'Slovakia', 1, 'SK', 'SV', 0, 'SKK', 'Koruna', 'Sk', 'sk.png'),
(200, 'Slovenia', 1, 'SI', 'SV', 0, 'EUR', 'Euro', '€', 'si.png'),
(201, 'Sweden', 1, 'SE', 'SW', 0, 'SEK', 'Krona', 'kr', 'se.png'),
(202, 'Swaziland', 1, 'SZ', 'SW', 0, 'SZL', 'Lilangeni', '', 'sz.png'),
(203, 'Seychelles', 1, 'SC', 'SY', 0, 'SCR', 'Rupee', '₨', 'sc.png'),
(204, 'Syria', 1, 'SY', 'SY', 0, 'SYP', 'Pound', '£', 'sy.png'),
(205, 'Turks and Caicos Islands', 1, 'TC', 'TC', 0, 'USD', 'Dollar', '$', 'tc.png'),
(206, 'Chad', 1, 'TD', 'TC', 0, 'XAF', 'Franc', '', 'td.png'),
(207, 'Togo', 1, 'TG', 'TG', 0, 'XOF', 'Franc', '', 'tg.png'),
(208, 'Thailand', 1, 'TH', 'TH', 0, 'THB', 'Baht', '฿', 'th.png'),
(209, 'Tajikistan', 1, 'TJ', 'TJ', 0, 'TJS', 'Somoni', '', 'tj.png'),
(210, 'Tokelau', 1, 'TK', 'TK', 0, 'NZD', 'Dollar', '$', 'tk.png'),
(211, 'Turkmenistan', 1, 'TM', 'TK', 0, 'TMM', 'Manat', 'm', 'tm.png'),
(212, 'East Timor', 1, 'TL', 'TL', 0, 'USD', 'Dollar', '$', 'tl.png'),
(213, 'Tonga', 1, 'TO', 'TO', 0, 'TOP', 'Pa\"anga', 'T$', 'to.png'),
(214, 'Trinidad and Tobago', 1, 'TT', 'TT', 0, 'TTD', 'Dollar', 'TT$', 'tt.png'),
(215, 'Tunisia', 1, 'TN', 'TU', 0, 'TND', 'Dinar', '', 'tn.png'),
(216, 'Turkey', 1, 'TR', 'TU', 0, 'TRY', 'Lira', 'YTL', 'tr.png'),
(217, 'Tuvalu', 1, 'TV', 'TU', 0, 'AUD', 'Dollar', '$', 'tv.png'),
(218, 'Taiwan', 1, 'TW', 'TW', 0, 'TWD', 'Dollar', 'NT$', 'tw.png'),
(219, 'Tanzania', 1, 'TZ', 'TZ', 0, 'TZS', 'Shilling', '', 'tz.png'),
(220, 'Uganda', 1, 'UG', 'UG', 0, 'UGX', 'Shilling', '', 'ug.png'),
(221, 'Ukraine', 1, 'UA', 'UK', 0, 'UAH', 'Hryvnia', '₴', 'ua.png'),
(223, 'Uruguay', 1, 'UY', 'UR', 0, 'UYU', 'Peso', '$U', 'uy.png'),
(224, 'United States', 1, 'US', 'US', 0, 'USD', 'Dollar', '$', 'us.png'),
(225, 'Uzbekistan', 1, 'UZ', 'UZ', 0, 'UZS', 'Som', 'лв', 'uz.png'),
(228, 'Venezuela', 1, 'VE', 'VE', 0, 'VEF', 'Bolivar', 'Bs', 've.png'),
(231, 'Vietnam', 1, 'VN', 'VN', 0, 'VND', 'Dong', '₫', 'vn.png'),
(232, 'Vanuatu', 1, 'VU', 'VU', 0, 'VUV', 'Vatu', 'Vt', 'vu.png'),
(233, 'Wallis and Futuna', 1, 'WF', 'WL', 0, 'XPF', 'Franc', '', 'wf.png'),
(234, 'Samoa', 1, 'WS', 'WS', 0, 'WST', 'Tala', 'WS$', 'ws.png'),
(235, 'Yemen', 1, 'YE', 'YE', 0, 'YER', 'Rial', '﷼', 'ye.png'),
(237, 'South Africa', 1, 'ZA', 'ZA', 0, 'ZAR', 'Rand', 'R', 'za.png'),
(238, 'Zambia', 1, 'ZM', 'ZM', 0, 'ZMK', 'Kwacha', 'ZK', 'zm.png'),
(239, 'Zimbabwe', 1, 'ZW', 'ZW', 0, 'ZWD', 'Dollar', 'Z$', 'zw.png');

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes`
--

CREATE TABLE `credit_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `cn_number` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `discount` double NOT NULL DEFAULT 0,
  `discount_type` enum('percent','fixed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'percent',
  `sub_total` double(8,2) NOT NULL,
  `total` double(8,2) NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('closed','open') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'closed',
  `recurring` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `billing_frequency` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_interval` int(11) DEFAULT NULL,
  `billing_cycle` int(11) DEFAULT NULL,
  `file` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_original_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes_invoice`
--

CREATE TABLE `credit_notes_invoice` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `credit_notes_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `credit_amount` double(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_note_items`
--

CREATE TABLE `credit_note_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `credit_note_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('item','discount','tax') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'item',
  `quantity` int(11) NOT NULL,
  `unit_price` double(8,2) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `taxes` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_code` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `exchange_rate` double DEFAULT NULL,
  `is_cryptocurrency` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `usd_price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `company_id`, `currency_name`, `currency_symbol`, `currency_code`, `exchange_rate`, `is_cryptocurrency`, `usd_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dollars', '$', 'USD', NULL, 'no', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(2, 1, 'Pounds', '£', 'GBP', NULL, 'no', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(3, 1, 'Euros', '€', 'EUR', NULL, 'no', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(4, 1, 'Rupee', '₹', 'INR', NULL, 'no', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `custom_field_group_id` int(10) UNSIGNED DEFAULT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `required` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `values` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_data`
--

CREATE TABLE `custom_fields_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `custom_field_id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(10000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_groups`
--

CREATE TABLE `custom_field_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `custom_field_groups`
--

INSERT INTO `custom_field_groups` (`id`, `company_id`, `name`, `model`) VALUES
(1, 1, 'Client', 'App\\ClientDetails'),
(2, 1, 'Employee', 'App\\EmployeeDetails'),
(3, 1, 'Project', 'App\\Project');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_widgets`
--

CREATE TABLE `dashboard_widgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `widget_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dashboard_widgets`
--

INSERT INTO `dashboard_widgets` (`id`, `company_id`, `widget_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'total_clients', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(2, 1, 'total_employees', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(3, 1, 'total_projects', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(4, 1, 'total_unpaid_invoices', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(5, 1, 'total_hours_logged', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(6, 1, 'total_pending_tasks', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(7, 1, 'total_today_attendance', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(8, 1, 'total_unresolved_tickets', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(9, 1, 'total_resolved_tickets', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(10, 1, 'recent_earnings', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(11, 1, 'settings_leaves', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(12, 1, 'new_tickets', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(13, 1, 'overdue_tasks', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(14, 1, 'completed_tasks', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(15, 1, 'client_feedbacks', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(16, 1, 'pending_follow_up', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(17, 1, 'project_activity_timeline', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(18, 1, 'user_activity_timeline', 1, '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `company_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Senior Designer', '2020-04-09 21:40:32', '2020-04-09 21:40:43'),
(2, 1, 'Project Manager', '2020-04-09 21:40:50', '2020-04-09 21:40:50'),
(3, 1, 'Junior Designer', '2020-04-09 21:41:02', '2020-04-09 21:41:02'),
(4, 1, 'Junior Developer', '2020-04-09 21:41:09', '2020-04-09 21:41:09'),
(5, 1, 'Senior Developer', '2020-04-09 21:41:15', '2020-04-09 21:41:15'),
(6, 1, 'Managing Director', '2020-04-09 21:42:50', '2020-04-09 21:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_notification_settings`
--

CREATE TABLE `email_notification_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `setting_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `send_email` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `send_slack` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `send_push` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_notification_settings`
--

INSERT INTO `email_notification_settings` (`id`, `company_id`, `setting_name`, `send_email`, `send_slack`, `send_push`, `created_at`, `updated_at`) VALUES
(7, 1, 'New Expense/Added by Admin', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(8, 1, 'New Expense/Added by Member', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(9, 1, 'Expense Status Changed', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(10, 1, 'New Support Ticket Request', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(11, 1, 'User Registration/Added by Admin', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(12, 1, 'Employee Assign to Project', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(13, 1, 'New Notice Published', 'no', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(14, 1, 'User Assign to Task', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(15, 1, 'New Leave Application', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(16, 1, 'Task Completed', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(17, 1, 'Invoice Create/Update Notification', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(18, 1, 'Payment Create/Update Notification', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(19, NULL, 'User Registration/Added by Admin', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(20, NULL, 'Employee Assign to Project', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(21, NULL, 'New Notice Published', 'no', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(22, NULL, 'User Assign to Task', 'yes', 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `employee_details`
--

CREATE TABLE `employee_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hourly_rate` double DEFAULT NULL,
  `slack_username` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `joining_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employee_details`
--

INSERT INTO `employee_details` (`id`, `company_id`, `user_id`, `employee_id`, `address`, `hourly_rate`, `slack_username`, `department_id`, `designation_id`, `created_at`, `updated_at`, `joining_date`, `last_date`) VALUES
(1, 1, 1, 'ES000', 'address', 50, NULL, 4, 6, '2020-04-09 11:24:06', '2020-04-09 21:43:55', '2020-04-09 17:00:00', NULL),
(2, 1, 2, 'emp-2', 'address', 50, NULL, NULL, NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06', '2020-04-09 18:24:06', NULL),
(3, 1, 4, 'ES001', NULL, NULL, NULL, 2, 2, '2020-04-09 21:42:08', '2020-04-09 21:42:08', '2020-04-09 17:00:00', NULL),
(4, 1, 5, 'ES002', NULL, NULL, NULL, 1, 1, '2020-04-09 21:44:31', '2020-04-09 21:44:31', '2020-04-07 17:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_docs`
--

CREATE TABLE `employee_docs` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hashname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_skills`
--

CREATE TABLE `employee_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `skill_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_teams`
--

CREATE TABLE `employee_teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `estimate_number` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid_till` date NOT NULL,
  `sub_total` double(16,2) NOT NULL,
  `total` double(16,2) NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('declined','accepted','waiting') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `discount_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_items`
--

CREATE TABLE `estimate_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `estimate_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `item_summary` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('item','discount','tax') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'item',
  `quantity` double(16,2) NOT NULL,
  `unit_price` double(16,2) NOT NULL,
  `amount` double(16,2) NOT NULL,
  `taxes` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `event_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `label_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `where` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime NOT NULL,
  `repeat` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `repeat_every` int(11) DEFAULT NULL,
  `repeat_cycles` int(11) DEFAULT NULL,
  `repeat_type` enum('day','week','month','year') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'day',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

CREATE TABLE `event_attendees` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `event_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `item_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_from` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double(8,2) NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `bill` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `faq_category_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('image','icon','task','bills','team','apps') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'image',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `title`, `description`, `image`, `icon`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Business Needs.', '<p>Manage your projects and your talent in a single system, resulting in empowered teams, satisfied clients, and increased profitability.</p>', NULL, NULL, 'image', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(2, 'Reports', '<p>Reports section to analyse what\'s working and what\'s not for your business</p>', NULL, NULL, 'image', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(3, 'Tickets', '<p>Whether someone\'s internet is not working, someone is facing issue with housekeeping or need something regarding their work they can raise ticket for all their problems. Admin can assign the tickets to respective department agents.</p>', NULL, NULL, 'image', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(4, 'Responsive', 'Your website works on any device: desktop, tablet or mobile.', NULL, 'fas fa-desktop', 'icon', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(5, 'Customizable', 'You can easily read, edit, and write your own code, or change everything.', NULL, 'fas fa-wrench', 'icon', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(6, 'UI Elements', 'There is a bunch of useful and necessary elements for developing your website.', NULL, 'fas fa-cubes', 'icon', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(7, 'Clean Code', 'You can find our code well organized, commented and readable.', NULL, 'fas fa-code', 'icon', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(8, 'Documented', 'As you can see in the source code, we provided a comprehensive documentation.', NULL, 'far fa-file-alt', 'icon', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(9, 'Free Updates', 'When you purchase this template, you\'ll freely receive future updates.', NULL, 'fas fa-download', 'icon', '2020-04-09 11:24:01', '2020-04-09 11:24:01'),
(10, 'Track Projects', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Keep a track of all your projects in the most simple way.</span>', NULL, 'fas fa-desktop', 'task', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(11, 'Add Members', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Add members to your projects and keep them in sync with the progress.</span>', NULL, 'fas fa-users', 'task', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(12, 'Assign Tasks', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Your website is fully responsive, it will work on any device, desktop, tablet and mobile.</span>', NULL, 'fas fa-list', 'task', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(13, 'Estimates', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Create estimates how much project can cost and send to your clients.</span>', NULL, 'fas fa-calculator', 'bills', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(14, 'Invoices', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Simple and professional invoices can be download in form of PDF.</span>', NULL, 'far fa-file-alt', 'bills', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(15, 'Payments', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Track payments done by clients in the payment section.</span>', NULL, 'fas fa-money-bill-alt', 'bills', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(16, 'Tickets', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">When someone is facing a problem, they can raise a ticket for their problems. Admin can assign the tickets to respective department agents.</span>', NULL, 'fas fa-ticket-alt', 'team', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(17, 'Leaves', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Employees can apply for the multiple leaves from their panel. Admin can approve or reject the leave applications.</span>', NULL, 'fas fa-ban', 'team', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(18, 'Attendance', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px; text-align: center;\">Attendance module allows employees to clock-in and clock-out, right from their dashboard. Admin can track the attendance of the team.</span>', NULL, 'far fa-check-circle', 'team', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(19, 'Github', NULL, NULL, NULL, 'apps', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(20, 'OneSignal', NULL, NULL, NULL, 'apps', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(21, 'Mailchimp', NULL, NULL, NULL, 'apps', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(22, 'Dropbox', NULL, NULL, NULL, 'apps', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(23, 'Slack', NULL, NULL, NULL, 'apps', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(24, 'Paypal', NULL, NULL, NULL, 'apps', '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `file_storage_settings`
--

CREATE TABLE `file_storage_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `filesystem` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `auth_keys` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_menu`
--

CREATE TABLE `footer_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_clients`
--

CREATE TABLE `front_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `front_clients`
--

INSERT INTO `front_clients` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Client 1', NULL, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(2, 'Client 2', NULL, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(3, 'Client 3', NULL, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(4, 'Client 4', NULL, '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `front_details`
--

CREATE TABLE `front_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `header_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `header_description` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `get_started_show` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `sign_in_show` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `feature_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `feature_description` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price_description` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_links` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `primary_color` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_management_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_management_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `manage_bills_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manage_bills_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `teamates_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `teamates_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `favourite_apps_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favourite_apps_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `cta_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cta_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `testimonial_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `testimonial_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `faq_title` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faq_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer_copyright_text` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `front_details`
--

INSERT INTO `front_details` (`id`, `header_title`, `header_description`, `image`, `get_started_show`, `sign_in_show`, `feature_title`, `feature_description`, `price_title`, `price_description`, `address`, `phone`, `email`, `social_links`, `created_at`, `updated_at`, `primary_color`, `task_management_title`, `task_management_detail`, `manage_bills_title`, `manage_bills_detail`, `teamates_title`, `teamates_detail`, `favourite_apps_title`, `favourite_apps_detail`, `cta_title`, `cta_detail`, `client_title`, `client_detail`, `testimonial_title`, `testimonial_detail`, `faq_title`, `faq_detail`, `footer_copyright_text`) VALUES
(1, 'Project Management System', 'The most powerful and simple way to collaborate with your team', '', 'yes', 'yes', 'Team communications for the 21st century.', NULL, 'Affordable Pricing', 'Worksuite for Teams is a single workspace for your small- to medium-sized company or team.', 'Company address', '+91 1234567890', 'company@example.com', '[{\"name\":\"facebook\",\"link\":\"https:\\/\\/facebook.com\"},{\"name\":\"twitter\",\"link\":\"https:\\/\\/twitter.com\"},{\"name\":\"instagram\",\"link\":\"https:\\/\\/instagram.com\"},{\"name\":\"dribbble\",\"link\":\"https:\\/\\/dribbble.com\"}]', '2020-04-09 11:24:01', '2020-04-09 11:24:05', '#2f20db', 'Task Management', 'Manage your projects and your talent in a single system, resulting in empowered teams, satisfied clients, and increased profitability.', 'Manages All Your Bills', 'Manage your Automate billing and revenue recognition to streamline the contract-to-cash cycle.', 'Manages All Your Bills', 'Manage your Automate billing and revenue recognition to streamline the contract-to-cash cycle.', 'Integrate With Your Favourite Apps.', 'Our app gives you the added advantage of several other third party apps through seamless integrations.', 'Managing Business Has Never Been So Easy.', 'Don\'t hesitate, Our experts will show you how our application can streamline the way your team works.', 'We Build Trust', 'More Than 700 People Use Our Product.', 'Loved By Businesses, And Individuals Across The Globe', NULL, 'Frequently Asked Questions', NULL, 'Copyright © 2020. All Rights Reserved');

-- --------------------------------------------------------

--
-- Table structure for table `front_faqs`
--

CREATE TABLE `front_faqs` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `front_faqs`
--

INSERT INTO `front_faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'Can i see demo?', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px;\">Yes, definitely. We would be happy to demonstrate you Worksuite through a web conference at your convenience. Please submit a query on our contact us page or drop a mail to our mail id worksuite@froiden.com.</span>', '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(2, 'How can i update app?', '<span style=\"color: rgb(68, 68, 68); font-family: Lato, sans-serif; font-size: 16px;\">Yes, definitely. We would be happy to demonstrate you Worksuite through a web conference at your convenience. Please submit a query on our contact us page or drop a mail to our mail id worksuite@froiden.com.</span>', '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `front_menu_buttons`
--

CREATE TABLE `front_menu_buttons` (
  `id` int(10) UNSIGNED NOT NULL,
  `home` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'home',
  `feature` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'feature',
  `price` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'price',
  `contact` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'contact',
  `get_start` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'get_start',
  `login` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'login',
  `contact_submit` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'contact_submit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `front_menu_buttons`
--

INSERT INTO `front_menu_buttons` (`id`, `home`, `feature`, `price`, `contact`, `get_start`, `login`, `contact_submit`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'Features', 'Pricing', 'Contact', 'Get Started', 'Login', 'Submit Enquiry', '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `gdpr_settings`
--

CREATE TABLE `gdpr_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `enable_gdpr` tinyint(1) NOT NULL DEFAULT 0,
  `show_customer_area` tinyint(1) NOT NULL DEFAULT 0,
  `show_customer_footer` tinyint(1) NOT NULL DEFAULT 0,
  `top_information_block` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_export` tinyint(1) NOT NULL DEFAULT 0,
  `data_removal` tinyint(1) NOT NULL DEFAULT 0,
  `lead_removal_public_form` tinyint(1) NOT NULL DEFAULT 0,
  `terms_customer_footer` tinyint(1) NOT NULL DEFAULT 0,
  `terms` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `policy` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_lead_edit` tinyint(1) NOT NULL DEFAULT 0,
  `consent_customer` tinyint(1) NOT NULL DEFAULT 0,
  `consent_leads` tinyint(1) NOT NULL DEFAULT 0,
  `consent_block` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gdpr_settings`
--

INSERT INTO `gdpr_settings` (`id`, `company_id`, `enable_gdpr`, `show_customer_area`, `show_customer_footer`, `top_information_block`, `enable_export`, `data_removal`, `lead_removal_public_form`, `terms_customer_footer`, `terms`, `policy`, `public_lead_edit`, `consent_customer`, `consent_leads`, `consent_block`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `global_currencies`
--

CREATE TABLE `global_currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `exchange_rate` double DEFAULT NULL,
  `usd_price` double DEFAULT NULL,
  `is_cryptocurrency` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `global_currencies`
--

INSERT INTO `global_currencies` (`id`, `currency_name`, `currency_symbol`, `currency_code`, `exchange_rate`, `usd_price`, `is_cryptocurrency`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dollars', '$', 'USD', 1, NULL, 'no', '2020-04-09 11:24:05', '2020-04-09 22:00:34', NULL),
(2, 'Pounds', '£', 'GBP', NULL, NULL, 'no', '2020-04-09 11:24:05', '2020-04-09 21:57:04', '2020-04-09 21:57:04'),
(3, 'Euros', '€', 'EUR', NULL, NULL, 'no', '2020-04-09 11:24:05', '2020-04-09 21:57:07', '2020-04-09 21:57:07'),
(4, 'Rupee', '₹', 'INR', NULL, NULL, 'no', '2020-04-09 11:24:05', '2020-04-09 21:57:09', '2020-04-09 21:57:09'),
(5, 'Rupiah', 'IDR', 'IDR', 15700, NULL, 'no', '2020-04-09 21:59:42', '2020-04-09 22:00:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE `global_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `timezone` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Asia/Kolkata',
  `locale` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `company_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_phone` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_background` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_updated_by` int(10) UNSIGNED DEFAULT NULL,
  `front_design` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_map_key` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `currency_converter_key` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha_key` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_recaptcha_secret` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supported_until` timestamp NULL DEFAULT NULL,
  `hide_cron_message` tinyint(1) NOT NULL DEFAULT 0,
  `week_start` int(11) NOT NULL DEFAULT 1,
  `system_update` tinyint(1) NOT NULL DEFAULT 1,
  `email_verification` tinyint(1) NOT NULL DEFAULT 1,
  `logo_background_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#171e28',
  `currency_key_version` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'free',
  `show_review_modal` tinyint(1) NOT NULL DEFAULT 1,
  `logo_front` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `currency_id`, `timezone`, `locale`, `company_name`, `company_email`, `company_phone`, `logo`, `login_background`, `address`, `website`, `last_updated_by`, `front_design`, `created_at`, `updated_at`, `google_map_key`, `currency_converter_key`, `google_recaptcha_key`, `google_recaptcha_secret`, `purchase_code`, `supported_until`, `hide_cron_message`, `week_start`, `system_update`, `email_verification`, `logo_background_color`, `currency_key_version`, `show_review_modal`, `logo_front`) VALUES
(1, 1, 'Asia/Jakarta', 'en', 'PT Horison Ekspansi Indonesia', 'hello@hei.co.id', '1234567891', '3c7de7ec21f46466484299e616652bfd.png', NULL, 'Company address', 'www.domain.com', 3, 1, '2020-04-09 11:24:05', '2020-04-09 22:06:43', '', '6c12788708871d0c499d', NULL, NULL, NULL, NULL, 0, 1, 0, 1, '#171e28', 'free', 1, '178373afca2d9c3bc4aeb5549d2f3968.png');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `occassion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `sub_total` double(16,2) NOT NULL,
  `discount` double NOT NULL DEFAULT 0,
  `discount_type` enum('percent','fixed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'percent',
  `total` double(16,2) NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('paid','unpaid','partial','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid',
  `recurring` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `billing_cycle` int(11) DEFAULT NULL,
  `billing_interval` int(11) DEFAULT NULL,
  `billing_frequency` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_original_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_note` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `estimate_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `item_summary` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('item','discount','tax') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'item',
  `quantity` double(16,2) NOT NULL,
  `unit_price` double(16,2) NOT NULL,
  `amount` double(16,2) NOT NULL,
  `taxes` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_settings`
--

CREATE TABLE `invoice_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_prefix` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_digit` int(10) UNSIGNED NOT NULL DEFAULT 3,
  `estimate_prefix` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'EST',
  `estimate_digit` int(10) UNSIGNED NOT NULL DEFAULT 3,
  `credit_note_prefix` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CN',
  `credit_note_digit` int(10) UNSIGNED NOT NULL DEFAULT 3,
  `template` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `due_after` int(11) NOT NULL,
  `invoice_terms` text COLLATE utf8_unicode_ci NOT NULL,
  `gst_number` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_gst` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `logo` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoice_settings`
--

INSERT INTO `invoice_settings` (`id`, `company_id`, `invoice_prefix`, `invoice_digit`, `estimate_prefix`, `estimate_digit`, `credit_note_prefix`, `credit_note_digit`, `template`, `due_after`, `invoice_terms`, `gst_number`, `show_gst`, `created_at`, `updated_at`, `logo`) VALUES
(1, NULL, 'INV', 3, 'EST', 3, 'CN', 3, 'invoice-1', 15, 'Thank you for your business. Please process this invoice within the due date.', NULL, 'no', '2020-04-09 11:23:53', '2020-04-09 11:23:53', NULL),
(2, 1, 'INV', 3, 'EST', 3, 'CN', 3, 'invoice-1', 15, 'Thank you for your business. Please process this invoice within the due date.', NULL, 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('pending','resolved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_settings`
--

CREATE TABLE `language_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_code` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `language_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `language_settings`
--

INSERT INTO `language_settings` (`id`, `language_code`, `language_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ar', 'Arabic', 'disabled', NULL, NULL),
(2, 'de', 'German', 'disabled', NULL, NULL),
(3, 'es', 'Spanish', 'disabled', NULL, '2020-04-09 22:03:06'),
(4, 'et', 'Estonian', 'disabled', NULL, NULL),
(5, 'fa', 'Farsi', 'disabled', NULL, NULL),
(6, 'fr', 'French', 'disabled', NULL, '2020-04-09 22:03:07'),
(7, 'gr', 'Greek', 'disabled', NULL, NULL),
(8, 'it', 'Italian', 'disabled', NULL, NULL),
(9, 'nl', 'Dutch', 'disabled', NULL, NULL),
(10, 'pl', 'Polish', 'disabled', NULL, NULL),
(11, 'pt', 'Portuguese', 'disabled', NULL, NULL),
(12, 'pt-br', 'Portuguese (Brazil)', 'disabled', NULL, NULL),
(13, 'ro', 'Romanian', 'disabled', NULL, NULL),
(14, 'ru', 'Russian', 'disabled', NULL, '2020-04-09 22:02:59'),
(15, 'tr', 'Turkish', 'disabled', NULL, NULL),
(16, 'zh-CN', 'Chinese (S)', 'disabled', NULL, NULL),
(17, 'zh-TW', 'Chinese (T)', 'disabled', NULL, NULL),
(18, 'ID', 'Indonesian', 'enabled', '2020-04-09 22:02:55', '2020-04-09 22:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `agent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `client_email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `next_follow_up` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_agents`
--

CREATE TABLE `lead_agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_files`
--

CREATE TABLE `lead_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `lead_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hashname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_url` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropbox_link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_follow_up`
--

CREATE TABLE `lead_follow_up` (
  `id` int(10) UNSIGNED NOT NULL,
  `lead_id` int(10) UNSIGNED NOT NULL,
  `remark` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `next_follow_up_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_sources`
--

CREATE TABLE `lead_sources` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_sources`
--

INSERT INTO `lead_sources` (`id`, `company_id`, `type`, `created_at`, `updated_at`) VALUES
(1, NULL, 'email', NULL, NULL),
(2, NULL, 'google', NULL, NULL),
(3, NULL, 'facebook', NULL, NULL),
(4, NULL, 'friend', NULL, NULL),
(5, NULL, 'direct visit', NULL, NULL),
(6, NULL, 'tv ad', NULL, NULL),
(7, 1, 'email', NULL, NULL),
(8, 1, 'google', NULL, NULL),
(9, 1, 'facebook', NULL, NULL),
(10, 1, 'friend', NULL, NULL),
(11, 1, 'direct visit', NULL, NULL),
(12, 1, 'tv ad', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lead_status`
--

CREATE TABLE `lead_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_status`
--

INSERT INTO `lead_status` (`id`, `company_id`, `type`, `created_at`, `updated_at`) VALUES
(1, NULL, 'pending', NULL, NULL),
(2, NULL, 'inprocess', NULL, NULL),
(3, NULL, 'converted', NULL, NULL),
(4, 1, 'pending', NULL, NULL),
(5, 1, 'inprocess', NULL, NULL),
(6, 1, 'converted', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` int(10) UNSIGNED NOT NULL,
  `duration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `leave_date` date NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('approved','pending','rejected') COLLATE utf8_unicode_ci NOT NULL,
  `reject_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `type_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `no_of_leaves` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `company_id`, `type_name`, `color`, `no_of_leaves`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Casual', 'success', 5, '2020-04-09 11:23:55', '2020-04-09 11:23:55'),
(2, NULL, 'Sick', 'danger', 5, '2020-04-09 11:23:55', '2020-04-09 11:23:55'),
(3, NULL, 'Earned', 'info', 5, '2020-04-09 11:23:55', '2020-04-09 11:23:55'),
(4, 1, 'Casual', 'success', 5, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(5, 1, 'Sick', 'danger', 5, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(6, 1, 'Earned', 'info', 5, '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `licences`
--

CREATE TABLE `licences` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `license_number` char(29) COLLATE utf8_unicode_ci NOT NULL,
  `package_id` int(10) UNSIGNED DEFAULT NULL,
  `company_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_person` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `last_payment_date` date DEFAULT NULL,
  `next_payment_date` date DEFAULT NULL,
  `status` enum('valid','invalid') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'valid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_time_for`
--

CREATE TABLE `log_time_for` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `log_time_for` enum('project','task') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'project',
  `auto_timer_stop` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `log_time_for`
--

INSERT INTO `log_time_for` (`id`, `company_id`, `log_time_for`, `auto_timer_stop`, `created_at`, `updated_at`) VALUES
(1, 1, 'project', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `ltm_translations`
--

CREATE TABLE `ltm_translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `locale` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ltm_translations`
--

INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'modules', 'permission.holidayNote', 'User can view the holidays as default even without any permission.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(2, 1, 'en', 'modules', 'permission.projectNote', 'User can view the basic details of projects assigned to him even without any permission.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(3, 1, 'en', 'modules', 'permission.attendanceNote', 'User can view his own attendance even without any permission.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(4, 1, 'en', 'modules', 'permission.taskNote', 'User can view the tasks assigned to him even without any permission.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(5, 1, 'en', 'modules', 'permission.ticketNote', 'User can view the tickets generated by him as default even without any permission.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(6, 1, 'en', 'modules', 'permission.eventNote', 'User can view the events to be attended by him as default even without any permission.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(7, 0, 'en', 'modules', 'slackSettings.uploadSlackLog', NULL, '2020-04-09 22:03:31', '2020-04-09 22:03:31'),
(8, 1, 'en', 'messages', 'smtpSuccess', 'Your SMTP details are correct', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(9, 1, 'en', 'messages', 'smtpNotSet', 'You have not configured SMTP settings. You might get an error when adding info ', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(10, 1, 'en', 'app', 'datatable', '//cdn.datatables.net/plug-ins/1.10.15/i18n/English.json', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(11, 1, 'en', 'app', 'employee', 'Employee', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(12, 1, 'en', 'modules', 'attendance.present', 'Present', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(13, 1, 'en', 'modules', 'attendance.absent', 'Absent', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(14, 1, 'en', 'modules', 'attendance.hoursClocked', 'Hours Clocked', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(15, 1, 'en', 'app', 'days', 'Days', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(16, 1, 'en', 'modules', 'attendance.late', 'Late', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(17, 1, 'en', 'modules', 'attendance.halfDay', 'Half Day', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(18, 1, 'en', 'app', 'edit', 'Edit', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(19, 1, 'en', 'app', 'view', 'View', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(20, 1, 'en', 'app', 'details', 'Details', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(21, 1, 'en', 'modules', 'projects.viewGanttChart', 'Gantt Chart', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(22, 1, 'en', 'modules', 'projects.viewPublicGanttChart', 'Public Gantt Chart', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(23, 1, 'en', 'app', 'delete', 'Delete', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(24, 1, 'en', 'messages', 'noMemberAddedToProject', 'No member added to this project.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(25, 1, 'en', 'modules', 'projects.addMemberTitle', 'Add Project Members', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(26, 1, 'en', 'app', 'inProgress', 'In Progress', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(27, 1, 'en', 'app', 'onHold', 'On Hold', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(28, 1, 'en', 'app', 'notStarted', 'Not Started', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(29, 1, 'en', 'app', 'canceled', 'Canceled', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(30, 1, 'en', 'app', 'finished', 'Finished', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(31, 1, 'en', 'app', 'progress', 'Progress', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(32, 1, 'en', 'app', 'unpaid', 'Unpaid', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(33, 1, 'en', 'modules', 'projects.projectName', 'Project Name', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(34, 1, 'en', 'modules', 'projects.members', 'Members', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(35, 1, 'en', 'app', 'deadline', 'Deadline', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(36, 1, 'en', 'app', 'client', 'Client', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(37, 1, 'en', 'app', 'completion', 'Completion', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(38, 1, 'en', 'app', 'status', 'Status', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(39, 1, 'en', 'app', 'active', 'Active', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(40, 1, 'en', 'app', 'id', 'Id', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(41, 1, 'en', 'app', 'task', 'Task', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(42, 1, 'en', 'app', 'project', 'Project', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(43, 1, 'en', 'app', 'menu.employees', 'Employees', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(44, 1, 'en', 'modules', 'timeLogs.startTime', 'Start Time', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(45, 1, 'en', 'modules', 'timeLogs.endTime', 'End Time', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(46, 1, 'en', 'modules', 'timeLogs.totalHours', 'Total Hours', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(47, 1, 'en', 'app', 'earnings', 'Earnings', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(48, 1, 'en', 'app', 'title', 'Title', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(49, 1, 'en', 'modules', 'tasks.assignTo', 'Assigned To', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(50, 1, 'en', 'app', 'dueDate', 'Due Date', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(51, 1, 'en', 'app', 'inactive', 'Inactive', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(52, 1, 'en', 'app', 'name', 'Name', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(53, 1, 'en', 'modules', 'client.companyName', 'Company Name', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(54, 1, 'en', 'app', 'email', 'Email', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(55, 1, 'en', 'app', 'createdAt', 'Created At', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(56, 1, 'en', 'modules', 'lead.changeToClient', 'Change To Client', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(57, 1, 'en', 'modules', 'lead.addFollowUp', 'Add Follow Up', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(58, 1, 'en', 'modules', 'lead.view', 'View', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(59, 1, 'en', 'modules', 'lead.edit', 'Edit', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(60, 1, 'en', 'app', 'lead', 'Lead', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(61, 1, 'en', 'app', 'pending', 'Pending', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(62, 1, 'en', 'app', 'clientName', 'Client Name', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(63, 1, 'en', 'modules', 'lead.companyName', 'Company Name', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(64, 1, 'en', 'app', 'createdOn', 'Created On', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(65, 1, 'en', 'modules', 'lead.nextFollowUp', 'Next Follow Up', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(66, 1, 'en', 'app', 'invoice', 'Invoice', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(67, 1, 'en', 'modules', 'invoices.amount', 'Amount', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(68, 1, 'en', 'modules', 'payments.paidOn', 'Paid On', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(69, 1, 'en', 'app', 'remark', 'Remark', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(70, 1, 'en', 'app', 'estimate', 'Estimate', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(71, 1, 'en', 'modules', 'invoices.total', 'Total', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(72, 1, 'en', 'modules', 'estimates.validTill', 'Valid Till', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(73, 1, 'en', 'messages', 'roleCannotChange', 'Role of this user cannot be changed.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(74, 1, 'en', 'app', 'role', 'User Role', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(75, 1, 'en', 'app', 'approved', 'Approved', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(76, 1, 'en', 'app', 'menu.leaves', 'Leaves', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(77, 1, 'en', 'app', 'upcoming', 'Upcoming', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(78, 1, 'en', 'app', 'download', 'Download', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(79, 1, 'en', 'app', 'upload', 'Upload', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(80, 1, 'en', 'modules', 'payments.addPayment', 'Add Payment', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(81, 1, 'en', 'app', 'cancel', 'Cancel', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(82, 0, 'en', 'modules', 'invoices.markCancel', NULL, '2020-04-09 22:03:31', '2020-04-09 22:03:31'),
(83, 1, 'en', 'modules', 'payments.paymentLink', 'Payment Link', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(84, 1, 'en', 'modules', 'credit-notes.addCreditNote', 'Add Credit Note', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(85, 1, 'en', 'app', 'paymentReminder', 'Payment Reminder', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(86, 1, 'en', 'app', 'credit-note', 'Credit Note', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(87, 1, 'en', 'modules', 'invoices.partial', 'Partially Paid', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(88, 1, 'en', 'app', 'total', 'Total', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(89, 1, 'en', 'app', 'paid', 'Paid', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(90, 1, 'en', 'modules', 'invoices.invoiceDate', 'Invoice Date', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(91, 1, 'en', 'modules', 'tickets.agent', 'Agent', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(92, 1, 'en', 'modules', 'tasks.priority', 'Priority', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(93, 1, 'en', 'modules', 'tickets.ticket', 'Ticket', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(94, 1, 'en', 'modules', 'tickets.ticketSubject', 'Ticket Subject', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(95, 1, 'en', 'modules', 'tickets.requesterName', 'Requester Name', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(96, 1, 'en', 'modules', 'tickets.requestedOn', 'Requested On', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(97, 1, 'en', 'app', 'others', 'Others', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(98, 1, 'en', 'modules', 'expenses.itemName', 'Item Name', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(99, 1, 'en', 'app', 'price', 'Price', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(100, 1, 'en', 'modules', 'expenses.purchaseFrom', 'Purchased From', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(101, 1, 'en', 'modules', 'expenses.purchaseDate', 'Purchase Date', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(102, 1, 'en', 'app', 'allowed', 'Allowed', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(103, 1, 'en', 'app', 'notAllowed', 'Not Allowed', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(104, 1, 'en', 'app', 'inclusiveAllTaxes', 'Inclusive All Taxes', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(105, 1, 'en', 'app', 'purchaseAllow', 'Purchase Allow', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(106, 1, 'en', 'modules', 'notices.notice', 'Notice', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(107, 1, 'en', 'app', 'date', 'Date', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(108, 1, 'en', 'app', 'to', 'To', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(109, 1, 'en', 'app', 'copy', 'Copy', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(110, 1, 'en', 'app', 'subject', 'Subject', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(111, 1, 'en', 'app', 'amount', 'Amount', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(112, 1, 'en', 'app', 'startDate', 'Start Date', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(113, 1, 'en', 'app', 'endDate', 'End Date', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(114, 1, 'en', 'app', 'action', 'Action', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(115, 1, 'en', 'modules', 'credit-notes.total', 'Total', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(116, 1, 'en', 'modules', 'credit-notes.creditNoteDate', 'Credit Note Date', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(117, 1, 'en', 'app', 'private', 'Private', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(118, 1, 'en', 'app', 'required', 'Required', '2020-04-09 22:03:31', '2020-04-09 22:03:45'),
(119, 1, 'en', 'messages', 'chooseProject', 'Choose a project.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(120, 1, 'en', 'messages', 'atleastOneValidation', 'Choose at-least 1', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(121, 1, 'en', 'modules', 'tickets.groupName', 'Group Name', '2020-04-09 22:03:31', '2020-04-09 22:03:47'),
(122, 1, 'en', 'messages', 'notificationRead', 'Notification marked as read.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(123, 1, 'en', 'auth', 'recaptchaFailed', 'Recaptcha not validated.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(124, 1, 'en', 'messages', 'ticketAddSuccess', 'Ticket created successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(125, 1, 'en', 'messages', 'ticketReplySuccess', 'Reply sent successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(126, 1, 'en', 'messages', 'ticketDeleteSuccess', 'Ticket deleted successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(127, 1, 'en', 'modules', 'client.viewDetails', 'View Details', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(128, 1, 'en', 'messages', 'newTaskAddedToTheProject', 'New task added to the project.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(129, 1, 'en', 'messages', 'taskCreatedSuccessfully', 'Task created successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(130, 1, 'en', 'messages', 'taskUpdatedSuccessfully', 'Task updated successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(131, 1, 'en', 'messages', 'taskUpdated', 'Marked the task as ', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(132, 1, 'en', 'messages', 'unAuthorisedUser', 'You are not authorised user.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(133, 1, 'en', 'messages', 'sortDone', 'Sorting done.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(134, 1, 'en', 'messages', 'leadClientChangeSuccess', 'Lead changed in client successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(135, 1, 'en', 'messages', 'clientAdded', 'Client info added successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(136, 1, 'en', 'messages', 'clientUpdated', 'Client info updated successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(137, 1, 'en', 'messages', 'clientDeleted', 'Client deleted successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(138, 1, 'en', 'messages', 'leaveAssignSuccess', 'Leave assigned successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(139, 1, 'en', 'messages', 'leaveStatusUpdate', 'Leave status updated successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(140, 1, 'en', 'messages', 'notAnAuthorisedDevice', 'This is not an authorised device for clock-in or clock-out', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(141, 1, 'en', 'messages', 'notAnValidLocation', 'This is not an valid location for clock-in or clock-out', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(142, 1, 'en', 'messages', 'attendanceSaveSuccess', 'Attendance Saved Successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(143, 0, 'en', 'messages', 'maxColckIn', NULL, '2020-04-09 22:03:31', '2020-04-09 22:03:31'),
(144, 1, 'en', 'messages', 'attendanceDelete', 'Attendance deleted successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(145, 1, 'en', 'modules', 'projectTemplate.projectUpdated', ' project details updated.', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(146, 1, 'en', 'messages', 'projectUpdated', 'Project updated successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(147, 1, 'en', 'messages', 'projectDeleted', 'Project deleted successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(148, 1, 'en', 'modules', 'projectTemplate.addMemberTitle', 'Add Template Members', '2020-04-09 22:03:31', '2020-04-09 22:03:48'),
(149, 1, 'en', 'messages', 'templateTaskCreatedSuccessfully', 'Template Task created successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(150, 1, 'en', 'messages', 'templateTaskUpdatedSuccessfully', 'Template Task updated successfully.', '2020-04-09 22:03:31', '2020-04-09 22:03:46'),
(151, 0, 'en', 'app', 'menu.ticketFiles', NULL, '2020-04-09 22:03:31', '2020-04-09 22:03:31'),
(152, 1, 'en', 'messages', 'fileDeleted', 'File deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(153, 1, 'en', 'messages', 'boardColumnSaved', 'Board column saved successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(154, 1, 'en', 'messages', 'expenseSuccess', 'Expense Added Successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(155, 1, 'en', 'messages', 'expenseUpdateSuccess', 'Expense updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(156, 1, 'en', 'messages', 'expenseDeleted', 'Expense deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(157, 1, 'en', 'messages', 'timeLogDeleted', 'Time log deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(158, 1, 'en', 'modules', 'timeLogs.startTimer', 'Start Timer', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(159, 1, 'en', 'messages', 'timerStoppedSuccessfully', 'Timer stopped successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(160, 1, 'en', 'messages', 'timeLogAdded', 'Time logged successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(161, 1, 'en', 'messages', 'timeLogUpdated', 'Time log updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(162, 1, 'en', 'messages', 'creditNoteDeleted', 'Credit Note deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(163, 1, 'en', 'messages', 'quantityNumber', 'Quantity should be a number', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(164, 1, 'en', 'messages', 'unitPriceNumber', 'Unit price should be a number', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(165, 1, 'en', 'messages', 'amountNumber', 'Amount should be a number.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(166, 1, 'en', 'messages', 'itemBlank', 'Item name cannot be blank.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(167, 1, 'en', 'messages', 'creditNoteCreated', 'Credit Note created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(168, 1, 'en', 'messages', 'invalidRequest', 'Invalid Request', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(169, 1, 'en', 'messages', 'creditNoteUpdated', 'Credit Note updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(170, 1, 'en', 'messages', 'fileUploadedSuccessfully', 'File uploaded successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(171, 1, 'en', 'messages', 'fileUploadIssue', 'File not uploaded. Please contact to administrator', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(172, 1, 'en', 'messages', 'productAdded', 'Product added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(173, 1, 'en', 'messages', 'productUpdated', 'Product updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(174, 1, 'en', 'messages', 'productDeleted', 'Product deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(175, 1, 'en', 'messages', 'newFileUploadedToTheProject', 'New file uploaded to the project.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(176, 1, 'en', 'messages', 'fileUploaded', 'File uploaded successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(177, 1, 'en', 'modules', 'projects.projectUpdated', ' project details updated.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(178, 1, 'en', 'messages', 'templateAddSuccess', 'Template added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(179, 1, 'en', 'messages', 'templateUpdateSuccess', 'Template update success.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(180, 1, 'en', 'messages', 'templateDeleteSuccess', 'Template deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(181, 1, 'en', 'messages', 'noticeAdded', 'Notice added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(182, 1, 'en', 'messages', 'noticeUpdated', 'Notice updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(183, 1, 'en', 'messages', 'noticeDeleted', 'Notice deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(184, 1, 'en', 'messages', 'employeeAdded', 'Employee added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(185, 1, 'en', 'messages', 'employeeUpdated', 'Employee info updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(186, 1, 'en', 'messages', 'adminCannotDelete', 'Admin user cannot be deleted.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(187, 1, 'en', 'messages', 'employeeDeleted', 'Employee deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(188, 1, 'en', 'messages', 'roleAssigned', 'Roles assigned successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(189, 1, 'en', 'messages', 'timerStartedTask', 'Started the timer for task ', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(190, 1, 'en', 'messages', 'timerStartedBy', 'Timer started by', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(191, 1, 'en', 'messages', 'timerStartedProject', 'Started the timer for project ', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(192, 1, 'en', 'messages', 'timerStartedSuccessfully', 'Timer started successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(193, 1, 'en', 'app', 'stop', 'Stop', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(194, 1, 'en', 'messages', 'timerAlreadyRunning', 'Timer is already running.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(195, 1, 'en', 'messages', 'timerStoppedBy', 'Timer stopped by', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(196, 1, 'en', 'messages', 'eventCreateSuccess', 'Event created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(197, 1, 'en', 'messages', 'eventDeleteSuccess', 'Event deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(198, 1, 'en', 'messages', 'subTaskAdded', 'Sub task added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(199, 1, 'en', 'messages', 'subTaskUpdated', 'Sub task updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(200, 1, 'en', 'messages', 'updatedProfile', 'Updated profile.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(201, 1, 'en', 'messages', 'profileUpdated', 'Profile updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(202, 1, 'en', 'messages', 'invoiceDeleted', 'Invoice deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(203, 1, 'en', 'messages', 'invoiceCanNotDeleted', 'Invalid Request You can not delete this invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(204, 1, 'en', 'messages', 'invoiceCreated', 'Invoice created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(205, 1, 'en', 'messages', 'invoiceUpdated', 'Invoice updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(206, 1, 'en', 'messages', 'paymentSuccess', 'Payment added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(207, 1, 'en', 'messages', 'paymentDeleted', 'Payment deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(208, 1, 'en', 'messages', 'isAddedAsProjectMember', 'is added as project member.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(209, 1, 'en', 'messages', 'membersAddedSuccessfully', 'Members added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(210, 1, 'en', 'messages', 'memberRemovedFromProject', 'Member removed from project successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(211, 1, 'en', 'messages', 'fetchChat', 'Fetching chat detail.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(212, 1, 'en', 'messages', 'contactAdded', 'Contact added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(213, 1, 'en', 'messages', 'contactUpdated', 'Contact updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(214, 1, 'en', 'messages', 'contactDeleted', 'Contact deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(215, 1, 'en', 'modules', 'lead.action', 'Action', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(216, 1, 'en', 'messages', 'LeadAddedUpdated', 'Lead added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(217, 1, 'en', 'messages', 'LeadUpdated', 'Lead updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(218, 1, 'en', 'messages', 'LeadDeleted', 'Lead deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(219, 1, 'en', 'messages', 'leadStatusChangeSuccess', 'Status changed successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(220, 1, 'en', 'messages', 'leadFollowUpAddedSuccess', 'Lead follow up added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(221, 1, 'en', 'messages', 'leadFollowUpUpdatedSuccess', 'Lead follow up added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(222, 1, 'en', 'messages', 'followUpFilter', 'Filter applied.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(223, 1, 'en', 'messages', 'taskDeletedSuccessfully', 'Task deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(224, 1, 'en', 'messages', 'holidayAddedSuccess', '<strong>New Holidays</strong> successfully added to the Database.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(225, 1, 'en', 'messages', 'holidayDeletedSuccess', 'Holidays successfully deleted.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(226, 1, 'en', 'messages', 'checkDayHoliday', 'Choose at-least 1.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(227, 1, 'en', 'app', 'menu.projects', 'Projects', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(228, 1, 'en', 'app', 'completed', 'Completed', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(229, 1, 'en', 'messages', 'addedAsNewProject', 'added as new project.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(230, 1, 'en', 'messages', 'templateMembersAddedSuccessfully', 'Template Members added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(231, 1, 'en', 'messages', 'templateMemberRemovedFromProject', 'Template Member removed from project successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(232, 1, 'en', 'app', 'menu.taskFiles', 'Task Files', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(233, 1, 'en', 'messages', 'addItem', 'Add at-least 1 item.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(234, 1, 'en', 'messages', 'estimateCreated', 'Estimate created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(235, 1, 'en', 'messages', 'estimateUpdated', 'Estimate updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(236, 1, 'en', 'messages', 'estimateDeleted', 'Estimate deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(237, 1, 'en', 'messages', 'noteCreated', 'Note created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(238, 1, 'en', 'messages', 'noteUpdated', 'Note updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(239, 1, 'en', 'messages', 'noteDeleted', 'Note deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(240, 1, 'en', 'messages', 'proposalCreated', 'Proposal created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(241, 1, 'en', 'messages', 'proposalUpdated', 'Proposal updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(242, 1, 'en', 'messages', 'proposalDeleted', 'Proposal deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(243, 1, 'en', 'messages', 'settingsUpdated', 'Settings updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(244, 1, 'en', 'messages', 'smtpError', 'Your SMTP details are not correct. Please update to correct one', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(245, 1, 'en', 'messages', 'smtpSecureEnabled', 'Please check if you have enabled less secure app on your account from here ', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(246, 1, 'en', 'messages', 'languageUpdated', 'Language updated successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(247, 1, 'en', 'messages', 'languageAdded', 'Language added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(248, 1, 'en', 'messages', 'languageDeleted', 'Language deleted successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(249, 1, 'en', 'modules', 'superadmin.verified', 'Verified', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(250, 1, 'en', 'modules', 'superadmin.registerDate', 'Register Date', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(251, 1, 'en', 'modules', 'superadmin.totalUsers', 'Total Users', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(252, 0, 'en', 'messages', 'userDeleted', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(253, 1, 'en', 'app', 'deactive', 'Deactive', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(254, 1, 'en', 'messages', 'currencyAdded', 'Currency added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(255, 1, 'en', 'messages', 'currencyUpdated', 'Currency updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(256, 1, 'en', 'modules', 'currencySettings.cantDeleteDefault', 'Cannot delete default currency.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(257, 1, 'en', 'messages', 'currencyDeleted', 'Currency deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(258, 1, 'en', 'messages', 'exchangeRateUpdateSuccess', 'Exchange rate updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(259, 1, 'en', 'messages', 'currencyConvertKeyUpdated', 'Currency convert API key updated successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(260, 1, 'en', 'app', 'menu.updates', 'Update Log', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(261, 1, 'en', 'app', 'package', 'Package', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(262, 1, 'en', 'messages', 'uploadSuccess', 'Details updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(263, 1, 'en', 'messages', 'superAdminUpdated', 'Super Admin info updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(264, 1, 'en', 'messages', 'updateSuccess', 'Updated successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(265, 1, 'en', 'app', 'addNew', 'Add New', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(266, 1, 'en', 'app', 'menu.faq', 'Admin FAQ', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(267, 1, 'en', 'messages', 'methodsAdded', 'Offline method added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(268, 1, 'en', 'messages', 'methodsUpdated', 'Offline method updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(269, 1, 'en', 'messages', 'methodsDeleted', 'Offline method deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(270, 1, 'en', 'app', 'menu.moduleSettings', 'Module Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(271, 1, 'en', 'messages', 'unsubscribeSuccess', 'Plan unsubscribe successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(272, 0, 'en', 'messages', 'renewalDeleted', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(273, 1, 'en', 'app', 'menu.projectSettings', 'Project Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(274, 1, 'en', 'app', 'menu.projectTemplateTask', 'Project Template Task', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(275, 1, 'en', 'messages', 'timelogAlreadyExist', 'Time-log already exist for this user.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(276, 1, 'en', 'messages', 'reportGenerated', 'Report generated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(277, 1, 'en', 'messages', 'groupAddedSuccess', 'Group added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(278, 1, 'en', 'messages', 'groupDeleteSuccess', 'Group deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(279, 1, 'en', 'messages', 'estimateCanNotDeleted', 'Invalid Request You can not delete this Estimate', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(280, 1, 'en', 'modules', 'leaves.halfDay', 'Half Day', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(281, 1, 'en', 'app', 'reject', 'Reject', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(282, 1, 'en', 'messages', 'milestoneSuccess', 'Milestone saved successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(283, 1, 'en', 'messages', 'deleteSuccess', 'Deleted Successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(284, 1, 'en', 'messages', 'upgradePackageForAddEmployees', 'You need to upgrade your package to create more employees because your employees length is :employeeCount and package max employees length is  :maxEmployees', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(285, 1, 'en', 'messages', 'downGradePackageForAddEmployees', 'You can\\\'t downgrade package because your employees length is :employeeCount and package max employees length is :maxEmployees', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(286, 1, 'en', 'messages', 'groupUpdatedSuccessfully', 'Group updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(287, 1, 'en', 'messages', 'departmentAdded', 'Department added successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(288, 1, 'en', 'messages', 'leadSourceAddSuccess', 'Lead source added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(289, 1, 'en', 'messages', 'leadSourceUpdateSuccess', 'Lead source updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(290, 1, 'en', 'messages', 'leadSourceDeleteSuccess', 'Lead source deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(291, 1, 'en', 'modules', 'proposal.action', 'Action', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(292, 1, 'en', 'modules', 'proposal.download', 'Download', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(293, 1, 'en', 'modules', 'proposal.edit', 'Edit', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(294, 1, 'en', 'modules', 'proposal.delete', 'Delete', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(295, 1, 'en', 'messages', 'creditNoteCanNotDeleted', 'Invalid Request You can not delete this Credit Note', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(296, 0, 'en', 'messages', 'pleaseEnterCreditAmount', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(297, 1, 'en', 'messages', 'creditNoteAppliedSuccessfully', 'Credit note applied successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(298, 1, 'en', 'messages', 'creditedInvoiceDeletedSuccessfully', 'Credited Invoice deleted successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(299, 1, 'en', 'messages', 'leadStatusAddSuccess', 'Lead sttaus added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(300, 1, 'en', 'messages', 'leadStatusUpdateSuccess', 'Lead status updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(301, 1, 'en', 'messages', 'leadStatusDeleteSuccess', 'Lead status deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(302, 1, 'en', 'app', 'menu.lead', 'Leads', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(303, 1, 'en', 'modules', 'gdpr.optIn', 'OPT IN', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(304, 1, 'en', 'modules', 'gdpr.optOut', 'OPT OUT', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(305, 1, 'en', 'messages', 'roleCreated', 'Role created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(306, 1, 'en', 'messages', 'roleUpdated', 'Role updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(307, 1, 'en', 'messages', 'logTimeUpdateSuccess', 'Log time updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(308, 1, 'en', 'messages', 'agentAddedSuccessfully', 'Agent added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(309, 1, 'en', 'messages', 'statusUpdatedSuccessfully', 'Status updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(310, 1, 'en', 'messages', 'agentRemoveSuccess', 'Agent removed successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(311, 1, 'en', 'app', 'menu.contracts', 'Contracts', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(312, 1, 'en', 'messages', 'contractAdded', 'Contract added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(313, 1, 'en', 'messages', 'contractUpdated', 'Contract updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(314, 1, 'en', 'messages', 'addDiscussion', 'Message successfully submitted', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(315, 1, 'en', 'modules', 'contracts.discussionUpdated', 'Discussion successfully updated.', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(316, 1, 'en', 'modules', 'contracts.discussionDeleted', 'Discussion successfully deleted.', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(317, 1, 'en', 'modules', 'hrs', 'hrs', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(318, 1, 'en', 'modules', 'mins', 'mins', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(319, 1, 'en', 'messages', 'updatedSuccessfully', 'Updated successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(320, 1, 'en', 'modules', 'issues.markResolved', 'Mark Resolved', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(321, 1, 'en', 'modules', 'issues.markPending', 'Mark Pending', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(322, 1, 'en', 'modules', 'issues.pending', 'Pending', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(323, 1, 'en', 'modules', 'issues.resolved', 'Resolved', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(324, 1, 'en', 'messages', 'issueStatusChanged', 'Issue status changed successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(325, 1, 'en', 'app', 'menu.attendanceReport', 'Attendance Report', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(326, 1, 'en', 'modules', 'attendance.totalWorkingDays', 'Total Working Days', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(327, 1, 'en', 'messages', 'ticketTypeAddSuccess', 'Ticket type added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(328, 1, 'en', 'messages', 'ticketTypeUpdateSuccess', 'Ticket type updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(329, 1, 'en', 'messages', 'ticketTypeDeleteSuccess', 'Ticket type deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(330, 1, 'en', 'messages', 'leaveTypeAdded', 'Leave type saved.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(331, 1, 'en', 'messages', 'leaveTypeDeleted', 'Leave type deleted.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(332, 1, 'en', 'messages', 'projectArchiveSuccessfully', 'Project archived successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(333, 1, 'en', 'messages', 'projectRevertSuccessfully', 'Project reverted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(334, 1, 'en', 'messages', 'categoryAdded', 'Category added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(335, 1, 'en', 'messages', 'categoryDeleted', 'Category deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(336, 1, 'en', 'modules', 'lead.leadAgent', 'Lead Agent', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(337, 1, 'en', 'messages', 'leadAgentAddSuccess', 'Lead agent add Successfully', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(338, 1, 'en', 'messages', 'importSuccess', 'File imported successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(339, 0, 'en', 'messages', 'taskSettingUpdateSuccess', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(340, 1, 'en', 'messages', 'taxAdded', 'Tax added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(341, 1, 'en', 'app', 'menu.designation', 'Designation', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(342, 1, 'en', 'messages', 'ticketChannelAddSuccess', 'Ticket channel added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(343, 1, 'en', 'messages', 'ticketChannelUpdateSuccess', 'Ticket channel updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(344, 1, 'en', 'messages', 'ticketChannelDeleteSuccess', 'Ticket channel deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(345, 1, 'en', 'app', 'menu.gdpr', 'GDPR', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(346, 1, 'en', 'messages', 'gdprUpdated', 'GDPR setting successfully updated', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(347, 1, 'en', 'app', 'rejected', 'Rejected', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(348, 1, 'en', 'messages', 'contractTypeAdded', 'Contract type added successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(349, 1, 'en', 'messages', 'contractTypeUpdated', 'Contract type updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(350, 1, 'en', 'messages', 'contractTypeDeleted', 'Contract type deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(351, 1, 'en', 'modules', 'gdpr.consent', 'Consent', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(352, 1, 'en', 'app', 'menu.clients', 'Clients', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(353, 1, 'en', 'messages', 'signUpThankYouVerify', 'Thank you for signing up. Please verify your email to get started', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(354, 1, 'en', 'messages', 'signUpThankYou', 'Thank you for signing up. Please login to get started', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(355, 1, 'en', 'modules', 'accountSettings.emailVerification', 'Email Verification', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(356, 1, 'en', 'app', 'menu.noticeBoard', 'Notice Board', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(357, 1, 'en', 'messages', 'companyChanged', 'Company changed successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(358, 1, 'en', 'app', 'menu.products', 'Products', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(359, 0, 'en', 'messages', 'selectProduct', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(360, 1, 'en', 'messages', 'issueCreated', 'Issue created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(361, 1, 'en', 'messages', 'issueUpdated', 'Issue updated successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(362, 1, 'en', 'messages', 'issueDeleted', 'Issue deleted successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(363, 1, 'en', 'app', 'menu.payments', 'Payments', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(364, 1, 'en', 'email', 'newExpense.subject', 'New Expense added', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(365, 1, 'en', 'email', 'hello', 'Hello', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(366, 1, 'en', 'email', 'loginDashboard', 'Login To Dashboard', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(367, 1, 'en', 'email', 'thankyouNote', 'Thank you for using our application!', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(368, 1, 'en', 'email', 'newNotice.subject', 'New Notice Published', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(369, 1, 'en', 'email', 'newNotice.text', 'New notice has been published. Login to view the notice.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(370, 1, 'en', 'email', 'cancelLicense.subject', 'License cancelled due to failed payment.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(371, 1, 'en', 'email', 'cancelLicense.text', 'License has been cancelled due to the failed payment. Please check the details.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(372, 1, 'en', 'modules', 'accountSettings.companyName', 'Company Name', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(373, 1, 'en', 'email', 'projectReminder.subject', 'Project Reminder', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(374, 1, 'en', 'email', 'projectReminder.text', 'This is to remind you about the due date of the following projects which is', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(375, 1, 'en', 'email', 'messages.loginForMoreDetails', 'Log in for more details.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(376, 1, 'en', 'email', 'emailVerify.subject', 'Email Verification', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(377, 1, 'en', 'email', 'emailVerify.text', 'Thank you for registration. Here are your email verification instructions. A request to your email verification has been made. If you did not make this request, simply ignore this email. If you did make this request, please verify ', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(378, 1, 'en', 'email', 'planUpdate.subject', 'Company updated plan.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(379, 0, 'en', 'email', 'removalRequestApprovedUser.subject', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(380, 0, 'en', 'email', 'removalRequestApprovedUser.text', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(381, 0, 'en', 'email', 'removalRequestRejectedUser.subject', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(382, 0, 'en', 'email', 'removalRequestRejectedUser.text', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(383, 1, 'en', 'email', 'taskComplete.subject', 'Task marked as complete', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(384, 1, 'en', 'email', 'removalRequestAdmin.subject', 'Data removal request', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(385, 1, 'en', 'email', 'removalRequestAdmin.text', 'Data removal request', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(386, 1, 'en', 'email', 'paymentReminder.subject', 'Payment Reminder', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(387, 1, 'en', 'email', 'taskComment.subject', 'Comment posted on task', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(388, 1, 'en', 'email', 'leaves.statusSubject', 'Leave application status updated', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(389, 1, 'en', 'email', 'leave.reject', 'Leave application rejected.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(390, 1, 'en', 'app', 'reason', 'Reason', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(391, 1, 'en', 'email', 'leaves.subject', 'New leave request received', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(392, 1, 'en', 'modules', 'leaves.leaveType', 'Leave Type', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(393, 1, 'en', 'modules', 'leaves.reason', 'Reason for absence', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(394, 1, 'en', 'email', 'reminder.subject', 'Reminder for assigned task', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(395, 1, 'en', 'email', 'newClientTask.subject', 'New Task Generated', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(396, 1, 'en', 'email', 'newTask.subject', 'New Task Assigned to You', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(397, 1, 'en', 'email', 'newEvent.subject', 'New Event Created', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(398, 1, 'en', 'email', 'newEvent.text', 'New event has been created. Download the attachment to add event to your calendar.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(399, 1, 'en', 'email', 'expenseStatus.subject', 'Expense status updated', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(400, 1, 'en', 'email', 'expenseStatus.text', 'Your expense status updated to', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(401, 1, 'en', 'email', 'newProjectMember.subject', 'New Project Assigned', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(402, 1, 'en', 'email', 'newProjectMember.text', 'You have been added as a member to the project', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(403, 1, 'en', 'email', 'newTicket.subject', 'New Support Ticket Requested', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(404, 1, 'en', 'email', 'newTicket.text', 'New Support Ticket is requested. Login to view the ticket.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(405, 0, 'en', 'email', 'removalRequestApprovedLead.subject', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(406, 0, 'en', 'email', 'removalRequestApprovedLead.text', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(407, 0, 'en', 'email', 'removalRequestRejectedLead.subject', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(408, 0, 'en', 'email', 'removalRequestRejectedLead.text', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(409, 1, 'en', 'email', 'taskUpdate.subject', 'Task updated', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(410, 1, 'en', 'email', 'licenseExpirePre.subject', 'Company license Expiring soon.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(411, 1, 'en', 'email', 'licenseExpirePre.text', 'Your company license is expiring soon Please check billing details.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(412, 1, 'en', 'email', 'newCompany.subject', 'New company registered', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(413, 1, 'en', 'email', 'newCompany.text', 'New company has been registered.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(414, 1, 'en', 'email', 'fileUpload.subject', 'New file uploaded to project : ', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(415, 1, 'en', 'modules', 'projects.fileName', 'File name', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(416, 1, 'en', 'email', 'leave.approve', 'Leave application approved.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(417, 0, 'en', 'email', 'planPurchase.subject', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(418, 1, 'en', 'email', 'planPurchase.text', 'Company purchased plan', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(419, 1, 'en', 'email', 'invoices.paymentReceived', 'Payment received for invoice.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(420, 1, 'en', 'email', 'leave.applied', 'Leave application applied.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(421, 1, 'en', 'email', 'newUser.subject', 'Welcome to', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(422, 1, 'en', 'email', 'newUser.text', 'Your account has been created successfully.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(423, 1, 'en', 'email', 'licenseExpire.subject', 'Company Licence expired.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(424, 1, 'en', 'email', 'licenseExpire.text', 'Your company license has been expired. Please check billing details.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(425, 1, 'en', 'app', 'theme', 'Theme', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(426, 1, 'en', 'app', 'menu.settings', 'Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45');
INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(427, 1, 'en', 'app', 'markRead', 'Mark as Read', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(428, 1, 'en', 'app', 'loginAsEmployee', 'Login As Employee', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(429, 1, 'en', 'app', 'logout', 'Logout', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(430, 1, 'en', 'app', 'menu.customers', 'Customers', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(431, 1, 'en', 'app', 'menu.hr', 'HR', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(432, 1, 'en', 'app', 'menu.work', 'Work', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(433, 1, 'en', 'app', 'menu.finance', 'Finance', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(434, 1, 'en', 'app', 'menu.reports', 'Reports', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(435, 1, 'en', 'app', 'menu.billing', 'Billing', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(436, 1, 'en', 'app', 'menu.profileSettings', 'Profile Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(437, 1, 'en', 'app', 'loginAsAdmin', 'Login As Admin', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(438, 1, 'en', 'app', 'menu.tasks', 'Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(439, 1, 'en', 'app', 'menu.accountSettings', 'Company Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(440, 1, 'en', 'app', 'front', 'Front', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(441, 1, 'en', 'app', 'section', 'Section', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(442, 1, 'en', 'app', 'footer', 'Footer', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(443, 1, 'en', 'app', 'menu.menu', 'Menu', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(444, 1, 'en', 'app', 'sign', 'Sign', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(445, 1, 'en', 'app', 'note', 'Note', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(446, 1, 'en', 'app', 'gstIn', 'GSTIN', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(447, 1, 'en', 'modules', 'invoices.paid', 'Paid', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(448, 1, 'en', 'modules', 'invoices.due', 'Due', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(449, 1, 'en', 'modules', 'invoices.appliedCredits', 'Applied Credits', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(450, 1, 'en', 'modules', 'client.password', 'Password', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(451, 1, 'en', 'app', 'rememberMe', 'Remember Me', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(452, 1, 'en', 'app', 'forgotPassword', 'Forgot Password', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(453, 1, 'en', 'email', 'regards', 'Regards', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(454, 1, 'en', 'modules', 'attendance.daysPresent', 'Days Present', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(455, 1, 'en', 'modules', 'attendance.holidays', 'Holidays', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(456, 1, 'en', 'app', 'apply', 'Apply', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(457, 1, 'en', 'app', 'su', 'Su', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(458, 1, 'en', 'app', 'mo', 'Mo', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(459, 1, 'en', 'app', 'tu', 'Tu', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(460, 1, 'en', 'app', 'we', 'We', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(461, 1, 'en', 'app', 'th', 'Th', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(462, 1, 'en', 'app', 'fr', 'Fr', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(463, 1, 'en', 'app', 'sa', 'Sa', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(464, 1, 'en', 'app', 'january', 'January', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(465, 1, 'en', 'app', 'february', 'February', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(466, 1, 'en', 'app', 'march', 'March', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(467, 1, 'en', 'app', 'april', 'April', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(468, 1, 'en', 'app', 'may', 'May', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(469, 1, 'en', 'app', 'june', 'June', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(470, 1, 'en', 'app', 'july', 'July', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(471, 1, 'en', 'app', 'august', 'August', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(472, 1, 'en', 'app', 'september', 'September', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(473, 1, 'en', 'app', 'october', 'October', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(474, 1, 'en', 'app', 'november', 'November', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(475, 1, 'en', 'app', 'december', 'December', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(476, 1, 'en', 'messages', 'noRecordFound', 'No record found.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(477, 1, 'en', 'app', 'menu.attendance', 'Attendance', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(478, 1, 'en', 'app', 'mark', 'Mark', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(479, 1, 'en', 'app', 'save', 'Save', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(480, 1, 'en', 'app', 'yes', 'Yes', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(481, 1, 'en', 'app', 'no', 'No', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(482, 1, 'en', 'modules', 'attendance.notClockOut', 'Did not clock out', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(483, 1, 'en', 'modules', 'attendance.holidayfor', 'Holiday for', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(484, 1, 'en', 'modules', 'attendance.leaveFor', 'Leave for', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(485, 1, 'en', 'app', 'month', 'Month', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(486, 1, 'en', 'app', 'year', 'Year', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(487, 1, 'en', 'modules', 'attendance.attendanceDetail', 'Attendance Detail', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(488, 1, 'en', 'modules', 'timeLogs.logTime', 'Log Time', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(489, 1, 'en', 'app', 'selectTask', 'Select Task', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(490, 1, 'en', 'app', 'selectProject', 'Select Project', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(491, 1, 'en', 'app', 'reset', 'Reset', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(492, 1, 'en', 'modules', 'projects.activeTimers', 'Active Timers', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(493, 1, 'en', 'modules', 'timeLogs.selectTask', 'Select Task', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(494, 1, 'en', 'modules', 'timeLogs.selectProject', 'Select Project', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(495, 1, 'en', 'modules', 'timeLogs.noProjectFound', 'No Task Assigned', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(496, 1, 'en', 'modules', 'timeLogs.noTaskFound', 'No Task Assigned', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(497, 1, 'en', 'modules', 'timeLogs.stopTimer', 'Stop Timer', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(498, 1, 'en', 'modules', 'client.updateTitle', 'Update Client Info', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(499, 1, 'en', 'app', 'update', 'Update', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(500, 1, 'en', 'modules', 'client.createTitle', 'Add Client Info', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(501, 1, 'en', 'modules', 'templateTasks.updateTask', 'Update Template Task', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(502, 1, 'en', 'modules', 'templateTasks.high', 'High', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(503, 1, 'en', 'modules', 'templateTasks.medium', 'Medium', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(504, 1, 'en', 'modules', 'templateTasks.low', 'Low', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(505, 1, 'en', 'modules', 'templateTasks.newTask', 'New Template Task', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(506, 1, 'en', 'modules', 'projectTemplate.updateTitle', 'Update Template Details', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(507, 1, 'en', 'modules', 'projectTemplate.createTitle', 'Add Template Template', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(508, 1, 'en', 'app', 'remove', 'Remove', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(509, 1, 'en', 'app', 'department', 'Department', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(510, 1, 'en', 'modules', 'tickets.totalTickets', 'Total Tickets', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(511, 1, 'en', 'modules', 'tickets.totalClosedTickets', 'Closed Tickets', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(512, 1, 'en', 'modules', 'tickets.totalOpenTickets', 'Open Tickets', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(513, 1, 'en', 'modules', 'tickets.totalPendingTickets', 'Pending Tickets', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(514, 1, 'en', 'modules', 'tickets.totalResolvedTickets', 'Resolved Tickets', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(515, 1, 'en', 'modules', 'tickets.ticketTrendGraph', 'Ticket trend graph', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(516, 1, 'en', 'messages', 'noMessage', 'No message found.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(517, 1, 'en', 'modules', 'tickets.applyTemplate', 'Apply Template', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(518, 0, 'en', 'modules', 'tickets.ticketTypes', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(519, 1, 'en', 'modules', 'tickets.addType', 'Add Type', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(520, 1, 'en', 'modules', 'tickets.addChannel', 'Add Channel', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(521, 1, 'en', 'modules', 'tickets.closeTicket', 'Close Ticket', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(522, 1, 'en', 'modules', 'tickets.reopenTicket', 'Reopen Ticket', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(523, 1, 'en', 'messages', 'noFileUploaded', 'You have not uploaded any file.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(524, 1, 'en', 'modules', 'payments.updatePayment', 'Update Payment', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(525, 1, 'en', 'app', 'optional', 'Optional', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(526, 1, 'en', 'modules', 'tasks.taskDetail', 'Task Detail', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(527, 1, 'en', 'modules', 'tasks.chooseAssignee', 'Choose Assignee', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(528, 1, 'en', 'modules', 'tasks.high', 'High', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(529, 1, 'en', 'modules', 'tasks.medium', 'Medium', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(530, 1, 'en', 'modules', 'tasks.low', 'Low', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(531, 1, 'en', 'modules', 'tasks.newTask', 'New Task', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(532, 1, 'en', 'modules', 'invoices.tax', 'Tax', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(533, 1, 'en', 'app', 'add', 'Add', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(534, 1, 'en', 'modules', 'messages.startConversation', 'Start Conversation', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(535, 1, 'en', 'modules', 'messages.searchContact', 'Search Contact', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(536, 1, 'en', 'messages', 'noUser', 'No user found.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(537, 1, 'en', 'modules', 'messages.typeMessage', 'Type your message here...', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(538, 1, 'en', 'messages', 'noConversation', 'No conversation found.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(539, 1, 'en', 'modules', 'messages.chooseMember', 'Choose Member', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(540, 1, 'en', 'modules', 'client.clientName', 'Client Name', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(541, 1, 'en', 'modules', 'messages.send', 'Send', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(542, 1, 'en', 'modules', 'events.addEvent', 'Add Event', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(543, 1, 'en', 'app', 'menu.Events', 'Events', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(544, 1, 'en', 'modules', 'events.viewAttendees', 'View Attendees', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(545, 1, 'en', 'modules', 'projects.dropFile', 'Drop files here OR click to upload', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(546, 1, 'en', 'app', 'description', 'Description', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(547, 1, 'en', 'modules', 'invoices.item', 'Item', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(548, 1, 'en', 'modules', 'invoices.qty', 'Qty/Hrs', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(549, 1, 'en', 'modules', 'invoices.unitPrice', 'Unit Price', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(550, 1, 'en', 'modules', 'invoices.addItem', 'Add Item', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(551, 1, 'en', 'modules', 'invoices.discount', 'Discount', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(552, 1, 'en', 'messages', 'discountMoreThenTotal', 'Discount cannot be more than total amount.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(553, 1, 'en', 'modules', 'invoices.addInvoice', 'Add Invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(554, 1, 'en', 'modules', 'payments.paymentDetails', 'Payment details', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(555, 1, 'en', 'modules', 'payments.totalAmount', 'Total Amount', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(556, 1, 'en', 'modules', 'payments.totalPaid', 'Total Paid', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(557, 1, 'en', 'modules', 'payments.totalDue', 'Total Due', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(558, 1, 'en', 'modules', 'invoices.amountPaid', 'Amount Paid', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(559, 1, 'en', 'modules', 'invoices.amountDue', 'Amount Due', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(560, 1, 'en', 'modules', 'invoices.downloadPdf', 'Download Pdf', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(561, 1, 'en', 'modules', 'estimates.convertEstimateTitle', 'Convert Estimate To Invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(562, 1, 'en', 'app', 'loading', 'Loading...', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(563, 1, 'en', 'app', 'changes', 'changes', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(564, 1, 'en', 'modules', 'estimates.updateEstimate', 'Update Estimate', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(565, 1, 'en', 'modules', 'estimates.createEstimate', 'Create Estimate', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(566, 1, 'en', 'modules', 'contacts.addContact', 'Add Contact', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(567, 1, 'en', 'modules', 'holiday.listOf', 'List Of', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(568, 1, 'en', 'modules', 'holiday.markSunday', ' Mark Default Holidays', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(569, 1, 'en', 'modules', 'holiday.date', 'Date', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(570, 1, 'en', 'modules', 'holiday.occasion', 'Occasion', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(571, 1, 'en', 'modules', 'holiday.day', 'Day', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(572, 1, 'en', 'modules', 'holiday.action', 'Action', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(573, 1, 'en', 'modules', 'holiday.markHoliday', 'Mark Holiday', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(574, 1, 'en', 'modules', 'holiday.title', 'Holiday', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(575, 1, 'en', 'modules', 'expenses.updateExpense', 'Update Expense', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(576, 1, 'en', 'modules', 'expenses.addExpense', 'Add Expense', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(577, 1, 'en', 'modules', 'leaves.applyLeave', 'Apply Leave', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(578, 1, 'en', 'messages', 'noPendingLeaves', 'No pending leaves remaining.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(579, 1, 'en', 'modules', 'leaves.assignLeave', 'Assign Leave', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(580, 1, 'en', 'messages', 'selectMultipleDates', 'You can select multiple dates.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(581, 1, 'en', 'modules', 'dashboard.totalProjects', 'Total Projects', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(582, 1, 'en', 'modules', 'tickets.overDueProjects', 'Overdue Projects', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(583, 1, 'en', 'modules', 'tickets.completedProjects', 'Completed Projects', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(584, 1, 'en', 'modules', 'projects.updateTitle', 'Update Project Details', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(585, 1, 'en', 'modules', 'projects.createTitle', 'Add Project', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(586, 1, 'en', 'messages', 'noClientAddedToProject', 'No client assigned to the project.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(587, 1, 'en', 'messages', 'noActiveTimer', 'No active timer.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(588, 1, 'en', 'modules', 'projects.openTasks', 'Open Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(589, 1, 'en', 'modules', 'projects.uploadFile', 'Upload File', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(590, 1, 'en', 'modules', 'notices.updateNotice', 'Update Notice', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(591, 1, 'en', 'modules', 'notices.addNotice', 'Add New Notice', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(592, 1, 'en', 'modules', 'tasks.assignBy', 'Assigned By', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(593, 1, 'en', 'modules', 'tasks.deleteRecurringTasks', 'Delete with recurring tasks.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(594, 1, 'en', 'modules', 'tasks.chooseTask', 'Choose Task', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(595, 1, 'en', 'modules', 'tasks.updateTask', 'Update Task', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(596, 1, 'en', 'modules', 'tasks.uplodedFiles', 'Uploded Files', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(597, 1, 'en', 'modules', 'tasks.markComplete', 'Mark as complete', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(598, 1, 'en', 'modules', 'tasks.markIncomplete', 'Mark as incomplete', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(599, 1, 'en', 'messages', 'remindToAssignedEmployee', 'Send Reminder', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(600, 1, 'en', 'modules', 'tasks.reminder', 'Reminder', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(601, 1, 'en', 'modules', 'tasks.history', 'History', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(602, 1, 'en', 'modules', 'tasks.subTask', 'Sub Task', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(603, 1, 'en', 'modules', 'tasks.comment', 'Comment', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(604, 1, 'en', 'app', 'submit', 'Submit', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(605, 1, 'en', 'modules', 'tasks.addBoardColumn', 'Add Column', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(606, 1, 'en', 'app', 'from', 'From', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(607, 1, 'en', 'app', 'today', 'Today', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(608, 1, 'en', 'app', 'close', 'Close', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(609, 1, 'en', 'modules', 'dashboard.totalHoursLogged', 'Hours Logged', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(610, 1, 'en', 'modules', 'dashboard.totalPendingTasks', 'Pending Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(611, 1, 'en', 'modules', 'dashboard.totalCompletedTasks', 'Completed Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(612, 1, 'en', 'modules', 'taskCalendar.note', 'Calendar shows the due tasks on their due dates.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(613, 1, 'en', 'modules', 'taskDetail', 'Task Detail', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(614, 1, 'en', 'modules', 'dashboard.totalLeads', 'Total Leads', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(615, 1, 'en', 'modules', 'dashboard.totalConvertedClient', 'Total Client Convert', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(616, 1, 'en', 'modules', 'dashboard.totalPendingFollowUps', 'Total Pending Follow Up', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(617, 1, 'en', 'app', 'filterResults', 'Filter Results', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(618, 1, 'en', 'app', 'next_follow_up', 'Next Follow Up', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(619, 1, 'en', 'modules', 'followup.updateFollow', 'Update Follow Up', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(620, 1, 'en', 'modules', 'followup.newFollowUp', 'New Follow Up', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(621, 1, 'en', 'modules', 'followup.followUpNote', 'Follow up add and edit functionality will work when lead <b>next follow up</b> will <b>YES</b>.', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(622, 1, 'en', 'modules', 'lead.leadFollowUp', 'Follow Up Next', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(623, 1, 'en', 'modules', 'lead.updateTitle', 'Update Lead Info', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(624, 1, 'en', 'modules', 'tickets.chooseAgents', 'Choose Agents', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(625, 1, 'en', 'modules', 'lead.createTitle', 'Add Lead Info', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(626, 1, 'en', 'modules', 'lead.leadSource', 'Lead Source', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(627, 1, 'en', 'modules', 'profile.updateTitle', 'Update Profile Info', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(628, 1, 'en', 'modules', 'profile.passwordNote', 'Leave blank to keep your current password.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(629, 1, 'en', 'modules', 'profile.uploadPicture', 'Upload your picture', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(630, 1, 'en', 'app', 'selectImage', 'Select Image', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(631, 1, 'en', 'app', 'change', 'Change', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(632, 1, 'en', 'modules', 'leaves.pendingLeaves', 'Pending Leaves', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(633, 1, 'en', 'app', 'accept', 'Accept', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(634, 1, 'en', 'modules', 'sticky.lastUpdated', 'Updated', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(635, 1, 'en', 'app', 'menu.stickyNotes', 'Sticky Notes', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(636, 1, 'en', 'modules', 'estimates.convertInvoiceTitle', 'CONVERT INVOICE TO CREDIT NOTE', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(637, 1, 'en', 'modules', 'credit-notes.item', 'Item', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(638, 1, 'en', 'modules', 'credit-notes.qty', 'Qty/Hrs', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(639, 1, 'en', 'modules', 'credit-notes.unitPrice', 'Unit Price', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(640, 1, 'en', 'modules', 'credit-notes.tax', 'Tax', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(641, 1, 'en', 'modules', 'credit-notes.amount', 'Amount', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(642, 1, 'en', 'modules', 'credit-notes.addItem', 'Add Item', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(643, 1, 'en', 'modules', 'credit-notes.discount', 'Discount', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(644, 1, 'en', 'modules', 'credit-notes.creditAmountUsed', 'Credit Amount Used', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(645, 1, 'en', 'modules', 'credit-notes.creditAmountRemaining', 'Credit Amount Remaining', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(646, 1, 'en', 'modules', 'credit-notes.downloadPdf', 'Download Pdf', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(647, 1, 'en', 'modules', 'proposal.updateProposal', 'Update Proposal', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(648, 1, 'en', 'modules', 'proposal.createTitle', 'Add Proposal Info', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(649, 1, 'en', 'app', 'menu.employeeDocs', 'Employee Documents', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(650, 1, 'en', 'messages', 'employeeDocsAllowedFormat', 'Allowed file formats: jpg, png, gif, doc, docx, xls, xlsx, pdf, txt.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(651, 1, 'en', 'modules', 'employees.updateTitle', 'update Employee Info', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(652, 1, 'en', 'modules', 'employees.updatePasswordNote', 'Employee will login using this password. (Leave blank to keep current password)', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(653, 1, 'en', 'modules', 'employees.slackUsername', 'Slack Username', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(654, 1, 'en', 'app', 'skills', 'Skills', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(655, 1, 'en', 'modules', 'employees.createTitle', 'Add Employee Info', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(656, 1, 'en', 'modules', 'employees.passwordNote', 'Employee will login using this password.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(657, 1, 'en', 'app', 'decline', 'Decline', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(658, 1, 'en', 'app', 'signed', 'Signed', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(659, 1, 'en', 'modules', 'dashboard.totalEmployees', 'Total Employees', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(660, 1, 'en', 'modules', 'roles.addRole', 'Manage Role', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(661, 1, 'en', 'modules', 'dashboard.totalClients', 'Total Clients', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(662, 1, 'en', 'modules', 'projectCategory.addProjectCategory', 'Add Project Category', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(663, 1, 'en', 'app', 'menu.ticketSettings', 'Ticket Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(664, 1, 'en', 'app', 'reply', 'Reply', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(665, 1, 'en', 'app', 'open', 'Open', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(666, 1, 'en', 'app', 'resolved', 'Resolved', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(667, 1, 'en', 'messages', 'logTimeNote', 'Log time setting will update on select.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(668, 1, 'en', 'modules', 'payments.import', 'Import CSV', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(669, 1, 'en', 'app', 'sampleFile', 'Sample File', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(670, 1, 'en', 'modules', 'taskCategory.manageTaskCategory', 'Manage Task Category', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(671, 1, 'en', 'modules', 'taskCategory.addTaskCategory', 'Add Task Category', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(672, 0, 'en', 'messages', 'noCommentFound', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(673, 1, 'en', 'modules', 'currencySettings.exchangeRate', 'Exchange Rate', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(674, 1, 'en', 'messages', 'exchangeRateNote', 'Exchange rate is calculated from your default currency. Change default currency in company settings.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(675, 1, 'en', 'messages', 'noMethodsAdded', 'No Methods Added.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(676, 1, 'en', 'modules', 'offlinePayment.title', 'Offline Payment Method', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(677, 1, 'en', 'app', 'menu.offlinePaymentMethod', 'Offline Payment Method', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(678, 1, 'en', 'messages', 'addPaypalWebhookUrl', 'Add this webhook url on your paypal app settings.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(679, 1, 'en', 'messages', 'addStripeWebhookUrl', 'Add this webhook url on your stripe app settings.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(680, 1, 'en', 'messages', 'productPrice', 'Insert price without currency code.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(681, 1, 'en', 'modules', 'projects.selectClient', 'Select Client', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(682, 1, 'en', 'app', 'client_name', 'Client Name', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(683, 1, 'en', 'app', 'company_name', 'Company Name', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(684, 1, 'en', 'modules', 'proposal.convertProposalTitle', 'Convert Proposal To Invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(685, 1, 'en', 'modules', 'invoices.type', 'Type', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(686, 1, 'en', 'app', 'creditedInvoices', 'Invoices Credited', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(687, 1, 'en', 'modules', 'invoices.copyPaymentLink', 'Copy Payment Link', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(688, 1, 'en', 'app', 'appliedCredits', 'Applied Credits', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(689, 1, 'en', 'app', 'copied', 'copied', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(690, 1, 'en', 'modules', 'slackSettings.notificationSubtitle', 'Select the events for which an notification should be sent to user.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(691, 1, 'en', 'modules', 'emailSettings.notificationSubtitle', 'Select the events for which an email should be sent to user.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(692, 1, 'en', 'modules', 'themeSettings.customCssNote', 'If you have large custom css then create following 3 files in <strong>public/css/</strong> and make changes in it.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(693, 1, 'en', 'modules', 'themeSettings.customCss', 'Custom CSS', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(694, 1, 'en', 'modules', 'themeSettings.customCssPlaceholder', 'Enter your custom css after this line', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(695, 1, 'en', 'app', 'updateContactDetails', 'Update Contact Details', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(696, 1, 'en', 'app', 'day', 'Day', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(697, 1, 'en', 'app', 'menu.holiday', 'Holiday', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(698, 1, 'en', 'modules', 'contracts.totalContracts', 'Total Contracts', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(699, 1, 'en', 'modules', 'contracts.aboutToExpire', 'About To Expire', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(700, 1, 'en', 'modules', 'contracts.expired', 'Expired', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(701, 1, 'en', 'modules', 'contracts.contractType', 'Contract Type', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(702, 1, 'en', 'app', 'renew', 'Renew', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(703, 1, 'en', 'app', 'menu.contract', 'Contract', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(704, 1, 'en', 'modules', 'contracts.contractRenewalHistory', 'Contract Renewal History', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(705, 1, 'en', 'modules', 'contracts.renewContract', 'Renew Contract', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(706, 1, 'en', 'modules', 'contracts.renewedThisContract', 'renewed this contract', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(707, 1, 'en', 'modules', 'contracts.addContractType', 'Add Contract Type', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(708, 1, 'en', 'modules', 'contracts.manageContractType', 'Manage Contract Type', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(709, 1, 'en', 'modules', 'contracts.summery', 'Summary', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(710, 1, 'en', 'modules', 'contracts.discussion', 'Discussion', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(711, 1, 'en', 'modules', 'contracts.contractNumber', 'Contract Number', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(712, 1, 'en', 'app', 'all', 'All', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(713, 1, 'en', 'modules', 'leaves.calendarView', 'Calendar View', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(714, 1, 'en', 'app', 'select', 'Select', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(715, 1, 'en', 'modules', 'leaves.addLeaveType', 'Add Leave Type', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(716, 1, 'en', 'modules', 'leaves.leaveRequest', 'Leave Request', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(717, 1, 'en', 'modules', 'leaves.remainingLeaves', 'Remaining Leaves', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(718, 1, 'en', 'modules', 'projects.projectCategory', 'Project Category', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(719, 1, 'en', 'messages', 'sweetAlertTitle', 'Are you sure?', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(720, 1, 'en', 'messages', 'archiveMessage', 'Do you want to archive this project.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(721, 1, 'en', 'messages', 'confirmArchive', 'Yes, archive it!', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(722, 1, 'en', 'messages', 'confirmNoArchive', 'No, cancel please!', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(723, 1, 'en', 'app', 'exportExcel', 'Export To Excel', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(724, 1, 'en', 'messages', 'noInvoice', 'No invoice created.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(725, 1, 'en', 'modules', 'projects.addFileLink', 'Add file link', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(726, 1, 'en', 'app', 'list', 'List', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(727, 1, 'en', 'modules', 'burndown.actual', 'Actual', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(728, 1, 'en', 'modules', 'burndown.ideal', 'Ideal', '2020-04-09 22:03:32', '2020-04-09 22:03:49'),
(729, 1, 'en', 'messages', 'unArchiveMessage', 'Do you want to revert this project.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(730, 1, 'en', 'messages', 'confirmRevert', 'Yes, revert it!', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(731, 1, 'en', 'modules', 'projects.milestones', 'Milestones', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(732, 1, 'en', 'modules', 'projects.createMilestone', 'Create Milestone', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(733, 1, 'en', 'app', 'menu.issues', 'Issues', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(734, 1, 'en', 'messages', 'noIssue', 'No issue found.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(735, 1, 'en', 'modules', 'projects.projectBudget', 'Project Budget', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(736, 1, 'en', 'modules', 'projects.hours_allocated', 'Hours Allocated', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(737, 1, 'en', 'modules', 'projects.expenses_total', 'Expenses', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(738, 1, 'en', 'modules', 'accountSettings.sendReminderInfo', 'Remind project members about due date of projects.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(739, 1, 'en', 'app', 'manage', 'Manage', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(740, 1, 'en', 'messages', 'noLeadSourceAdded', 'No lead source added.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(741, 1, 'en', 'messages', 'noLeadAgentAdded', 'No Leads Added.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(742, 1, 'en', 'modules', 'lead.leadStatus', 'Lead Status', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(743, 1, 'en', 'messages', 'noLeadStatusAdded', 'No lead status added.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(744, 1, 'en', 'app', 'menu.attendanceSettings', 'Attendance Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(745, 1, 'en', 'modules', 'attendance.ipAddress', 'IP Address', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(746, 1, 'en', 'app', 'search', 'Search...', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(747, 1, 'en', 'app', 'menu.timeLogs', 'Time Logs', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(748, 1, 'en', 'modules', 'dashboard.totalUnpaidInvoices', 'Unpaid Invoices', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(749, 1, 'en', 'modules', 'dashboard.totalTodayAttendance', 'Today Attendance', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(750, 1, 'en', 'modules', 'tickets.totalUnresolvedTickets', 'Unresolved Tickets', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(751, 1, 'en', 'modules', 'dashboard.weatherSetLocation', 'Set current location to see weather', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(752, 1, 'en', 'messages', 'earningChartNote', 'The earnings are mentioned in your base currency. You can change it here.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(753, 1, 'en', 'messages', 'noOpenTasks', 'No open tasks.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(754, 1, 'en', 'app', 'customers', 'Customers', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(755, 1, 'en', 'app', 'leadAgent', 'Lead Agent', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(756, 1, 'en', 'modules', 'tickets.ticketType', 'Ticket Type', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(757, 1, 'en', 'messages', 'noTicketTypeAdded', 'No ticket type added.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(758, 1, 'en', 'modules', 'tickets.agents', 'Agents', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(759, 1, 'en', 'modules', 'tickets.manageGroups', 'Manage Groups', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(760, 1, 'en', 'messages', 'noAgentAdded', 'No agent added.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(761, 1, 'en', 'modules', 'tickets.template', 'Template', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(762, 1, 'en', 'messages', 'noTemplateFound', 'No template found.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(763, 1, 'en', 'app', 'menu.replyTemplates', 'Reply Templates', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(764, 1, 'en', 'app', 'menu.ticketChannel', 'Ticket Channel', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(765, 0, 'en', 'messages', 'noTicketChannelAdded', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(766, 1, 'en', 'app', 'team', 'Team', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(767, 1, 'en', 'modules', 'moduleSettings.moduleSetting', 'Module Setting', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(768, 1, 'en', 'modules', 'moduleSettings.employeeSubTitle', 'Select the modules which you want to enable.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(769, 1, 'en', 'modules', 'moduleSettings.section', 'section.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(770, 1, 'en', 'messages', 'taskSettingNote', 'Self task setting\n will update on select.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(771, 1, 'en', 'modules', 'customFields.addField', 'Add Field', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(772, 1, 'en', 'modules', 'invoiceSettings.logo', 'Invoice Logo', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(773, 1, 'en', 'modules', 'accountSettings.uploadLogo', 'Upload your logo', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(774, 1, 'en', 'app', 'menu.leaveSettings', 'Leaves Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(775, 1, 'en', 'modules', 'sticky.colors', 'Color', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(776, 1, 'en', 'app', 'menu.contractType', 'Contract Type', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(777, 1, 'en', 'app', 'viewInvoice', 'View Invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(778, 1, 'en', 'app', 'applyToInvoice', 'Apply To Invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(779, 1, 'en', 'modules', 'credit-notes.creditAmountTotal', 'Credit Amount Total', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(780, 1, 'en', 'modules', 'credit-notes.applyToInvoice', 'Apply Credits To Invoice', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(781, 1, 'en', 'app', 'annual', 'Annual', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(782, 1, 'en', 'app', 'monthly', 'Monthly', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(783, 1, 'en', 'app', 'max', 'Max', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(784, 1, 'en', 'modules', 'billing.nextPaymentDate', 'Next Payment Date', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(785, 1, 'en', 'modules', 'billing.previousPaymentDate', 'Previous Payment Date', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(786, 1, 'en', 'modules', 'invoices.payPaypal', 'Pay via Paypal', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(787, 1, 'en', 'modules', 'invoices.payStripe', 'Pay via Stripe', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(788, 1, 'en', 'messages', 'noPaymentGatewayEnabled', 'Superadmin has not enabled any payment gateway. Ask superadmin to enable payment gateway  to see Buy button', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(789, 1, 'en', 'app', 'menu.packages', 'Packages', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(790, 1, 'en', 'modules', 'employees.employeeName', 'Employee Name', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(791, 1, 'en', 'modules', 'taskReport.taskToComplete', 'Total Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(792, 1, 'en', 'modules', 'taskReport.completedTasks', 'Completed Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(793, 1, 'en', 'modules', 'taskReport.pendingTasks', 'Pending Tasks', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(794, 1, 'en', 'app', 'duration', 'Duration', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(795, 1, 'en', 'modules', 'incomeVsExpenseReport.totalExpense', 'Total Expense', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(796, 1, 'en', 'modules', 'financeReport.noteText', 'The earnings are calculated with latest exchange rate for different currencies.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(797, 1, 'en', 'modules', 'dashboard.freeEmployees', 'Not working on project', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(798, 0, 'en', 'messages', 'manageDepartment', NULL, '2020-04-09 22:03:32', '2020-04-09 22:03:32'),
(799, 1, 'en', 'modules', 'accountSettings.updateTitle', 'Update Organization Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(800, 1, 'en', 'app', 'disableCache', 'Disable Cache', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(801, 1, 'en', 'app', 'enableCache', 'Enable Cache', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(802, 1, 'en', 'modules', 'accountSettings.updateEnableDisableTest', 'Enable/Disable app update setting.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(803, 1, 'en', 'modules', 'accountSettings.emailVerificationEnableDisable', 'Enable/Disable newly registered companies email verification.', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(804, 1, 'en', 'app', 'language', 'Language', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(805, 1, 'en', 'messages', 'faviconNote', 'Go to <a href=\"https://www.favicon-generator.org/\">https://www.favicon-generator.org</a> and generate favicons.\n\nAfter that upload the favicons to public/favicon directory.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(806, 1, 'en', 'app', 'testimonial', 'Testimonials', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(807, 1, 'en', 'modules', 'frontSettings.title', 'Frontend CMS', '2020-04-09 22:03:32', '2020-04-09 22:03:48'),
(808, 1, 'en', 'messages', 'headerImageSizeMessage', 'Uploading image size should be 688x504.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(809, 1, 'en', 'app', 'menu.invoices', 'Invoices', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(810, 1, 'en', 'app', 'faqCategory', 'FAQ Category', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(811, 1, 'en', 'app', 'back', 'Back', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(812, 1, 'en', 'modules', 'update.newUpdate', 'New update available', '2020-04-09 22:03:32', '2020-04-09 22:03:47'),
(813, 1, 'en', 'modules', 'dashboard.totalCompanies', 'Total Companies', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(814, 1, 'en', 'modules', 'dashboard.activeCompanies', 'Active Companies', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(815, 1, 'en', 'modules', 'dashboard.licenseExpired', 'License Expired', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(816, 1, 'en', 'modules', 'dashboard.inactiveCompanies', 'Inactive Companies', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(817, 1, 'en', 'messages', 'installationWelcome', ' Welcome to Worksuite! Let\'s get you started', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(818, 1, 'en', 'messages', 'installationProgress', 'Progress', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(819, 1, 'en', 'messages', 'installationStep1', 'Step 1. Installation', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(820, 1, 'en', 'messages', 'installationCongratulation', 'Congratulations! You have taken the first step to better managing your worksuite', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(821, 1, 'en', 'messages', 'installationStep2', 'Step 2. SMTP Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(822, 1, 'en', 'messages', 'installationSmtp', 'Add your smtp details to make emails work', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(823, 1, 'en', 'messages', 'installationStep3', 'Step 3. Global Settings', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(824, 1, 'en', 'messages', 'installationCompanySetting', 'Make changes to global setting to start using app.', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(825, 1, 'en', 'messages', 'installationStep4', 'Step 4. Profile Setting', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(826, 1, 'en', 'messages', 'installationProfileSetting', 'Update your login email and password', '2020-04-09 22:03:32', '2020-04-09 22:03:46'),
(827, 1, 'en', 'app', 'superAdmin', 'Super Admin', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(828, 1, 'en', 'app', 'new', 'New', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(829, 1, 'en', 'app', 'info', 'Info', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(830, 1, 'en', 'app', 'module', 'Module', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(831, 1, 'en', 'app', 'freeTrial', 'Free Trial', '2020-04-09 22:03:32', '2020-04-09 22:03:45'),
(832, 1, 'en', 'messages', 'FeatureImageSizeMessage', 'Uploading image size should be 400x352.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(833, 1, 'en', 'app', 'install', 'Install', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(834, 1, 'en', 'app', 'verifyEnvato', 'Verify Envato Purchase Code', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(835, 1, 'en', 'messages', 'loginAgain', 'You will have to login again to see the changes.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(836, 1, 'en', 'app', 'offlineRequest', 'Offline Request', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(837, 1, 'en', 'app', 'faq', 'Faq', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(838, 1, 'en', 'modules', 'accountSettings.currencyConverterKey', 'Currency converter key', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(839, 1, 'en', 'messages', 'addCurrencyNote', 'Add currency converter key to add or edit currency and Update exchange rate.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(840, 1, 'en', 'messages', 'currencyConvertApiKeyUrl', 'Get API key by this url', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(841, 1, 'en', 'modules', 'slackSettings.uploadSlackLogo', 'Upload Notification Logo', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(842, 1, 'en', 'app', 'frontClient', 'Front Client', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(843, 1, 'en', 'app', 'company', 'Company', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(844, 1, 'en', 'modules', 'employees.updateAdminPasswordNote', 'Admin will login using this password. (Leave blank to keep current password)', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(845, 1, 'en', 'modules', 'offlinePayment.method', 'Method', '2020-04-09 22:03:33', '2020-04-09 22:03:48'),
(846, 1, 'en', 'modules', 'offlinePayment.active', 'Active', '2020-04-09 22:03:33', '2020-04-09 22:03:48'),
(847, 1, 'en', 'modules', 'offlinePayment.inActive', 'Inactive', '2020-04-09 22:03:33', '2020-04-09 22:03:48'),
(848, 1, 'en', 'modules', 'sticky.addNote', 'Add Note', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(849, 1, 'en', 'app', 'superAdminPanel', 'Super Admin Panel', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(850, 1, 'en', 'app', 'panel', 'Panel', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(851, 1, 'en', 'app', 'employeePanel', 'Employee Panel', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(852, 1, 'en', 'modules', 'gdpr.requestDataRemoval', 'Request Data Removal', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(853, 1, 'en', 'modules', 'gdpr.dataRemovalDescription', 'Briefly describe the purpose of removal of data', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(854, 1, 'en', 'modules', 'invoices.payNow', 'Pay Now', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(855, 1, 'en', 'modules', 'invoices.payRazorpay', 'Pay via Razorpay', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(856, 1, 'en', 'app', 'forbiddenError', 'Forbidden Error', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(857, 1, 'en', 'app', 'menu.home', 'Home', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(858, 1, 'en', 'modules', 'profile.yourName', 'Your Name', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(859, 1, 'en', 'modules', 'profile.yourEmail', 'Your Email', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(860, 1, 'en', 'modules', 'messages.message', 'Message', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(861, 1, 'en', 'app', 'yourEmailAddress', 'Your Email Address', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(862, 1, 'en', 'app', 'confirmPassword', 'Confirm Password', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(863, 1, 'en', 'app', 'signup', 'Sign Up', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(864, 1, 'en', 'app', 'yourPlan', ' Your Plan', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(865, 1, 'en', 'modules', 'frontCms.pickPlan', 'Pick your plan', '2020-04-09 22:03:33', '2020-04-09 22:03:48'),
(866, 1, 'en', 'app', 'login', 'Log In', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(867, 1, 'en', 'app', 'invoiceNumber', 'Invoice Number', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(868, 1, 'en', 'app', 'product', 'Product', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(869, 1, 'en', 'app', 'purchase', 'Purchase', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(870, 1, 'en', 'modules', 'messages.chooseAdmin', 'Choose Admin', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(871, 1, 'en', 'modules', 'issues.updateIssue', 'Update Issue', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(872, 1, 'en', 'modules', 'issues.addIssue', 'Add Issue', '2020-04-09 22:03:33', '2020-04-09 22:03:47'),
(873, 1, 'en', 'modules', 'dashboard.totalPaidAmount', 'Paid Amount', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(874, 1, 'en', 'modules', 'dashboard.totalOutstandingAmount', 'Outstanding Amount', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(875, 1, 'en', 'app', 'welcome', 'Welcome', '2020-04-09 22:03:33', '2020-04-09 22:03:45'),
(876, 1, 'en', 'messages', 'newInvoiceCreated', 'New invoice created.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(877, 1, 'en', 'messages', 'planPurchaseBy', 'Plan purchased by', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(878, 1, 'en', 'messages', 'planUpdatedBy', 'Plan updated by', '2020-04-09 22:03:33', '2020-04-09 22:03:46');
INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(879, 1, 'en', 'messages', 'namedCompanyRegistered.', 'named company registered.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(880, 1, 'en', 'messages', 'offlinePackageChangeRequest', 'New offline package request', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(881, 1, 'en', 'messages', 'newEstimateReceived', 'New Estimate Received.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(882, 1, 'en', 'messages', 'newEstimateTotal', 'Estimate of :total is generated.', '2020-04-09 22:03:33', '2020-04-09 22:03:46'),
(883, 0, 'en', '_json', 'icon-people', NULL, '2020-04-09 22:03:33', '2020-04-09 22:03:33'),
(884, 0, 'en', '_json', 'status', NULL, '2020-04-09 22:03:33', '2020-04-09 22:03:33'),
(885, 0, 'en', '_json', 'changes', NULL, '2020-04-09 22:03:33', '2020-04-09 22:03:33'),
(886, 0, 'en', '_json', 'Pay', NULL, '2020-04-09 22:03:33', '2020-04-09 22:03:33'),
(887, 1, 'en', 'app', 'phone', 'Phone', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(888, 1, 'en', 'app', 'mobile', 'Mobile', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(889, 1, 'en', 'app', 'address', 'Address', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(890, 1, 'en', 'app', 'hideCompletedTasks', 'Hide Completed Tasks', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(891, 1, 'en', 'app', 'incomplete', 'Incomplete', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(892, 1, 'en', 'app', 'selectDateRange', 'Select Date Range', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(893, 1, 'en', 'app', 'adminPanel', 'Admin Panel', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(894, 1, 'en', 'app', 'clientPanel', 'Client Panel', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(895, 1, 'en', 'app', 'paymentOn', 'Payment On', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(896, 1, 'en', 'app', 'gateway', 'Gateway', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(897, 1, 'en', 'app', 'transactionId', 'Transaction Id', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(898, 1, 'en', 'app', 'timeLog', 'Time Log ', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(899, 1, 'en', 'app', 'category', 'Category', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(900, 1, 'en', 'app', 'projectTemplate', 'Project Template', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(901, 1, 'en', 'app', 'menu.dashboard', 'Dashboard', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(902, 1, 'en', 'app', 'menu.taskCalendar', 'Task Calendar', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(903, 1, 'en', 'app', 'menu.taskReport', 'Task Report', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(904, 1, 'en', 'app', 'menu.timeLogReport', 'Time Log Report', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(905, 1, 'en', 'app', 'menu.financeReport', 'Finance Report', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(906, 1, 'en', 'app', 'menu.chooseTheme', 'Choose Front Theme', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(907, 1, 'en', 'app', 'menu.emailSettings', 'Email Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(908, 1, 'en', 'app', 'menu.currencySettings', 'Currency Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(909, 1, 'en', 'app', 'menu.contacts', 'Contacts', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(910, 1, 'en', 'app', 'menu.messages', 'Messages', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(911, 1, 'en', 'app', 'menu.themeSettings', 'Theme Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(912, 1, 'en', 'app', 'menu.estimates', 'Estimates', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(913, 1, 'en', 'app', 'menu.paymentGatewayCredential', 'Payment Credentials', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(914, 1, 'en', 'app', 'menu.expenses', 'Expenses', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(915, 1, 'en', 'app', 'menu.incomeVsExpenseReport', 'Income Vs Expense Report', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(916, 1, 'en', 'app', 'menu.invoiceSettings', 'Invoice Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(917, 1, 'en', 'app', 'menu.slackSettings', 'Slack Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(918, 1, 'en', 'app', 'menu.ticketAgents', 'Ticket Agents', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(919, 1, 'en', 'app', 'menu.ticketTypes', 'Ticket Types', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(920, 1, 'en', 'app', 'menu.tickets', 'Tickets', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(921, 1, 'en', 'app', 'menu.customFields', 'Custom Fields', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(922, 1, 'en', 'app', 'menu.payroll', 'Payroll', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(923, 1, 'en', 'app', 'menu.rolesPermission', 'Roles & Permissions', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(924, 1, 'en', 'app', 'menu.messageSettings', 'Message Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(925, 1, 'en', 'app', 'menu.storageSettings', 'Storage Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(926, 1, 'en', 'app', 'menu.employeeList', 'Employee List', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(927, 1, 'en', 'app', 'menu.teams', 'Teams', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(928, 1, 'en', 'app', 'menu.leaveReport', 'Leave Report', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(929, 1, 'en', 'app', 'menu.leadSource', 'Lead Source', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(930, 1, 'en', 'app', 'menu.leadStatus', 'Lead Status', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(931, 1, 'en', 'app', 'menu.onlinePayment', 'Online Payment Credential', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(932, 1, 'en', 'app', 'menu.method', 'Method', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(933, 1, 'en', 'app', 'menu.timeLogSettings', 'Time Log Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(934, 1, 'en', 'app', 'menu.projectTemplate', 'Project Template', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(935, 1, 'en', 'app', 'menu.projectTemplateMember', 'Project Template Member', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(936, 1, 'en', 'app', 'menu.addProjectTemplate', 'Project Templates', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(937, 1, 'en', 'app', 'menu.template', 'Template', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(938, 1, 'en', 'app', 'menu.leadFiles', 'Lead Files', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(939, 1, 'en', 'app', 'menu.pushNotifications', 'Push Notifications', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(940, 1, 'en', 'app', 'menu.notificationSettings', 'Notification Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(941, 1, 'en', 'app', 'menu.viewArchive', 'View Archive', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(942, 1, 'en', 'app', 'menu.clientModule', 'Client Module Setting', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(943, 1, 'en', 'app', 'menu.employeeModule', 'Employee Module Setting', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(944, 1, 'en', 'app', 'menu.adminModule', 'Admin Module Setting', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(945, 1, 'en', 'app', 'menu.documents', 'Documents', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(946, 1, 'en', 'app', 'menu.pushNotificationSetting', 'Push Notification Setting', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(947, 1, 'en', 'app', 'menu.companies', 'Companies', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(948, 1, 'en', 'app', 'menu.features', 'FEATURES', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(949, 1, 'en', 'app', 'menu.pricing', 'PRICING', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(950, 1, 'en', 'app', 'menu.contact', 'CONTACT', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(951, 1, 'en', 'app', 'menu.credit-note', 'Credit Note', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(952, 1, 'en', 'app', 'menu.financeSettings', 'Finance Settings', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(953, 1, 'en', 'app', 'menu.accountSetup', 'Account Setup', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(954, 1, 'en', 'app', 'menu.faqCategory', 'FAQ Category', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(955, 1, 'en', 'app', 'menu.customModule', 'Custom Modules', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(956, 1, 'en', 'app', 'projectAdminPanel', 'Project Admin Panel', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(957, 1, 'en', 'app', 'loginAsProjectAdmin', 'Login As Project Admin', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(958, 1, 'en', 'app', 'last', 'Last', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(959, 1, 'en', 'app', 'income', 'Income', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(960, 1, 'en', 'app', 'expense', 'Expense', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(961, 1, 'en', 'app', 'week', 'Week', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(962, 1, 'en', 'app', 'filterBy', 'Filter by', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(963, 1, 'en', 'app', 'value', 'Value', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(964, 1, 'en', 'app', 'monday', 'Monday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(965, 1, 'en', 'app', 'tuesday', 'Tuesday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(966, 1, 'en', 'app', 'wednesday', 'Wednesday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(967, 1, 'en', 'app', 'thursday', 'Thursday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(968, 1, 'en', 'app', 'friday', 'Friday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(969, 1, 'en', 'app', 'saturday', 'Saturday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(970, 1, 'en', 'app', 'sunday', 'Sunday', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(971, 1, 'en', 'app', 'newNotifications', 'New notifications', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(972, 1, 'en', 'app', 'complete', 'Complete', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(973, 1, 'en', 'app', 'low', 'Low', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(974, 1, 'en', 'app', 'Manage role', 'Manage role', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(975, 1, 'en', 'app', 'Search:', 'Search:', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(976, 1, 'en', 'app', 'selectFile', 'Select File', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(977, 1, 'en', 'app', 'admin', 'Admin', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(978, 1, 'en', 'app', 'noPermission', 'You do not have permission to access this.', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(979, 1, 'en', 'app', 'recoverPassword', 'Recover Password', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(980, 1, 'en', 'app', 'sendPasswordLink', 'Send Reset Link', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(981, 1, 'en', 'app', 'enterEmailInstruction', 'Enter your Email and instructions will be sent to you!', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(982, 1, 'en', 'app', 'medium', 'Medium', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(983, 1, 'en', 'app', 'high', 'High', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(984, 1, 'en', 'app', 'urgent', 'Urgent', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(985, 1, 'en', 'app', 'male', 'male', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(986, 1, 'en', 'app', 'female', 'female', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(987, 1, 'en', 'app', 'source', 'Source', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(988, 1, 'en', 'app', 'followUp', 'Follow Up', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(989, 1, 'en', 'app', 'notice', 'Note', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(990, 1, 'en', 'app', 'minutes', 'Minutes', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(991, 1, 'en', 'app', 'onLeave', 'On Leave', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(992, 1, 'en', 'app', 'enable', 'Enable', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(993, 1, 'en', 'app', 'disable', 'Disable', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(994, 1, 'en', 'app', 'partial', 'Partial', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(995, 1, 'en', 'app', 'global', 'Global', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(996, 1, 'en', 'app', 'watchTutorial', 'Watch Tutorial', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(997, 1, 'en', 'app', 'latitude', 'Latitude', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(998, 1, 'en', 'app', 'longitude', 'Longitude', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(999, 1, 'en', 'app', 'image', 'Image', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1000, 1, 'en', 'app', 'icon', 'Icon', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1001, 1, 'en', 'app', 'featureWithImage', 'Feature With Image', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1002, 1, 'en', 'app', 'featureWithIcon', 'Feature With Icon', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1003, 1, 'en', 'app', 'gstNumber', 'GST Number', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1004, 1, 'en', 'app', 'showGst', 'Show GST Number', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1005, 1, 'en', 'app', 'mobileNumber', 'Mobile Number', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1006, 1, 'en', 'app', 'password', 'Password', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1007, 1, 'en', 'app', 'webhook', 'Webhook URL', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1008, 1, 'en', 'app', 'searchResults', 'Search Results', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1009, 1, 'en', 'app', 'leave_type', 'Leave Type', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1010, 1, 'en', 'app', 'tableView', 'Table View', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1011, 1, 'en', 'app', 'calendarView', 'Calendar View', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1012, 1, 'en', 'app', 'refreshCache', 'Refresh Cache', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1013, 1, 'en', 'app', 'purchasePackage', 'Purchase Package', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1014, 1, 'en', 'app', 'thumbnail', 'Thumbnail', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1015, 1, 'en', 'app', 'zero', 'Zero', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1016, 1, 'en', 'app', 'viewPayments', 'View Payments', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1017, 1, 'en', 'app', 'billedTo', 'Billed To', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1018, 1, 'en', 'app', 'thanks', 'Thanks', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1019, 1, 'en', 'app', 'terms', 'Terms', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1020, 1, 'en', 'app', 'congratulations', 'Congratulations', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1021, 1, 'en', 'app', 'paperlessOffice', 'You have taken the first step to create a paperless office', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1022, 1, 'en', 'app', 'summary', 'Summary', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1023, 1, 'en', 'app', 'language_code', 'Language Code', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1024, 1, 'en', 'app', 'enabled', 'Enabled', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1025, 1, 'en', 'app', 'disabled', 'Disabled', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1026, 1, 'en', 'app', 'designation', 'Designation', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1027, 1, 'en', 'app', 'slug', 'Slug', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1028, 1, 'en', 'app', 'timelogs', 'Time Logs', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1029, 1, 'en', 'app', 'clients', 'Clients', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1030, 1, 'en', 'app', 'employees', 'Employees', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1031, 1, 'en', 'app', 'lastActivity', 'Last Activity', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1032, 1, 'en', 'app', 'contracts.description', 'Description', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1033, 1, 'en', 'app', 'content', 'Content', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1034, 1, 'en', 'app', 'credit-notes.invoiceDate', 'Invoice Date', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1035, 1, 'en', 'app', 'credit-notes.invoiceAmount', 'Invoice Amount', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1036, 1, 'en', 'app', 'credit-notes.invoiceBalanceDue', 'Invoice Balance Due', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1037, 1, 'en', 'app', 'credit-notes.amountToCredit', 'Amount To Credit', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1038, 1, 'en', 'app', 'credit-notes.remainingAmount', 'Remaining Amount', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1039, 1, 'en', 'app', 'credit-notes.amountCredited', 'Amount Credited', '2020-04-09 22:03:45', '2020-04-09 22:03:45'),
(1040, 1, 'en', 'app', 'purchaseDate', 'Purchase Date', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1041, 1, 'en', 'app', 'file', 'File', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1042, 1, 'en', 'app', 'verify', 'Verify', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1043, 1, 'en', 'app', 'bills', 'Bills', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1044, 1, 'en', 'app', 'apps', 'Application', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1045, 1, 'en', 'app', 'rating', 'Rating', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1046, 1, 'en', 'app', 'comment', 'Comment', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1047, 1, 'en', 'app', 'cta', 'Cta', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1048, 1, 'en', 'app', 'feature', 'Feature', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1049, 1, 'en', 'app', 'page', 'Page', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1050, 1, 'en', 'app', 'home', 'Home', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1051, 1, 'en', 'app', 'contact', 'Contact', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1052, 1, 'en', 'app', 'billedMonthly', 'Billed Monthly', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1053, 1, 'en', 'app', 'billedAnnually', 'Billed Annually', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1054, 1, 'en', 'app', 'pickUp', 'Pick Up', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1055, 1, 'en', 'app', 'question', 'Question', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1056, 1, 'en', 'app', 'answer', 'Answer', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1057, 1, 'en', 'app', 'get_start', 'Get Started', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1058, 1, 'en', 'app', 'contact_submit', 'Contact Submit', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1059, 1, 'en', 'app', 'frontNewTheme', 'Front New Theme', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1060, 1, 'en', 'app', 'frontThemeDesign', 'Front Theme Design', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1061, 1, 'en', 'app', 'selectTheme', 'Select the theme for front website', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1062, 1, 'en', 'app', 'version', 'Version', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1063, 1, 'en', 'app', 'currentVersion', 'Current Version', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1064, 1, 'en', 'app', 'latestVersion', 'Latest Version', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1065, 1, 'en', 'app', 'collapseSidebar', 'Collapse Sidebar', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1066, 1, 'en', 'app', 'public', 'Public', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1067, 1, 'en', 'auth', 'failed', 'These credentials do not match our records.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1068, 1, 'en', 'auth', 'throttle', 'Too many login attempts. Please try again in :seconds seconds.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1069, 1, 'en', 'email', 'planUpdate.text', 'named company has been updated plan', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1070, 1, 'en', 'email', 'invoices.offlinePaymentRequest', 'Offline Payment Request.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1071, 1, 'en', 'email', 'paymentReminder.content', 'This is to remind you about the due date of the following project invoice payment which is', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1072, 1, 'en', 'installer_messages', 'title', 'Laravel Installer', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1073, 1, 'en', 'installer_messages', 'next', 'Next Step', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1074, 1, 'en', 'installer_messages', 'finish', 'Install', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1075, 1, 'en', 'installer_messages', 'welcome.title', 'Welcome To The Installer', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1076, 1, 'en', 'installer_messages', 'welcome.message', 'Welcome to the setup wizard.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1077, 1, 'en', 'installer_messages', 'requirements.title', 'Server Requirements', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1078, 1, 'en', 'installer_messages', 'permissions.title', 'Permissions', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1079, 1, 'en', 'installer_messages', 'environment.title', 'Database Configuration', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1080, 1, 'en', 'installer_messages', 'environment.save', 'Save .env', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1081, 1, 'en', 'installer_messages', 'environment.success', 'Your .env file settings have been saved.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1082, 1, 'en', 'installer_messages', 'environment.errors', 'Unable to save the .env file, Please create it manually.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1083, 1, 'en', 'installer_messages', 'install', 'Install', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1084, 1, 'en', 'installer_messages', 'final.title', 'Finished', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1085, 1, 'en', 'installer_messages', 'final.finished', 'Application has been successfully installed.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1086, 1, 'en', 'installer_messages', 'final.exit', 'Click here to exit', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1087, 1, 'en', 'installer_messages', 'checkPermissionAgain', ' Check Permission Again', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1088, 1, 'en', 'messages', 'Login As Employee', 'Login As Employee', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1089, 1, 'en', 'messages', 'newMemberAddedToTheProject', 'New member added to the project.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1090, 1, 'en', 'messages', 'clientUploadedAFileToTheProject', '(Client) uploaded a file to the project.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1091, 1, 'en', 'messages', 'noProjectFound', 'No Project Found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1092, 1, 'en', 'messages', 'noProjectAssigned', 'No project assigned to you.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1093, 1, 'en', 'messages', 'noActivityByThisUser', 'No activity by the user.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1094, 1, 'en', 'messages', 'noProjectCategoryAdded', 'No project category added.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1095, 1, 'en', 'messages', 'noClientAdded', 'No client added.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1096, 1, 'en', 'messages', 'noOpenIssues', 'No open issues.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1097, 1, 'en', 'messages', 'defaultColorNote', 'If you will not choose any color blue will be default', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1098, 1, 'en', 'messages', 'categoryUpdated', 'Category updated successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1099, 1, 'en', 'messages', 'noNotice', 'No notice published.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1100, 1, 'en', 'messages', 'templateTaskDeletedSuccessfully', 'Template Task deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1101, 1, 'en', 'messages', 'noProjectTemplateAdded', 'No project template added.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1102, 1, 'en', 'messages', 'selectTemplate', 'Select Template', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1103, 1, 'en', 'messages', 'monthWiseDataNotFound', 'No Holiday found for this month.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1104, 1, 'en', 'messages', 'noDocsFound', 'No Document found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1105, 1, 'en', 'messages', 'noFaqCreated', 'Seems like no faq has been created by the admin', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1106, 1, 'en', 'messages', 'noModules', 'No modules has been installed.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1107, 1, 'en', 'messages', 'welcome.message', 'Welcome to the setup wizard', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1108, 1, 'en', 'messages', 'welcome.title', 'Welcome to the installer', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1109, 1, 'en', 'messages', 'title', 'Laravel installer', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1110, 1, 'en', 'messages', 'requirements.title', 'formalities', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1111, 1, 'en', 'messages', 'permissions.title', 'Permissions', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1112, 1, 'en', 'messages', 'next', 'Following', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1113, 1, 'en', 'messages', 'finish', 'Install', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1114, 1, 'en', 'messages', 'final.title', 'Finalized.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1115, 1, 'en', 'messages', 'final.finished', 'The application has been installed successfully!', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1116, 1, 'en', 'messages', 'final.exit', 'Click here to exit.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1117, 1, 'en', 'messages', 'environment.errors', 'It is not possible to create the .env file, please try manually.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1118, 1, 'en', 'messages', 'environment.save', 'Save .env file', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1119, 1, 'en', 'messages', 'environment.success', 'Changes to your .env file have been saved.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1120, 1, 'en', 'messages', 'environment.title', 'Settings of the environment', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1121, 1, 'en', 'messages', 'noProjectCategory', 'No project category found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1122, 1, 'en', 'messages', 'noTaskCategory', 'No task category found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1123, 1, 'en', 'messages', 'databaseUpdated', 'Database update successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1124, 1, 'en', 'messages', 'noGroupAdded', 'No group added.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1125, 1, 'en', 'messages', 'noFeedbackReceived', 'No feedback received.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1126, 1, 'en', 'messages', 'customFieldCreateSuccess', 'Custom field created successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1127, 1, 'en', 'messages', 'permissionUpdated', 'Permission updated successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1128, 1, 'en', 'messages', 'noRoleMemberFound', 'No member is assigned to this role.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1129, 1, 'en', 'messages', 'noRoleFound', 'No role found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1130, 1, 'en', 'messages', 'noTicketFound', 'No ticket found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1131, 1, 'en', 'messages', 'noLeaveTypeAdded', 'No leave type added.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1132, 1, 'en', 'messages', 'leaveDeleteSuccess', 'Leave deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1133, 1, 'en', 'messages', 'updateAlert', 'Do not click update now button if the application is customised. Your changes will be lost.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1134, 1, 'en', 'messages', 'updateBackupNotice', 'Take backup of files and database before updating.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1135, 1, 'en', 'messages', 'fieldBlank', 'Field cannot be blank.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1136, 1, 'en', 'messages', 'defaultRoleCantDelete', 'Default role can not be deleted.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1137, 1, 'en', 'messages', 'defaultRolesCantDelete', 'Admin, Client and Employee roles are default roles and  Default roles can not be deleted.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1138, 1, 'en', 'messages', 'noPendingLeadFollowUps', 'No pending follow-up.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1139, 1, 'en', 'messages', 'noTaskAddedToProject', 'No task added to this project.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1140, 1, 'en', 'messages', 'noAttendanceDetailTOday', 'No attendance detail for today.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1141, 1, 'en', 'messages', 'noAttendanceDetail', 'No attendance detail.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1142, 1, 'en', 'messages', 'noTaskCategoryAdded', 'No task category added.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1143, 1, 'en', 'messages', 'NewCompanyRegistered', 'New company registered.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1144, 1, 'en', 'messages', 'planPurchaseByCompany', 'Plan purchased by company', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1145, 1, 'en', 'messages', 'planUpdatedByCompany', 'Plan updated by company', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1146, 1, 'en', 'messages', 'feature.addedSuccess', 'Feature added successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1147, 1, 'en', 'messages', 'feature.updatedSuccess', 'Feature updated successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1148, 1, 'en', 'messages', 'feature.deletedSuccess', 'Feature deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1149, 1, 'en', 'messages', 'reminderMailSuccess', 'Reminder Mail sent successfully', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1150, 1, 'en', 'messages', 'employeeSelfTask', 'Employee can create task for self.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1151, 1, 'en', 'messages', 'licenseExpiredNote', 'License has been expired please purchase.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1152, 1, 'en', 'messages', 'purchasePackageMessage', 'You need to purchase a package to explore more features.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1153, 1, 'en', 'messages', 'createSuccess', 'Created successfully', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1154, 1, 'en', 'messages', 'designationAdded', 'Designation added successfully', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1155, 1, 'en', 'messages', 'officeTimeOver', 'Office hours have passed. You cannot mark attendance for today now.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1156, 1, 'en', 'messages', 'cacheEnabled', 'Cache is Enabled', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1157, 1, 'en', 'messages', 'cacheDisabled', 'Cache is disabled', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1158, 1, 'en', 'messages', 'contractDeleted', 'Contract deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1159, 1, 'en', 'messages', 'noContractType', 'No contract type found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1160, 1, 'en', 'messages', 'noLeadAgent', 'No lead agent found.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1161, 1, 'en', 'messages', 'testimonial.addedSuccess', 'Testimonial added successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1162, 1, 'en', 'messages', 'testimonial.updatedSuccess', 'Testimonial updated successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1163, 1, 'en', 'messages', 'testimonial.deletedSuccess', 'Testimonial deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1164, 1, 'en', 'messages', 'frontClient.addedSuccess', 'Front Client added successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1165, 1, 'en', 'messages', 'frontClient.updatedSuccess', 'Front Client updated successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1166, 1, 'en', 'messages', 'frontClient.deletedSuccess', 'Front Client deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1167, 1, 'en', 'messages', 'frontFaq.addedSuccess', 'Front faq added successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1168, 1, 'en', 'messages', 'frontFaq.updatedSuccess', 'Front faq updated successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1169, 1, 'en', 'messages', 'frontFaq.deletedSuccess', 'Front faq deleted successfully.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1170, 1, 'en', 'messages', 'ratingShouldBe', 'Rating should be 1 - 5', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1171, 1, 'en', 'messages', 'frontOldNewTheme', 'Enable/Disable front theme new or old.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1172, 1, 'en', 'messages', 'noDepartment', 'Seems like no department exist in the database. Create your first department', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1173, 1, 'en', 'messages', 'noDesignation', 'Seems like no designation exist in the database. Create your first designation', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1174, 1, 'en', 'messages', 'recordSaved', 'Record saved successfully', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1175, 1, 'en', 'modules', 'dashboard.totalPaidInvoices', 'Paid Invoices', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1176, 1, 'en', 'modules', 'dashboard.totalPendingIssues', 'Total Pending Issues', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1177, 1, 'en', 'modules', 'dashboard.recentEarnings', 'Recent Earnings', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1178, 1, 'en', 'modules', 'dashboard.overdueTasks', 'Overdue Tasks', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1179, 1, 'en', 'modules', 'dashboard.pendingClientIssues', 'Pending Issues', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1180, 1, 'en', 'modules', 'dashboard.projectActivityTimeline', 'Project Activity Timeline', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1181, 1, 'en', 'modules', 'dashboard.userActivityTimeline', 'User Activity Timeline', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1182, 1, 'en', 'modules', 'dashboard.dueDate', 'Due Date', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1183, 1, 'en', 'modules', 'dashboard.newTickets', 'New Tickets', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1184, 1, 'en', 'modules', 'dashboard.followUpDate', 'Follow Up Date', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1185, 1, 'en', 'modules', 'dashboard.pendingFollowUp', 'Pending FollowUp', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1186, 1, 'en', 'modules', 'dashboard.nextFollowUp', 'Next Follow Up', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1187, 1, 'en', 'modules', 'dashboard.holidayCheck', 'Today is Holiday for', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1188, 1, 'en', 'modules', 'dashboard.totalArchiveProjects', 'Total Archived Projects', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1189, 1, 'en', 'modules', 'dashboard.dashboardWidgets', 'Dashboard Widgets', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1190, 1, 'en', 'modules', 'dashboard.clientFeedback', 'Client Feedback', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1191, 1, 'en', 'modules', 'dashboard.totalResolvedTickets', 'Total Resolved Tickets', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1192, 1, 'en', 'modules', 'dashboard.totalUnresolvedTickets', 'Total Unresolved Tickets', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1193, 1, 'en', 'modules', 'dashboard.settingsLeaves', 'Settings Leaves', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1194, 1, 'en', 'modules', 'dashboard.completedTasks', 'Completed Tasks', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1195, 1, 'en', 'modules', 'dashboard.clientFeedbacks', 'Client Feedbacks', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1196, 1, 'en', 'modules', 'client.companyDetails', 'Company Details', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1197, 1, 'en', 'modules', 'client.clientOtherDetails', 'Client Other Details', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1198, 1, 'en', 'modules', 'client.website', 'Website', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1199, 1, 'en', 'modules', 'client.address', 'Address', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1200, 1, 'en', 'modules', 'client.clientDetails', 'Client Details', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1201, 1, 'en', 'modules', 'client.clientEmail', 'Client Email', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1202, 1, 'en', 'modules', 'client.emailNote', 'Client will login using this email.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1203, 1, 'en', 'modules', 'client.passwordNote', 'Client will login using this password.', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1204, 1, 'en', 'modules', 'client.passwordUpdateNote', 'Client will login using this password. (Leave blank to keep current password)', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1205, 1, 'en', 'modules', 'client.mobile', 'Mobile', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1206, 1, 'en', 'modules', 'client.addNewClient', 'Add New Client', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1207, 1, 'en', 'modules', 'client.projectName', 'Project Name', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1208, 1, 'en', 'modules', 'client.startedOn', 'Started On', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1209, 1, 'en', 'modules', 'client.deadline', 'Deadline', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1210, 1, 'en', 'modules', 'client.generateRandomPassword', 'Generate Random Password', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1211, 1, 'en', 'modules', 'client.offline', 'Offline', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1212, 1, 'en', 'modules', 'client.online', 'Online', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1213, 1, 'en', 'modules', 'client.all', 'All', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1214, 1, 'en', 'modules', 'client.sendCredentials', 'Send Credentials', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1215, 1, 'en', 'modules', 'client.sendCredentialsMessage', 'Do you want to send credentials via E-mail to client ?', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1216, 1, 'en', 'modules', 'gdpr.gdpr', 'GDPR', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1217, 1, 'en', 'modules', 'gdpr.customers', 'Customers', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1218, 1, 'en', 'modules', 'gdpr.purpose', 'Purpose', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1219, 1, 'en', 'modules', 'gdpr.ipAddress', 'IP Address', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1220, 1, 'en', 'modules', 'gdpr.staffMember', 'Staff Member', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1221, 1, 'en', 'modules', 'gdpr.additionalDescription', 'Additional Description', '2020-04-09 22:03:46', '2020-04-09 22:03:46'),
(1222, 1, 'en', 'modules', 'gdpr.purposeDescription', 'Purpose Description', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1223, 1, 'en', 'modules', 'gdpr.removalRequestSuccess', 'Removal request has been sent to the admin. You will informed once it is approved', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1224, 1, 'en', 'modules', 'contacts.contactName', 'Contact Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1225, 1, 'en', 'modules', 'employees.addNewEmployee', 'Add New Employee', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1226, 1, 'en', 'modules', 'employees.employeeEmail', 'Employee Email', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1227, 1, 'en', 'modules', 'employees.emailNote', 'Employee will login using this email.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1228, 1, 'en', 'modules', 'employees.employeePassword', 'Password', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1229, 1, 'en', 'modules', 'employees.jobTitle', 'Job Title', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1230, 1, 'en', 'modules', 'employees.hourlyRate', 'Hourly Rate', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1231, 1, 'en', 'modules', 'employees.tasksDone', 'Tasks Done', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1232, 1, 'en', 'modules', 'employees.hoursLogged', 'Hours Logged', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1233, 1, 'en', 'modules', 'employees.activity', 'Activity', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1234, 1, 'en', 'modules', 'employees.profile', 'Profile', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1235, 1, 'en', 'modules', 'employees.fullName', 'Full Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1236, 1, 'en', 'modules', 'employees.startTime', 'Start Time', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1237, 1, 'en', 'modules', 'employees.endTime', 'End Time', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1238, 1, 'en', 'modules', 'employees.totalHours', 'Total Hours', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1239, 1, 'en', 'modules', 'employees.memo', 'Memo', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1240, 1, 'en', 'modules', 'employees.joiningDate', 'Joining Date', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1241, 1, 'en', 'modules', 'employees.gender', 'Gender', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1242, 1, 'en', 'modules', 'employees.title', 'Select Employee', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1243, 1, 'en', 'modules', 'employees.role', 'Role', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1244, 1, 'en', 'modules', 'employees.lastDate', 'Last Date', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1245, 1, 'en', 'modules', 'employees.employeeId', 'Employee ID', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1246, 1, 'en', 'modules', 'projects.addNewProject', 'Add New Project', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1247, 1, 'en', 'modules', 'projects.projectMembers', 'Project Members', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1248, 1, 'en', 'modules', 'projects.startDate', 'Start Date', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1249, 1, 'en', 'modules', 'projects.deadline', 'Deadline', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1250, 1, 'en', 'modules', 'projects.projectSummary', 'Project Summary', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1251, 1, 'en', 'modules', 'projects.note', 'Note', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1252, 1, 'en', 'modules', 'projects.clientFeedback', 'Client Feedback', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1253, 1, 'en', 'modules', 'projects.projectCompletionStatus', 'Project Completion Status', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1254, 1, 'en', 'modules', 'projects.overview', 'Overview', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1255, 1, 'en', 'modules', 'projects.files', 'Files', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1256, 1, 'en', 'modules', 'projects.whoWorking', 'Who\'s Working', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1257, 1, 'en', 'modules', 'projects.activeSince', 'Active Since', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1258, 1, 'en', 'modules', 'projects.daysLeft', 'Days Left', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1259, 1, 'en', 'modules', 'projects.hoursLogged', 'Hours Logged', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1260, 1, 'en', 'modules', 'projects.issuesPending', 'Issues Pending', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1261, 1, 'en', 'modules', 'projects.activityTimeline', 'Activity Timeline', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1262, 1, 'en', 'modules', 'projects.noOpenTasks', 'No open tasks.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1263, 1, 'en', 'modules', 'projects.calculateTasksProgress', 'Calculate progress through tasks', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1264, 1, 'en', 'modules', 'projects.clientViewTask', 'Client can view tasks of this project', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1265, 1, 'en', 'modules', 'projects.manualTimelog', 'Allow manual time logs?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1266, 1, 'en', 'modules', 'projects.clientTaskNotification', 'Send task notification to client?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1267, 1, 'en', 'modules', 'projects.allProject', 'All Projects', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1268, 1, 'en', 'modules', 'projects.withoutDeadline', 'Without deadline', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1269, 1, 'en', 'modules', 'projects.projectExpenseInfo', 'Calculated from Expenses Module', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1270, 1, 'en', 'modules', 'projects.projectEarningInfo', 'Calculated from Payments Module', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1271, 1, 'en', 'modules', 'projects.selfAssignAsProjectMember', 'Self assign as project member', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1272, 1, 'en', 'modules', 'projects.milestoneCost', 'Milestone Cost', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1273, 1, 'en', 'modules', 'projects.milestoneSummary', 'Milestone Summary', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1274, 1, 'en', 'modules', 'projects.milestoneTitle', 'Milestone Title', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1275, 1, 'en', 'modules', 'projects.resume', 'Resume', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1276, 1, 'en', 'modules', 'projects.pause', 'Pause', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1277, 1, 'en', 'modules', 'projects.budgetInfo', 'Budget Info', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1278, 1, 'en', 'modules', 'projects.remindBefore', 'Remind before', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1279, 1, 'en', 'modules', 'projects.burndownChart', 'Burndown Chart', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1280, 1, 'en', 'modules', 'tasks.lastCreated', 'Last Created', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1281, 1, 'en', 'modules', 'tasks.dueSoon', 'Due Soon', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1282, 1, 'en', 'modules', 'tasks.taskBoard', 'Task Board', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1283, 1, 'en', 'modules', 'tasks.columnName', 'Column Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1284, 1, 'en', 'modules', 'tasks.labelColor', 'Label Color', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1285, 1, 'en', 'modules', 'tasks.tasksTable', 'Tasks Table', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1286, 1, 'en', 'modules', 'tasks.position', 'Position', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1287, 1, 'en', 'modules', 'tasks.taskCategory', 'Task Category', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1288, 1, 'en', 'modules', 'tasks.category', 'Category', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1289, 1, 'en', 'modules', 'tasks.cyclesToolTip', 'Recurring task will be created between start and due date for the below number of cycles.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1290, 1, 'en', 'modules', 'tasks.dependent', 'Task is dependent on another task', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1291, 1, 'en', 'modules', 'tasks.dependentTask', 'Dependent Task', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1292, 1, 'en', 'modules', 'tasks.preDeadlineReminder', 'Send task reminder before', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1293, 1, 'en', 'modules', 'tasks.onDeadlineReminder', 'Send task reminder on the day of deadline', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1294, 1, 'en', 'modules', 'tasks.postDeadlineReminder', 'Send task reminder after', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1295, 1, 'en', 'modules', 'tasks.makePrivate', 'Make Private', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1296, 1, 'en', 'modules', 'tasks.privateInfo', 'Private tasks are only visible to admin, assignor and assignee.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1297, 1, 'en', 'modules', 'tasks.createActivity', 'Task is created by', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1298, 1, 'en', 'modules', 'tasks.updateActivity', 'Task details are updated by ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1299, 1, 'en', 'modules', 'tasks.statusActivity', 'Task status changed by ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1300, 1, 'en', 'modules', 'tasks.commentActivity', 'Comment added by ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1301, 1, 'en', 'modules', 'tasks.fileActivity', 'File is uploaded by ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1302, 1, 'en', 'modules', 'tasks.subTaskCreateActivity', 'Sub task is created by ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1303, 1, 'en', 'modules', 'tasks.subTaskUpdateActivity', 'Sub task is updated by ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1304, 1, 'en', 'modules', 'tasks.defaultTaskStatus', 'Default Task Status', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1305, 1, 'en', 'modules', 'invoices.unpaid', 'Unpaid', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1306, 1, 'en', 'modules', 'invoices.currency', 'Currency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1307, 1, 'en', 'modules', 'invoices.subTotal', 'Sub Total', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1308, 1, 'en', 'modules', 'invoices.billedTo', 'Billed To', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1309, 1, 'en', 'modules', 'invoices.generatedBy', 'Generated By', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1310, 1, 'en', 'modules', 'invoices.price', 'Price', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1311, 1, 'en', 'modules', 'invoices.isRecurringPayment', 'Is it a recurring payments?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1312, 1, 'en', 'modules', 'invoices.billingCycle', 'Billing Cycle', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1313, 1, 'en', 'modules', 'invoices.billingFrequency', 'Billing Frequency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1314, 1, 'en', 'modules', 'invoices.billingInterval', 'Billing Interval', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1315, 1, 'en', 'modules', 'invoices.recurringPayments', 'Recurring Payment', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1316, 1, 'en', 'modules', 'invoices.taxName', 'Tax Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1317, 1, 'en', 'modules', 'invoices.rate', 'Rate', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1318, 1, 'en', 'modules', 'invoices.payOffline', 'Pay Offline', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1319, 1, 'en', 'modules', 'invoices.uploadInvoice', 'Upload Invoice', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1320, 1, 'en', 'modules', 'invoices.project', 'Project', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1321, 1, 'en', 'modules', 'invoices.review', 'Review', '2020-04-09 22:03:47', '2020-04-09 22:03:47');
INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1322, 1, 'en', 'modules', 'invoices.OfflinePaymentRequest', 'Offline Payment Request', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1323, 1, 'en', 'modules', 'invoices.paymentGateway', 'Payment Gateway', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1324, 1, 'en', 'modules', 'invoices.transactionID', 'Transaction ID', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1325, 1, 'en', 'modules', 'invoices.remark', 'Remark', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1326, 1, 'en', 'modules', 'invoices.paymentDetails', 'Payment Details', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1327, 1, 'en', 'modules', 'invoices.paidOn', 'Paid On', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1328, 1, 'en', 'modules', 'issues.reportedOn', 'Reported On', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1329, 1, 'en', 'modules', 'timeLogs.whoLogged', 'Who Logged', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1330, 1, 'en', 'modules', 'timeLogs.memo', 'Memo', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1331, 1, 'en', 'modules', 'timeLogs.lastUpdatedBy', 'Last updated by', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1332, 1, 'en', 'modules', 'timeLogs.employeeName', 'Employee Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1333, 1, 'en', 'modules', 'timeLogs.startDate', 'Start Date', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1334, 1, 'en', 'modules', 'timeLogs.endDate', 'End Date', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1335, 1, 'en', 'modules', 'timeLogs.task', 'Task', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1336, 1, 'en', 'modules', 'taskCalendar.taskDetail', 'Task Detail', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1337, 1, 'en', 'modules', 'notices.noticeHeading', 'Notice Heading', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1338, 1, 'en', 'modules', 'notices.noticeDetails', 'Notice Details', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1339, 1, 'en', 'modules', 'notices.toEmployee', 'To Employees', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1340, 1, 'en', 'modules', 'notices.toClients', 'To Clients', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1341, 1, 'en', 'modules', 'taskReport.chartTitle', 'Pie Chart', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1342, 1, 'en', 'modules', 'leaveReport.leaveReport', 'Leave Report', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1343, 1, 'en', 'modules', 'timeLogReport.chartTitle', 'Time Log Bar Chart', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1344, 1, 'en', 'modules', 'financeReport.showAmountIn', 'Show amount in ', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1345, 1, 'en', 'modules', 'financeReport.selectCurrency', 'Select Currency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1346, 1, 'en', 'modules', 'financeReport.chartTitle', 'Earnings Bar Chart', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1347, 1, 'en', 'modules', 'financeReport.financeReport', 'Earnings Bar Chart', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1348, 1, 'en', 'modules', 'accountSettings.companyEmail', 'Company Email', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1349, 1, 'en', 'modules', 'accountSettings.companyPhone', 'Company Phone', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1350, 1, 'en', 'modules', 'accountSettings.companyWebsite', 'Company Website', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1351, 1, 'en', 'modules', 'accountSettings.companyLogo', 'Logo', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1352, 1, 'en', 'modules', 'accountSettings.companyAddress', 'Company Address', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1353, 1, 'en', 'modules', 'accountSettings.defaultTimezone', 'Default Timezone', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1354, 1, 'en', 'modules', 'accountSettings.defaultCurrency', 'Default Currency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1355, 1, 'en', 'modules', 'accountSettings.changeLanguage', 'Change Language', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1356, 1, 'en', 'modules', 'accountSettings.getLocation', 'Set current location', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1357, 1, 'en', 'modules', 'accountSettings.dateFormat', 'Date Format', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1358, 1, 'en', 'modules', 'accountSettings.timeFormat', 'Time Format', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1359, 1, 'en', 'modules', 'accountSettings.google_map_key', 'Google map key', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1360, 1, 'en', 'modules', 'accountSettings.google_recaptcha_key', 'Google Recaptcha Key', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1361, 1, 'en', 'modules', 'accountSettings.sendReminder', 'Send Reminder', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1362, 1, 'en', 'modules', 'accountSettings.google_recaptcha_secret', 'Google Recaptcha Secret', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1363, 1, 'en', 'modules', 'accountSettings.weekStartFrom', 'Week start from', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1364, 1, 'en', 'modules', 'accountSettings.updateEnableDisable', 'App Update', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1365, 1, 'en', 'modules', 'accountSettings.frontLogo', 'Front Logo', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1366, 1, 'en', 'modules', 'profile.yourPassword', 'Your Password', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1367, 1, 'en', 'modules', 'profile.yourMobileNumber', 'Your Mobile Number', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1368, 1, 'en', 'modules', 'profile.yourAddress', 'Your Address', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1369, 1, 'en', 'modules', 'profile.profilePicture', 'Profile Picture', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1370, 1, 'en', 'modules', 'emailSettings.notificationTitle', 'Set Email Notification Settings', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1371, 1, 'en', 'modules', 'emailSettings.configTitle', 'Mail Configuration', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1372, 1, 'en', 'modules', 'emailSettings.mailDriver', 'Mail Driver', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1373, 1, 'en', 'modules', 'emailSettings.mailHost', 'Mail Host', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1374, 1, 'en', 'modules', 'emailSettings.mailPort', 'Mail Port', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1375, 1, 'en', 'modules', 'emailSettings.mailUsername', 'Mail Username', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1376, 1, 'en', 'modules', 'emailSettings.mailPassword', 'Mail Password', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1377, 1, 'en', 'modules', 'emailSettings.mailFrom', 'Mail From Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1378, 1, 'en', 'modules', 'emailSettings.mailEncryption', 'Mail Encryption', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1379, 1, 'en', 'modules', 'emailSettings.userRegistration', 'User Registration/Added by Admin', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1380, 1, 'en', 'modules', 'emailSettings.employeeAssign', 'Employee Assign to Project', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1381, 1, 'en', 'modules', 'emailSettings.newNotice', 'New Notice Published', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1382, 1, 'en', 'modules', 'emailSettings.taskAssign', 'User Assign to Task', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1383, 1, 'en', 'modules', 'emailSettings.expenseAdded', 'New Expense (Added by Admin)', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1384, 1, 'en', 'modules', 'emailSettings.expenseMember', 'New Expense (Added by Member)', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1385, 1, 'en', 'modules', 'emailSettings.expenseStatus', 'Expense Status Changed', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1386, 1, 'en', 'modules', 'emailSettings.ticketRequest', 'New Support Ticket Request', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1387, 1, 'en', 'modules', 'emailSettings.mailFromEmail', 'Mail From Email', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1388, 1, 'en', 'modules', 'emailSettings.leaveRequest', 'Leave Request Received', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1389, 1, 'en', 'modules', 'emailSettings.taskComplete', 'Task completed', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1390, 1, 'en', 'modules', 'emailSettings.sendTestEmail', 'Send Test Email', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1391, 1, 'en', 'modules', 'emailSettings.removeImage', 'Remove Image', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1392, 1, 'en', 'modules', 'emailSettings.invoiceNotification', 'Invoice  Notification', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1393, 1, 'en', 'modules', 'moduleSettings.employeeModuleTitle', 'Modules', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1394, 1, 'en', 'modules', 'moduleSettings.clientModuleTitle', 'Client Module Title', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1395, 1, 'en', 'modules', 'moduleSettings.clientSubTitle', 'Select the modules which client can access.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1396, 1, 'en', 'modules', 'currencySettings.addNewCurrency', 'Add New Currency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1397, 1, 'en', 'modules', 'currencySettings.currencyName', 'Currency Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1398, 1, 'en', 'modules', 'currencySettings.currencySymbol', 'Currency Symbol', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1399, 1, 'en', 'modules', 'currencySettings.currencyCode', 'Currency Code', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1400, 1, 'en', 'modules', 'currencySettings.currencies', 'Currencies', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1401, 1, 'en', 'modules', 'currencySettings.updateTitle', 'Update Currency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1402, 1, 'en', 'modules', 'currencySettings.isCryptoCurrency', 'Is Cryptocurrency', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1403, 1, 'en', 'modules', 'currencySettings.usdPrice', 'Usd Price', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1404, 1, 'en', 'modules', 'currencySettings.usdPriceInfo', 'Required to calculate earnings.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1405, 1, 'en', 'modules', 'messages.allowClientEmployeeChat', 'Allow chat between client and employees?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1406, 1, 'en', 'modules', 'messages.allowClientAdminChat', 'Allow chat between client and admin?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1407, 1, 'en', 'modules', 'messages.admins', 'Administrators', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1408, 1, 'en', 'modules', 'messages.members', 'Project Members', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1409, 1, 'en', 'modules', 'projectCategory.categoryName', 'Category Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1410, 1, 'en', 'modules', 'themeSettings.adminPanelTheme', 'Admin Panel Theme', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1411, 1, 'en', 'modules', 'themeSettings.projectAdminPanelTheme', 'Project Admin Panel Theme', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1412, 1, 'en', 'modules', 'themeSettings.employeePanelTheme', 'Employee Panel Theme', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1413, 1, 'en', 'modules', 'themeSettings.clientPanelTheme', 'Client Panel Theme', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1414, 1, 'en', 'modules', 'themeSettings.headerColor', 'Secondary Color', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1415, 1, 'en', 'modules', 'themeSettings.sidebarColor', 'Sidebar Color', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1416, 1, 'en', 'modules', 'themeSettings.sidebarTextColor', 'Sidebar Text Color', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1417, 1, 'en', 'modules', 'themeSettings.linkColor', 'Link Color', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1418, 1, 'en', 'modules', 'themeSettings.loginScreenBackground', 'Login Screen Background', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1419, 1, 'en', 'modules', 'themeSettings.uploadImage', 'Upload Image', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1420, 1, 'en', 'modules', 'themeSettings.useDefaultTheme', 'Use Default Theme', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1421, 1, 'en', 'modules', 'themeSettings.useCustomTheme', 'Use Custom Theme', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1422, 1, 'en', 'modules', 'themeSettings.enableRoundTheme', 'Enable rounded theme?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1423, 1, 'en', 'modules', 'themeSettings.publicCss', 'public/css/', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1424, 1, 'en', 'modules', 'estimates.waiting', 'Waiting', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1425, 1, 'en', 'modules', 'estimates.accepted', 'Accepted', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1426, 1, 'en', 'modules', 'estimates.declined', 'Declined', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1427, 1, 'en', 'modules', 'estimates.estimatesNumber', 'Estimate #', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1428, 1, 'en', 'modules', 'estimates.firstName', 'First Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1429, 1, 'en', 'modules', 'estimates.lastName', 'Last Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1430, 1, 'en', 'modules', 'estimates.signature', 'Signature', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1431, 1, 'en', 'modules', 'estimates.undo', 'Undo', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1432, 1, 'en', 'modules', 'estimates.clear', 'Clear', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1433, 1, 'en', 'modules', 'estimates.signatureAndConfirmation', 'Signature & Confirmation Of Identity', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1434, 1, 'en', 'modules', 'payments.selectInvoice', 'Select Invoice', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1435, 1, 'en', 'modules', 'payments.paymentGateway', 'Payment Gateway', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1436, 1, 'en', 'modules', 'payments.transactionId', 'Transaction Id', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1437, 1, 'en', 'modules', 'payments.paypalStatus', 'Paypal Status', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1438, 1, 'en', 'modules', 'payments.markInvoicePaid', 'Mark Invoice Paid?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1439, 1, 'en', 'modules', 'payments.stripeStatus', 'Stripe Status', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1440, 1, 'en', 'modules', 'payments.firstCharacter', 'First Character is Currency?', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1441, 1, 'en', 'modules', 'payments.remark', 'Remark', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1442, 1, 'en', 'modules', 'payments.amount', 'Amount', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1443, 1, 'en', 'modules', 'payments.paymentDetailNotFound', 'Payment details not found.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1444, 1, 'en', 'modules', 'payments.razorpayStatus', 'Razorpay Status', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1445, 1, 'en', 'modules', 'invoiceSettings.invoicePrefix', 'Invoice Prefix', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1446, 1, 'en', 'modules', 'invoiceSettings.template', 'Template', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1447, 1, 'en', 'modules', 'invoiceSettings.dueAfter', 'Due after', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1448, 1, 'en', 'modules', 'invoiceSettings.invoiceTerms', 'Invoice terms', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1449, 1, 'en', 'modules', 'invoiceSettings.updateTitle', 'Update Finance Settings', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1450, 1, 'en', 'modules', 'invoiceSettings.invoiceDigit', 'Invoice Number Digits', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1451, 1, 'en', 'modules', 'invoiceSettings.invoiceLookLike', 'Invoice Number Sample', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1452, 1, 'en', 'modules', 'invoiceSettings.estimatePrefix', 'Estimate Prefix', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1453, 1, 'en', 'modules', 'invoiceSettings.estimateDigit', 'Estimate Number Digits', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1454, 1, 'en', 'modules', 'invoiceSettings.estimateLookLike', 'Estimate Number Sample', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1455, 1, 'en', 'modules', 'invoiceSettings.credit_notePrefix', 'Credit Note Prefix', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1456, 1, 'en', 'modules', 'invoiceSettings.credit_noteDigit', 'Credit Note Digits', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1457, 1, 'en', 'modules', 'invoiceSettings.credit_noteLookLike', 'Credit Note Number Sample', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1458, 1, 'en', 'modules', 'slackSettings.updateTitle', 'Update Slack Settings', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1459, 1, 'en', 'modules', 'slackSettings.notificationTitle', 'Set Notification Settings', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1460, 1, 'en', 'modules', 'slackSettings.sendTestNotification', 'Send Test Notification', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1461, 1, 'en', 'modules', 'slackSettings.slackWebhook', 'Slack Webhook', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1462, 1, 'en', 'modules', 'slackSettings.slackNotificationLogo', 'Notification Logo', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1463, 1, 'en', 'modules', 'update.systemDetails', 'System Details', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1464, 1, 'en', 'modules', 'update.updateTitle', 'Update To New Version', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1465, 1, 'en', 'modules', 'update.updateDatabase', 'Update Database', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1466, 1, 'en', 'modules', 'update.fileReplaceAlert', 'To update the worksuite to new version check documentation for the instructions.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1467, 1, 'en', 'modules', 'update.updateDatabaseButton', 'Click to update database', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1468, 1, 'en', 'modules', 'update.updateNow', 'Update Now', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1469, 1, 'en', 'modules', 'update.updateAlternate', 'If the Update Now button does not work then follow the update instructions as mentioned in the documentation.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1470, 1, 'en', 'modules', 'update.updateManual', 'Update Manually', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1471, 1, 'en', 'modules', 'update.updateFiles', 'Update Files', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1472, 1, 'en', 'modules', 'update.downloadUpdateFile', 'Download Update File', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1473, 1, 'en', 'modules', 'update.install', 'Install', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1474, 1, 'en', 'modules', 'update.moduleFile', 'Module zip file', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1475, 1, 'en', 'modules', 'incomeVsExpenseReport.totalIncome', 'Total Income', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1476, 1, 'en', 'modules', 'incomeVsExpenseReport.chartTitle', 'Bar Chart', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1477, 1, 'en', 'modules', 'tickets.assignGroup', 'Assign Group', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1478, 1, 'en', 'modules', 'tickets.addGroup', 'Add group', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1479, 1, 'en', 'modules', 'tickets.group', 'Group', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1480, 1, 'en', 'modules', 'tickets.channelName', 'Channel Name', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1481, 1, 'en', 'modules', 'tickets.templateHeading', 'Template Heading', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1482, 1, 'en', 'modules', 'tickets.templateText', 'Template Text', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1483, 1, 'en', 'modules', 'tickets.addTicket', 'Create Ticket', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1484, 1, 'en', 'modules', 'tickets.ticketDescription', 'Ticket Description', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1485, 1, 'en', 'modules', 'tickets.tags', 'Tags', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1486, 1, 'en', 'modules', 'tickets.reply', 'Reply', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1487, 1, 'en', 'modules', 'tickets.requestTicket', 'Request Support Ticket', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1488, 1, 'en', 'modules', 'tickets.goToAgentDashboard', 'Go To Agent Dashboard', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1489, 1, 'en', 'modules', 'tickets.totalTicketInfo', 'No. of new tickets which were created for the selected date range.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1490, 1, 'en', 'modules', 'tickets.closedTicketInfo', 'No. of tickets which were closed in the selected date range.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1491, 1, 'en', 'modules', 'tickets.openTicketInfo', 'No. of tickets which are not yet assigned to any agent and updated in the selected date range.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1492, 1, 'en', 'modules', 'tickets.pendingTicketInfo', 'No. of tickets which were updated in the selected date range and are assigned to an agent.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1493, 1, 'en', 'modules', 'tickets.resolvedTicketInfo', 'No. of tickets which were resolved in the selected date range but waiting for requester confirmation.', '2020-04-09 22:03:47', '2020-04-09 22:03:47'),
(1494, 1, 'en', 'modules', 'tickets.inProcessProjects', 'In Process Projects', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1495, 1, 'en', 'modules', 'tickets.urgent', 'Urgent', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1496, 1, 'en', 'modules', 'tickets.nofilter', 'No filter', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1497, 1, 'en', 'modules', 'tickets.noGroupAssigned', 'No group assigned', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1498, 1, 'en', 'modules', 'attendance.officeStartTime', 'Office Start Time', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1499, 1, 'en', 'modules', 'attendance.officeEndTime', 'Office End Time', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1500, 1, 'en', 'modules', 'attendance.halfDayMarkTime', 'HalfDay Mark Time', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1501, 1, 'en', 'modules', 'attendance.lateMark', 'Late mark after (minutes)', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1502, 1, 'en', 'modules', 'attendance.allowSelfClock', 'Allowed Employee self Clock-In/Clock-Out', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1503, 1, 'en', 'modules', 'attendance.markAttendance', 'Mark Attendance', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1504, 1, 'en', 'modules', 'attendance.clock_in', 'Clock In', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1505, 1, 'en', 'modules', 'attendance.clock_out', 'Clock Out', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1506, 1, 'en', 'modules', 'attendance.working_from', 'Working From', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1507, 1, 'en', 'modules', 'attendance.officeOpenDays', 'Office opens on', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1508, 1, 'en', 'modules', 'attendance.currentTime', 'Current Time', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1509, 1, 'en', 'modules', 'attendance.attendanceByDate', 'Attendance By Date', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1510, 1, 'en', 'modules', 'attendance.attendanceByMember', 'Attendance by Member', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1511, 1, 'en', 'modules', 'attendance.holiday', 'Holiday', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1512, 1, 'en', 'modules', 'attendance.checkininday', 'Maximum check-in allowed in a day?', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1513, 1, 'en', 'modules', 'attendance.maxColckIn', 'Maximum check-ins reached.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1514, 1, 'en', 'modules', 'attendance.yes', 'Yes', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1515, 1, 'en', 'modules', 'attendance.no', 'No', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1516, 1, 'en', 'modules', 'attendance.leave', 'On Leave', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1517, 1, 'en', 'modules', 'attendance.checkForIp', 'Clock-in check with added IP address', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1518, 1, 'en', 'modules', 'attendance.checkForRadius', 'Clock-in check with added location Radius', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1519, 1, 'en', 'modules', 'attendance.radius', 'Radius (in meter)', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1520, 1, 'en', 'modules', 'customFields.label', 'Label', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1521, 1, 'en', 'modules', 'events.eventName', 'Event Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1522, 1, 'en', 'modules', 'events.startOn', 'Starts On', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1523, 1, 'en', 'modules', 'events.endOn', 'Ends On', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1524, 1, 'en', 'modules', 'events.addAttendees', 'Add Attendees', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1525, 1, 'en', 'modules', 'events.allEmployees', 'All Employees', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1526, 1, 'en', 'modules', 'events.where', 'Where', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1527, 1, 'en', 'modules', 'events.repeat', 'Repeat', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1528, 1, 'en', 'modules', 'events.repeatEvery', 'Repeat every', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1529, 1, 'en', 'modules', 'events.cycles', 'Cycles', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1530, 1, 'en', 'modules', 'events.cyclesToolTip', 'Recurring will be stopped after the number of cycles. Keep it blank for infinity.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1531, 1, 'en', 'modules', 'payroll.addPayroll', 'Add Payroll', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1532, 1, 'en', 'modules', 'payroll.amountPaid', 'Amount Paid', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1533, 1, 'en', 'modules', 'payroll.updatePayroll', 'Update Payroll', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1534, 1, 'en', 'modules', 'payroll.projectPayrollReport', 'Project Payroll Report', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1535, 1, 'en', 'modules', 'payroll.totalEarning', 'Total Earning', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1536, 1, 'en', 'modules', 'permission.selectAll', 'Select All', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1537, 1, 'en', 'modules', 'permission.addRoleMember', 'Manage Role Members', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1538, 1, 'en', 'modules', 'permission.addMembers', 'Add Members', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1539, 1, 'en', 'modules', 'permission.roleName', 'Role Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1540, 1, 'en', 'modules', 'leaves.selectDuration', 'Select Duration', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1541, 1, 'en', 'modules', 'leaves.single', 'Single', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1542, 1, 'en', 'modules', 'leaves.multiple', 'Multiple', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1543, 1, 'en', 'modules', 'leaves.hours', 'Hours', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1544, 1, 'en', 'modules', 'leaves.selectDates', 'Select Dates', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1545, 1, 'en', 'modules', 'leaves.applicantName', 'Applicant Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1546, 1, 'en', 'modules', 'leaves.updateLeave', 'Update Leave', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1547, 1, 'en', 'modules', 'leaves.noOfLeaves', 'No of Leaves', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1548, 1, 'en', 'modules', 'leaves.countLeavesFromDateOfJoining', 'Count leaves from the date of joining', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1549, 1, 'en', 'modules', 'leaves.countLeavesFromStartOfYear', 'Count leaves from the start of the year', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1550, 1, 'en', 'modules', 'leaves.leavesTaken', 'Leaves Taken', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1551, 1, 'en', 'modules', 'leaves.myLeaves', 'My Leaves', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1552, 1, 'en', 'modules', 'low', 'Low', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1553, 1, 'en', 'modules', 'lead.companyDetails', 'Company Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1554, 1, 'en', 'modules', 'lead.website', 'Website', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1555, 1, 'en', 'modules', 'lead.address', 'Address', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1556, 1, 'en', 'modules', 'lead.leadDetails', 'Lead Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1557, 1, 'en', 'modules', 'lead.clientName', 'Client Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1558, 1, 'en', 'modules', 'lead.clientEmail', 'Client Email', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1559, 1, 'en', 'modules', 'lead.emailNote', 'Lead will login using this email.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1560, 1, 'en', 'modules', 'lead.password', 'Password', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1561, 1, 'en', 'modules', 'lead.passwordNote', 'Client will login using this password.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1562, 1, 'en', 'modules', 'lead.passwordUpdateNote', 'Client will login using this password. (Leave blank to keep current password)', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1563, 1, 'en', 'modules', 'lead.mobile', 'Mobile', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1564, 1, 'en', 'modules', 'lead.addNewLead', 'Add New Lead', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1565, 1, 'en', 'modules', 'lead.viewDetails', 'View Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1566, 1, 'en', 'modules', 'lead.remark', 'Remark', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1567, 1, 'en', 'modules', 'lead.proposal', 'Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1568, 1, 'en', 'modules', 'lead.profile', 'Profile', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1569, 1, 'en', 'modules', 'lead.followUp', 'Follow Up', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1570, 1, 'en', 'modules', 'lead.note', 'Note', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1571, 1, 'en', 'modules', 'lead.email', 'Email', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1572, 1, 'en', 'modules', 'lead.source', 'Source', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1573, 1, 'en', 'modules', 'lead.status', 'Status', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1574, 1, 'en', 'modules', 'lead.leadDetail', 'Lead Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1575, 1, 'en', 'modules', 'lead.all', 'All', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1576, 1, 'en', 'modules', 'lead.lead', 'Lead', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1577, 1, 'en', 'modules', 'lead.client', 'Client', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1578, 1, 'en', 'modules', 'lead.pending', 'Pending', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1579, 1, 'en', 'modules', 'lead.file', 'Files', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1580, 1, 'en', 'modules', 'proposal.updateTitle', 'Update Proposal Info', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1581, 1, 'en', 'modules', 'proposal.addNewLead', 'Add New Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1582, 1, 'en', 'modules', 'proposal.viewDetails', 'View Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1583, 1, 'en', 'modules', 'proposal.title', ' Lead Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1584, 1, 'en', 'modules', 'proposal.proposal', ' Lead Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1585, 1, 'en', 'modules', 'proposal.createProposal', 'Create Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1586, 1, 'en', 'modules', 'proposal.validTill', 'Valid Till', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1587, 1, 'en', 'modules', 'proposal.waiting', 'Waiting', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1588, 1, 'en', 'modules', 'proposal.accepted', 'Accepted', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1589, 1, 'en', 'modules', 'proposal.declined', 'Declined', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1590, 1, 'en', 'modules', 'proposal.view', 'View', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1591, 1, 'en', 'modules', 'followup.addNewLead', 'Add New Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1592, 1, 'en', 'modules', 'followup.viewDetails', 'View Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1593, 1, 'en', 'modules', 'followup.title', ' Lead Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1594, 1, 'en', 'modules', 'followup.proposal', ' Lead Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1595, 1, 'en', 'modules', 'followup.createProposal', 'Create Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1596, 1, 'en', 'modules', 'followup.validTill', 'Valid Till', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1597, 1, 'en', 'modules', 'followup.waiting', 'Waiting', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1598, 1, 'en', 'modules', 'followup.accepted', 'Accepted', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1599, 1, 'en', 'modules', 'followup.declined', 'Declined', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1600, 1, 'en', 'modules', 'followup.updateProposal', 'Update Proposal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1601, 1, 'en', 'modules', 'followup.convertProposalTitle', 'Convert Proposal To Invoice', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1602, 1, 'en', 'modules', 'followup.followUpNotFound', 'No follow up found', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1603, 1, 'en', 'modules', 'holiday.addNewHoliday', 'Add Holiday', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1604, 1, 'en', 'modules', 'holiday.viewDetails', 'View Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1605, 1, 'en', 'modules', 'holiday.createHoliday', 'Create Holiday', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1606, 1, 'en', 'modules', 'holiday.followUpNotFound', 'No Holiday found', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1607, 1, 'en', 'modules', 'holiday.viewOnCalendar', 'View on Calendar', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1608, 1, 'en', 'modules', 'holiday.officeHolidayMarkDays', 'Mark day for Holiday', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1609, 1, 'en', 'modules', 'offlinePayment.description', 'Description', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1610, 1, 'en', 'modules', 'offlinePayment.addMethod', 'Add Method', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1611, 1, 'en', 'modules', 'projectTemplate.addNewTemplate', 'Add New Template', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1612, 1, 'en', 'modules', 'projectTemplate.projectName', 'Template Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1613, 1, 'en', 'modules', 'projectTemplate.projectMembers', 'Template Members', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1614, 1, 'en', 'modules', 'projectTemplate.selectClient', 'Select Client', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1615, 1, 'en', 'modules', 'projectTemplate.startDate', 'Start Date', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1616, 1, 'en', 'modules', 'projectTemplate.deadline', 'Deadline', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1617, 1, 'en', 'modules', 'projectTemplate.projectSummary', 'Template Summary', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1618, 1, 'en', 'modules', 'projectTemplate.note', 'Note', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1619, 1, 'en', 'modules', 'projectTemplate.projectCategory', 'Template Category', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1620, 1, 'en', 'modules', 'projectTemplate.clientFeedback', 'Client Feedback', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1621, 1, 'en', 'modules', 'projectTemplate.projectCompletionStatus', 'Template Completion Status', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1622, 1, 'en', 'modules', 'projectTemplate.overview', 'Overview', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1623, 1, 'en', 'modules', 'projectTemplate.members', 'Members', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1624, 1, 'en', 'modules', 'projectTemplate.files', 'Files', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1625, 1, 'en', 'modules', 'projectTemplate.activeTimers', 'Active Timers', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1626, 1, 'en', 'modules', 'projectTemplate.whoWorking', 'Who\'s Working', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1627, 1, 'en', 'modules', 'projectTemplate.activeSince', 'Active Since', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1628, 1, 'en', 'modules', 'projectTemplate.openTasks', 'Open Tasks', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1629, 1, 'en', 'modules', 'projectTemplate.daysLeft', 'Days Left', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1630, 1, 'en', 'modules', 'projectTemplate.hoursLogged', 'Hours Logged', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1631, 1, 'en', 'modules', 'projectTemplate.issuesPending', 'Issues Pending', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1632, 1, 'en', 'modules', 'projectTemplate.activityTimeline', 'Activity Timeline', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1633, 1, 'en', 'modules', 'projectTemplate.uploadFile', 'Upload File', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1634, 1, 'en', 'modules', 'projectTemplate.dropFile', 'Drop files here OR click to upload', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1635, 1, 'en', 'modules', 'projectTemplate.noOpenTasks', 'No open tasks.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1636, 1, 'en', 'modules', 'projectTemplate.calculateTasksProgress', 'Calculate progress through tasks', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1637, 1, 'en', 'modules', 'projectTemplate.viewGanttChart', 'Gantt Chart', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1638, 1, 'en', 'modules', 'projectTemplate.clientViewTask', 'Client can view tasks of this project', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1639, 1, 'en', 'modules', 'projectTemplate.clientTaskNotification', 'Send task notification to client?', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1640, 1, 'en', 'modules', 'projectTemplate.manualTimelog', 'Allow manual time logs?', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1641, 1, 'en', 'modules', 'templateTasks.lastCreated', 'Last Created', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1642, 1, 'en', 'modules', 'templateTasks.dueSoon', 'Due Soon', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1643, 1, 'en', 'modules', 'templateTasks.assignTo', 'Assigned To', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1644, 1, 'en', 'modules', 'templateTasks.priority', 'Priority', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1645, 1, 'en', 'modules', 'templateTasks.chooseAssignee', 'Choose Assignee', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1646, 1, 'en', 'modules', 'templateTasks.taskDetail', 'Template Task Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1647, 1, 'en', 'modules', 'templateTasks.taskBoard', 'Template Task Board', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1648, 1, 'en', 'modules', 'templateTasks.addBoardColumn', 'Add Column', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1649, 1, 'en', 'modules', 'templateTasks.columnName', 'Column Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1650, 1, 'en', 'modules', 'templateTasks.labelColor', 'Label Color', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1651, 1, 'en', 'modules', 'templateTasks.tasksTable', 'Tasks Table', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1652, 1, 'en', 'modules', 'templateTasks.position', 'Position', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1653, 1, 'en', 'modules', 'templateTasks.subTask', 'Sub Template Task', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1654, 1, 'en', 'modules', 'templateTasks.comment', 'Comment', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1655, 1, 'en', 'modules', 'logTimeSetting.title', 'Log Time Setting', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1656, 1, 'en', 'modules', 'logTimeSetting.project', 'Project', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1657, 1, 'en', 'modules', 'logTimeSetting.task', 'Task', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1658, 1, 'en', 'modules', 'logTimeSetting.autoStopTimerAfterOfficeTime', 'Stop timer automatically after office time.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1659, 1, 'en', 'modules', 'taskCategory.categoryName', 'Category Name', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1660, 1, 'en', 'modules', 'taskCategory.taskCategory', 'Task Category', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1661, 1, 'en', 'modules', 'pushSettings.updateTitle', 'Update Push Notification Settings', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1662, 1, 'en', 'modules', 'pushSettings.oneSignalAppId', 'One Signal App ID', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1663, 1, 'en', 'modules', 'pushSettings.oneSignalRestApiKey', 'One Signal Rest API Key', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1664, 1, 'en', 'modules', 'stripeSettings.title', 'Stripe Settings', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1665, 1, 'en', 'modules', 'stripeSettings.apiKey', 'Api Key', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1666, 1, 'en', 'modules', 'stripeSettings.apiSecret', 'Api Secret', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1667, 1, 'en', 'modules', 'stripeSettings.webhookKey', 'Webhook Key Secret', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1668, 1, 'en', 'modules', 'stripeSettings.subtitle', 'Stripe credentials', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1669, 1, 'en', 'modules', 'frontCms.updateTitle', 'Update Front Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1670, 1, 'en', 'modules', 'frontCms.headerTitle', 'Header Title', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1671, 1, 'en', 'modules', 'frontCms.frontDetail', 'Front Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1672, 1, 'en', 'modules', 'frontCms.featureDetail', 'Feature Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1673, 1, 'en', 'modules', 'frontCms.headerDescription', 'Header Description', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1674, 1, 'en', 'modules', 'frontCms.mainImage', 'Header Image', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1675, 1, 'en', 'modules', 'frontCms.featureTitle', 'Feature Title', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1676, 1, 'en', 'modules', 'frontCms.featureDescription', 'Feature Description', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1677, 1, 'en', 'modules', 'frontCms.priceDetail', 'Price Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1678, 1, 'en', 'modules', 'frontCms.priceTitle', 'Price Title', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1679, 1, 'en', 'modules', 'frontCms.priceDescription', 'Price Description', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1680, 1, 'en', 'modules', 'frontCms.contactDetail', 'Contact Detail', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1681, 1, 'en', 'modules', 'frontCms.getStartedButtonShow', 'Show get started button', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1682, 1, 'en', 'modules', 'frontCms.singInButtonShow', 'Show sign-in button', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1683, 1, 'en', 'modules', 'frontCms.perYear', 'Per Year', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1684, 1, 'en', 'modules', 'frontCms.perMonth', 'Per Month', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1685, 1, 'en', 'modules', 'frontCms.getStarted', 'Get Started', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1686, 1, 'en', 'modules', 'frontCms.submitEnquiry', 'Submit Enquiry', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1687, 1, 'en', 'modules', 'frontCms.socialLinks', 'Social Links', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1688, 1, 'en', 'modules', 'frontCms.facebook', 'Facebook', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1689, 1, 'en', 'modules', 'frontCms.twitter', 'Twitter', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1690, 1, 'en', 'modules', 'frontCms.instagram', 'Instagram', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1691, 1, 'en', 'modules', 'frontCms.dribbble', 'Dribbble', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1692, 1, 'en', 'modules', 'frontCms.enterFacebookLink', 'Enter Facebook Link', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1693, 1, 'en', 'modules', 'frontCms.enterTwitterLink', 'Enter Twitter Link', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1694, 1, 'en', 'modules', 'frontCms.enterInstagramLink', 'Enter Instagram Link', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1695, 1, 'en', 'modules', 'frontCms.enterDribbbleLink', 'Enter Dribbble Link', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1696, 1, 'en', 'modules', 'frontCms.enterSocialLinks', 'Please enter social links in', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1697, 1, 'en', 'modules', 'frontCms.socialLinksNote', 'Note: Leave input blank to hide it on Home Page.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1698, 1, 'en', 'modules', 'frontCms.primaryColor', 'Primary Color', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1699, 1, 'en', 'modules', 'featureSetting.addFeature', 'Add Feature', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1700, 1, 'en', 'modules', 'featureSetting.editFeature', 'Edit Feature', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1701, 1, 'en', 'modules', 'feature.setting', 'Feature Setting', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1702, 1, 'en', 'modules', 'module.attendance', 'Attendance', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1703, 1, 'en', 'modules', 'module.clients', 'Clients', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1704, 1, 'en', 'modules', 'module.employees', 'Employees', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1705, 1, 'en', 'modules', 'module.estimates', 'Estimates', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1706, 1, 'en', 'modules', 'module.events', 'Events', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1707, 1, 'en', 'modules', 'module.expenses', 'Expenses', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1708, 1, 'en', 'modules', 'module.holidays', 'Holidays', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1709, 1, 'en', 'modules', 'module.invoices', 'Invoices', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1710, 1, 'en', 'modules', 'module.leads', 'Leads', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1711, 1, 'en', 'modules', 'module.leaves', 'Leaves', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1712, 1, 'en', 'modules', 'module.messages', 'Messages', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1713, 1, 'en', 'modules', 'module.notice board', 'Notice Board', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1714, 1, 'en', 'modules', 'module.noticeBoard', 'Notice Board', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1715, 1, 'en', 'modules', 'module.notices', 'Notices', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1716, 1, 'en', 'modules', 'module.payments', 'Payments', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1717, 1, 'en', 'modules', 'module.products', 'Products', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1718, 1, 'en', 'modules', 'module.projects', 'Projects', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1719, 1, 'en', 'modules', 'module.tasks', 'Tasks', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1720, 1, 'en', 'modules', 'module.tickets', 'Tickets', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1721, 1, 'en', 'modules', 'module.timelogs', 'Time Logs', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1722, 1, 'en', 'modules', 'module.creditNotes', 'Credit Note', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1723, 1, 'en', 'modules', 'module.contracts', 'Contracts', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1724, 1, 'en', 'modules', 'module.reports', 'Reports', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1725, 1, 'en', 'modules', 'paymentSetting.paypal', 'Paypal', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1726, 1, 'en', 'modules', 'paymentSetting.stripe', 'Stripe', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1727, 1, 'en', 'modules', 'paymentSetting.paypalClientId', 'Paypal Client Id', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1728, 1, 'en', 'modules', 'paymentSetting.paypalSecret', 'Paypal Secret', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1729, 1, 'en', 'modules', 'paymentSetting.stripeClientId', 'Publishable Key', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1730, 1, 'en', 'modules', 'paymentSetting.stripeSecret', 'Stripe Secret', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1731, 1, 'en', 'modules', 'paymentSetting.stripeWebhookSecret', 'Stripe Webhook Secret', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1732, 1, 'en', 'modules', 'paymentSetting.razorpay', 'Razorpay', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1733, 1, 'en', 'modules', 'packageSetting.noOfDays', 'Number Of Days', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1734, 1, 'en', 'modules', 'packageSetting.notificationBeforeDays', 'Notification Before Days', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1735, 1, 'en', 'modules', 'package.stripeAnnualPlanId', 'Stripe Annual Plan ID', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1736, 1, 'en', 'modules', 'package.stripeMonthlyPlanId', 'Stripe Monthly Plan ID', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1737, 1, 'en', 'modules', 'package.razorpayAnnualPlanId', 'Razorpay Annual Plan ID', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1738, 1, 'en', 'modules', 'package.razorpayMonthlyPlanId', 'Razorpay Monthly Plan ID', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1739, 1, 'en', 'modules', 'credit-notes.currency', 'Currency', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1740, 1, 'en', 'modules', 'credit-notes.isRecurringPayment', 'Is it a recurring payments?', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1741, 1, 'en', 'modules', 'credit-notes.subTotal', 'Sub Total', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1742, 1, 'en', 'modules', 'credit-notes.paid', 'Paid', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1743, 1, 'en', 'modules', 'credit-notes.unpaid', 'Unpaid', '2020-04-09 22:03:48', '2020-04-09 22:03:48');
INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1744, 1, 'en', 'modules', 'credit-notes.partial', 'Partial', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1745, 1, 'en', 'modules', 'credit-notes.billingFrequency', 'Billing Frequency', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1746, 1, 'en', 'modules', 'credit-notes.billingInterval', 'Billing Interval', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1747, 1, 'en', 'modules', 'credit-notes.billingCycle', 'Billing Cycle', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1748, 1, 'en', 'modules', 'credit-notes.billedTo', 'Billed To', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1749, 1, 'en', 'modules', 'credit-notes.generatedBy', 'Generated By', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1750, 1, 'en', 'modules', 'credit-notes.price', 'Price', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1751, 1, 'en', 'modules', 'credit-notes.noInvoicesFound', 'There are no available invoices for this project.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1752, 1, 'en', 'modules', 'credit-notes.closed', 'Closed', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1753, 1, 'en', 'modules', 'credit-notes.open', 'Open', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1754, 1, 'en', 'modules', 'superadmin.details', 'Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1755, 1, 'en', 'modules', 'superadmin.recentRegisteredCompanies', 'Recent Registered Companies', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1756, 1, 'en', 'modules', 'superadmin.recentSubscriptions', 'Recent Subscriptions', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1757, 1, 'en', 'modules', 'superadmin.recentLicenseExpiredCompanies', 'Recent Licence Expired Companies', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1758, 1, 'en', 'modules', 'billing.yourCurrentPlan', 'Your Current Plan', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1759, 1, 'en', 'modules', 'billing.unsubscribe', 'Unsubscribe', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1760, 1, 'en', 'modules', 'billing.changePlan', 'Change Plan', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1761, 1, 'en', 'modules', 'billing.choosePlan', 'Choose Plan', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1762, 1, 'en', 'modules', 'company.accountSetup', 'Account Details', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1763, 1, 'en', 'modules', 'projectSettings.sendNotificationsTo', 'Send Reminder To', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1764, 1, 'en', 'modules', 'footer.setting', 'Footer Menu Setting', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1765, 1, 'en', 'modules', 'footer.addFooter', 'Add Footer Menu', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1766, 1, 'en', 'modules', 'footer.editFooter', 'Edit Footer Menu', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1767, 1, 'en', 'modules', 'footer.footerCopyrightText', 'Footer Copyright Text', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1768, 1, 'en', 'modules', 'leave.leaveRequest', 'Leave Request Received', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1769, 1, 'en', 'modules', 'contracts.createContract', 'Create Contract', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1770, 1, 'en', 'modules', 'contracts.createContractType', 'Create Contract Type', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1771, 1, 'en', 'modules', 'contracts.addComment', 'Add Comment', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1772, 1, 'en', 'modules', 'contracts.contractValue', 'Contract Value', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1773, 1, 'en', 'modules', 'contracts.endDate', 'End Date', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1774, 1, 'en', 'modules', 'contracts.active', 'Active', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1775, 1, 'en', 'modules', 'contracts.notes', 'Notes', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1776, 1, 'en', 'modules', 'contracts.editDiscussion', 'Edit Discussion', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1777, 1, 'en', 'modules', 'contracts.discussionAdded', 'Discussion successfully added.', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1778, 1, 'en', 'modules', 'contracts.newStartDate', 'New start date', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1779, 1, 'en', 'modules', 'contracts.newEndDate', 'New end date', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1780, 1, 'en', 'modules', 'contracts.newAmount', 'New amount', '2020-04-09 22:03:48', '2020-04-09 22:03:48'),
(1781, 1, 'en', 'pagination', 'previous', '&laquo; Previous', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1782, 1, 'en', 'pagination', 'next', 'Next &raquo;', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1783, 1, 'en', 'passwords', 'password', 'Passwords must be at least six characters and match the confirmation.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1784, 1, 'en', 'passwords', 'reset', 'Your password has been reset!', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1785, 1, 'en', 'passwords', 'sent', 'We have e-mailed your password reset link!', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1786, 1, 'en', 'passwords', 'token', 'This password reset token is invalid.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1787, 1, 'en', 'passwords', 'user', 'We can\'t find a user with that e-mail address.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1788, 1, 'en', 'validation', 'accepted', 'The :attribute must be accepted.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1789, 1, 'en', 'validation', 'active_url', 'The :attribute is not a valid URL.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1790, 1, 'en', 'validation', 'after', 'The :attribute must be a date after :date.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1791, 1, 'en', 'validation', 'after_or_equal', 'The :attribute must be a date after or equal to :date.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1792, 1, 'en', 'validation', 'alpha', 'The :attribute may only contain letters.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1793, 1, 'en', 'validation', 'alpha_dash', 'The :attribute may only contain letters, numbers, and dashes.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1794, 1, 'en', 'validation', 'alpha_num', 'The :attribute may only contain letters and numbers.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1795, 1, 'en', 'validation', 'array', 'The :attribute must be an array.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1796, 1, 'en', 'validation', 'before', 'The :attribute must be a date before :date.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1797, 1, 'en', 'validation', 'before_or_equal', 'The :attribute must be a date before or equal to :date.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1798, 1, 'en', 'validation', 'between.numeric', 'The :attribute must be between :min and :max.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1799, 1, 'en', 'validation', 'between.file', 'The :attribute must be between :min and :max kilobytes.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1800, 1, 'en', 'validation', 'between.string', 'The :attribute must be between :min and :max characters.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1801, 1, 'en', 'validation', 'between.array', 'The :attribute must have between :min and :max items.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1802, 1, 'en', 'validation', 'boolean', 'The :attribute field must be true or false.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1803, 1, 'en', 'validation', 'confirmed', 'The :attribute confirmation does not match.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1804, 1, 'en', 'validation', 'date', 'The :attribute is not a valid date.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1805, 1, 'en', 'validation', 'date_format', 'The :attribute does not match the format :format.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1806, 1, 'en', 'validation', 'different', 'The :attribute and :other must be different.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1807, 1, 'en', 'validation', 'digits', 'The :attribute must be :digits digits.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1808, 1, 'en', 'validation', 'digits_between', 'The :attribute must be between :min and :max digits.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1809, 1, 'en', 'validation', 'dimensions', 'The :attribute has invalid image dimensions.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1810, 1, 'en', 'validation', 'distinct', 'The :attribute field has a duplicate value.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1811, 1, 'en', 'validation', 'email', 'The :attribute must be a valid email address.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1812, 1, 'en', 'validation', 'exists', 'The selected :attribute is invalid.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1813, 1, 'en', 'validation', 'file', 'The :attribute must be a file.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1814, 1, 'en', 'validation', 'filled', 'The :attribute field must have a value.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1815, 1, 'en', 'validation', 'image', 'The :attribute must be an image.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1816, 1, 'en', 'validation', 'in', 'The selected :attribute is invalid.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1817, 1, 'en', 'validation', 'in_array', 'The :attribute field does not exist in :other.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1818, 1, 'en', 'validation', 'integer', 'The :attribute must be an integer.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1819, 1, 'en', 'validation', 'ip', 'The :attribute must be a valid IP address.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1820, 1, 'en', 'validation', 'json', 'The :attribute must be a valid JSON string.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1821, 1, 'en', 'validation', 'max.numeric', 'The :attribute may not be greater than :max.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1822, 1, 'en', 'validation', 'max.file', 'The :attribute may not be greater than :max kilobytes.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1823, 1, 'en', 'validation', 'max.string', 'The :attribute may not be greater than :max characters.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1824, 1, 'en', 'validation', 'max.array', 'The :attribute may not have more than :max items.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1825, 1, 'en', 'validation', 'mimes', 'The :attribute must be a file of type: :values.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1826, 1, 'en', 'validation', 'mimetypes', 'The :attribute must be a file of type: :values.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1827, 1, 'en', 'validation', 'min.numeric', 'The :attribute must be at least :min.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1828, 1, 'en', 'validation', 'min.file', 'The :attribute must be at least :min kilobytes.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1829, 1, 'en', 'validation', 'min.string', 'The :attribute must be at least :min characters.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1830, 1, 'en', 'validation', 'min.array', 'The :attribute must have at least :min items.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1831, 1, 'en', 'validation', 'not_in', 'The selected :attribute is invalid.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1832, 1, 'en', 'validation', 'numeric', 'The :attribute must be a number.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1833, 1, 'en', 'validation', 'present', 'The :attribute field must be present.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1834, 1, 'en', 'validation', 'regex', 'The :attribute format is invalid.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1835, 1, 'en', 'validation', 'required', 'The :attribute field is required.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1836, 1, 'en', 'validation', 'required_if', 'The :attribute field is required when :other is :value.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1837, 1, 'en', 'validation', 'required_unless', 'The :attribute field is required unless :other is in :values.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1838, 1, 'en', 'validation', 'required_with', 'The :attribute field is required when :values is present.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1839, 1, 'en', 'validation', 'required_with_all', 'The :attribute field is required when :values is present.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1840, 1, 'en', 'validation', 'required_without', 'The :attribute field is required when :values is not present.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1841, 1, 'en', 'validation', 'required_without_all', 'The :attribute field is required when none of :values are present.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1842, 1, 'en', 'validation', 'same', 'The :attribute and :other must match.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1843, 1, 'en', 'validation', 'size.numeric', 'The :attribute must be :size.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1844, 1, 'en', 'validation', 'size.file', 'The :attribute must be :size kilobytes.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1845, 1, 'en', 'validation', 'size.string', 'The :attribute must be :size characters.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1846, 1, 'en', 'validation', 'size.array', 'The :attribute must contain :size items.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1847, 1, 'en', 'validation', 'string', 'The :attribute must be a string.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1848, 1, 'en', 'validation', 'timezone', 'The :attribute must be a valid zone.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1849, 1, 'en', 'validation', 'unique', 'The :attribute has already been taken.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1850, 1, 'en', 'validation', 'uploaded', 'The :attribute failed to upload.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1851, 1, 'en', 'validation', 'url', 'The :attribute format is invalid.', '2020-04-09 22:03:49', '2020-04-09 22:03:49'),
(1852, 1, 'en', 'validation', 'custom.attribute-name.rule-name', 'custom-message', '2020-04-09 22:03:49', '2020-04-09 22:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `message_settings`
--

CREATE TABLE `message_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `allow_client_admin` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `allow_client_employee` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message_settings`
--

INSERT INTO `message_settings` (`id`, `company_id`, `allow_client_admin`, `allow_client_employee`, `created_at`, `updated_at`) VALUES
(1, NULL, 'no', 'no', '2020-04-09 11:23:55', '2020-04-09 11:23:55'),
(2, 1, 'no', 'no', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_04_02_193003_create_countries_table', 1),
(2, '2014_04_02_193005_create_translations_table', 1),
(3, '2014_10_12_000000_create_users_table', 1),
(4, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2016_06_20_112951_create_user_chat_table', 1),
(6, '2017_03_23_110416_add_column_users_table', 1),
(7, '2017_03_23_111036_create_client_details_table', 1),
(8, '2017_03_23_112028_create_client_contacts_table', 1),
(9, '2017_03_23_112353_create_employee_details_table', 1),
(10, '2017_03_23_114438_create_organisation_settings_table', 1),
(11, '2017_03_23_122646_create_project_category_table', 1),
(12, '2017_03_23_123601_create_projects_table', 1),
(13, '2017_03_23_125424_create_project_members_table', 1),
(14, '2017_03_23_125625_create_project_time_logs_table', 1),
(15, '2017_03_23_130413_create_project_files_table', 1),
(16, '2017_03_24_051800_create_tasks_table', 1),
(17, '2017_03_24_054355_create_notices_table', 1),
(18, '2017_03_24_055005_create_conversation_table', 1),
(19, '2017_03_24_055539_create_conversation_reply_table', 1),
(20, '2017_03_24_055859_create_invoices_table', 1),
(21, '2017_03_24_060421_create_invoice_items_table', 1),
(22, '2017_03_24_060751_create_quotations_table', 1),
(23, '2017_03_24_061241_create_quotation_items_table', 1),
(24, '2017_03_24_061505_create_sticky_notes_table', 1),
(25, '2017_03_24_064541_create_issues_table', 1),
(26, '2017_03_29_123858_entrust_setup_tables', 1),
(27, '2017_04_04_193158_AddColumnsProjectFilesTable', 1),
(28, '2017_04_05_063103_change_clientid_projectid_invoice_table', 1),
(29, '2017_04_06_051401_add_discount_column_invoice_table', 1),
(30, '2017_04_06_054728_add_status_column_issues_table', 1),
(31, '2017_04_08_152902_add_total_hours_column_time_log_table', 1),
(32, '2017_04_18_095809_create_project_activity_table', 1),
(33, '2017_04_18_103815_create_user_activities_table', 1),
(34, '2017_04_19_101519_create_email_notification_settings_table', 1),
(35, '2017_04_20_185211_add_colour_column_sticky_notes_table', 1),
(36, '2017_04_28_114154_create_notifications_table', 1),
(37, '2017_05_03_131016_add_project_completion_field_projects', 1),
(38, '2017_05_03_174333_create_currencies_table', 1),
(39, '2017_05_05_124330_create_module_settings_table', 1),
(40, '2017_05_05_233111_add_status_column_invoices', 1),
(41, '2017_05_11_140502_add_currency_symbol_column_invoices', 1),
(42, '2017_05_11_170244_add_currency_id_column_organisation_settings_table', 1),
(43, '2017_05_22_172748_add_timezone_column_settings_table', 1),
(44, '2017_05_24_120216_create_smtp_settings_table', 1),
(45, '2017_05_31_112158_create_universal_search_table', 1),
(46, '2017_06_22_131112_add_locale_organisation_settings_table', 1),
(47, '2017_07_13_091922_add_calculate_task_progress_column_project_table', 1),
(48, '2017_07_20_093528_on_delete_setnull_timelog', 1),
(49, '2017_07_21_120958_create_theme_settings_table', 1),
(50, '2017_07_24_113657_add_link_color_column_theme_settings_table', 1),
(51, '2017_07_24_123050_add_login_background_organisation_settings_table', 1),
(52, '2017_07_27_101351_add_column_type_invoice_items_table', 1),
(53, '2017_07_28_102010_create_estimates_table', 1),
(54, '2017_07_28_103230_create_estimate_items_table', 1),
(55, '2017_08_04_064431_create_payments_table', 1),
(56, '2017_08_05_103940_create_payment_gateway_credential_table', 1),
(57, '2017_08_08_055908_add_enable_paypal_column_payment_gateway_table', 1),
(58, '2017_08_09_054230_create_expenses_table', 1),
(59, '2017_08_21_065430_add_exchange_rate_column_currency_table', 1),
(60, '2017_08_21_131318_create_invoice_setting_table', 1),
(61, '2017_08_22_055908_add_expense_email_setting_to_email_notification_setting_table', 1),
(62, '2017_08_28_110759_add_recurring_columns_in_invoice_table', 1),
(63, '2017_08_30_061016_add_plan_id_to_payments_table', 1),
(64, '2017_08_30_093400_create_settings_table', 1),
(65, '2017_08_30_123956_add_slack_username_column_employee_details_table', 1),
(66, '2017_08_30_133725_add_send_slack_column_email_notification_settings_table', 1),
(67, '2017_09_01_060715_add_stipe_column_to_payment_credentials_table', 1),
(68, '2017_09_01_090124_add_customer_id_column_to_payments_table', 1),
(69, '2017_09_02_084049_add_locale_column_users_table', 1),
(70, '2017_09_14_095429_create_ticket_reply_templates_table', 1),
(71, '2017_09_14_095815_create_ticket_types_table', 1),
(72, '2017_09_14_100400_create_ticket_groups_table', 1),
(73, '2017_09_14_100530_create_ticket_tag_list_table', 1),
(74, '2017_09_14_114900_create_ticket_channels_table', 1),
(75, '2017_09_14_115003_create_ticket_agent_groups_table', 1),
(76, '2017_09_14_115004_create_tickets_table', 1),
(77, '2017_09_14_115005_create_ticket_tags_table', 1),
(78, '2017_09_18_064917_add_status_column_ticket_agent_group_table', 1),
(79, '2017_09_24_101700_create_ticket_replies_table', 1),
(80, '2017_09_25_060229_drop_description_column_ticket_table', 1),
(81, '2017_09_25_072611_add_deleted_at_column_tickets', 1),
(82, '2017_09_25_072627_add_deleted_at_column_ticket_reply', 1),
(83, '2017_10_03_094922_ticket_notification_migration', 1),
(84, '2017_10_03_134003_add_latitude_longitude_column', 1),
(85, '2017_10_12_111741_create_attendance_setting_table', 1),
(86, '2017_10_13_051909_create_attendance_table', 1),
(87, '2017_10_26_051335_add_mail_from_email_column_smtp_settings_table', 1),
(88, '2017_10_26_112253_add_office_open_days_column_attendance_settings_table', 1),
(89, '2017_11_01_054603_add_columns_to_client_details', 1),
(90, '2017_11_02_045542_change_on_cascade_project_members', 1),
(91, '2017_11_07_054438_add_project_admin_column_project_table', 1),
(92, '2017_11_07_125619_remove_project_admin_role', 1),
(93, '2017_11_08_045549_make_project_id_nullable_tasks_table', 1),
(94, '2017_11_09_071318_create_taskboard_columns_table', 1),
(95, '2017_11_09_092817_add_column_tasks_table', 1),
(96, '2017_11_20_070830_create_custom_fields_table', 1),
(97, '2017_11_20_071758_create_custom_fields__data_table', 1),
(98, '2017_11_22_071535_create_events_table', 1),
(99, '2017_11_23_065323_add_cryptocurrency_columns', 1),
(100, '2017_11_24_103957_create_event_attendees_table', 1),
(101, '2017_12_07_034433_change cascade users in time log table', 1),
(102, '2017_12_12_071823_create_modules_table', 1),
(103, '2017_12_12_073501_add_module_id_column_permissions_table', 1),
(104, '2017_12_21_114839_change_upload_folder', 1),
(105, '2017_12_28_112910_create_leave_types_table', 1),
(106, '2017_12_30_184422_create_leaves_table', 1),
(107, '2018_01_02_122442_add_leaves_notification_setting', 1),
(108, '2018_01_05_062543_add_user_css_column_theme_settings', 1),
(109, '2018_01_09_180937_add_task_completed_notification_setting', 1),
(110, '2018_01_29_073527_create_message_setting_table', 1),
(111, '2018_04_12_100452_add_dropbox_link_column_project_files_table', 1),
(112, '2018_04_12_123243_create_file_storage_table', 1),
(113, '2018_04_13_072732_create_groups_table', 1),
(114, '2018_04_13_092757_create_employee_groups_table', 1),
(115, '2018_04_17_113657_set_attendance_late_column_default', 1),
(116, '2018_05_07_065407_alter_invoice_id_null_payments', 1),
(117, '2018_05_07_065557_add_currency_id_column_payments_table', 1),
(118, '2018_05_08_064539_add_note_column_invoices', 1),
(119, '2018_05_15_072536_add_project_id_column_payments', 1),
(120, '2018_05_28_094515_set_gateway_column_null_payments_table', 1),
(121, '2018_05_29_070343_change_completed_on_column_to_datetime', 1),
(122, '2018_05_29_114402_populate_file_storage_settings_table', 1),
(123, '2018_05_30_051128_add_google_url_to_project_files_table', 1),
(124, '2018_06_05_092136_create_sub_tasks_table', 1),
(125, '2018_06_06_091511_create_task_comments_table', 1),
(126, '2018_06_11_054204_create_push_subscriptions_table', 1),
(127, '2018_06_14_094059_create_taxes_table', 1),
(128, '2018_06_18_065034_add_tax_id_column_invoice_items_table', 1),
(129, '2018_06_18_071442_add_discount_column_invoice_items_table', 1),
(130, '2018_06_21_052408_change_default_taskboard_columns', 1),
(131, '2018_06_26_160023_add_leave_count_column_leave_types_table', 1),
(132, '2018_06_27_072813_add_leaves_start_from_column', 1),
(133, '2018_06_27_075233_add_joining_date_column', 1),
(134, '2018_06_27_113713_add_gender_column_users_table', 1),
(135, '2018_06_28_054604_add_client_view_task_column_project_table', 1),
(136, '2018_06_28_191256_create_language_settings_table', 1),
(137, '2018_06_29_060331_add_active_theme_column_settings', 1),
(138, '2018_06_29_081128_add_manual_timelog_column_project_timelog', 1),
(139, '2018_06_29_104709_seed_languages', 1),
(140, '2018_08_02_121259_add_minutes_column_time_log_table', 1),
(141, '2018_08_22_103829_add_leaves_module', 1),
(142, '2018_08_22_104302_add_leaves_permissions', 1),
(143, '2018_08_27_114329_add_module_list_in_module_settings', 1),
(144, '2018_08_30_065158_add_status_column_users_table', 1),
(145, '2018_08_31_095814_create_lead_table', 1),
(146, '2018_08_31_095815_create_lead_source_table', 1),
(147, '2018_08_31_095815_create_lead_status_table', 1),
(148, '2018_08_31_095816_create_lead_follow_up_table', 1),
(149, '2018_09_04_095158_alter_lead_table', 1),
(150, '2018_09_04_095816_add_lead_module', 1),
(151, '2018_09_05_102010_create_proposal_table', 1),
(152, '2018_09_05_113230_create_proposal_items_table', 1),
(153, '2018_09_07_051239_alter_lead_website_table', 1),
(154, '2018_09_15_174026_add_default_lead_settings', 1),
(155, '2018_09_17_045718_add_leads_permission', 1),
(156, '2018_09_19_091643_add_remarks_to_payments_table', 1),
(157, '2018_09_19_100708_create_products_table', 1),
(158, '2018_09_21_095816_create_offline_payment_method_table', 1),
(159, '2018_09_25_065158_alter_payment_table', 1),
(160, '2018_09_28_110029_create_log_time_for_table', 1),
(161, '2018_09_28_965158_alter_project_time_log_table', 1),
(162, '2018_10_03_121901_create_packages_table', 1),
(163, '2018_10_03_121902_alter_organisation_settings_table', 1),
(164, '2018_10_04_042418_create_licences_table', 1),
(165, '2018_10_04_082754_add_super_admin_column_in_users_table', 1),
(166, '2018_10_08_091643_alter_project_table', 1),
(167, '2018_10_08_095950_create_subscriptions_table', 1),
(168, '2018_10_08_110029_create_lead_files_table', 1),
(169, '2018_10_08_120639_add_company_id_in_users_table', 1),
(170, '2018_10_10_110029_create_holidays_table', 1),
(171, '2018_10_10_114514_add_company_id_in_teams_table', 1),
(172, '2018_10_10_120621_add_company_id_in_leads_table', 1),
(173, '2018_10_10_123601_create_project_templates_table', 1),
(174, '2018_10_10_125424_create_project_template_members_table', 1),
(175, '2018_10_10_135816_add_holiday_module', 1),
(176, '2018_10_10_251800_create_project_template_tasks_table', 1),
(177, '2018_10_11_044355_add_company_id_in_attendances_table', 1),
(178, '2018_10_11_055814_add_company_id_in_holidays_table', 1),
(179, '2018_10_11_061029_add_company_id_in_projects_table', 1),
(180, '2018_10_11_061955_add_company_id_in_project_category_table', 1),
(181, '2018_10_11_063520_add_company_id_in_project_members_table', 1),
(182, '2018_10_11_065229_add_company_id_in_invoices_table', 1),
(183, '2018_10_11_070557_add_company_id_in_project_activity_table', 1),
(184, '2018_10_11_071656_add_company_id_in_products_table', 1),
(185, '2018_10_11_072547_add_company_id_in_taxes_table', 1),
(186, '2018_10_11_081816_add_company_id_in_tasks_table', 1),
(187, '2018_10_11_083600_add_company_id_in_taskboard_columns_table', 1),
(188, '2018_10_11_100425_add_company_id_in_estimates_table', 1),
(189, '2018_10_11_101701_add_company_id_in_payments_table', 1),
(190, '2018_10_11_102047_add_company_id_in_expenses_table', 1),
(191, '2018_10_11_110008_add_company_id_in_employee_details_table', 1),
(192, '2018_10_11_115208_add_company_id_in_project_time_logs_table', 1),
(193, '2018_10_11_115805_add_company_id_in_user_activities_table', 1),
(194, '2018_10_12_045341_add_company_id_in_tickets_table', 1),
(195, '2018_10_12_051409_add_company_id_in_ticket_channels_table', 1),
(196, '2018_10_12_052646_add_company_id_in_ticket_types_table', 1),
(197, '2018_10_12_060038_add_company_id_in_ticket_groups_table', 1),
(198, '2018_10_12_061136_add_company_id_in_ticket_agent_groups_table', 1),
(199, '2018_10_12_061807_add_company_id_in_ticket_reply_templates_table', 1),
(200, '2018_10_12_072321_add_company_id_in_events_table', 1),
(201, '2018_10_12_090132_add_company_id_in_leave_types_table', 1),
(202, '2018_10_12_090146_add_company_id_in_leaves_table', 1),
(203, '2018_10_12_093431_add_company_id_in_notices_table', 1),
(204, '2018_10_12_110433_add_company_id_in_email_notification_settings_table', 1),
(205, '2018_10_12_110842_add_company_id_in_smtp_settings_table', 1),
(206, '2018_10_15_051607_add_company_id_in_currencies_table', 1),
(207, '2018_10_15_052819_create_global_settings_table', 1),
(208, '2018_10_15_065737_add_company_id_in_theme_settings_table', 1),
(209, '2018_10_15_070856_alter_currency_id_in_companies_table', 1),
(210, '2018_10_15_083914_add_company_id_in_payment_gateway_credentials_table', 1),
(211, '2018_10_15_093625_add_company_id_in_invoice_settings_table', 1),
(212, '2018_10_15_094709_add_company_id_in_slack_settings_table', 1),
(213, '2018_10_15_105445_add_company_id_in_attendance_settings_table', 1),
(214, '2018_10_15_115927_add_company_id_in_custom_field_groups_table', 1),
(215, '2018_10_16_045235_add_company_id_in_module_settings_table', 1),
(216, '2018_10_16_071301_add_company_id_in_roles_table', 1),
(217, '2018_10_16_095816_add_holiday_module_detail', 1),
(218, '2018_10_17_043749_add_company_id_in_message_settings_table', 1),
(219, '2018_10_17_052214_add_company_id_in_file_storage_settings_table', 1),
(220, '2018_10_17_063334_add_company_id_in_lead_sources_table', 1),
(221, '2018_10_17_063359_add_company_id_in_lead_status_table', 1),
(222, '2018_10_17_081757_remove_config_datatable_file', 1),
(223, '2018_10_17_965158_alter_leads_address_table', 1),
(224, '2018_10_17_965168_alter_leads_phone_table', 1),
(225, '2018_10_18_034518_create_stripe_invoices_table', 1),
(226, '2018_10_18_075228_add_column_in_global_settings_table', 1),
(227, '2018_10_18_091643_alter_attendance_setting_table', 1),
(228, '2018_10_19_045718_add_holidays_permission', 1),
(229, '2018_10_20_094413_add_products_module', 1),
(230, '2018_10_20_094504_add_products_permissions', 1),
(231, '2018_10_21_051239_alter_holiday_website_table', 1),
(232, '2018_10_22_050933_alter_state_column_companies_table', 1),
(233, '2018_10_23_071525_remove_company_id_column_smtp_settings_table', 1),
(234, '2018_10_24_041117_add_column_email_verification_code_in_users_table', 1),
(235, '2018_10_24_071300_add_file_column_to_invoices_table', 1),
(236, '2018_10_24_965158_alter_employee_detail_table', 1),
(237, '2018_10_29_965158_alter_attendance_setting_default_table', 1),
(238, '2018_11_02_061629_add_column_in_proposals_table', 1),
(239, '2018_11_10_071300_alter_user_table', 1),
(240, '2018_11_10_122646_create_task_category_table', 1),
(241, '2018_11_15_105021_alter_stripe_invoices_table', 1),
(242, '2018_11_16_072246_add_company_id_in_client_details_table', 1),
(243, '2018_11_16_104747_add_column_in_estimate_items_table', 1),
(244, '2018_11_16_112847_add_column_in_proposals_items_table', 1),
(245, '2018_11_22_044348_add_estimate_number_column_in_estimates_table', 1),
(246, '2018_11_30_965158_alter_invoice_item_table', 1),
(247, '2018_12_12_965158_alter_invoice_estimate_response_table', 1),
(248, '2018_12_14_094504_add_expenses_permissions', 1),
(249, '2018_12_14_194504_add_expenses_permissions_detail', 1),
(250, '2018_12_20_1065158_alter_company_setting_table', 1),
(251, '2018_12_20_965158_alter_estimate_quantity_table', 1),
(252, '2018_12_27_074504_check_verify_purchase_file', 1),
(253, '2018_12_28_075730_create_push_notification_settings_table', 1),
(254, '2018_12_28_082056_add_send_push_column_email_notification_table', 1),
(255, '2018_12_28_123245_add_onesignal_player_id_column_users_table', 1),
(256, '2019_01_02_1065158_alter_module_setting_table', 1),
(257, '2019_01_02_2065158_insert_module_setting_client_table', 1),
(258, '2019_01_04_110029_create_employee_docs_table', 1),
(259, '2019_01_10_063520_add_company_id_in_lead_files_table', 1),
(260, '2019_01_17_045235_add_company_id_in_project_template_table', 1),
(261, '2019_01_17_055235_add_company_id_in_task_category_table', 1),
(262, '2019_01_17_065235_add_company_id_in_employee_docs_table', 1),
(263, '2019_01_17_075235_add_company_id_in_log_time_for_table', 1),
(264, '2019_01_21_1065158_alter_task_creator_table', 1),
(265, '2019_02_06_1065158_alter_attendance_check_table', 1),
(266, '2019_02_08_174333_create_global_currencies_table', 1),
(267, '2019_02_08_275235_add_currency_id_in_global_setting_table', 1),
(268, '2019_02_11_1065158_alter_log_time_for_table', 1),
(269, '2019_02_12_2065158_insert_module_setting_client_task_table', 1),
(270, '2019_02_13_110029_create_skills_table', 1),
(271, '2019_02_13_130029_create_employee_skills_table', 1),
(272, '2019_02_15_1065158_alter_employee_end_date_table', 1),
(273, '2019_02_15_1165158_alter_custom_status_table', 1),
(274, '2019_02_20_074848_create_jobs_table', 1),
(275, '2019_02_22_1165158_add_company_currency_api_google_api', 1),
(276, '2019_02_22_1165158_add_currency_api_google_api', 1),
(277, '2019_02_25_965158_alter_package_max_size_table', 1),
(278, '2019_02_28_965158_alter_package_sort_billing_cycle_table', 1),
(279, '2019_03_04_073501_change_module_id_notice_permissions_table', 1),
(280, '2019_03_05_110029_create_front_detail_table', 1),
(281, '2019_03_05_110039_create_feature_table', 1),
(282, '2019_03_08_1165158_create_stripe_table', 1),
(283, '2019_03_08_965158_alter_invoice_project_id_null_table', 1),
(284, '2019_03_11_132024_seed_front_end_data', 1),
(285, '2019_03_18_1165158_alter_stripe_setting_table', 1),
(286, '2019_03_19_061905_add_google_recaptcha_key_column_global_settings', 1),
(287, '2019_03_19_1265158_paypal_invoice_table', 1),
(288, '2019_04_03_965158_alter_project_deadline_table', 1),
(289, '2019_04_04_074848_alter_invoice_setting_table', 1),
(290, '2019_04_04_075848_alter_client_Details_table', 1),
(291, '2019_04_04_1165158_alter_package_default_table', 1),
(292, '2019_04_10_075848_alter_company_task_table', 1),
(293, '2019_04_17_1165158_create_package_setting_table', 1),
(294, '2019_04_22_075848_alter_package_setting_table', 1),
(295, '2019_06_05_163256_add_timezone_column_global_settings_table', 1),
(296, '2019_06_05_180258_add_locale_column_global_settings_table', 1),
(297, '2019_06_21_100408_add_name_and_email_columns_to_client_details_table', 1),
(298, '2019_07_05_083850_add_company_id_in_client_contacts_table', 1),
(299, '2019_07_09_133247_remove_invoice_unique_index', 1),
(300, '2019_07_16_145850_add_deleted_at_in_estimates_table', 1),
(301, '2019_07_16_145851_add_deleted_at_in_invoices_table', 1),
(302, '2019_07_17_145848_remove_estimate_unique_index', 1),
(303, '2019_07_19_112506_add_project_id_column_in_expenses_table', 1),
(304, '2019_08_05_112511_create_credit_notes_table', 1),
(305, '2019_08_05_112513_create_credit_note_items_table', 1),
(306, '2019_08_06_112518_add_credit_note_column_in_invoices_table', 1),
(307, '2019_08_07_112521_add_columns_in_invoice_settings_table', 1),
(308, '2019_08_13_073129_update_settings_add_envato_key', 1),
(309, '2019_08_13_073129_update_settings_add_support_key', 1),
(310, '2019_08_14_091832_add_item_summary_invoice_items_table', 1),
(311, '2019_08_14_105412_add_item_summary_estimate_items_table', 1),
(312, '2019_08_16_075733_change_price_size_proposal', 1),
(313, '2019_08_22_055908_add_invoice_email_setting_to_email_notification_setting_table', 1),
(314, '2019_08_22_075432_remove_unique_column_name_taskboard', 1),
(315, '2019_08_22_121805_add_external_link_column_project_files_table', 1),
(316, '2019_08_26_120718_add_offline_method_id_column_payments_table', 1),
(317, '2019_08_28_070105_create_project_milestones_table', 1),
(318, '2019_08_28_081847_update_smtp_setting_verified', 1),
(319, '2019_08_28_100242_add_columns_projects_table', 1),
(320, '2019_08_28_101747_add_milestone_id_column_tasks_table', 1),
(321, '2019_08_28_115700_add_budget_columns_projects_table', 1),
(322, '2019_08_28_2083812_add_invoice_created_column_project_milestones_table', 1),
(323, '2019_08_29_140115_make_smtp_type_nullable', 1),
(324, '2019_09_03_021925_add_currency_in_free_trail', 1),
(325, '2019_09_04_115714_add_recurring_task_id_column_in_tasks_table', 1),
(326, '2019_09_09_041914_create_project_settings_table', 1),
(327, '2019_09_09_045042_create_faq_categories_table', 1),
(328, '2019_09_09_045056_create_faqs_table', 1),
(329, '2019_09_09_081030_add_rounded_theme_column', 1),
(330, '2019_09_09_115714_add_cron_job_message_hide_table', 1),
(331, '2019_09_12_061447_add_google_recaptcha_secret_in_global_settings_table', 1),
(332, '2019_09_12_1074848_create_designation_table', 1),
(333, '2019_09_12_115714_add_team_field_employee_table', 1),
(334, '2019_10_01_110039_create_footer_menu_table', 1),
(335, '2019_10_03_110030_add_social_links_column_in_front_details_table', 1),
(336, '2019_10_03_112806_add_week_start_column_in_companies_table', 1),
(337, '2019_10_04_101818_add_paypal_mode_in_payment_gateway_credentials_table', 1),
(338, '2019_10_04_124931_add_week_start_column_gloabl_settings', 1),
(339, '2019_10_07_063300_add_last_login_column_in_companies_table', 1),
(340, '2019_10_07_063301_add_payments_module_clients', 1),
(341, '2019_10_07_183130_create_dashboard_widgets_table', 1),
(342, '2019_10_07_191818_add_razorpay_detail_in_payment_gateway_credentials_table', 1),
(343, '2019_10_07_201818_add_razorpay_detail_in_stripe_setting_table', 1),
(344, '2019_10_09_191818_add_razorpay_plan_id_in_packages_table', 1),
(345, '2019_10_10_095950_create_razorpay_subscriptions_table', 1),
(346, '2019_10_10_1534518_create_razorpay_invoices_table', 1),
(347, '2019_10_14_060314_create_accept_estimates_table', 1),
(348, '2019_10_14_110606_add_estimate_id_column_in_invoices_table', 1),
(349, '2019_10_15_052931_create_contract_types_table', 1),
(350, '2019_10_15_052932_create_contracts_table', 1),
(351, '2019_10_15_084310_add_contract_module_in_module_settings', 1),
(352, '2019_10_15_115655_create_contract_signs_table', 1),
(353, '2019_10_17_051544_create_contract_discussions_table', 1),
(354, '2019_10_19_191818_add_order_id_in_razorpay_invoice_table', 1),
(355, '2019_10_19_5074854_add_status_column_projects_table', 1),
(356, '2019_10_22_5074864_add_company_id_in_skills_table', 1),
(357, '2019_10_22_5074874_add_company_id_in_universal_search_table', 1),
(358, '2019_10_23_122412_create_contract_renews_table', 1),
(359, '2019_10_23_130413_create_task_files_table', 1),
(360, '2019_10_23_230413_create_ticket_files_table', 1),
(361, '2019_10_23_5074884_alter_company_id_in_project_category_table', 1),
(362, '2019_10_24_120220_add_origin_amount_column_in_contracts_table', 1),
(363, '2019_10_31_043520_add_dependent_task_id_in_tasks_table', 1),
(364, '2019_10_31_122412_create_lead_agent_table', 1),
(365, '2019_11_01_142619_add_column_to_in_notices_table', 1),
(366, '2019_11_02_051209_create_invoice_credit_note_pivot_table', 1),
(367, '2019_11_02_051855_alter_credit_note_status_in_credit_notes_table', 1),
(368, '2019_11_04_0455045_add_column_invoice_item_table', 1),
(369, '2019_11_04_0455055_add_column_credit_note_item_table', 1),
(370, '2019_11_04_0455065_add_column_estimate_item_table', 1),
(371, '2019_11_04_0455075_add_column_products_table', 1),
(372, '2019_11_04_063551_create_gdpr_settings_table', 1),
(373, '2019_11_04_091725_create_removal_requests_table', 1),
(374, '2019_11_04_091810_create_removal_requests_lead_table', 1),
(375, '2019_11_06_092918_add_client_id_in_invoices_table', 1),
(376, '2019_11_06_120145_create_offline_invoices_table', 1),
(377, '2019_11_06_120146_create_offline_plan_changes_table', 1),
(378, '2019_11_08_082637_add_purchase_allow_in_product_table', 1),
(379, '2019_11_12_054145_add_system_update_column_in_global_settings_table', 1),
(380, '2019_11_14_082655_add_employee_id_column_in_employee_details_table', 1),
(381, '2019_11_18_054145_add_discount_column_in_proposal_table', 1),
(382, '2019_11_18_064145_add_tax_column_in_proposal_item_table', 1),
(383, '2019_11_18_123900_create_offline_invoice_payments_table', 1),
(384, '2019_11_19_064145_change_universal_search_client_id', 1),
(385, '2019_11_20_111631_add_payent_method_id_in_offline_invoices_table', 1),
(386, '2019_11_29_122129_add_paypal_mode_column_in_stripe_setting_table', 1),
(387, '2019_12_01_115133_alter_invoice_status_table', 1),
(388, '2019_12_09_171149_make_taxes_nullable_propsal_items_table', 1),
(389, '2019_12_11_082834_add_email_verification_column_in_global_settings_table', 1),
(390, '2019_12_18_121031_add_date_picker_format_column_in_companies_table', 1),
(391, '2019_12_20_143625_add_logo_background_color_column_settings_table', 1),
(392, '2020_01_13_055908_add_payment_email_setting_to_email_notification_setting_table', 1),
(393, '2020_01_13_1100390_create_testimonials_table', 1),
(394, '2020_01_13_1100391_create_front_clients_table', 1),
(395, '2020_01_13_115133_alter_feature_setting_table', 1),
(396, '2020_01_13_122129_add_extra_column_in_front_setting_table', 1),
(397, '2020_01_15_045056_create_front_faqs_table', 1),
(398, '2020_01_15_045057_create_front_menu_table', 1),
(399, '2020_01_15_056908_add_date_picker_format_to_company_setting_table', 1),
(400, '2020_01_16_132024_seed_front_data', 1),
(401, '2020_01_22_093727_add_version_column_global_settings', 1),
(402, '2020_01_22_122009_add_is_private_column_tasks_table', 1),
(403, '2020_01_23_062328_create_task_history_table', 1),
(404, '2020_01_24_093737_add_employee_details_of_default_admin', 1),
(405, '2020_01_24_134008_add_default_task_status_column_organisation_settings', 1),
(406, '2020_02_01_101914_update_settings_review', 1),
(407, '2020_02_13_101914_update_global_phone', 1),
(408, '2020_02_14_101914_update_front_old_settings_global', 1),
(409, '2020_02_18_132351_add_front_setting_logo', 1),
(410, '2020_02_19_121221_create_storage_settings', 1),
(411, '2020_02_19_132351_add_soft_delete_global_currency', 1),
(412, '2020_02_24_060416_update_invoice_setting_logo', 1),
(413, '2020_02_26_121650_add_report_module', 1),
(414, '2020_03_03_121750_add_stripe_active_subscription', 1),
(415, '2020_03_11_101914_remove_employee_id_unique', 1),
(416, '2020_03_11_101924_product_modules_setting_client', 1),
(417, '2020_03_27_102832_create_task_users_table', 1),
(418, '2020_01_31_121040_api_settings', 2),
(419, '2020_02_01_085612_create_devices_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'clients', '', NULL, NULL),
(2, 'employees', '', NULL, NULL),
(3, 'projects', 'User can view the basic details of projects assigned to him even without any permission.', NULL, NULL),
(4, 'attendance', 'User can view his own attendance even without any permission.', NULL, NULL),
(5, 'tasks', 'User can view the tasks assigned to him even without any permission.', NULL, NULL),
(6, 'estimates', '', NULL, NULL),
(7, 'invoices', '', NULL, NULL),
(8, 'payments', '', NULL, NULL),
(9, 'timelogs', '', NULL, NULL),
(10, 'tickets', 'User can view the tickets generated by him as default even without any permission.', NULL, NULL),
(11, 'events', 'User can view the events to be attended by him as default even without any permission.', NULL, NULL),
(12, 'messages', '', NULL, NULL),
(13, 'notices', '', NULL, NULL),
(14, 'leaves', 'User can view the leaves applied by him as default even without any permission.', NULL, NULL),
(15, 'leads', NULL, NULL, NULL),
(16, 'holidays', NULL, '2020-04-09 11:23:59', '2020-04-09 11:23:59'),
(17, 'products', NULL, '2020-04-09 11:23:59', '2020-04-09 11:23:59'),
(18, 'expenses', 'User can view and add(self expenses) the expenses as default even without any permission.', '2020-04-09 11:24:00', '2020-04-09 11:24:00'),
(19, 'contracts', 'User can view all contracts', '2020-04-09 11:24:03', '2020-04-09 11:24:03'),
(20, 'reports', 'Report module', '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `module_settings`
--

CREATE TABLE `module_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `module_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('admin','employee','client') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `module_settings`
--

INSERT INTO `module_settings` (`id`, `company_id`, `module_name`, `status`, `type`, `created_at`, `updated_at`) VALUES
(121, 1, 'employees', 'active', 'employee', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(122, 1, 'employees', 'active', 'admin', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(123, 1, 'projects', 'active', 'client', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(124, 1, 'projects', 'active', 'employee', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(125, 1, 'projects', 'active', 'admin', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(126, 1, 'tasks', 'active', 'client', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(127, 1, 'tasks', 'active', 'employee', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(128, 1, 'tasks', 'active', 'admin', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(129, 1, 'timelogs', 'active', 'employee', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(130, 1, 'timelogs', 'active', 'admin', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(131, 1, 'reports', 'active', 'employee', '2020-04-13 04:17:21', '2020-04-13 04:17:21'),
(132, 1, 'reports', 'active', 'admin', '2020-04-13 04:17:21', '2020-04-13 04:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(10) UNSIGNED NOT NULL,
  `to` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'employee',
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `heading` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('80c4208f-499d-42a8-b3b6-59dfb3bff188', 'App\\Notifications\\NewProjectMember', 'App\\User', 5, '{\"id\":3,\"company_id\":1,\"user_id\":5,\"project_id\":1,\"user\":{\"id\":5,\"company_id\":1,\"name\":\"Michelle\",\"email\":\"michelle@gmail.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-10T04:44:31+00:00\",\"updated_at\":\"2020-04-10T04:44:31+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[{\"id\":\"d774ea4f-da02-4130-aa67-2bb38f8cf36b\",\"type\":\"App\\\\Notifications\\\\NewUser\",\"notifiable_type\":\"App\\\\User\",\"notifiable_id\":5,\"data\":{\"id\":5,\"company_id\":1,\"name\":\"Michelle\",\"email\":\"michelle@gmail.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-10T04:44:31+00:00\",\"updated_at\":\"2020-04-10T04:44:31+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":null,\"role\":[]},\"read_at\":null,\"created_at\":\"2020-04-10 04:44:31\",\"updated_at\":\"2020-04-10 04:44:31\"}],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":\"employee\",\"role\":[{\"user_id\":5,\"role_id\":2}]}}', NULL, '2020-04-09 21:47:32', '2020-04-09 21:47:32'),
('b6959110-c0c5-48e5-943c-e2c75bb3b19d', 'App\\Notifications\\NewProjectMember', 'App\\User', 4, '{\"id\":2,\"company_id\":1,\"user_id\":4,\"project_id\":1,\"user\":{\"id\":4,\"company_id\":1,\"name\":\"Cleming\",\"email\":\"cleming@gmail.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-10T04:42:08+00:00\",\"updated_at\":\"2020-04-10T04:42:08+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[{\"id\":\"eeadd0df-d8ac-4a6b-8f49-9a2cefbca5a6\",\"type\":\"App\\\\Notifications\\\\NewUser\",\"notifiable_type\":\"App\\\\User\",\"notifiable_id\":4,\"data\":{\"id\":4,\"company_id\":1,\"name\":\"Cleming\",\"email\":\"cleming@gmail.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-10T04:42:08+00:00\",\"updated_at\":\"2020-04-10T04:42:08+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":null,\"role\":[]},\"read_at\":null,\"created_at\":\"2020-04-10 04:42:08\",\"updated_at\":\"2020-04-10 04:42:08\"}],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":\"employee\",\"role\":[{\"user_id\":4,\"role_id\":2}]}}', NULL, '2020-04-09 21:47:32', '2020-04-09 21:47:32'),
('d5f08b0e-edda-4c55-852f-1e6241575249', 'App\\Notifications\\NewProjectMember', 'App\\User', 1, '{\"id\":1,\"company_id\":1,\"user_id\":1,\"project_id\":1,\"user\":{\"id\":1,\"company_id\":1,\"name\":\"Raymond\",\"email\":\"admin@example.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-09T18:24:06+00:00\",\"updated_at\":\"2020-04-10T04:43:22+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":\"admin\",\"role\":[{\"user_id\":1,\"role_id\":1},{\"user_id\":1,\"role_id\":2}]}}', NULL, '2020-04-09 21:46:10', '2020-04-09 21:46:10'),
('d774ea4f-da02-4130-aa67-2bb38f8cf36b', 'App\\Notifications\\NewUser', 'App\\User', 5, '{\"id\":5,\"company_id\":1,\"name\":\"Michelle\",\"email\":\"michelle@gmail.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-10T04:44:31+00:00\",\"updated_at\":\"2020-04-10T04:44:31+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":null,\"role\":[]}', NULL, '2020-04-09 21:44:31', '2020-04-09 21:44:31'),
('eeadd0df-d8ac-4a6b-8f49-9a2cefbca5a6', 'App\\Notifications\\NewUser', 'App\\User', 4, '{\"id\":4,\"company_id\":1,\"name\":\"Cleming\",\"email\":\"cleming@gmail.com\",\"image\":null,\"mobile\":null,\"gender\":\"male\",\"locale\":\"en\",\"status\":\"active\",\"login\":\"enable\",\"onesignal_player_id\":null,\"created_at\":\"2020-04-10T04:42:08+00:00\",\"updated_at\":\"2020-04-10T04:42:08+00:00\",\"super_admin\":\"0\",\"email_verification_code\":null,\"unreadNotifications\":[],\"image_url\":\"http:\\/\\/localhost\\/employee-tracker-admin\\/public\\/img\\/default-profile-3.png\",\"modules\":[\"clients\",\"employees\",\"projects\",\"attendance\",\"tasks\",\"estimates\",\"invoices\",\"payments\",\"timelogs\",\"tickets\",\"events\",\"messages\",\"notices\",\"leaves\",\"leads\",\"holidays\",\"products\",\"expenses\",\"contracts\",\"reports\"],\"user_other_role\":null,\"role\":[]}', NULL, '2020-04-09 21:42:08', '2020-04-09 21:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `offline_invoices`
--

CREATE TABLE `offline_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `package_type` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offline_method_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) UNSIGNED NOT NULL,
  `pay_date` date NOT NULL,
  `next_pay_date` date DEFAULT NULL,
  `status` enum('paid','unpaid','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `offline_invoices`
--

INSERT INTO `offline_invoices` (`id`, `company_id`, `package_id`, `package_type`, `offline_method_id`, `transaction_id`, `amount`, `pay_date`, `next_pay_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'annual', NULL, NULL, 5000.00, '2020-04-10', '2021-04-10', 'paid', '2020-04-09 11:39:43', '2020-04-09 11:39:43'),
(2, 1, 3, 'annual', NULL, NULL, 999999.99, '2020-04-09', '2021-04-09', 'paid', '2020-04-10 01:35:22', '2020-04-10 01:35:22'),
(3, 1, 5, 'annual', NULL, NULL, 5000.00, '2020-04-15', '2021-04-15', 'paid', '2020-04-13 04:07:37', '2020-04-13 04:07:37'),
(4, 1, 1, 'annual', NULL, NULL, 0.00, '2020-04-14', '2021-04-14', 'paid', '2020-04-13 04:17:21', '2020-04-13 04:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `offline_invoice_payments`
--

CREATE TABLE `offline_invoice_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `slip` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('pending','approve','reject') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offline_payment_methods`
--

CREATE TABLE `offline_payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT 'yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `offline_payment_methods`
--

INSERT INTO `offline_payment_methods` (`id`, `company_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Bank Transfer', 'Silahkan transfer melalui rekening bank kami', 'yes', '2020-04-09 11:39:41', '2020-04-09 11:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `offline_plan_changes`
--

CREATE TABLE `offline_plan_changes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `package_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `offline_method_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('verified','pending','rejected') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_storage_size` int(10) UNSIGNED DEFAULT NULL,
  `max_file_size` int(10) UNSIGNED DEFAULT NULL,
  `annual_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `monthly_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `billing_cycle` tinyint(3) UNSIGNED DEFAULT NULL,
  `max_employees` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sort` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_in_package` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `stripe_annual_plan_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `razorpay_annual_plan_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_monthly_plan_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_monthly_plan_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default` enum('yes','no','trial') COLLATE utf8_unicode_ci DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `currency_id`, `name`, `description`, `max_storage_size`, `max_file_size`, `annual_price`, `monthly_price`, `billing_cycle`, `max_employees`, `sort`, `module_in_package`, `stripe_annual_plan_id`, `razorpay_annual_plan_id`, `razorpay_monthly_plan_id`, `stripe_monthly_plan_id`, `default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Free Trial', 'Its a default package and cannot be deleted', NULL, NULL, 0.00, 0.00, NULL, 3, NULL, '{\"2\":\"employees\",\"3\":\"projects\",\"5\":\"tasks\",\"9\":\"timelogs\",\"20\":\"reports\"}', 'default_plan', NULL, NULL, 'default_plan', 'yes', '2020-04-09 11:24:01', '2020-04-09 22:05:15'),
(2, 1, 'Trial', 'Its a trial package', NULL, NULL, 0.00, 0.00, NULL, 20, NULL, '[\"contracts\",\"reports\"]', 'trial_plan', NULL, NULL, 'trial_plan', 'trial', '2020-04-09 11:24:01', '2020-04-09 11:24:05'),
(3, 1, 'Starter', 'Starter Package', 1024, 30, 999999.99, 500000.00, NULL, 10, NULL, '{\"2\":\"employees\",\"3\":\"projects\",\"5\":\"tasks\",\"9\":\"timelogs\",\"20\":\"reports\"}', 'starter_annual', NULL, NULL, 'starter_monthly', 'no', '2020-04-09 11:24:06', '2020-04-10 01:32:36'),
(5, 1, 'Enterprise', 'Quidem deserunt nobis asperiores fuga Ullamco corporis culpa', 10240, 100, 5000.00, 500.00, NULL, 500, NULL, '{\"1\":\"clients\",\"2\":\"employees\",\"3\":\"projects\",\"4\":\"attendance\",\"5\":\"tasks\",\"6\":\"estimates\",\"7\":\"invoices\",\"8\":\"payments\",\"9\":\"timelogs\",\"10\":\"tickets\",\"11\":\"events\",\"12\":\"messages\",\"13\":\"notices\",\"14\":\"leaves\",\"15\":\"leads\",\"16\":\"holidays\",\"17\":\"products\",\"18\":\"expenses\",\"19\":\"contracts\",\"20\":\"reports\"}', 'larger_annual', NULL, NULL, 'larger_monthly', 'no', '2020-04-09 11:24:06', '2020-04-09 11:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `package_settings`
--

CREATE TABLE `package_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `no_of_days` int(11) DEFAULT 30,
  `modules` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notification_before` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `package_settings`
--

INSERT INTO `package_settings` (`id`, `status`, `no_of_days`, `modules`, `notification_before`, `created_at`, `updated_at`) VALUES
(1, 'inactive', 30, NULL, NULL, '2020-04-09 11:24:01', '2020-04-09 11:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` double NOT NULL,
  `gateway` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `plan_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('complete','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'complete',
  `paid_on` datetime DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `offline_method_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_credentials`
--

CREATE TABLE `payment_gateway_credentials` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `paypal_client_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `stripe_client_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_webhook_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `razorpay_key` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_webhook_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'deactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `paypal_mode` enum('sandbox','live') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sandbox'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_gateway_credentials`
--

INSERT INTO `payment_gateway_credentials` (`id`, `company_id`, `paypal_client_id`, `paypal_secret`, `paypal_status`, `stripe_client_id`, `stripe_secret`, `stripe_webhook_secret`, `stripe_status`, `razorpay_key`, `razorpay_secret`, `razorpay_webhook_secret`, `razorpay_status`, `created_at`, `updated_at`, `paypal_mode`) VALUES
(1, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 'active', NULL, NULL, NULL, 'deactive', '2020-04-09 11:23:53', '2020-04-09 11:23:53', 'sandbox'),
(2, 1, NULL, NULL, 'active', NULL, NULL, NULL, 'active', NULL, NULL, NULL, 'deactive', '2020-04-09 11:24:06', '2020-04-09 11:24:06', 'sandbox');

-- --------------------------------------------------------

--
-- Table structure for table `paypal_invoices`
--

CREATE TABLE `paypal_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `package_id` int(10) UNSIGNED DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `transaction_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_frequency` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_interval` int(11) DEFAULT NULL,
  `paid_on` datetime DEFAULT NULL,
  `next_pay_date` datetime DEFAULT NULL,
  `recurring` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT 'no',
  `status` enum('paid','unpaid','pending') COLLATE utf8_unicode_ci DEFAULT 'pending',
  `plan_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `module_id`, `created_at`, `updated_at`) VALUES
(1, 'add_clients', 'Add Clients', NULL, 1, NULL, NULL),
(2, 'view_clients', 'View Clients', NULL, 1, NULL, NULL),
(3, 'edit_clients', 'Edit Clients', NULL, 1, NULL, NULL),
(4, 'delete_clients', 'Delete Clients', NULL, 1, NULL, NULL),
(5, 'add_employees', 'Add Employees', NULL, 2, NULL, NULL),
(6, 'view_employees', 'View Employees', NULL, 2, NULL, NULL),
(7, 'edit_employees', 'Edit Employees', NULL, 2, NULL, NULL),
(8, 'delete_employees', 'Delete Employees', NULL, 2, NULL, NULL),
(9, 'add_projects', 'Add Project', NULL, 3, NULL, NULL),
(10, 'view_projects', 'View Project', NULL, 3, NULL, NULL),
(11, 'edit_projects', 'Edit Project', NULL, 3, NULL, NULL),
(12, 'delete_projects', 'Delete Project', NULL, 3, NULL, NULL),
(13, 'add_attendance', 'Add Attendance', NULL, 4, NULL, NULL),
(14, 'view_attendance', 'View Attendance', NULL, 4, NULL, NULL),
(15, 'add_tasks', 'Add Tasks', NULL, 5, NULL, NULL),
(16, 'view_tasks', 'View Tasks', NULL, 5, NULL, NULL),
(17, 'edit_tasks', 'Edit Tasks', NULL, 5, NULL, NULL),
(18, 'delete_tasks', 'Delete Tasks', NULL, 5, NULL, NULL),
(19, 'add_estimates', 'Add Estimates', NULL, 6, NULL, NULL),
(20, 'view_estimates', 'View Estimates', NULL, 6, NULL, NULL),
(21, 'edit_estimates', 'Edit Estimates', NULL, 6, NULL, NULL),
(22, 'delete_estimates', 'Delete Estimates', NULL, 6, NULL, NULL),
(23, 'add_invoices', 'Add Invoices', NULL, 7, NULL, NULL),
(24, 'view_invoices', 'View Invoices', NULL, 7, NULL, NULL),
(25, 'edit_invoices', 'Edit Invoices', NULL, 7, NULL, NULL),
(26, 'delete_invoices', 'Delete Invoices', NULL, 7, NULL, NULL),
(27, 'add_payments', 'Add Payments', NULL, 8, NULL, NULL),
(28, 'view_payments', 'View Payments', NULL, 8, NULL, NULL),
(29, 'edit_payments', 'Edit Payments', NULL, 8, NULL, NULL),
(30, 'delete_payments', 'Delete Payments', NULL, 8, NULL, NULL),
(31, 'add_timelogs', 'Add Timelogs', NULL, 9, NULL, NULL),
(32, 'view_timelogs', 'View Timelogs', NULL, 9, NULL, NULL),
(33, 'edit_timelogs', 'Edit Timelogs', NULL, 9, NULL, NULL),
(34, 'delete_timelogs', 'Delete Timelogs', NULL, 9, NULL, NULL),
(35, 'add_tickets', 'Add Tickets', NULL, 10, NULL, NULL),
(36, 'view_tickets', 'View Tickets', NULL, 10, NULL, NULL),
(37, 'edit_tickets', 'Edit Tickets', NULL, 10, NULL, NULL),
(38, 'delete_tickets', 'Delete Tickets', NULL, 10, NULL, NULL),
(39, 'add_events', 'Add Events', NULL, 11, NULL, NULL),
(40, 'view_events', 'View Events', NULL, 11, NULL, NULL),
(41, 'edit_events', 'Edit Events', NULL, 11, NULL, NULL),
(42, 'delete_events', 'Delete Events', NULL, 11, NULL, NULL),
(43, 'add_notice', 'Add Notice', NULL, 13, NULL, '2020-04-09 11:24:01'),
(44, 'view_notice', 'View Notice', NULL, 13, NULL, '2020-04-09 11:24:01'),
(45, 'edit_notice', 'Edit Notice', NULL, 13, NULL, '2020-04-09 11:24:01'),
(46, 'delete_notice', 'Delete Notice', NULL, 13, NULL, '2020-04-09 11:24:01'),
(47, 'add_leave', 'Add Leave', NULL, 14, NULL, NULL),
(48, 'view_leave', 'View Leave', NULL, 14, NULL, NULL),
(49, 'edit_leave', 'Edit Leave', NULL, 14, NULL, NULL),
(50, 'delete_leave', 'Delete Leave', NULL, 14, NULL, NULL),
(51, 'add_lead', 'Add Lead', NULL, 15, NULL, NULL),
(52, 'view_lead', 'View Lead', NULL, 15, NULL, NULL),
(53, 'edit_lead', 'Edit Lead', NULL, 15, NULL, NULL),
(54, 'delete_lead', 'Delete Lead', NULL, 15, NULL, NULL),
(55, 'add_holiday', 'Add Holiday', NULL, 16, NULL, NULL),
(56, 'view_holiday', 'View Holiday', NULL, 16, NULL, NULL),
(57, 'edit_holiday', 'Edit Holiday', NULL, 16, NULL, NULL),
(58, 'delete_holiday', 'Delete Holiday', NULL, 16, NULL, NULL),
(59, 'add_product', 'Add Product', NULL, 17, NULL, NULL),
(60, 'view_product', 'View Product', NULL, 17, NULL, NULL),
(61, 'edit_product', 'Edit Product', NULL, 17, NULL, NULL),
(62, 'delete_product', 'Delete Product', NULL, 17, NULL, NULL),
(63, 'add_expenses', 'Add Expenses', NULL, 18, NULL, NULL),
(64, 'view_expenses', 'View Expenses', NULL, 18, NULL, NULL),
(65, 'edit_expenses', 'Edit Expenses', NULL, 18, NULL, NULL),
(66, 'delete_expenses', 'Delete Expenses', NULL, 18, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `taxes` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allow_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `project_summary` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_admin` int(10) UNSIGNED DEFAULT NULL,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `feedback` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `manual_timelog` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disable',
  `client_view_task` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disable',
  `allow_client_notification` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disable',
  `completion_percent` tinyint(4) NOT NULL,
  `calculate_task_progress` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'true',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_budget` double(20,2) DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `hours_allocated` double(8,2) DEFAULT NULL,
  `status` enum('not started','in progress','on hold','canceled','finished') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'in progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `company_id`, `project_name`, `project_summary`, `project_admin`, `start_date`, `deadline`, `notes`, `category_id`, `client_id`, `feedback`, `manual_timelog`, `client_view_task`, `allow_client_notification`, `completion_percent`, `calculate_task_progress`, `created_at`, `updated_at`, `deleted_at`, `project_budget`, `currency_id`, `hours_allocated`, `status`) VALUES
(1, 1, 'Bahana Line Group', '<p>This is project summary</p>', 1, '2020-04-01', '2020-08-31', NULL, 3, NULL, NULL, 'disable', 'disable', 'disable', 0, 'true', '2020-04-09 21:46:10', '2020-04-09 21:47:37', NULL, NULL, NULL, NULL, 'in progress');

-- --------------------------------------------------------

--
-- Table structure for table `project_activity`
--

CREATE TABLE `project_activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `activity` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_activity`
--

INSERT INTO `project_activity` (`id`, `company_id`, `project_id`, `activity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Raymond is added as project member.', '2020-04-09 21:46:10', '2020-04-09 21:46:10'),
(2, 1, 1, 'Bahana Line Group added as new project.', '2020-04-09 21:46:10', '2020-04-09 21:46:10'),
(3, 1, 1, 'New task added to the project.', '2020-04-09 21:47:18', '2020-04-09 21:47:18'),
(4, 1, 1, 'New task added to the project.', '2020-04-09 21:47:18', '2020-04-09 21:47:18'),
(5, 1, 1, 'Cleming is added as project member.', '2020-04-09 21:47:32', '2020-04-09 21:47:32'),
(6, 1, 1, 'Michelle is added as project member.', '2020-04-09 21:47:32', '2020-04-09 21:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `project_category`
--

CREATE TABLE `project_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `category_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_category`
--

INSERT INTO `project_category` (`id`, `company_id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laravel', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(2, 1, 'Java', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(3, 1, 'ERP', '2020-04-09 21:45:37', '2020-04-09 21:45:37');

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

CREATE TABLE `project_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `hashname` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_url` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropbox_link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `external_link_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_link` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `company_id`, `user_id`, `project_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2020-04-09 21:46:10', '2020-04-09 21:46:10'),
(2, 1, 4, 1, '2020-04-09 21:47:32', '2020-04-09 21:47:32'),
(3, 1, 5, 1, '2020-04-09 21:47:32', '2020-04-09 21:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `project_milestones`
--

CREATE TABLE `project_milestones` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `milestone_title` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `summary` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `cost` double(8,2) NOT NULL,
  `status` enum('complete','incomplete') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'incomplete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_created` tinyint(1) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_settings`
--

CREATE TABLE `project_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `send_reminder` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `remind_time` int(11) NOT NULL,
  `remind_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `remind_to` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '["admins","members"]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_settings`
--

INSERT INTO `project_settings` (`id`, `company_id`, `send_reminder`, `remind_time`, `remind_type`, `remind_to`, `created_at`, `updated_at`) VALUES
(1, 1, 'no', 5, 'days', '[\"admins\",\"members\"]', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `project_templates`
--

CREATE TABLE `project_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `project_summary` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `feedback` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_view_task` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disable',
  `allow_client_notification` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disable',
  `manual_timelog` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_template_members`
--

CREATE TABLE `project_template_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_template_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_template_tasks`
--

CREATE TABLE `project_template_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `heading` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_template_id` int(10) UNSIGNED NOT NULL,
  `priority` enum('low','medium','high') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_time_logs`
--

CREATE TABLE `project_time_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `memo` text COLLATE utf8_unicode_ci NOT NULL,
  `total_hours` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_minutes` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edited_by_user` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `lead_id` int(10) UNSIGNED NOT NULL,
  `valid_till` date NOT NULL,
  `sub_total` double(16,2) NOT NULL,
  `total` double(16,2) NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('declined','accepted','waiting') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_convert` tinyint(1) NOT NULL DEFAULT 0,
  `discount_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_items`
--

CREATE TABLE `proposal_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `proposal_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('item','discount','tax') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'item',
  `quantity` double(16,2) NOT NULL,
  `unit_price` double(16,2) NOT NULL,
  `amount` double(16,2) NOT NULL,
  `item_summary` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxes` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purpose_consent`
--

CREATE TABLE `purpose_consent` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purpose_consent_leads`
--

CREATE TABLE `purpose_consent_leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `lead_id` int(10) UNSIGNED NOT NULL,
  `purpose_consent_id` int(10) UNSIGNED NOT NULL,
  `status` enum('agree','disagree') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'agree',
  `ip` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_by_id` int(10) UNSIGNED DEFAULT NULL,
  `additional_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purpose_consent_users`
--

CREATE TABLE `purpose_consent_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `purpose_consent_id` int(10) UNSIGNED NOT NULL,
  `status` enum('agree','disagree') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'agree',
  `ip` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_by_id` int(10) UNSIGNED NOT NULL,
  `additional_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_notification_settings`
--

CREATE TABLE `push_notification_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `onesignal_app_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `onesignal_rest_api_key` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `notification_logo` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `push_notification_settings`
--

INSERT INTO `push_notification_settings` (`id`, `onesignal_app_id`, `onesignal_rest_api_key`, `notification_logo`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 'inactive', '2020-04-09 11:24:00', '2020-04-09 11:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `endpoint` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_token` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `client_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `client_email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_total` double(8,2) NOT NULL,
  `total` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_invoices`
--

CREATE TABLE `razorpay_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `invoice_id` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `subscription_id` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(12,2) UNSIGNED NOT NULL,
  `pay_date` date NOT NULL,
  `next_pay_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_subscriptions`
--

CREATE TABLE `razorpay_subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `subscription_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `razorpay_id` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `razorpay_plan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `removal_requests`
--

CREATE TABLE `removal_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `removal_requests_lead`
--

CREATE TABLE `removal_requests_lead` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `lead_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rest_api_settings`
--

CREATE TABLE `rest_api_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_code` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supported_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rest_api_settings`
--

INSERT INTO `rest_api_settings` (`id`, `purchase_code`, `supported_until`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, '2020-04-11 02:17:34', '2020-04-11 02:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `company_id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'App Administrator', 'Admin is allowed to manage everything of the app.', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(2, 1, 'employee', 'Employee', 'Employee can see tasks and projects assigned to him.', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(3, 1, 'client', 'Client', 'Client can see own tasks and projects.', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(4, 2),
(5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slack_settings`
--

CREATE TABLE `slack_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `slack_webhook` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `slack_logo` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `slack_settings`
--

INSERT INTO `slack_settings` (`id`, `company_id`, `slack_webhook`, `slack_logo`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(2, 1, NULL, NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `smtp_settings`
--

CREATE TABLE `smtp_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `mail_driver` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'smtp.gmail.com',
  `mail_port` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '587',
  `mail_username` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'youremail@gmail.com',
  `mail_password` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'your password',
  `mail_from_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'your name',
  `mail_from_email` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'from@email.com',
  `mail_encryption` enum('tls','ssl') COLLATE utf8_unicode_ci DEFAULT 'tls',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `smtp_settings`
--

INSERT INTO `smtp_settings` (`id`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_from_name`, `mail_from_email`, `mail_encryption`, `created_at`, `updated_at`, `verified`) VALUES
(1, 'mail', 'smtp.gmail.com', '587', 'myemail@gmail.com', 'mypassword', 'froiden', 'from@email.com', 'tls', '2020-04-09 11:23:59', '2020-04-09 11:23:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sticky_notes`
--

CREATE TABLE `sticky_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `note_text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `colour` enum('blue','yellow','red','gray','purple','green') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'blue',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `storage_settings`
--

CREATE TABLE `storage_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filesystem` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local',
  `auth_keys` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'disabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `storage_settings`
--

INSERT INTO `storage_settings` (`id`, `filesystem`, `auth_keys`, `status`, `created_at`, `updated_at`) VALUES
(1, 'local', NULL, 'enabled', '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `stripe_invoices`
--

CREATE TABLE `stripe_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) UNSIGNED NOT NULL,
  `pay_date` date NOT NULL,
  `next_pay_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_setting`
--

CREATE TABLE `stripe_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `api_key` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `webhook_key` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_client_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `stripe_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `razorpay_key` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_webhook_secret` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razorpay_status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'deactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `paypal_mode` enum('sandbox','live') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stripe_setting`
--

INSERT INTO `stripe_setting` (`id`, `api_key`, `api_secret`, `webhook_key`, `paypal_client_id`, `paypal_secret`, `paypal_status`, `stripe_status`, `razorpay_key`, `razorpay_secret`, `razorpay_webhook_secret`, `razorpay_status`, `created_at`, `updated_at`, `paypal_mode`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 'inactive', 'inactive', NULL, NULL, NULL, 'deactive', '2020-04-09 11:24:01', '2020-04-09 11:24:01', 'sandbox');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `stripe_id` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `stripe_plan` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_status` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_tasks`
--

CREATE TABLE `sub_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` enum('incomplete','complete') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'incomplete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taskboard_columns`
--

CREATE TABLE `taskboard_columns` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `column_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `taskboard_columns`
--

INSERT INTO `taskboard_columns` (`id`, `company_id`, `column_name`, `slug`, `label_color`, `priority`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Incomplete', 'incomplete', '#d21010', 1, '2020-04-09 11:23:54', '2020-04-09 11:24:01'),
(2, NULL, 'Completed', 'completed', '#679c0d', 2, '2020-04-09 11:23:56', '2020-04-09 11:24:01'),
(3, 1, 'TODO', 'incomplete', '#d21010', 1, '2020-04-09 11:24:06', '2020-04-09 21:48:09'),
(4, 1, 'DONE', 'completed', '#679c0d', 4, '2020-04-09 11:24:06', '2020-04-09 21:49:08'),
(5, 1, 'IN PROGRESS', 'in_progress', '#ecb652', 2, '2020-04-09 21:48:32', '2020-04-09 21:48:48'),
(6, 1, 'IN REVIEW', 'in_review', '#af95fc', 3, '2020-04-09 21:49:00', '2020-04-09 21:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `heading` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `task_category_id` int(10) UNSIGNED DEFAULT NULL,
  `priority` enum('low','medium','high') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('incomplete','completed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'incomplete',
  `board_column_id` int(10) UNSIGNED DEFAULT 1,
  `column_priority` int(11) NOT NULL,
  `completed_on` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `recurring_task_id` int(10) UNSIGNED DEFAULT NULL,
  `dependent_task_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `milestone_id` int(10) UNSIGNED DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `company_id`, `heading`, `description`, `due_date`, `start_date`, `project_id`, `task_category_id`, `priority`, `status`, `board_column_id`, `column_priority`, `completed_on`, `created_by`, `recurring_task_id`, `dependent_task_id`, `created_at`, `updated_at`, `milestone_id`, `is_private`) VALUES
(1, 1, 'Creating wireframe for absence app', NULL, '2020-04-22', '2020-04-08', 1, 1, 'high', 'incomplete', 3, 1, NULL, 1, NULL, NULL, '2020-04-09 21:47:18', '2020-04-10 01:23:05', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_category`
--

CREATE TABLE `task_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `category_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_category`
--

INSERT INTO `task_category` (`id`, `company_id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Design', '2020-04-09 21:46:36', '2020-04-09 21:46:36');

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

CREATE TABLE `task_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_files`
--

CREATE TABLE `task_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_url` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hashname` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropbox_link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_link_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_history`
--

CREATE TABLE `task_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `sub_task_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `board_column_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_history`
--

INSERT INTO `task_history` (`id`, `task_id`, `sub_task_id`, `user_id`, `details`, `board_column_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'createActivity', 3, '2020-04-09 21:47:18', '2020-04-09 21:47:18'),
(2, 1, NULL, 1, 'updateActivity', 3, '2020-04-10 01:23:05', '2020-04-10 01:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `task_users`
--

CREATE TABLE `task_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_users`
--

INSERT INTO `task_users` (`id`, `task_id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 1, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `rate_percent` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `team_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `company_id`, `team_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Designer', '2020-04-09 21:39:46', '2020-04-09 21:39:46'),
(2, 1, 'Web Developer', '2020-04-09 21:39:57', '2020-04-09 21:39:57'),
(3, 1, 'Mobile Developer', '2020-04-09 21:40:05', '2020-04-09 21:40:05'),
(4, 1, 'Board of Directors', '2020-04-09 21:43:42', '2020-04-09 21:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `rating` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(1, 'theon salvatore', 'Lorem ipsum dolor sit detudzdae amet, rcquisc adipiscing elit.\n                            Aenean amet socada commodo sit.', 5.00, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(2, 'jenna gilbert', 'Lorem ipsum dolor sit detudzdae amet, rcquisc adipiscing elit.\n                            Aenean amet socada commodo sit.', 4.00, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(3, 'Redh gilbert', 'Lorem ipsum dolor sit detudzdae amet, rcquisc adipiscing elit.\n                            Aenean amet socada commodo sit.', 3.00, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(4, 'angela whatson', 'Lorem ipsum dolor sit detudzdae amet, rcquisc adipiscing elit.\n                            Aenean amet socada commodo sit.', 4.00, '2020-04-09 11:24:05', '2020-04-09 11:24:05'),
(5, 'angela whatson', 'Lorem ipsum dolor sit detudzdae amet, rcquisc adipiscing elit.\n                            Aenean amet socada commodo sit.', 2.00, '2020-04-09 11:24:05', '2020-04-09 11:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `theme_settings`
--

CREATE TABLE `theme_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `panel` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `header_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `sidebar_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `sidebar_text_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `link_color` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff',
  `user_css` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `theme_settings`
--

INSERT INTO `theme_settings` (`id`, `company_id`, `panel`, `header_color`, `sidebar_color`, `sidebar_text_color`, `link_color`, `user_css`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', '#ed4040', '#292929', '#cbcbcb', '#ffffff', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(2, 1, 'project_admin', '#5475ed', '#292929', '#cbcbcb', '#ffffff', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(3, 1, 'employee', '#f7c80c', '#292929', '#cbcbcb', '#ffffff', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(4, 1, 'client', '#00c292', '#292929', '#cbcbcb', '#ffffff', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('open','pending','resolved','closed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'medium',
  `agent_id` int(10) UNSIGNED DEFAULT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `type_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_agent_groups`
--

CREATE TABLE `ticket_agent_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `agent_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('enabled','disabled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_channels`
--

CREATE TABLE `ticket_channels` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `channel_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_channels`
--

INSERT INTO `ticket_channels` (`id`, `company_id`, `channel_name`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Email', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(2, NULL, 'Phone', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(3, NULL, 'Twitter', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(4, NULL, 'Facebook', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(5, 1, 'Email', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(6, 1, 'Phone', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(7, 1, 'Twitter', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(8, 1, 'Facebook', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_files`
--

CREATE TABLE `ticket_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ticket_reply_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_url` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hashname` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropbox_link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_link_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_groups`
--

CREATE TABLE `ticket_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `group_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_groups`
--

INSERT INTO `ticket_groups` (`id`, `company_id`, `group_name`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Sales', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(2, NULL, 'Code', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(3, NULL, 'Management', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(4, 1, 'Sales', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(5, 1, 'Code', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(6, 1, 'Management', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_reply_templates`
--

CREATE TABLE `ticket_reply_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `reply_heading` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `reply_text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_tags`
--

CREATE TABLE `ticket_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_tag_list`
--

CREATE TABLE `ticket_tag_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `company_id`, `type`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Question', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(2, NULL, 'Problem', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(3, NULL, 'Incident', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(4, NULL, 'Feature Request', '2020-04-09 11:23:53', '2020-04-09 11:23:53'),
(5, 1, 'Question', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(6, 1, 'Problem', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(7, 1, 'Incident', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(8, 1, 'Feature Request', '2020-04-09 11:24:06', '2020-04-09 11:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `universal_search`
--

CREATE TABLE `universal_search` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `searchable_id` int(11) NOT NULL,
  `module_type` enum('ticket','invoice','notice','proposal','task','creditNote','client','employee','project','estimate','lead') COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `route_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `universal_search`
--

INSERT INTO `universal_search` (`id`, `company_id`, `searchable_id`, `module_type`, `title`, `route_name`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'employee', 'Admin Name', 'admin.employees.show', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(2, 1, 2, 'employee', 'Alexandria Waters', 'admin.employees.show', '2020-04-09 11:24:06', '2020-04-09 11:24:06'),
(3, 1, 4, 'employee', 'Cleming', 'admin.employees.show', '2020-04-09 21:42:08', '2020-04-09 21:42:08'),
(4, 1, 5, 'employee', 'Michelle', 'admin.employees.show', '2020-04-09 21:44:31', '2020-04-09 21:44:31'),
(5, 1, 1, 'project', 'Project: Bahana Line Group', 'admin.projects.show', '2020-04-09 21:46:10', '2020-04-09 21:46:10'),
(6, 1, 1, 'task', 'Task: Creating wireframe for absence app', 'admin.all-tasks.edit', '2020-04-09 21:47:18', '2020-04-09 21:47:18'),
(7, 1, 1, 'task', 'Task Creating wireframe for absence app', 'admin.all-tasks.edit', '2020-04-09 21:47:18', '2020-04-09 21:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','others') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'male',
  `locale` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `login` enum('enable','disable') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'enable',
  `onesignal_player_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `super_admin` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `email_verification_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `name`, `email`, `password`, `remember_token`, `image`, `mobile`, `gender`, `locale`, `status`, `login`, `onesignal_player_id`, `created_at`, `updated_at`, `super_admin`, `email_verification_code`) VALUES
(1, 1, 'Raymond', 'admin@example.com', '$2y$10$qAhigS/3aX8NVyTX7Mtz/.sTpy4AJLy/OxyGRS6eAUL9HoGb.bLI.', NULL, NULL, NULL, 'male', 'en', 'active', 'enable', NULL, '2020-04-09 11:24:06', '2020-04-09 21:43:22', '0', NULL),
(2, 1, 'Alexandria Waters', 'armstrong.pink@example.net', '$2y$10$ElbS6msAUr3ox2JLmPoUKOUVYuYiJx0Z08jqlBGS/8RhkVssGZ3AK', NULL, NULL, NULL, 'male', 'en', 'active', 'enable', NULL, '2020-04-09 11:24:06', '2020-04-09 11:24:06', '0', NULL),
(3, NULL, 'Super Admin', 'hello@hei.co.id', '$2y$10$kBuzrQVD6nDLOP.inCqVp.sQqke0Klr7Dx0hDu9JEe.N0hvglfkRO', 'I5w2beJ0gMc8gsoKjSH0UZZksI1ZTscePTXWVvp1DU7gXITeBOWOqbAD8MZR', NULL, NULL, '', 'en', 'active', 'enable', NULL, '2020-04-09 11:24:06', '2020-04-09 11:27:28', '1', NULL),
(4, 1, 'Cleming', 'cleming@gmail.com', '$2y$10$ulP4kN7d143ta5WQKL09Ie08.y9mCTWh5tWTs0O4yxX3R9BYCp99u', NULL, NULL, NULL, 'male', 'en', 'active', 'enable', NULL, '2020-04-09 21:42:08', '2020-04-09 21:42:08', '0', NULL),
(5, 1, 'Michelle', 'michelle@gmail.com', '$2y$10$0JCdLz0RE2X2cgPudAEXGOCQwjqAfo9niy5PYbjpAGvkULnOMdEVy', NULL, NULL, NULL, 'male', 'en', 'active', 'enable', NULL, '2020-04-09 21:44:31', '2020-04-09 21:44:31', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_chat`
--

CREATE TABLE `users_chat` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_one` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `message` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `from` int(10) UNSIGNED DEFAULT NULL,
  `to` int(10) UNSIGNED DEFAULT NULL,
  `message_seen` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `activity` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accept_estimates`
--
ALTER TABLE `accept_estimates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accept_estimates_company_id_foreign` (`company_id`),
  ADD KEY `accept_estimates_estimate_id_foreign` (`estimate_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`),
  ADD KEY `attendances_company_id_foreign` (`company_id`);

--
-- Indexes for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_contacts_user_id_foreign` (`user_id`),
  ADD KEY `client_contacts_company_id_foreign` (`company_id`);

--
-- Indexes for table `client_details`
--
ALTER TABLE `client_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_details_user_id_foreign` (`user_id`),
  ADD KEY `client_details_company_id_foreign` (`company_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_settings_last_updated_by_foreign` (`last_updated_by`),
  ADD KEY `companies_package_id_foreign` (`package_id`),
  ADD KEY `companies_currency_id_foreign` (`currency_id`),
  ADD KEY `companies_default_task_status_foreign` (`default_task_status`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contracts_company_id_foreign` (`company_id`),
  ADD KEY `contracts_client_id_foreign` (`client_id`),
  ADD KEY `contracts_contract_type_id_foreign` (`contract_type_id`);

--
-- Indexes for table `contract_discussions`
--
ALTER TABLE `contract_discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_discussions_company_id_foreign` (`company_id`),
  ADD KEY `contract_discussions_contract_id_foreign` (`contract_id`),
  ADD KEY `contract_discussions_from_foreign` (`from`);

--
-- Indexes for table `contract_renews`
--
ALTER TABLE `contract_renews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_renews_company_id_foreign` (`company_id`),
  ADD KEY `contract_renews_renewed_by_foreign` (`renewed_by`),
  ADD KEY `contract_renews_contract_id_foreign` (`contract_id`);

--
-- Indexes for table `contract_signs`
--
ALTER TABLE `contract_signs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_signs_company_id_foreign` (`company_id`),
  ADD KEY `contract_signs_contract_id_foreign` (`contract_id`);

--
-- Indexes for table `contract_types`
--
ALTER TABLE `contract_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_types_company_id_foreign` (`company_id`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_user_one_foreign` (`user_one`),
  ADD KEY `conversation_user_two_foreign` (`user_two`);

--
-- Indexes for table `conversation_reply`
--
ALTER TABLE `conversation_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_reply_conversation_id_foreign` (`conversation_id`),
  ADD KEY `conversation_reply_user_id_foreign` (`user_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countries_iso_alpha2_index` (`iso_alpha2`),
  ADD KEY `countries_iso_alpha3_index` (`iso_alpha3`);

--
-- Indexes for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_notes_company_id_foreign` (`company_id`),
  ADD KEY `credit_notes_project_id_foreign` (`project_id`),
  ADD KEY `credit_notes_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `credit_notes_invoice`
--
ALTER TABLE `credit_notes_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_note_items`
--
ALTER TABLE `credit_note_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_note_items_credit_note_id_foreign` (`credit_note_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currencies_company_id_foreign` (`company_id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_fields_custom_field_group_id_foreign` (`custom_field_group_id`);

--
-- Indexes for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_fields_data_custom_field_id_foreign` (`custom_field_id`),
  ADD KEY `custom_fields_data_model_index` (`model`);

--
-- Indexes for table `custom_field_groups`
--
ALTER TABLE `custom_field_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_field_groups_model_index` (`model`),
  ADD KEY `custom_field_groups_company_id_foreign` (`company_id`);

--
-- Indexes for table `dashboard_widgets`
--
ALTER TABLE `dashboard_widgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dashboard_widgets_company_id_foreign` (`company_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designations_company_id_foreign` (`company_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `email_notification_settings`
--
ALTER TABLE `email_notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_notification_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `employee_details`
--
ALTER TABLE `employee_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_details_slack_username_unique` (`slack_username`),
  ADD KEY `employee_details_user_id_foreign` (`user_id`),
  ADD KEY `employee_details_company_id_foreign` (`company_id`),
  ADD KEY `employee_details_designation_id_foreign` (`designation_id`),
  ADD KEY `employee_details_department_id_foreign` (`department_id`);

--
-- Indexes for table `employee_docs`
--
ALTER TABLE `employee_docs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_docs_user_id_foreign` (`user_id`),
  ADD KEY `employee_docs_company_id_foreign` (`company_id`);

--
-- Indexes for table `employee_skills`
--
ALTER TABLE `employee_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_skills_user_id_foreign` (`user_id`),
  ADD KEY `employee_skills_skill_id_foreign` (`skill_id`);

--
-- Indexes for table `employee_teams`
--
ALTER TABLE `employee_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_teams_team_id_foreign` (`team_id`),
  ADD KEY `employee_teams_user_id_foreign` (`user_id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estimates_client_id_foreign` (`client_id`),
  ADD KEY `estimates_currency_id_foreign` (`currency_id`),
  ADD KEY `estimates_company_id_foreign` (`company_id`);

--
-- Indexes for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estimate_items_estimate_id_foreign` (`estimate_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_company_id_foreign` (`company_id`);

--
-- Indexes for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_attendees_user_id_foreign` (`user_id`),
  ADD KEY `event_attendees_event_id_foreign` (`event_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_currency_id_foreign` (`currency_id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`),
  ADD KEY `expenses_company_id_foreign` (`company_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_faq_category_id_foreign` (`faq_category_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_storage_settings`
--
ALTER TABLE `file_storage_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_storage_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `footer_menu`
--
ALTER TABLE `footer_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_clients`
--
ALTER TABLE `front_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_details`
--
ALTER TABLE `front_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_faqs`
--
ALTER TABLE `front_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_menu_buttons`
--
ALTER TABLE `front_menu_buttons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gdpr_settings`
--
ALTER TABLE `gdpr_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gdpr_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `global_currencies`
--
ALTER TABLE `global_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `global_settings_last_updated_by_foreign` (`last_updated_by`),
  ADD KEY `global_settings_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `holidays_company_id_foreign` (`company_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_project_id_foreign` (`project_id`),
  ADD KEY `invoices_currency_id_foreign` (`currency_id`),
  ADD KEY `invoices_company_id_foreign` (`company_id`),
  ADD KEY `invoices_estimate_id_foreign` (`estimate_id`),
  ADD KEY `invoices_client_id_foreign` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issues_user_id_foreign` (`user_id`),
  ADD KEY `issues_project_id_foreign` (`project_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `language_settings`
--
ALTER TABLE `language_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_company_id_foreign` (`company_id`),
  ADD KEY `leads_agent_id_foreign` (`agent_id`);

--
-- Indexes for table `lead_agents`
--
ALTER TABLE `lead_agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_agents_company_id_foreign` (`company_id`),
  ADD KEY `lead_agents_user_id_foreign` (`user_id`);

--
-- Indexes for table `lead_files`
--
ALTER TABLE `lead_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_files_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_files_user_id_foreign` (`user_id`),
  ADD KEY `lead_files_company_id_foreign` (`company_id`);

--
-- Indexes for table `lead_follow_up`
--
ALTER TABLE `lead_follow_up`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_follow_up_lead_id_foreign` (`lead_id`);

--
-- Indexes for table `lead_sources`
--
ALTER TABLE `lead_sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_sources_company_id_foreign` (`company_id`);

--
-- Indexes for table `lead_status`
--
ALTER TABLE `lead_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_status_company_id_foreign` (`company_id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_user_id_foreign` (`user_id`),
  ADD KEY `leaves_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leaves_company_id_foreign` (`company_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_types_company_id_foreign` (`company_id`);

--
-- Indexes for table `licences`
--
ALTER TABLE `licences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licences_company_id_foreign` (`company_id`),
  ADD KEY `licences_package_id_foreign` (`package_id`);

--
-- Indexes for table `log_time_for`
--
ALTER TABLE `log_time_for`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_time_for_company_id_foreign` (`company_id`);

--
-- Indexes for table `ltm_translations`
--
ALTER TABLE `ltm_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_settings`
--
ALTER TABLE `message_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_settings`
--
ALTER TABLE `module_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notices_company_id_foreign` (`company_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `offline_invoices`
--
ALTER TABLE `offline_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offline_invoices_company_id_foreign` (`company_id`),
  ADD KEY `offline_invoices_package_id_foreign` (`package_id`),
  ADD KEY `offline_invoices_offline_method_id_foreign` (`offline_method_id`);

--
-- Indexes for table `offline_invoice_payments`
--
ALTER TABLE `offline_invoice_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offline_invoice_payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `offline_invoice_payments_client_id_foreign` (`client_id`),
  ADD KEY `offline_invoice_payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `offline_payment_methods`
--
ALTER TABLE `offline_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offline_payment_methods_company_id_foreign` (`company_id`);

--
-- Indexes for table `offline_plan_changes`
--
ALTER TABLE `offline_plan_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offline_plan_changes_company_id_foreign` (`company_id`),
  ADD KEY `offline_plan_changes_package_id_foreign` (`package_id`),
  ADD KEY `offline_plan_changes_invoice_id_foreign` (`invoice_id`),
  ADD KEY `offline_plan_changes_offline_method_id_foreign` (`offline_method_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packages_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `package_settings`
--
ALTER TABLE `package_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD UNIQUE KEY `payments_plan_id_unique` (`plan_id`),
  ADD UNIQUE KEY `payments_event_id_unique` (`event_id`),
  ADD KEY `payments_currency_id_foreign` (`currency_id`),
  ADD KEY `payments_project_id_foreign` (`project_id`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payments_company_id_foreign` (`company_id`),
  ADD KEY `payments_offline_method_id_foreign` (`offline_method_id`);

--
-- Indexes for table `payment_gateway_credentials`
--
ALTER TABLE `payment_gateway_credentials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_gateway_credentials_company_id_foreign` (`company_id`);

--
-- Indexes for table `paypal_invoices`
--
ALTER TABLE `paypal_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paypal_invoices_company_id_foreign` (`company_id`),
  ADD KEY `paypal_invoices_currency_id_foreign` (`currency_id`),
  ADD KEY `paypal_invoices_package_id_foreign` (`package_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD KEY `permissions_module_id_foreign` (`module_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_company_id_foreign` (`company_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_category_id_foreign` (`category_id`),
  ADD KEY `projects_client_id_foreign` (`client_id`),
  ADD KEY `projects_project_admin_foreign` (`project_admin`),
  ADD KEY `projects_company_id_foreign` (`company_id`),
  ADD KEY `projects_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `project_activity`
--
ALTER TABLE `project_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_activity_project_id_foreign` (`project_id`),
  ADD KEY `project_activity_company_id_foreign` (`company_id`);

--
-- Indexes for table `project_category`
--
ALTER TABLE `project_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_category_company_id_foreign` (`company_id`);

--
-- Indexes for table `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_files_user_id_foreign` (`user_id`),
  ADD KEY `project_files_project_id_foreign` (`project_id`),
  ADD KEY `project_files_company_id_foreign` (`company_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_members_project_id_foreign` (`project_id`),
  ADD KEY `project_members_user_id_foreign` (`user_id`),
  ADD KEY `project_members_company_id_foreign` (`company_id`);

--
-- Indexes for table `project_milestones`
--
ALTER TABLE `project_milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_milestones_company_id_foreign` (`company_id`),
  ADD KEY `project_milestones_project_id_foreign` (`project_id`),
  ADD KEY `project_milestones_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `project_settings`
--
ALTER TABLE `project_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `project_templates`
--
ALTER TABLE `project_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_templates_category_id_foreign` (`category_id`),
  ADD KEY `project_templates_client_id_foreign` (`client_id`),
  ADD KEY `project_templates_company_id_foreign` (`company_id`);

--
-- Indexes for table `project_template_members`
--
ALTER TABLE `project_template_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_template_members_user_id_foreign` (`user_id`),
  ADD KEY `project_template_members_project_template_id_foreign` (`project_template_id`);

--
-- Indexes for table `project_template_tasks`
--
ALTER TABLE `project_template_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_template_tasks_user_id_foreign` (`user_id`),
  ADD KEY `project_template_tasks_project_template_id_foreign` (`project_template_id`);

--
-- Indexes for table `project_time_logs`
--
ALTER TABLE `project_time_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_time_logs_edited_by_user_foreign` (`edited_by_user`),
  ADD KEY `project_time_logs_project_id_foreign` (`project_id`),
  ADD KEY `project_time_logs_user_id_foreign` (`user_id`),
  ADD KEY `project_time_logs_task_id_foreign` (`task_id`),
  ADD KEY `project_time_logs_company_id_foreign` (`company_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposals_lead_id_foreign` (`lead_id`),
  ADD KEY `proposals_currency_id_foreign` (`currency_id`),
  ADD KEY `proposals_company_id_foreign` (`company_id`);

--
-- Indexes for table `proposal_items`
--
ALTER TABLE `proposal_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_items_proposal_id_foreign` (`proposal_id`),
  ADD KEY `proposal_items_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `purpose_consent`
--
ALTER TABLE `purpose_consent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purpose_consent_company_id_foreign` (`company_id`);

--
-- Indexes for table `purpose_consent_leads`
--
ALTER TABLE `purpose_consent_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purpose_consent_leads_lead_id_foreign` (`lead_id`),
  ADD KEY `purpose_consent_leads_purpose_consent_id_foreign` (`purpose_consent_id`),
  ADD KEY `purpose_consent_leads_updated_by_id_foreign` (`updated_by_id`);

--
-- Indexes for table `purpose_consent_users`
--
ALTER TABLE `purpose_consent_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purpose_consent_users_client_id_foreign` (`client_id`),
  ADD KEY `purpose_consent_users_purpose_consent_id_foreign` (`purpose_consent_id`),
  ADD KEY `purpose_consent_users_updated_by_id_foreign` (`updated_by_id`);

--
-- Indexes for table `push_notification_settings`
--
ALTER TABLE `push_notification_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  ADD KEY `push_subscriptions_user_id_index` (`user_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_items_quotation_id_foreign` (`quotation_id`);

--
-- Indexes for table `razorpay_invoices`
--
ALTER TABLE `razorpay_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `razorpay_invoices_company_id_foreign` (`company_id`),
  ADD KEY `razorpay_invoices_package_id_foreign` (`package_id`);

--
-- Indexes for table `razorpay_subscriptions`
--
ALTER TABLE `razorpay_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `removal_requests`
--
ALTER TABLE `removal_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `removal_requests_company_id_foreign` (`company_id`),
  ADD KEY `removal_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `removal_requests_lead`
--
ALTER TABLE `removal_requests_lead`
  ADD PRIMARY KEY (`id`),
  ADD KEY `removal_requests_lead_company_id_foreign` (`company_id`),
  ADD KEY `removal_requests_lead_lead_id_foreign` (`lead_id`);

--
-- Indexes for table `rest_api_settings`
--
ALTER TABLE `rest_api_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_company_id_foreign` (`company_id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skills_company_id_foreign` (`company_id`);

--
-- Indexes for table `slack_settings`
--
ALTER TABLE `slack_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slack_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sticky_notes`
--
ALTER TABLE `sticky_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sticky_notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `storage_settings`
--
ALTER TABLE `storage_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stripe_invoices`
--
ALTER TABLE `stripe_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stripe_invoices_company_id_foreign` (`company_id`),
  ADD KEY `stripe_invoices_package_id_foreign` (`package_id`);

--
-- Indexes for table `stripe_setting`
--
ALTER TABLE `stripe_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_tasks_task_id_foreign` (`task_id`);

--
-- Indexes for table `taskboard_columns`
--
ALTER TABLE `taskboard_columns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taskboard_columns_company_id_foreign` (`company_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_board_column_id_foreign` (`board_column_id`),
  ADD KEY `tasks_company_id_foreign` (`company_id`),
  ADD KEY `tasks_task_category_id_foreign` (`task_category_id`),
  ADD KEY `tasks_created_by_foreign` (`created_by`),
  ADD KEY `tasks_milestone_id_foreign` (`milestone_id`),
  ADD KEY `tasks_recurring_task_id_foreign` (`recurring_task_id`),
  ADD KEY `tasks_dependent_task_id_foreign` (`dependent_task_id`);

--
-- Indexes for table `task_category`
--
ALTER TABLE `task_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_category_company_id_foreign` (`company_id`);

--
-- Indexes for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_comments_user_id_foreign` (`user_id`),
  ADD KEY `task_comments_task_id_foreign` (`task_id`);

--
-- Indexes for table `task_files`
--
ALTER TABLE `task_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_files_company_id_foreign` (`company_id`),
  ADD KEY `task_files_user_id_foreign` (`user_id`),
  ADD KEY `task_files_task_id_foreign` (`task_id`);

--
-- Indexes for table `task_history`
--
ALTER TABLE `task_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_history_task_id_foreign` (`task_id`),
  ADD KEY `task_history_sub_task_id_foreign` (`sub_task_id`),
  ADD KEY `task_history_user_id_foreign` (`user_id`),
  ADD KEY `task_history_board_column_id_foreign` (`board_column_id`);

--
-- Indexes for table `task_users`
--
ALTER TABLE `task_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_users_task_id_foreign` (`task_id`),
  ADD KEY `task_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taxes_company_id_foreign` (`company_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teams_company_id_foreign` (`company_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theme_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_user_id_foreign` (`user_id`),
  ADD KEY `tickets_agent_id_foreign` (`agent_id`),
  ADD KEY `tickets_channel_id_foreign` (`channel_id`),
  ADD KEY `tickets_type_id_foreign` (`type_id`),
  ADD KEY `tickets_company_id_foreign` (`company_id`);

--
-- Indexes for table `ticket_agent_groups`
--
ALTER TABLE `ticket_agent_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_agent_groups_agent_id_foreign` (`agent_id`),
  ADD KEY `ticket_agent_groups_group_id_foreign` (`group_id`),
  ADD KEY `ticket_agent_groups_company_id_foreign` (`company_id`);

--
-- Indexes for table `ticket_channels`
--
ALTER TABLE `ticket_channels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_channels_company_id_foreign` (`company_id`);

--
-- Indexes for table `ticket_files`
--
ALTER TABLE `ticket_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_files_company_id_foreign` (`company_id`),
  ADD KEY `ticket_files_user_id_foreign` (`user_id`),
  ADD KEY `ticket_files_ticket_reply_id_foreign` (`ticket_reply_id`);

--
-- Indexes for table `ticket_groups`
--
ALTER TABLE `ticket_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_groups_company_id_foreign` (`company_id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `ticket_reply_templates`
--
ALTER TABLE `ticket_reply_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_reply_templates_company_id_foreign` (`company_id`);

--
-- Indexes for table `ticket_tags`
--
ALTER TABLE `ticket_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_tags_tag_id_foreign` (`tag_id`),
  ADD KEY `ticket_tags_ticket_id_foreign` (`ticket_id`);

--
-- Indexes for table `ticket_tag_list`
--
ALTER TABLE `ticket_tag_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_types_company_id_foreign` (`company_id`);

--
-- Indexes for table `universal_search`
--
ALTER TABLE `universal_search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `universal_search_company_id_foreign` (`company_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_company_id_foreign` (`company_id`);

--
-- Indexes for table `users_chat`
--
ALTER TABLE `users_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_chat_user_one_foreign` (`user_one`),
  ADD KEY `users_chat_user_id_foreign` (`user_id`),
  ADD KEY `users_chat_from_foreign` (`from`),
  ADD KEY `users_chat_to_foreign` (`to`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activities_user_id_foreign` (`user_id`),
  ADD KEY `user_activities_company_id_foreign` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accept_estimates`
--
ALTER TABLE `accept_estimates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `client_contacts`
--
ALTER TABLE `client_contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_details`
--
ALTER TABLE `client_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_discussions`
--
ALTER TABLE `contract_discussions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_renews`
--
ALTER TABLE `contract_renews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_signs`
--
ALTER TABLE `contract_signs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_types`
--
ALTER TABLE `contract_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation_reply`
--
ALTER TABLE `conversation_reply`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_notes_invoice`
--
ALTER TABLE `credit_notes_invoice`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_note_items`
--
ALTER TABLE `credit_note_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_groups`
--
ALTER TABLE `custom_field_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dashboard_widgets`
--
ALTER TABLE `dashboard_widgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_notification_settings`
--
ALTER TABLE `email_notification_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_docs`
--
ALTER TABLE `employee_docs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_skills`
--
ALTER TABLE `employee_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_teams`
--
ALTER TABLE `employee_teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_items`
--
ALTER TABLE `estimate_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_attendees`
--
ALTER TABLE `event_attendees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `file_storage_settings`
--
ALTER TABLE `file_storage_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_menu`
--
ALTER TABLE `footer_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_clients`
--
ALTER TABLE `front_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `front_details`
--
ALTER TABLE `front_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `front_faqs`
--
ALTER TABLE `front_faqs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `front_menu_buttons`
--
ALTER TABLE `front_menu_buttons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gdpr_settings`
--
ALTER TABLE `gdpr_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `global_currencies`
--
ALTER TABLE `global_currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `global_settings`
--
ALTER TABLE `global_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_settings`
--
ALTER TABLE `language_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_agents`
--
ALTER TABLE `lead_agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_files`
--
ALTER TABLE `lead_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_follow_up`
--
ALTER TABLE `lead_follow_up`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_sources`
--
ALTER TABLE `lead_sources`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lead_status`
--
ALTER TABLE `lead_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `licences`
--
ALTER TABLE `licences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_time_for`
--
ALTER TABLE `log_time_for`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ltm_translations`
--
ALTER TABLE `ltm_translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1853;

--
-- AUTO_INCREMENT for table `message_settings`
--
ALTER TABLE `message_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=420;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `module_settings`
--
ALTER TABLE `module_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offline_invoices`
--
ALTER TABLE `offline_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offline_invoice_payments`
--
ALTER TABLE `offline_invoice_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offline_payment_methods`
--
ALTER TABLE `offline_payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `offline_plan_changes`
--
ALTER TABLE `offline_plan_changes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `package_settings`
--
ALTER TABLE `package_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateway_credentials`
--
ALTER TABLE `payment_gateway_credentials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `paypal_invoices`
--
ALTER TABLE `paypal_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_activity`
--
ALTER TABLE `project_activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_category`
--
ALTER TABLE `project_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_milestones`
--
ALTER TABLE `project_milestones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_settings`
--
ALTER TABLE `project_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_templates`
--
ALTER TABLE `project_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_template_members`
--
ALTER TABLE `project_template_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_template_tasks`
--
ALTER TABLE `project_template_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_time_logs`
--
ALTER TABLE `project_time_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_items`
--
ALTER TABLE `proposal_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purpose_consent`
--
ALTER TABLE `purpose_consent`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purpose_consent_leads`
--
ALTER TABLE `purpose_consent_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purpose_consent_users`
--
ALTER TABLE `purpose_consent_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_notification_settings`
--
ALTER TABLE `push_notification_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `razorpay_invoices`
--
ALTER TABLE `razorpay_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `razorpay_subscriptions`
--
ALTER TABLE `razorpay_subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `removal_requests`
--
ALTER TABLE `removal_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `removal_requests_lead`
--
ALTER TABLE `removal_requests_lead`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rest_api_settings`
--
ALTER TABLE `rest_api_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slack_settings`
--
ALTER TABLE `slack_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sticky_notes`
--
ALTER TABLE `sticky_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `storage_settings`
--
ALTER TABLE `storage_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stripe_invoices`
--
ALTER TABLE `stripe_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_setting`
--
ALTER TABLE `stripe_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taskboard_columns`
--
ALTER TABLE `taskboard_columns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_category`
--
ALTER TABLE `task_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_files`
--
ALTER TABLE `task_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_history`
--
ALTER TABLE `task_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `task_users`
--
ALTER TABLE `task_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `theme_settings`
--
ALTER TABLE `theme_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_agent_groups`
--
ALTER TABLE `ticket_agent_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_channels`
--
ALTER TABLE `ticket_channels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ticket_files`
--
ALTER TABLE `ticket_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_groups`
--
ALTER TABLE `ticket_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_reply_templates`
--
ALTER TABLE `ticket_reply_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_tags`
--
ALTER TABLE `ticket_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_tag_list`
--
ALTER TABLE `ticket_tag_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `universal_search`
--
ALTER TABLE `universal_search`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_chat`
--
ALTER TABLE `users_chat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accept_estimates`
--
ALTER TABLE `accept_estimates`
  ADD CONSTRAINT `accept_estimates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accept_estimates_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  ADD CONSTRAINT `attendance_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD CONSTRAINT `client_contacts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `client_contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_details`
--
ALTER TABLE `client_details`
  ADD CONSTRAINT `client_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `client_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `companies_default_task_status_foreign` FOREIGN KEY (`default_task_status`) REFERENCES `taskboard_columns` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `companies_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `organisation_settings_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contracts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contracts_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contract_discussions`
--
ALTER TABLE `contract_discussions`
  ADD CONSTRAINT `contract_discussions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contract_discussions_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contract_discussions_from_foreign` FOREIGN KEY (`from`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contract_renews`
--
ALTER TABLE `contract_renews`
  ADD CONSTRAINT `contract_renews_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contract_renews_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contract_renews_renewed_by_foreign` FOREIGN KEY (`renewed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contract_signs`
--
ALTER TABLE `contract_signs`
  ADD CONSTRAINT `contract_signs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contract_signs_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contract_types`
--
ALTER TABLE `contract_types`
  ADD CONSTRAINT `contract_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `conversation_user_one_foreign` FOREIGN KEY (`user_one`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conversation_user_two_foreign` FOREIGN KEY (`user_two`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conversation_reply`
--
ALTER TABLE `conversation_reply`
  ADD CONSTRAINT `conversation_reply_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conversation_reply_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD CONSTRAINT `credit_notes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `credit_notes_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `credit_notes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `credit_note_items`
--
ALTER TABLE `credit_note_items`
  ADD CONSTRAINT `credit_note_items_credit_note_id_foreign` FOREIGN KEY (`credit_note_id`) REFERENCES `credit_notes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `currencies`
--
ALTER TABLE `currencies`
  ADD CONSTRAINT `currencies_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD CONSTRAINT `custom_fields_custom_field_group_id_foreign` FOREIGN KEY (`custom_field_group_id`) REFERENCES `custom_field_groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  ADD CONSTRAINT `custom_fields_data_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `custom_field_groups`
--
ALTER TABLE `custom_field_groups`
  ADD CONSTRAINT `custom_field_groups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dashboard_widgets`
--
ALTER TABLE `dashboard_widgets`
  ADD CONSTRAINT `dashboard_widgets_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `email_notification_settings`
--
ALTER TABLE `email_notification_settings`
  ADD CONSTRAINT `email_notification_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_details`
--
ALTER TABLE `employee_details`
  ADD CONSTRAINT `employee_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_details_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_details_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_docs`
--
ALTER TABLE `employee_docs`
  ADD CONSTRAINT `employee_docs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_docs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_skills`
--
ALTER TABLE `employee_skills`
  ADD CONSTRAINT `employee_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_skills_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_teams`
--
ALTER TABLE `employee_teams`
  ADD CONSTRAINT `employee_teams_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_teams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `estimates`
--
ALTER TABLE `estimates`
  ADD CONSTRAINT `estimates_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estimates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estimates_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD CONSTRAINT `estimate_items_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD CONSTRAINT `event_attendees_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_attendees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_faq_category_id_foreign` FOREIGN KEY (`faq_category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `file_storage_settings`
--
ALTER TABLE `file_storage_settings`
  ADD CONSTRAINT `file_storage_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gdpr_settings`
--
ALTER TABLE `gdpr_settings`
  ADD CONSTRAINT `gdpr_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD CONSTRAINT `global_settings_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `global_currencies` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `global_settings_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `holidays_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  ADD CONSTRAINT `invoice_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `lead_agents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lead_agents`
--
ALTER TABLE `lead_agents`
  ADD CONSTRAINT `lead_agents_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lead_agents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lead_files`
--
ALTER TABLE `lead_files`
  ADD CONSTRAINT `lead_files_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lead_files_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lead_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lead_follow_up`
--
ALTER TABLE `lead_follow_up`
  ADD CONSTRAINT `lead_follow_up_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lead_sources`
--
ALTER TABLE `lead_sources`
  ADD CONSTRAINT `lead_sources_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lead_status`
--
ALTER TABLE `lead_status`
  ADD CONSTRAINT `lead_status_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leaves_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD CONSTRAINT `leave_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `licences`
--
ALTER TABLE `licences`
  ADD CONSTRAINT `licences_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `licences_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `log_time_for`
--
ALTER TABLE `log_time_for`
  ADD CONSTRAINT `log_time_for_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message_settings`
--
ALTER TABLE `message_settings`
  ADD CONSTRAINT `message_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_settings`
--
ALTER TABLE `module_settings`
  ADD CONSTRAINT `module_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offline_invoices`
--
ALTER TABLE `offline_invoices`
  ADD CONSTRAINT `offline_invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_invoices_offline_method_id_foreign` FOREIGN KEY (`offline_method_id`) REFERENCES `offline_payment_methods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_invoices_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offline_invoice_payments`
--
ALTER TABLE `offline_invoice_payments`
  ADD CONSTRAINT `offline_invoice_payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_invoice_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_invoice_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `offline_payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offline_payment_methods`
--
ALTER TABLE `offline_payment_methods`
  ADD CONSTRAINT `offline_payment_methods_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offline_plan_changes`
--
ALTER TABLE `offline_plan_changes`
  ADD CONSTRAINT `offline_plan_changes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_plan_changes_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `offline_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_plan_changes_offline_method_id_foreign` FOREIGN KEY (`offline_method_id`) REFERENCES `offline_payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_plan_changes_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `global_currencies` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_offline_method_id_foreign` FOREIGN KEY (`offline_method_id`) REFERENCES `offline_payment_methods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_gateway_credentials`
--
ALTER TABLE `payment_gateway_credentials`
  ADD CONSTRAINT `payment_gateway_credentials_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paypal_invoices`
--
ALTER TABLE `paypal_invoices`
  ADD CONSTRAINT `paypal_invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paypal_invoices_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `project_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_project_admin_foreign` FOREIGN KEY (`project_admin`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `project_activity`
--
ALTER TABLE `project_activity`
  ADD CONSTRAINT `project_activity_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_activity_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_category`
--
ALTER TABLE `project_category`
  ADD CONSTRAINT `project_category_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_files`
--
ALTER TABLE `project_files`
  ADD CONSTRAINT `project_files_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_files_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_milestones`
--
ALTER TABLE `project_milestones`
  ADD CONSTRAINT `project_milestones_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_milestones_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_settings`
--
ALTER TABLE `project_settings`
  ADD CONSTRAINT `project_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_templates`
--
ALTER TABLE `project_templates`
  ADD CONSTRAINT `project_templates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `project_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `project_templates_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `project_templates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_template_members`
--
ALTER TABLE `project_template_members`
  ADD CONSTRAINT `project_template_members_project_template_id_foreign` FOREIGN KEY (`project_template_id`) REFERENCES `project_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_template_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_template_tasks`
--
ALTER TABLE `project_template_tasks`
  ADD CONSTRAINT `project_template_tasks_project_template_id_foreign` FOREIGN KEY (`project_template_id`) REFERENCES `project_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_template_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_time_logs`
--
ALTER TABLE `project_time_logs`
  ADD CONSTRAINT `project_time_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_time_logs_edited_by_user_foreign` FOREIGN KEY (`edited_by_user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `project_time_logs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_time_logs_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_time_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proposals_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proposals_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `proposal_items`
--
ALTER TABLE `proposal_items`
  ADD CONSTRAINT `proposal_items_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proposal_items_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purpose_consent`
--
ALTER TABLE `purpose_consent`
  ADD CONSTRAINT `purpose_consent_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purpose_consent_leads`
--
ALTER TABLE `purpose_consent_leads`
  ADD CONSTRAINT `purpose_consent_leads_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purpose_consent_leads_purpose_consent_id_foreign` FOREIGN KEY (`purpose_consent_id`) REFERENCES `purpose_consent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purpose_consent_leads_updated_by_id_foreign` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purpose_consent_users`
--
ALTER TABLE `purpose_consent_users`
  ADD CONSTRAINT `purpose_consent_users_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purpose_consent_users_purpose_consent_id_foreign` FOREIGN KEY (`purpose_consent_id`) REFERENCES `purpose_consent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purpose_consent_users_updated_by_id_foreign` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD CONSTRAINT `push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `razorpay_invoices`
--
ALTER TABLE `razorpay_invoices`
  ADD CONSTRAINT `razorpay_invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `razorpay_invoices_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `removal_requests`
--
ALTER TABLE `removal_requests`
  ADD CONSTRAINT `removal_requests_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `removal_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `removal_requests_lead`
--
ALTER TABLE `removal_requests_lead`
  ADD CONSTRAINT `removal_requests_lead_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `removal_requests_lead_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `slack_settings`
--
ALTER TABLE `slack_settings`
  ADD CONSTRAINT `slack_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sticky_notes`
--
ALTER TABLE `sticky_notes`
  ADD CONSTRAINT `sticky_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stripe_invoices`
--
ALTER TABLE `stripe_invoices`
  ADD CONSTRAINT `stripe_invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stripe_invoices_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  ADD CONSTRAINT `sub_tasks_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `taskboard_columns`
--
ALTER TABLE `taskboard_columns`
  ADD CONSTRAINT `taskboard_columns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_board_column_id_foreign` FOREIGN KEY (`board_column_id`) REFERENCES `taskboard_columns` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_dependent_task_id_foreign` FOREIGN KEY (`dependent_task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_recurring_task_id_foreign` FOREIGN KEY (`recurring_task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_task_category_id_foreign` FOREIGN KEY (`task_category_id`) REFERENCES `task_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_category`
--
ALTER TABLE `task_category`
  ADD CONSTRAINT `task_category_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD CONSTRAINT `task_comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_files`
--
ALTER TABLE `task_files`
  ADD CONSTRAINT `task_files_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_files_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_history`
--
ALTER TABLE `task_history`
  ADD CONSTRAINT `task_history_board_column_id_foreign` FOREIGN KEY (`board_column_id`) REFERENCES `taskboard_columns` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `task_history_sub_task_id_foreign` FOREIGN KEY (`sub_task_id`) REFERENCES `sub_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_history_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_users`
--
ALTER TABLE `task_users`
  ADD CONSTRAINT `task_users_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `taxes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD CONSTRAINT `theme_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `ticket_channels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `ticket_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_agent_groups`
--
ALTER TABLE `ticket_agent_groups`
  ADD CONSTRAINT `ticket_agent_groups_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_agent_groups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_agent_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `ticket_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_channels`
--
ALTER TABLE `ticket_channels`
  ADD CONSTRAINT `ticket_channels_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_files`
--
ALTER TABLE `ticket_files`
  ADD CONSTRAINT `ticket_files_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_files_ticket_reply_id_foreign` FOREIGN KEY (`ticket_reply_id`) REFERENCES `ticket_replies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_groups`
--
ALTER TABLE `ticket_groups`
  ADD CONSTRAINT `ticket_groups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_reply_templates`
--
ALTER TABLE `ticket_reply_templates`
  ADD CONSTRAINT `ticket_reply_templates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_tags`
--
ALTER TABLE `ticket_tags`
  ADD CONSTRAINT `ticket_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `ticket_tag_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_tags_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD CONSTRAINT `ticket_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `universal_search`
--
ALTER TABLE `universal_search`
  ADD CONSTRAINT `universal_search_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_chat`
--
ALTER TABLE `users_chat`
  ADD CONSTRAINT `users_chat_from_foreign` FOREIGN KEY (`from`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_chat_to_foreign` FOREIGN KEY (`to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_chat_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_chat_user_one_foreign` FOREIGN KEY (`user_one`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
