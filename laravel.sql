-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2024 at 01:07 PM
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
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(123) NOT NULL,
  `country` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `address_lane_1` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `landmark` varchar(255) NOT NULL,
  `town` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `country`, `name`, `mobile`, `email`, `pincode`, `address_lane_1`, `area`, `landmark`, `town`, `state`, `created_at`, `updated_at`) VALUES
(1, '3', 'India', 'Hriday Kumar', '9856321470', 'hridaykumar186@gmail.com', '160071', '24', 'sector 71', 'Near ivy hospital sector 71', 'Mohali', 'Punjab', '2024-07-24 04:54:18', '2024-07-24 04:55:23'),
(2, '0', 'India', 'Guest', '8235708100', 'traineemidriff1@gmail.com', '160071', 'House no:- 24 sectoe 71', 'sector 71', 'Near ivy hospital sector 71', 'Mohali', 'Punjab', '2024-07-24 07:03:48', '2024-07-24 07:03:48'),
(3, '8', 'India', 'Hriday kumar', '8235708100', 'hridaykumar186@gmail.com', '160071', '24', '1', '1', '1', 'Punjab', '2024-07-29 07:07:27', '2024-07-29 07:07:27'),
(4, '0', 'India', 'Test', '9856325874', 'test@gmail.com', '160071', '45', '45', '54', '45', 'Punjab', '2024-07-29 07:09:09', '2024-07-29 07:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('otp_', 'i:605384;', 1722579682);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES
(12, 3, 5, 27, 1570.00, 42390.00, 0.00, 40270.50, '2024-07-31 05:10:57', '2024-07-31 05:10:57'),
(13, 3, 1, 2, 998.00, 998.00, 0.00, 998.00, '2024-08-02 06:00:51', '2024-08-04 22:43:45'),
(14, 8, 1, 4, 499.00, 1996.00, 0.00, 1996.00, '2024-08-02 06:02:20', '2024-08-04 23:43:20'),
(15, 8, 4, 1, 335.00, 335.00, 0.00, 335.00, '2024-08-04 22:44:53', '2024-08-04 22:44:53'),
(17, 8, 5, 2, 1570.00, 3140.00, 0.00, 3140.00, '2024-08-04 23:46:22', '2024-08-04 23:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso_code` char(2) NOT NULL,
  `iso_code_3` char(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso_code`, `iso_code_3`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(2, 'Åland Islands', 'AX', 'ALA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(3, 'Albania', 'AL', 'ALB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(4, 'Algeria', 'DZ', 'DZA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(5, 'American Samoa', 'AS', 'ASM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(6, 'Andorra', 'AD', 'AND', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(7, 'Angola', 'AO', 'AGO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(8, 'Anguilla', 'AI', 'AIA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(9, 'Antarctica', 'AQ', 'ATA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(10, 'Antigua and Barbuda', 'AG', 'ATG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(11, 'Argentina', 'AR', 'ARG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(12, 'Armenia', 'AM', 'ARM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(13, 'Aruba', 'AW', 'ABW', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(14, 'Australia', 'AU', 'AUS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(15, 'Austria', 'AT', 'AUT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(16, 'Azerbaijan', 'AZ', 'AZE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(17, 'Bahamas', 'BS', 'BHS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(18, 'Bahrain', 'BH', 'BHR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(19, 'Bangladesh', 'BD', 'BGD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(20, 'Barbados', 'BB', 'BRB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(21, 'Belarus', 'BY', 'BLR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(22, 'Belgium', 'BE', 'BEL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(23, 'Belize', 'BZ', 'BLZ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(24, 'Benin', 'BJ', 'BEN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(25, 'Bermuda', 'BM', 'BMU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(26, 'Bhutan', 'BT', 'BTN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(27, 'Bolivia', 'BO', 'BOL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(28, 'Bosnia and Herzegovina', 'BA', 'BIH', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(29, 'Botswana', 'BW', 'BWA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(30, 'Bouvet Island', 'BV', 'BVT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(31, 'Brazil', 'BR', 'BRA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(32, 'British Indian Ocean Territory', 'IO', 'IOT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(33, 'Brunei Darussalam', 'BN', 'BRN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(34, 'Bulgaria', 'BG', 'BGR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(35, 'Burkina Faso', 'BF', 'BFA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(36, 'Burundi', 'BI', 'BDI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(37, 'Cambodia', 'KH', 'KHM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(38, 'Cameroon', 'CM', 'CMR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(39, 'Canada', 'CA', 'CAN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(40, 'Cape Verde', 'CV', 'CPV', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(41, 'Cayman Islands', 'KY', 'CYM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(42, 'Central African Republic', 'CF', 'CAF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(43, 'Chad', 'TD', 'TCD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(44, 'Chile', 'CL', 'CHL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(45, 'China', 'CN', 'CHN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(46, 'Christmas Island', 'CX', 'CXR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(47, 'Cocos (Keeling) Islands', 'CC', 'CCK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(48, 'Colombia', 'CO', 'COL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(49, 'Comoros', 'KM', 'COM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(50, 'Congo', 'CG', 'COG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(51, 'Congo, the Democratic Republic of the', 'CD', 'COD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(52, 'Cook Islands', 'CK', 'COK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(53, 'Costa Rica', 'CR', 'CRI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(54, 'Côte d\'Ivoire', 'CI', 'CIV', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(55, 'Croatia', 'HR', 'HRV', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(56, 'Cuba', 'CU', 'CUB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(57, 'Curaçao', 'CW', 'CUW', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(58, 'Cyprus', 'CY', 'CYP', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(59, 'Czech Republic', 'CZ', 'CZE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(60, 'Denmark', 'DK', 'DNK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(61, 'Djibouti', 'DJ', 'DJI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(62, 'Dominica', 'DM', 'DMA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(63, 'Dominican Republic', 'DO', 'DOM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(64, 'Ecuador', 'EC', 'ECU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(65, 'Egypt', 'EG', 'EGY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(66, 'El Salvador', 'SV', 'SLV', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(67, 'Equatorial Guinea', 'GQ', 'GNQ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(68, 'Eritrea', 'ER', 'ERI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(69, 'Estonia', 'EE', 'EST', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(70, 'Ethiopia', 'ET', 'ETH', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(71, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(72, 'Faroe Islands', 'FO', 'FRO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(73, 'Fiji', 'FJ', 'FJI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(74, 'Finland', 'FI', 'FIN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(75, 'France', 'FR', 'FRA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(76, 'French Guiana', 'GF', 'GUF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(77, 'French Polynesia', 'PF', 'PYF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(78, 'French Southern Territories', 'TF', 'ATF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(79, 'Gabon', 'GA', 'GAB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(80, 'Gambia', 'GM', 'GMB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(81, 'Georgia', 'GE', 'GEO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(82, 'Germany', 'DE', 'DEU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(83, 'Ghana', 'GH', 'GHA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(84, 'Gibraltar', 'GI', 'GIB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(85, 'Greece', 'GR', 'GRC', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(86, 'Greenland', 'GL', 'GRL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(87, 'Grenada', 'GD', 'GRD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(88, 'Guadeloupe', 'GP', 'GLP', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(89, 'Guam', 'GU', 'GUM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(90, 'Guatemala', 'GT', 'GTM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(91, 'Guernsey', 'GG', 'GGY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(92, 'Guinea', 'GN', 'GIN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(93, 'Guinea-Bissau', 'GW', 'GNB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(94, 'Guyana', 'GY', 'GUY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(95, 'Haiti', 'HT', 'HTI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(96, 'Heard Island and McDonald Islands', 'HM', 'HMD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(97, 'Holy See (Vatican City State)', 'VA', 'VAT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(98, 'Honduras', 'HN', 'HND', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(99, 'Hong Kong', 'HK', 'HKG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(100, 'Hungary', 'HU', 'HUN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(101, 'Iceland', 'IS', 'ISL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(102, 'India', 'IN', 'IND', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(103, 'Indonesia', 'ID', 'IDN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(104, 'Iran, Islamic Republic of', 'IR', 'IRN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(105, 'Iraq', 'IQ', 'IRQ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(106, 'Ireland', 'IE', 'IRL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(107, 'Isle of Man', 'IM', 'IMN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(108, 'Israel', 'IL', 'ISR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(109, 'Italy', 'IT', 'ITA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(110, 'Jamaica', 'JM', 'JAM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(111, 'Japan', 'JP', 'JPN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(112, 'Jersey', 'JE', 'JEY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(113, 'Jordan', 'JO', 'JOR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(114, 'Kazakhstan', 'KZ', 'KAZ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(115, 'Kenya', 'KE', 'KEN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(116, 'Kiribati', 'KI', 'KIR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(117, 'Korea, Democratic People\'s Republic of', 'KP', 'PRK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(118, 'Korea, Republic of', 'KR', 'KOR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(119, 'Kuwait', 'KW', 'KWT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(120, 'Kyrgyzstan', 'KG', 'KGZ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(121, 'Lao People\'s Democratic Republic', 'LA', 'LAO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(122, 'Latvia', 'LV', 'LVA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(123, 'Lebanon', 'LB', 'LBN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(124, 'Lesotho', 'LS', 'LSO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(125, 'Liberia', 'LR', 'LBR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(126, 'Libya', 'LY', 'LBY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(127, 'Liechtenstein', 'LI', 'LIE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(128, 'Lithuania', 'LT', 'LTU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(129, 'Luxembourg', 'LU', 'LUX', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(130, 'Macao', 'MO', 'MAC', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(131, 'Macedonia, the former Yugoslav Republic of', 'MK', 'MKD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(132, 'Madagascar', 'MG', 'MDG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(133, 'Malawi', 'MW', 'MWI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(134, 'Malaysia', 'MY', 'MYS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(135, 'Maldives', 'MV', 'MDV', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(136, 'Mali', 'ML', 'MLI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(137, 'Malta', 'MT', 'MLT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(138, 'Marshall Islands', 'MH', 'MHL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(139, 'Martinique', 'MQ', 'MTQ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(140, 'Mauritania', 'MR', 'MRT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(141, 'Mauritius', 'MU', 'MUS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(142, 'Mayotte', 'YT', 'MYT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(143, 'Mexico', 'MX', 'MEX', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(144, 'Micronesia, Federated States of', 'FM', 'FSM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(145, 'Moldova, Republic of', 'MD', 'MDA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(146, 'Monaco', 'MC', 'MCO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(147, 'Mongolia', 'MN', 'MNG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(148, 'Montenegro', 'ME', 'MNE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(149, 'Montserrat', 'MS', 'MSR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(150, 'Morocco', 'MA', 'MAR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(151, 'Mozambique', 'MZ', 'MOZ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(152, 'Myanmar', 'MM', 'MMR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(153, 'Namibia', 'NA', 'NAM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(154, 'Nauru', 'NR', 'NRU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(155, 'Nepal', 'NP', 'NPL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(156, 'Netherlands', 'NL', 'NLD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(157, 'New Caledonia', 'NC', 'NCL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(158, 'New Zealand', 'NZ', 'NZL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(159, 'Nicaragua', 'NI', 'NIC', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(160, 'Niger', 'NE', 'NER', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(161, 'Nigeria', 'NG', 'NGA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(162, 'Niue', 'NU', 'NIU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(163, 'Norfolk Island', 'NF', 'NFK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(164, 'Northern Mariana Islands', 'MP', 'MNP', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(165, 'Norway', 'NO', 'NOR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(166, 'Oman', 'OM', 'OMN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(167, 'Pakistan', 'PK', 'PAK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(168, 'Palau', 'PW', 'PLW', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(169, 'Palestine, State of', 'PS', 'PSE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(170, 'Panama', 'PA', 'PAN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(171, 'Papua New Guinea', 'PG', 'PNG', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(172, 'Paraguay', 'PY', 'PRY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(173, 'Peru', 'PE', 'PER', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(174, 'Philippines', 'PH', 'PHL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(175, 'Pitcairn', 'PN', 'PCN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(176, 'Poland', 'PL', 'POL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(177, 'Portugal', 'PT', 'PRT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(178, 'Puerto Rico', 'PR', 'PRI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(179, 'Qatar', 'QA', 'QAT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(180, 'Réunion', 'RE', 'REU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(181, 'Romania', 'RO', 'ROU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(182, 'Russian Federation', 'RU', 'RUS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(183, 'Rwanda', 'RW', 'RWA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(184, 'Saint Barthélemy', 'BL', 'BLM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(185, 'Saint Helena, Ascension and Tristan da Cunha', 'SH', 'SHN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(186, 'Saint Kitts and Nevis', 'KN', 'KNA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(187, 'Saint Lucia', 'LC', 'LCA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(188, 'Saint Martin (French part)', 'MF', 'MAF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(189, 'Saint Pierre and Miquelon', 'PM', 'SPM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(190, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(191, 'Samoa', 'WS', 'WSM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(192, 'San Marino', 'SM', 'SMR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(193, 'Sao Tomeand Principe', 'ST', 'STP', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(194, 'Saudi Arabia', 'SA', 'SAU', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(195, 'Senegal', 'SN', 'SEN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(196, 'Serbia', 'RS', 'SRB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(197, 'Seychelles', 'SC', 'SYC', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(198, 'Sierra Leone', 'SL', 'SLE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(199, 'Singapore', 'SG', 'SGP', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(200, 'Sint Maarten (Dutch part)', 'SX', 'SXM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(201, 'Slovakia', 'SK', 'SVK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(202, 'Slovenia', 'SI', 'SVN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(203, 'Solomon Islands', 'SB', 'SLB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(204, 'Somalia', 'SO', 'SOM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(205, 'South Africa', 'ZA', 'ZAF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(206, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(207, 'South Sudan', 'SS', 'SSD', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(208, 'Spain', 'ES', 'ESP', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(209, 'Sri Lanka', 'LK', 'LKA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(210, 'Sudan', 'SD', 'SDN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(211, 'Suriname', 'SR', 'SUR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(212, 'Svalbard and Jan Mayen', 'SJ', 'SJM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(213, 'Swaziland', 'SZ', 'SWZ', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(214, 'Sweden', 'SE', 'SWE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(215, 'Switzerland', 'CH', 'CHE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(216, 'Syrian Arab Republic', 'SY', 'SYR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(217, 'Taiwan, Province of China', 'TW', 'TWN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(218, 'Tajikistan', 'TJ', 'TJK', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(219, 'Tanzania, United Republic of', 'TZ', 'TZA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(220, 'Thailand', 'TH', 'THA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(221, 'Timor-Leste', 'TL', 'TLS', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(222, 'Togo', 'TG', 'TGO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(223, 'Tokelau', 'TK', 'TKL', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(224, 'Tonga', 'TO', 'TON', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(225, 'Trinidad and Tobago', 'TT', 'TTO', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(226, 'Tunisia', 'TN', 'TUN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(227, 'Turkey', 'TR', 'TUR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(228, 'Turkmenistan', 'TM', 'TKM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(229, 'Turks and Caicos Islands', 'TC', 'TCA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(230, 'Tuvalu', 'TV', 'TUV', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(231, 'Uganda', 'UG', 'UGA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(232, 'Ukraine', 'UA', 'UKR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(233, 'United Arab Emirates', 'AE', 'ARE', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(234, 'United Kingdom', 'GB', 'GBR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(235, 'United States', 'US', 'USA', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(236, 'United States Minor Outlying Islands', 'UM', 'UMI', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(237, 'Uruguay', 'UY', 'URY', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(238, 'Uzbekistan', 'UZ', 'UZB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(239, 'Vanuatu', 'VU', 'VUT', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(240, 'Venezuela, Bolivarian Republic of', 'VE', 'VEN', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(241, 'Viet Nam', 'VN', 'VNM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(242, 'Virgin Islands, British', 'VG', 'VGB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(243, 'Virgin Islands, U.S.', 'VI', 'VIR', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(244, 'Wallis and Futuna', 'WF', 'WLF', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(245, 'Western Sahara', 'EH', 'ESH', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(246, 'Yemen', 'YE', 'YEM', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(247, 'Zambia', 'ZM', 'ZMB', '2024-07-15 06:02:27', '2024-07-15 06:02:27'),
(248, 'Zimbabwe', 'ZW', 'ZWE', '2024-07-15 06:02:27', '2024-07-15 06:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount` double NOT NULL,
  `upto` double NOT NULL,
  `used` varchar(452) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `user_id`, `name`, `code`, `discount`, `upto`, `used`, `created_at`, `updated_at`) VALUES
(1, '3', 'Midriff', '5qUEc72NIt', 25, 100, '1', '2024-07-30 05:54:52', '2024-07-31 01:03:14'),
(2, NULL, 'abc', 'JTB7wqcBIP', 25, 100, '0', '2024-07-31 01:12:26', '2024-07-31 01:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_07_05_052735_add_new_column_to_users_table', 2),
(5, '2024_07_05_053344_create_products_table', 3),
(6, '2024_07_08_061159_create_cart_table', 4),
(7, '2024_07_09_071323_create_products_images_table', 5),
(8, '2024_07_10_064121_create_product_images_table', 6),
(9, '2024_07_12_114847_create_addresses_table', 6),
(10, '2024_07_15_110304_create_orders_table', 7),
(11, '2024_07_24_051759_add_new_column_to_addresses_table', 8),
(12, '2024_07_26_114953_create_quotations_table', 9),
(13, '2024_07_26_121502_create_quotations_table', 10),
(14, '2024_07_26_123042_create_quotations_table', 11),
(15, '2024_07_30_102033_create_coupons_table', 12),
(16, '2024_08_01_093612_create_personal_access_tokens_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stripe_transaction_id` varchar(789) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` varchar(588) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Order',
  `cancel` varchar(255) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `stripe_transaction_id`, `user_id`, `address_id`, `product_id`, `quantity`, `status`, `cancel`, `created_at`, `updated_at`) VALUES
(1, 'pi_3PjJjBL5Iy13zKBN0CGdDTck', 8, 3, '5', '1', 'cancelled', '1', '2024-08-02 06:02:58', '2024-08-05 04:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 8, 'MyApp', '77a34aa9f9a11b5cc9039034f254f9e6857ea9053940a7b4aeab6dbf9f791e6b', '[\"*\"]', '2024-08-02 04:33:17', NULL, '2024-08-02 01:43:28', '2024-08-02 04:33:17'),
(2, 'App\\Models\\User', 8, 'MyApp', '29ad2cc37e11006c69349ab976b99ad34deb3abd7f32b69d6ccdce2c34e29ae0', '[\"*\"]', NULL, NULL, '2024-08-02 04:36:59', '2024-08-02 04:36:59'),
(3, 'App\\Models\\User', 8, 'MyApp', '67ebc9dd0a4766883dbf224b21a7569d73b1496977758bae20f0f5a19667e473', '[\"*\"]', NULL, NULL, '2024-08-02 04:40:19', '2024-08-02 04:40:19'),
(4, 'App\\Models\\User', 8, 'MyApp', 'bd6f5de46c5fde10906492363b1bf979df0740ae88a99bac5700f27119e8752a', '[\"*\"]', NULL, NULL, '2024-08-02 04:44:23', '2024-08-02 04:44:23'),
(5, 'App\\Models\\User', 8, 'MyApp', 'f8ecbe5b1eb6ff4ff9d2ab9996aa8089f5ed4b3055d666a334f7c3d168a75b8b', '[\"*\"]', NULL, NULL, '2024-08-02 04:47:57', '2024-08-02 04:47:57'),
(6, 'App\\Models\\User', 8, 'MyApp', '68a8eb41b7cbeb8a5988ca6cbb0d33b9134cd24a5feeb052f3cd823e4d048609', '[\"*\"]', NULL, NULL, '2024-08-02 04:48:42', '2024-08-02 04:48:42'),
(7, 'App\\Models\\User', 8, 'MyApp', '6a9ca71c42d0e0544e738062b3d4d1d8ef326a2c8efe642538fd0a64561cd7f6', '[\"*\"]', NULL, NULL, '2024-08-02 04:51:24', '2024-08-02 04:51:24'),
(8, 'App\\Models\\User', 1, 'MyApp', '977ab8574b8b4f3f3a6e84645bde8210b42ecd654bae958ea3f4dbb2efda2db7', '[\"*\"]', NULL, NULL, '2024-08-05 02:08:48', '2024-08-05 02:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(3, 'Xiaomi 13 Pro 5G(Ceramic Black, 12GB RAM 256GB Storage)', 'Xiaomi 13 Pro (Ceramic Black, 12GB RAM 256GB Storage) | Leica Professional 50MP Triple Camera | Biggest Camera Sensor 1\" IMX989 | SD 8 Gen 2', 1030.00, NULL, '1720762850-OIP (3).jpg', '2024-07-12 00:10:50', '2024-07-12 00:32:36'),
(4, 'realme 12 Pro+ 5G (Submarine Blue, 8GB RAM, 256GB Storage)', 'realme 12 Pro+ 5G (Submarine Blue, 8GB RAM, 256GB Storage) | 6.7\" 120Hz Curved AMOLED Display | 64MP Periscope + 50MP Sony IMX 890 OIS Camera + 8MP | 32MP Selfie Camera | 67W Super VOOC Charge', 335.00, NULL, '1720763232-OIP (8).jpg', '2024-07-12 00:17:12', '2024-07-12 00:17:12'),
(5, 'Samsung Galaxy S24 Ultra 5G', 'Samsung Galaxy S24 Ultra 5G AI Smartphone (Titanium Gray, 12GB, 256GB Storage)', 1570.00, NULL, '1720764021-OIP (14).jpg', '2024-07-12 00:30:21', '2024-07-12 00:30:21'),
(6, 'Xiaomi 14 Ultra (White, 16GB RAM, 512GB Storage)', 'Xiaomi 13 Pro 5G(Ceramic Black, 12GB RAM 256GB Storage)\r\nXiaomi 13 Pro (Ceramic Black, 12GB RAM 256GB Storage) | Leica Professional 50MP Triple Camera | Biggest Camera Sensor 1\" IMX989 | SD 8 Gen 2', 1200.00, NULL, '1720767325-OIP (17).jpg', '2024-07-12 01:25:25', '2024-07-12 01:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `products_images`
--

CREATE TABLE `products_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `images` varchar(999) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_images`
--

INSERT INTO `products_images` (`id`, `product_id`, `images`, `created_at`, `updated_at`) VALUES
(1, 1, '1720612844-4.jpg,1720612844-3.jpg,1720612844-2.jpg,1720612844-1.jpg', '2024-07-10 06:30:44', '2024-07-10 06:30:44'),
(3, 3, '1720762850-OIP (3).jpg,1720762850-OIP (2).jpg,1720762850-OIP (1).jpg,1720762850-OIP.jpg', '2024-07-12 00:10:50', '2024-07-12 00:10:50'),
(4, 4, '1720763232-OIP (8).jpg,1720763232-OIP (7).jpg,1720763232-OIP (6).jpg,1720763232-OIP (5).jpg,1720763232-OIP (4).jpg', '2024-07-12 00:17:12', '2024-07-12 00:17:12'),
(5, 5, '1720764021-OIP (14).jpg,1720764021-OIP (13).jpg,1720764021-OIP (12).jpg,1720764021-OIP (11).jpg,1720764021-OIP (10).jpg,1720764021-OIP (9).jpg,1720764021-download (4).jpg', '2024-07-12 00:30:21', '2024-07-12 00:30:21'),
(6, 6, '1720767325-OIP (17).jpg,1720767325-OIP (16).jpg,1720767325-download (6).jpg,1720767325-download (5).jpg,1720767325-OIP (15).jpg', '2024-07-12 01:25:25', '2024-07-12 01:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(123) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(1, '3', 'Products ( Xiaomi )', '2024-07-29 04:57:24', '2024-08-05 01:04:40'),
(2, '3', 'Products ( Samsung )', '2024-07-29 05:08:08', '2024-07-30 02:17:10'),
(3, '2', '123456', '2024-07-30 06:34:00', '2024-07-30 03:44:15'),
(4, '8', '123456', '2024-07-31 01:24:40', '2024-08-05 01:07:09'),
(5, '8', '1234', '2024-08-05 00:22:46', '2024-08-05 00:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_products`
--

CREATE TABLE `quotation_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(123) NOT NULL,
  `quotation_id` varchar(123) NOT NULL,
  `product_id` varchar(123) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_products`
--

INSERT INTO `quotation_products` (`id`, `user_id`, `quotation_id`, `product_id`, `price`, `quantity`, `subtotal`, `total`, `created_at`, `updated_at`) VALUES
(3, '3', '2', '5', 1570.00, 30, 47100.00, 44745.00, '2024-07-29 05:08:08', '2024-07-31 01:56:12'),
(6, '3', '1', '4', 335.00, 30, 10050.00, 9547.50, '2024-07-29 07:13:42', '2024-07-31 01:23:02'),
(8, '3', '2', '3', 1030.00, 30, 30900.00, 29355.00, '2024-07-29 23:13:52', '2024-07-31 01:56:12'),
(9, '3', '1', '1', 499.00, 30, 14970.00, 14221.50, '2024-07-29 23:14:50', '2024-07-31 01:23:02'),
(10, '3', '3', '6', 1200.00, 1, 1200.00, 1200.00, '2024-07-31 01:22:31', '2024-07-31 01:23:02'),
(11, '3', '4', '5', 1570.00, 27, 42390.00, 40270.50, '2024-07-31 01:24:40', '2024-07-31 05:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2gU2sHO9CUqDPHvL39Qsr3wfhpiOtcM0ud1MtHEh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiQlNEQUpxd0xLWmJSOUtqMUJZQWdRNllVa3pHbWZlN2Q3QnNJc25DbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnMtZGV0YWlscy8xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1OiJlbWFpbCI7czoxNDoiYWRtaW5AbWFpbC5jb20iO3M6MjoiaWQiO2k6MTtzOjQ6Im5hbWUiO3M6NToiQWRtaW4iO3M6NDoicm9sZSI7czo1OiJhZG1pbiI7fQ==', 1722853617),
('5FLCqXxxaw22zBsaqAjythAYoJzp8nFIHesoKHX5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 Edg/127.0.0.0', 'YTo3OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3VzZXJzL3F1b3RhdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoidW5rdHR2ekRCVEdVNnY0UVI1NTFPUllIZXdGZzFWRlNXWTVhdW41RiI7czo1OiJlbWFpbCI7czoyMDoidGVzdGFwaTEyM0BnbWFpbC5jb20iO3M6MjoiaWQiO2k6ODtzOjQ6Im5hbWUiO3M6NjoiSHJpZGF5IjtzOjQ6InJvbGUiO3M6NDoidXNlciI7fQ==', 1722841101),
('msTu8qyn2YFxNzjSNwf9sNwGlwz0lnyqSMRrV9gO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 Edg/127.0.0.0', 'YTo3OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2OiJfdG9rZW4iO3M6NDA6ImpoZThMamh5VFIwRmJlak9IOGhiSnE5aUk1ZnJZMEJwY2R4Q05CZVYiO3M6NToiZW1haWwiO3M6MjU6InRyYWluZWVtaWRyaWZmMUBnbWFpbC5jb20iO3M6MjoiaWQiO2k6MztzOjQ6Im5hbWUiO3M6MTU6Ik1pZHJpZmYgdHJhaW5lZSI7czo0OiJyb2xlIjtzOjQ6InVzZXIiO30=', 1722855484),
('Q0c0gzXWZpjMXP7L3ik7oxAR0hBcagr3lH50gwsn', NULL, '127.0.0.1', 'PostmanRuntime/7.40.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUFFT0hlMXhXYk1qYWZyNndualFZREk3Um9yR1U0TnFoTDJrNEo0ciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1722851583),
('V0nzD8H3uOduabqgnYtWaYYluQUHRuUasZAJteRA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiMkFNcllhZ1I0Z2kzeGp3NlJuYnBzTU51ZFhBWFNWdFNkODJRRUd2byI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NToiZW1haWwiO3M6MTQ6ImFkbWluQG1haWwuY29tIjtzOjI6ImlkIjtpOjE7czo0OiJuYW1lIjtzOjU6IkFkbWluIjtzOjQ6InJvbGUiO3M6NToiYWRtaW4iO30=', 1722843041),
('vx3u7l5DZv6gPbc7q3uyZ25ypHbXWtM6M8AuIIf9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkdXcXVuNWJuZmF5dDNQWWhQWXhhb1B6cUJnR2Fwams4QlpIN1dGZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2Vycy9zaW5nbGVfcHJvZHVjdHMvMyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1722849411);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'Andhra Pradesh', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(2, 'Arunachal Pradesh', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(3, 'Assam', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(4, 'Bihar', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(5, 'Chhattisgarh', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(6, 'Goa', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(7, 'Gujarat', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(8, 'Haryana', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(9, 'Himachal Pradesh', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(10, 'Jammu and Kashmir', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(11, 'Jharkhand', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(12, 'Karnataka', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(13, 'Kerala', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(14, 'Madhya Pradesh', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(15, 'Maharashtra', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(16, 'Manipur', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(17, 'Meghalaya', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(18, 'Mizoram', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(19, 'Nagaland', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(20, 'Odisha', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(21, 'Punjab', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(22, 'Rajasthan', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(23, 'Sikkim', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(24, 'Tamil Nadu', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(25, 'Telangana', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(26, 'Tripura', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(27, 'Uttar Pradesh', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(28, 'Uttarakhand', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21'),
(29, 'West Bengal', 1, '2024-07-15 06:25:21', '2024-07-15 06:25:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(152) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(785) DEFAULT NULL,
  `google_id` varchar(152) DEFAULT NULL,
  `linkedin_id` varchar(152) DEFAULT NULL,
  `facaebook_id` varchar(152) DEFAULT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `email_verify` varchar(255) NOT NULL DEFAULT '0',
  `role` varchar(751) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `token`, `google_id`, `linkedin_id`, `facaebook_id`, `profile_picture`, `status`, `email_verify`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(0, 'Guest', 'guest@example.com', '', '', '', '', NULL, NULL, '1720683914-1720161069.png', '0', '0', 'guest', NULL, NULL, NULL),
(1, 'Admin', 'admin@mail.com', '', '$2y$12$upm6WB4QhRWH8VBG1hWbUOOGob0xFW0dTgZz4/PBtZXRRPJC/vsfm', 'vzcpBQvJlmsA2Fj3GO93cJjObaAQoqVXMiBi2oiq79115820', '', NULL, NULL, '1720693583-images.png', '1', '0', 'admin', '8fSBNipbYw2N1LBdrHFQtRGEALOtgHIVAfuIbSsY', '2024-07-04 05:49:03', '2024-08-05 04:04:20'),
(2, 'Hriday Kumar', 'hridaykumar186@gmail.com', '8235708100', '$2y$12$74XAskD/fGm5TxhEQgBEZuhywzlRJh6ZdOyisd7x4L04KoN2IIOqS', '', '', NULL, NULL, '1720160832.png', '1', '0', 'user', 'aG0VHSG1WDcOO6CdEzSEAnpRseiE7GdCRJ0x6MLd', '2024-07-05 00:57:13', '2024-07-26 01:31:06'),
(3, 'Midriff trainee', 'traineemidriff1@gmail.com', '9632587410', '$2y$12$8kgeZGp7JAncBjrfq2IBue2b4iJ4wYdrUgF9pNnNI8GmTWhXzU6gy', '', '112876557883285021590', NULL, NULL, '1720683914-1720161069.png', '0', '0', 'user', 'Oe0mieoft6A7RUL0WLFoqtCx0WXBRph4gXDNX8NWtG0csrp7X8YFlZkv3Jke', '2024-07-25 02:29:36', '2024-07-26 04:09:15'),
(4, 'Midriff', 'traineemidriff11@gmail.com', '8235708100', '$2y$12$rRTChGLwlE4nqUHRyh9RPOPbE7.qWLfr24LiP17BItr/Dwxz7D8w.', '', NULL, NULL, NULL, '1721975095.jpg', '1', '0', 'user', 's1t3rb4wOGYRmD2z971PX1PrFFC4i1xdRVlnDOTM', '2024-07-26 00:54:55', '2024-07-26 01:50:47'),
(5, 'Hriday Kumar', 'midriff.dev20@gmail.com', '8235708100', '$2y$12$wa5XP/qqpoh3FMAbVvhXEOwL5iQDzKNAwnayYYeVcGkKKyso2mS9y', '', NULL, NULL, NULL, '1721975260.jpg', '0', '0', 'user', '6OIPeEeca7771A5MHVRGpqOjWM5xR9j3hMISHdJ8', '2024-07-26 00:57:40', '2024-07-26 00:57:40'),
(6, 'Midriff', 'traineemidriff@21gmail.com', '8235708100', '$2y$12$phU72CqmNha9Xuo6zIG8rO/P5sl8WHpv7xZncEqngT.sDdKFrhYvy', '', NULL, NULL, NULL, '1721975449.jpg', '0', '0', 'user', 'ThCqypS0nnUP2u3Cc35uQDfHTO08irEZDHvzml4O', '2024-07-26 01:00:49', '2024-07-26 01:53:44'),
(7, 'Hriday', 'traineemidriff12@gmail.com', '9658741230', '$2y$12$OlFNyRq9IgpWkZk7EIdg1OcOAA4M5EoeG6SJs3iMIxBFN85TH7ypG', '', NULL, NULL, NULL, '1722581817.jpg', '0', '0', 'user', '1UnXqf0XkrPN3kqdK4WK0zaGARXxecBTEmbpjvOM', '2024-08-02 01:26:57', '2024-08-02 01:26:57'),
(8, 'Hriday', 'testapi123@gmail.com', '9658741239', '$2y$12$XCDL4PQoLHpUH3uGmJLaI.edemttyrgR3pvbultguNHP0sk.oJ57y', 'LBbZRUdtst0CkShLmpIOe0eJMcwZMdGPvIg8D38X4dda6d03', NULL, NULL, NULL, '1722602294.jpg', '1', '0', 'user', 'ByVpKprzSTU2e369ioGbHmzjxD5CUWg7kuO1ydrT', '2024-08-02 01:43:27', '2024-08-04 22:55:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_images`
--
ALTER TABLE `products_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_products`
--
ALTER TABLE `quotation_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products_images`
--
ALTER TABLE `products_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quotation_products`
--
ALTER TABLE `quotation_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
