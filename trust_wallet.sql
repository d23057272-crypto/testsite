-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 02, 2025 at 02:24 PM
-- Server version: 8.0.42-cll-lve
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gczzqaya_wallet`
--

-- --------------------------------------------------------

--
-- Table structure for table `bots`
--

CREATE TABLE `bots` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `bot_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `trading_pair` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'BTC/USDT',
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'active',
  `min_amount` decimal(18,8) NOT NULL,
  `max_amount` decimal(18,8) NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '24h',
  `total_profit` decimal(18,8) DEFAULT '0.00000000',
  `total_subscribers` int DEFAULT '0',
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `last_trade_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `bots`
--

INSERT INTO `bots` (`id`, `name`, `bot_type`, `trading_pair`, `status`, `min_amount`, `max_amount`, `duration`, `total_profit`, `total_subscribers`, `settings`, `last_trade_at`, `created_at`, `updated_at`) VALUES
(1, 'BTC Grid Pro', 'grid', 'BTC/USDT', 'active', 200.00000000, 20000.00000000, '24h', 15.75000000, 18, '{\"grid_levels\": 12, \"upper_price\": 35000, \"lower_price\": 30000}', NULL, '2025-01-20 08:25:32', '2025-04-04 05:09:08'),
(2, 'ETH DCA Master', 'dca', 'ETH/USDT', 'active', 50.00000000, 5000.00000000, '7d', 8.45000000, 9, '{\"interval_hours\": 6, \"target_profit\": 3}', NULL, '2025-01-20 08:25:32', '2025-01-22 14:08:13'),
(3, 'Quick Arbitrage', 'arbitrage', 'USDT/BUSD', 'active', 200.00000000, 20000.00000000, '4h', 5.25000000, 3, '{\"min_spread\": 0.8, \"exchanges\": [\"binance\", \"huobi\"]}', NULL, '2025-01-20 08:25:32', '2025-02-16 08:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `bot_settings`
--

CREATE TABLE `bot_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `bot_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `supported_pairs` text COLLATE utf8mb4_unicode_ci,
  `duration_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `bot_settings`
--

INSERT INTO `bot_settings` (`id`, `bot_type`, `name`, `is_active`, `parameters`, `supported_pairs`, `duration_options`, `created_at`, `updated_at`) VALUES
(1, 'grid', 'Grid Trading Bot', 1, '{\"grid_levels\":\"10\",\"upper_limit\":\"1.05\",\"lower_limit\":\"0.95\"}', '[\"BTC\\/USDT\",\"ETH\\/USDT\",\"LTC\\/USDT\",\"XRP\\/USDT\",\"BNB\\/USDT\",\"ADA\\/USDT\",\"SOL\\/USDT\",\"DOGE\\/USDT\",\"DASH\\/USDT\",\"XLM\\/USDT\"]', '[\"5m\",\"10m\",\"15m\",\"30m\",\"1h\",\"4h\",\"12h\",\"24h\",\"3d\",\"7d\",\"30d\"]', '2025-01-20 08:25:32', '2025-01-22 16:09:51'),
(2, 'dca', 'DCA Trading Bot', 1, '{\"interval_hours\": 4, \"price_deviation\": 2}', NULL, '[\"24h\", \"7d\", \"30d\"]', '2025-01-20 08:25:32', '2025-01-20 08:25:32'),
(3, 'arbitrage', 'Arbitrage Bot', 1, '{\"min_spread\": 0.5, \"max_slippage\": 0.1}', NULL, '[\"1h\", \"4h\", \"12h\", \"24h\"]', '2025-01-20 08:25:32', '2025-01-20 08:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `bot_subscriptions`
--

CREATE TABLE `bot_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `bot_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(18,8) NOT NULL,
  `current_profit` decimal(18,8) DEFAULT '0.00000000',
  `total_profit` decimal(18,8) DEFAULT '0.00000000',
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'active',
  `subscribed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bot_trades`
--

CREATE TABLE `bot_trades` (
  `id` bigint UNSIGNED NOT NULL,
  `bot_id` bigint UNSIGNED NOT NULL,
  `trading_pair` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(18,8) NOT NULL,
  `price` decimal(18,8) NOT NULL,
  `profit` decimal(18,8) DEFAULT NULL,
  `result` enum('win','loss') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `bot_trades`
--

INSERT INTO `bot_trades` (`id`, `bot_id`, `trading_pair`, `action`, `amount`, `price`, `profit`, `result`, `metadata`, `created_at`, `updated_at`) VALUES
(19, 1, 'BTC/USDT', 'buy', 200.00000000, 97300.00000000, 5000.00000000, 'win', '{\"notes\":\"User subscribed to bot with dynamic pair.\",\"updated_for_trade\":\"2025-02-16 08:15:14\"}', '2025-02-16 04:45:14', '2025-02-16 04:48:12'),
(20, 1, 'BTC/USDT', 'sell', 500.00000000, 97276.20000000, 672.70000000, 'win', '{\"notes\":\"User subscribed to bot with dynamic pair.\",\"updated_for_trade\":\"2025-02-16 11:35:01\"}', '2025-02-16 08:05:01', '2025-02-16 08:06:59'),
(21, 3, 'BTC/USDT', 'sell', 500.00000000, 97294.19000000, 2000.00000000, 'win', '{\"notes\":\"User subscribed to bot with dynamic pair.\",\"updated_for_trade\":\"2025-02-16 11:35:43\"}', '2025-02-16 08:05:43', '2025-02-20 02:41:59'),
(22, 1, 'BTC/USDT', 'sell', 1000.00000000, 84436.99000000, 0.00000000, NULL, '{\"notes\":\"User subscribed to bot with dynamic pair.\",\"updated_for_trade\":\"2025-04-04 08:39:08\"}', '2025-04-04 05:09:07', '2025-04-04 05:09:08');

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
('0026476a20bfbd08714155bb66f0b4feb2d25c1c', 'i:1;', 1747202276),
('0026476a20bfbd08714155bb66f0b4feb2d25c1c:timer', 'i:1747202276;', 1747202276),
('01ec40215edd21ed26d1bedde644afcfa4ccd665', 'i:2;', 1744471469),
('01ec40215edd21ed26d1bedde644afcfa4ccd665:timer', 'i:1744471469;', 1744471469),
('1a1162ec85b1d21244d7d3ecba5bc65878b73777', 'i:1;', 1747314806),
('1a1162ec85b1d21244d7d3ecba5bc65878b73777:timer', 'i:1747314806;', 1747314806),
('1b04f217730b9e677ec7021775bb6c2c8f8ca543', 'i:1;', 1745929642),
('1b04f217730b9e677ec7021775bb6c2c8f8ca543:timer', 'i:1745929642;', 1745929642),
('25a5e3012854728e0c6ab97fdcbb65c3a00c0965', 'i:1;', 1745029091),
('25a5e3012854728e0c6ab97fdcbb65c3a00c0965:timer', 'i:1745029091;', 1745029091),
('3341b16b2e02679ecb3d3fccb14a5667fa751d0d', 'i:2;', 1746528430),
('3341b16b2e02679ecb3d3fccb14a5667fa751d0d:timer', 'i:1746528430;', 1746528430),
('35139ef894b28b73bea022755166a23933c7d9cb', 'i:1;', 1745178799),
('35139ef894b28b73bea022755166a23933c7d9cb:timer', 'i:1745178799;', 1745178799),
('3e6bf6c89ba8a8b8b189f85975b0fab42bdc6d4a', 'i:2;', 1745367956),
('3e6bf6c89ba8a8b8b189f85975b0fab42bdc6d4a:timer', 'i:1745367956;', 1745367956),
('41f448afc82647d1376ad508aec1ebe28826b8f7', 'i:1;', 1745838512),
('41f448afc82647d1376ad508aec1ebe28826b8f7:timer', 'i:1745838512;', 1745838512),
('640bacfb48aefac1f91028c01603e5c78d4f63ca', 'i:1;', 1744922716),
('640bacfb48aefac1f91028c01603e5c78d4f63ca:timer', 'i:1744922716;', 1744922716),
('6c41101fe24a8f80c8cb51781f9e9ecf8c7a4d39', 'i:1;', 1744856147),
('6c41101fe24a8f80c8cb51781f9e9ecf8c7a4d39:timer', 'i:1744856147;', 1744856147),
('86970064ea53b6d66b7c53cbc91c58b4f06fc6fd', 'i:1;', 1747598199),
('86970064ea53b6d66b7c53cbc91c58b4f06fc6fd:timer', 'i:1747598199;', 1747598199),
('89a1c105a4720482e52ae423839ed97c693201ca', 'i:1;', 1746729370),
('89a1c105a4720482e52ae423839ed97c693201ca:timer', 'i:1746729370;', 1746729370),
('8ada660e06b787f245667943dc948dafab997e25', 'i:1;', 1747202702),
('8ada660e06b787f245667943dc948dafab997e25:timer', 'i:1747202702;', 1747202702),
('8da4dabfaeb4a44681c9777c85db39140e3e12e6', 'i:1;', 1744908283),
('8da4dabfaeb4a44681c9777c85db39140e3e12e6:timer', 'i:1744908283;', 1744908283),
('abf749051d8b000946c71a2e216e55eeb49cf414', 'i:1;', 1749836332),
('abf749051d8b000946c71a2e216e55eeb49cf414:timer', 'i:1749836332;', 1749836332),
('belsimi@gnail.com|2600:1004:b171:2ed6:3d70:c9b5:2529:ced1', 'i:1;', 1745333024),
('belsimi@gnail.com|2600:1004:b171:2ed6:3d70:c9b5:2529:ced1:timer', 'i:1745333024;', 1745333024),
('belsimi49@gmail.com|134.215.27.100', 'i:1;', 1745362846),
('belsimi49@gmail.com|134.215.27.100:timer', 'i:1745362846;', 1745362846),
('belsimi49rgmail.com|134.215.27.100', 'i:1;', 1745362807),
('belsimi49rgmail.com|134.215.27.100:timer', 'i:1745362807;', 1745362807),
('cfd1799660639c006d19f3ca7ebd518a1d6e6ca2', 'i:1;', 1746023172),
('cfd1799660639c006d19f3ca7ebd518a1d6e6ca2:timer', 'i:1746023172;', 1746023172),
('crypto_prices', 'a:12:{s:7:\"bitcoin\";a:3:{s:3:\"usd\";d:107385.5;s:14:\"usd_24h_change\";d:0.77;s:12:\"last_updated\";i:1751458284;}s:8:\"ethereum\";a:3:{s:3:\"usd\";d:2442.75;s:14:\"usd_24h_change\";d:-0.16999999999999998;s:12:\"last_updated\";i:1751458284;}s:6:\"tether\";a:3:{s:3:\"usd\";i:1;s:14:\"usd_24h_change\";i:0;s:12:\"last_updated\";i:1751458284;}s:4:\"tron\";a:3:{s:3:\"usd\";d:0.2817;s:14:\"usd_24h_change\";d:1.1400000000000001;s:12:\"last_updated\";i:1751458284;}s:11:\"binancecoin\";a:3:{s:3:\"usd\";d:656.681;s:14:\"usd_24h_change\";d:0.72;s:12:\"last_updated\";i:1751458284;}s:8:\"polkadot\";a:3:{s:3:\"usd\";d:3.3231;s:14:\"usd_24h_change\";d:-0.29;s:12:\"last_updated\";i:1751458284;}s:12:\"bitcoin-cash\";a:3:{s:3:\"usd\";d:501.69;s:14:\"usd_24h_change\";d:-3.56;s:12:\"last_updated\";i:1751458284;}s:8:\"litecoin\";a:3:{s:3:\"usd\";d:84.194;s:14:\"usd_24h_change\";d:-1.5699999999999998;s:12:\"last_updated\";i:1751458284;}s:7:\"stellar\";a:3:{s:3:\"usd\";d:0.2303;s:14:\"usd_24h_change\";d:-3.11;s:12:\"last_updated\";i:1751458284;}s:4:\"dash\";a:3:{s:3:\"usd\";d:19.66;s:14:\"usd_24h_change\";d:-0.15;s:12:\"last_updated\";i:1751458284;}s:6:\"solana\";a:3:{s:3:\"usd\";d:148.063;s:14:\"usd_24h_change\";d:-0.52;s:12:\"last_updated\";i:1751458284;}s:8:\"dogecoin\";a:3:{s:3:\"usd\";d:0.16086;s:14:\"usd_24h_change\";d:0.18;s:12:\"last_updated\";i:1751458284;}}', 1751458344),
('d20016547f489da25167fa1dbe9a00bfd82298c0', 'i:1;', 1747425136),
('d20016547f489da25167fa1dbe9a00bfd82298c0:timer', 'i:1747425136;', 1747425136),
('demo@mail.com|102.90.98.69', 'i:1;', 1747168401),
('demo@mail.com|102.90.98.69:timer', 'i:1747168401;', 1747168401),
('demo@mail.com|2c0f:f5c0:449:a2ba:449b:e4cb:4e1e:d083', 'i:1;', 1747314681),
('demo@mail.com|2c0f:f5c0:449:a2ba:449b:e4cb:4e1e:d083:timer', 'i:1747314681;', 1747314681),
('demo21|102.90.102.151', 'i:1;', 1751403205),
('demo21|102.90.102.151:timer', 'i:1751403205;', 1751403205),
('innerpeace_2006@yahoo.com|2600:4040:24a5:6d00:edbe:7ac6:6654:b1f9', 'i:1;', 1744856298),
('innerpeace_2006@yahoo.com|2600:4040:24a5:6d00:edbe:7ac6:6654:b1f9:timer', 'i:1744856298;', 1744856298),
('myprince804@gmail.com|212.56.53.73', 'i:2;', 1748316377),
('myprince804@gmail.com|212.56.53.73:timer', 'i:1748316377;', 1748316377),
('myprince804@icloud.com|212.56.53.73', 'i:2;', 1748316350),
('myprince804@icloud.com|212.56.53.73:timer', 'i:1748316350;', 1748316350),
('ordu.solomon@gmail.com|102.207.252.4', 'i:1;', 1746526435),
('ordu.solomon@gmail.com|102.207.252.4:timer', 'i:1746526435;', 1746526435),
('ordu.solomon@gmail.com|102.90.82.41', 'i:1;', 1746429160),
('ordu.solomon@gmail.com|102.90.82.41:timer', 'i:1746429160;', 1746429160),
('paulaharmen901@gmail.com|185.228.3.93', 'i:1;', 1746577536),
('paulaharmen901@gmail.com|185.228.3.93:timer', 'i:1746577536;', 1746577536),
('pri45685@gmail.com|212.56.53.73', 'i:2;', 1748316533),
('pri45685@gmail.com|212.56.53.73:timer', 'i:1748316533;', 1748316533),
('rezanabpour11@gmail.com|2600:4040:24a5:6d00:e89c:31a0:be1a:ebfa', 'i:1;', 1744909599),
('rezanabpour11@gmail.com|2600:4040:24a5:6d00:e89c:31a0:be1a:ebfa:timer', 'i:1744909599;', 1744909599),
('sternhub7@gmail.com|102.90.82.41', 'i:1;', 1746429149),
('sternhub7@gmail.com|102.90.82.41:timer', 'i:1746429149;', 1746429149),
('user123|102.89.34.102', 'i:1;', 1750952873),
('user123|102.89.34.102:timer', 'i:1750952873;', 1750952873),
('user123|102.90.96.37', 'i:1;', 1751402946),
('user123|102.90.96.37:timer', 'i:1751402946;', 1751402946),
('user1234|102.90.98.69', 'i:1;', 1747168384),
('user1234|102.90.98.69:timer', 'i:1747168384;', 1747168384),
('user1234|146.70.186.150', 'i:2;', 1746429575),
('user1234|146.70.186.150:timer', 'i:1746429575;', 1746429575),
('user1234|2c0f:f5c0:484:12db:3f78:97ae:eab5:8cbb', 'i:1;', 1747139537),
('user1234|2c0f:f5c0:484:12db:3f78:97ae:eab5:8cbb:timer', 'i:1747139536;', 1747139536);

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
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `crypto_asset_id` bigint UNSIGNED NOT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_month` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_year` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cvv` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive','frozen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `card_type` enum('virtual','physical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'virtual',
  `last_four` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_transactions`
--

CREATE TABLE `card_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `card_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crypto_assets`
--

CREATE TABLE `crypto_assets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `btc_balance` decimal(18,4) DEFAULT '0.0000',
  `btc_status` tinyint(1) DEFAULT '1',
  `btc_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usdt_trc20_balance` decimal(18,4) DEFAULT '0.0000',
  `usdt_trc20_status` tinyint(1) DEFAULT '1',
  `usdt_trc20_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usdt_erc20_balance` decimal(18,4) DEFAULT '0.0000',
  `usdt_erc20_status` tinyint(1) DEFAULT '1',
  `usdt_erc20_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eth_balance` decimal(18,4) DEFAULT '0.0000',
  `eth_status` tinyint(1) DEFAULT '1',
  `eth_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx_balance` decimal(18,4) DEFAULT '0.0000',
  `trx_status` tinyint(1) DEFAULT '1',
  `trx_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usdt_bep20_balance` decimal(18,4) DEFAULT '0.0000',
  `usdt_bep20_status` tinyint(1) DEFAULT '1',
  `usdt_bep20_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bch_balance` decimal(18,4) DEFAULT '0.0000',
  `bch_status` tinyint(1) DEFAULT '1',
  `bch_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ltc_balance` decimal(18,4) DEFAULT '0.0000',
  `ltc_status` tinyint(1) DEFAULT '1',
  `ltc_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xlm_balance` decimal(18,4) DEFAULT '0.0000',
  `xlm_status` tinyint(1) DEFAULT '1',
  `xlm_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dash_balance` decimal(18,4) DEFAULT '0.0000',
  `dash_status` tinyint(1) DEFAULT '1',
  `dash_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sol_balance` decimal(18,4) DEFAULT '0.0000',
  `sol_status` tinyint(1) DEFAULT '1',
  `sol_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bnb_balance` decimal(18,4) DEFAULT '0.0000',
  `bnb_status` tinyint(1) DEFAULT '1',
  `bnb_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dot_balance` decimal(18,4) DEFAULT '0.0000',
  `dot_status` tinyint(1) DEFAULT '1',
  `dot_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doge_balance` decimal(18,4) DEFAULT '0.0000',
  `doge_status` tinyint(1) DEFAULT '1',
  `doge_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_fee` decimal(10,4) DEFAULT '0.0000',
  `usdt_trc20_fee` decimal(10,4) DEFAULT '0.0000',
  `usdt_erc20_fee` decimal(10,4) DEFAULT '0.0000',
  `eth_fee` decimal(10,4) DEFAULT '0.0000',
  `trx_fee` decimal(10,4) DEFAULT '0.0000',
  `usdt_bep20_fee` decimal(10,4) DEFAULT '0.0000',
  `bch_fee` decimal(10,4) DEFAULT '0.0000',
  `ltc_fee` decimal(10,4) DEFAULT '0.0000',
  `xlm_fee` decimal(10,4) DEFAULT '0.0000',
  `dash_fee` decimal(10,4) DEFAULT '0.0000',
  `sol_fee` decimal(10,4) DEFAULT '0.0000',
  `bnb_fee` decimal(10,4) DEFAULT '0.0000',
  `dot_fee` decimal(10,4) DEFAULT '0.0000',
  `doge_fee` decimal(10,4) DEFAULT '0.0000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `btc_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `usdt_trc20_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `usdt_erc20_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `eth_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `trx_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `usdt_bep20_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `bch_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `ltc_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `xlm_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `dash_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `sol_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `bnb_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `dot_swap_fee` decimal(8,4) DEFAULT '0.0000',
  `doge_swap_fee` decimal(8,4) DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crypto_assets`
--

INSERT INTO `crypto_assets` (`id`, `user_id`, `btc_balance`, `btc_status`, `btc_address`, `usdt_trc20_balance`, `usdt_trc20_status`, `usdt_trc20_address`, `usdt_erc20_balance`, `usdt_erc20_status`, `usdt_erc20_address`, `eth_balance`, `eth_status`, `eth_address`, `trx_balance`, `trx_status`, `trx_address`, `usdt_bep20_balance`, `usdt_bep20_status`, `usdt_bep20_address`, `bch_balance`, `bch_status`, `bch_address`, `ltc_balance`, `ltc_status`, `ltc_address`, `xlm_balance`, `xlm_status`, `xlm_address`, `dash_balance`, `dash_status`, `dash_address`, `sol_balance`, `sol_status`, `sol_address`, `bnb_balance`, `bnb_status`, `bnb_address`, `dot_balance`, `dot_status`, `dot_address`, `doge_balance`, `doge_status`, `doge_address`, `btc_fee`, `usdt_trc20_fee`, `usdt_erc20_fee`, `eth_fee`, `trx_fee`, `usdt_bep20_fee`, `bch_fee`, `ltc_fee`, `xlm_fee`, `dash_fee`, `sol_fee`, `bnb_fee`, `dot_fee`, `doge_fee`, `created_at`, `updated_at`, `btc_swap_fee`, `usdt_trc20_swap_fee`, `usdt_erc20_swap_fee`, `eth_swap_fee`, `trx_swap_fee`, `usdt_bep20_swap_fee`, `bch_swap_fee`, `ltc_swap_fee`, `xlm_swap_fee`, `dash_swap_fee`, `sol_swap_fee`, `bnb_swap_fee`, `dot_swap_fee`, `doge_swap_fee`) VALUES
(1, 1, 0.4478, 1, 'ahhay3g3g', 292.2048, 1, 'a99qj', 19480.3400, 1, '293j', 0.0000, 1, 'iq91', 0.0000, 1, 'jsjjs', 18165.5346, 1, 's99w', 0.0000, 1, 's99j2', 0.0000, 1, 'sn83u', 0.0000, 1, '38hsh', 0.0000, 1, 'sn83h', 0.0000, 1, '8shhs', 0.0000, 1, 's8bsb3', 0.0000, 1, 'su8383', 0.0000, 1, 'DGEB4C3579G8JOH50A4GPUVKXM00QC7RWY', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-02-16 03:42:22', '2025-02-16 04:31:51', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(352, 338, 0.4000, 1, 'bc1qmxls4m3xefupc0cgum3njellca8f0hu58sx6ux', 310.0000, 1, 'TZAXYNEuqjYNBc4FXQ4vp2iCMRHbRE7zVZ', 0.0000, 1, '0x702c35F28E37Ec78C97C6670A91116811BD4b7AD', 0.0000, 1, '0x702c35F28E37Ec78C97C6670A91116811BD4b7AD', 0.0000, 1, 'TZAXYNEuqjYNBc4FXQ4vp2iCMRHbRE7zVZ', 0.0000, 1, '0x702c35F28E37Ec78C97C6670A91116811BD4b7AD', 0.0000, 1, 'qrr0ljtyj53vd2kvg7lkanq8nfkztk2ukunduwluc5', 0.0000, 1, 'ltc1qw5w3wwf7572j2fhjh096jvjqvltcga835kayhq', 0.0000, 1, 'GACPCDMC5IUALTSLZMPVQ7GO6ZDJN45HO2OO2DRHOGJDFB7SQZVKHROR', 0.0000, 1, 'Xm1KibG9gKzfaRTJhmZkwKjSQSkDSCMZY9', 0.0000, 1, '6gNcgUX7X3Ub18h3mAkTQwi8e5NZMCTWzSvAzicmhVCx', 0.0000, 1, '0x702c35F28E37Ec78C97C6670A91116811BD4b7AD', 0.0000, 1, '144c28jkbKg9RwqJ5XugkxNATCgCwNw7tUN859YHSYjY8PbT', 0.0000, 1, 'DTDrsZJDC9L9A8rc6yRfprtW7xT4rwzvfv', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-12 13:18:18', '2025-04-12 13:18:18', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(354, 340, 10.6600, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 0, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 0, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 0, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 0, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 0, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 0, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 0, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 0, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 0, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 0, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 0, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 0, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 0, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-16 23:07:19', '2025-04-24 17:56:47', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(355, 341, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-17 14:43:30', '2025-04-17 14:43:30', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(356, 342, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-17 15:25:44', '2025-04-17 15:25:44', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(357, 343, 0.8175, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-17 15:33:23', '2025-04-24 17:54:55', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(358, 344, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-17 18:06:34', '2025-04-17 18:06:34', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(360, 346, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-28 09:05:41', '2025-04-28 09:05:42', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(361, 347, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-29 10:25:19', '2025-04-29 10:25:19', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(362, 348, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-04-30 12:24:04', '2025-04-30 12:24:04', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(363, 349, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-06 08:42:29', '2025-05-06 08:42:29', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(364, 350, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-08 16:34:00', '2025-05-08 16:34:00', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(365, 351, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-14 03:53:36', '2025-05-14 03:53:37', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(366, 352, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-14 04:01:57', '2025-05-14 04:01:57', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(367, 353, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-14 04:02:54', '2025-05-14 04:02:54', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(368, 354, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-15 11:11:17', '2025-05-15 11:11:17', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(369, 355, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-16 17:47:25', '2025-05-16 17:47:25', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(370, 356, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-16 17:47:52', '2025-05-16 17:47:52', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(371, 357, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-05-18 17:55:15', '2025-05-18 17:55:15', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000),
(372, 358, 0.0000, 1, 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 0.0000, 1, '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 0.0000, 1, 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 0.0000, 1, 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 0.0000, 1, 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 0.0000, 1, 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 0.0000, 1, '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 0.0000, 1, '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 0.0000, 1, '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 0.0000, 1, 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2025-06-13 15:35:25', '2025-06-13 15:35:25', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000);

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
(4, '2024_12_07_080343_add_passcode_to_users_table', 1),
(6, '2024_12_14_150621_create_card', 2),
(7, '2024_12_14_153904_card_transactions', 3),
(8, '2024_12_14_171924_transactions', 4),
(9, '2024_12_15_150052_create_notifications_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `extra_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `extra_data`, `created_at`, `updated_at`) VALUES
(2, 340, 'kyc_upload', 'KYC Documents Uploaded', 'Your KYC documents have been submitted for verification.', 1, '\"{\\\"documents_uploaded\\\":[\\\"front_id\\\",\\\"back_id\\\",\\\"proof_of_residence\\\"],\\\"upload_timestamp\\\":\\\"2025-04-17 01:34:22\\\"}\"', '2025-04-16 23:34:22', '2025-04-17 19:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('ordu.solomon@gmail.com', '$2y$12$wbv6zhl4SubrBKMeIa5HMO8Ch90iwyjQzZ07VQtANZBIXBEq.6lJC', '2025-04-11 15:29:24');

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
('AnYNmJpcM2iO1U6oVEMVRLP3swdzzleiH4frS9NM', 338, '102.90.99.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 19_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/19.0 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaDVZTGh4VUgwUGJuR0xmWllKYkhyRVlZY3hpYm5qZ0QydGZ1QjBKRyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cHM6Ly90cnV0aHh3YWxsZXQuY29tL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMwOiJodHRwczovL3RydXRoeHdhbGxldC5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozMzg7fQ==', 1751458008),
('etT9BVkHFWsDGGtbUwVJeInRtKMxTWsip18oFqgO', NULL, '102.90.99.208', 'Mozilla/5.0 (iPhone; CPU iPhone OS 19_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/19.0 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNzFyVjFpd1h0elpPMFB0clNnUXJyMXRzWks1WnBUMVp1bDVIa2NXSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cHM6Ly90cnV0aHh3YWxsZXQuY29tL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL3RydXRoeHdhbGxldC5jb20vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1751458463),
('gAxiGeQDl6UfIR16xHK7Uuj6obLC95O5Joo84Jj4', NULL, '102.90.99.208', 'Mozilla/5.0 (iPhone; CPU iPhone OS 19_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/19.0 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVhpa3hoMUNNNHZVelF0RzlvR01HdHlpRGY2RUhPaEpjU3lvbFNkMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vdHJ1dGh4d2FsbGV0LmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1751458463),
('QKPL7y5LHDTbfO8WK1nm9mkzgFPUbQ5WhJWMeP4e', 338, '102.90.99.208', 'Mozilla/5.0 (iPhone; CPU iPhone OS 19_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/19.0 Mobile/15E148 Safari/604.1', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoid29OMlAwNTdHbXYxYlNVNGFnUU9rc29VbFNjeVhxanlIbE1GUENFVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vdHJ1dGh4d2FsbGV0LmNvbS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMzODtzOjE3OiJwYXNzY29kZV92ZXJpZmllZCI7YjoxO3M6MjA6InBhc3Njb2RlX3ZlcmlmaWVkX2F0IjtPOjI1OiJJbGx1bWluYXRlXFN1cHBvcnRcQ2FyYm9uIjozOntzOjQ6ImRhdGUiO3M6MjY6IjIwMjUtMDctMDIgMTI6MTc6MjQuNTU3NTcxIjtzOjEzOiJ0aW1lem9uZV90eXBlIjtpOjM7czo4OiJ0aW1lem9uZSI7czozOiJVVEMiO319', 1751458644);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `display_name`, `description`, `is_public`, `order`, `created_at`, `updated_at`) VALUES
(1, 'site_logo', 'uploads/logos/1744392178_logo.png', 'general', 'file', 'Site Logo', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:22:58'),
(2, 'site_favicon', 'uploads/favicon/1744392218_favicon.png', 'general', 'file', 'Site Favicon', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:23:38'),
(3, 'site_name', 'TruthX Wallet', 'general', 'text', 'Site Name', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:23:38'),
(4, 'company_name', 'TruthX Wallet', 'general', 'text', 'Company Name', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:23:38'),
(5, 'company_address', 'ATWELL\r\n6164\r\nWestern Australia\r\nAustralia', 'general', 'textarea', 'Company Address', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:23:38'),
(6, 'company_phone', '+1', 'general', 'text', 'Company Phone', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:23:38'),
(7, 'company_email', 'info@truthxwallet.com', 'general', 'email', 'Company Email', NULL, 1, 0, '2024-12-15 16:50:09', '2025-04-11 15:23:38'),
(8, 'email_header_logo', 'uploads/email/1744392683_logo.png', 'email', 'file', 'Email Header Logo', NULL, 1, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(9, 'mail_mailer', 'smtp', 'email', 'text', 'Mail Mailer', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(10, 'mail_host', 'mail.truthxwallet.com', 'email', 'text', 'Mail Host', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(11, 'mail_port', '587', 'email', 'text', 'Mail Port', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(12, 'mail_username', 'info@truthxwallet.com', 'email', 'text', 'Mail Username', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(13, 'mail_encryption', 'tls', 'email', 'text', 'Mail Encryption', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(14, 'mail_from_address', 'info@truthxwallet.com', 'email', 'email', 'Mail From Address', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(15, 'mail_from_name', 'TruthX Wallet', 'email', 'text', 'Mail From Name', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(16, 'mail_password', 'IA9fViL5zjNO', 'email', 'password', 'Mail Password', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(17, 'notify_admin_on_user_register', '1', 'notifications', 'boolean', 'Notify Admin On User Register', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(18, 'notify_admin_on_kyc_submission', '1', 'notifications', 'boolean', 'Notify Admin On Kyc Submission', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(19, 'notify_user_on_kyc_approval', '1', 'notifications', 'boolean', 'Notify User On Kyc Approval', NULL, 0, 0, '2024-12-17 11:18:41', '2025-04-11 15:31:23'),
(20, 'referral_enabled', '1', 'referral', 'boolean', 'Enable Referral System', NULL, 1, 0, '2025-02-13 10:22:53', '2025-04-11 15:23:38'),
(21, 'referral_bonus_amount', '50.00', 'referral', 'number', 'Referral Bonus Amount', NULL, 1, 0, '2025-02-13 10:22:53', '2025-04-11 15:23:38'),
(22, 'referral_bonus_type', 'instant', 'referral', 'select', 'Referral Bonus Distribution Type', NULL, 1, 0, '2025-02-13 10:22:53', '2025-04-11 15:23:38'),
(23, 'btc_address', 'bc1q9d6qpl29gz9wy3ssvypx5s4qnnycf97ktjskud', 'wallets', 'text', 'Btc address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(24, 'eth_address', '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 'wallets', 'text', 'Eth address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(25, 'usdt_trc20_address', 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 'wallets', 'text', 'Usdt trc20 address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(26, 'usdt_erc20_address', '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 'wallets', 'text', 'Usdt erc20 address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(27, 'usdt_bep20_address', '0xc8bAcF6fB9A61E6b12804a6FE44f0D4550dFec75', 'wallets', 'text', 'Usdt bep20 address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(28, 'trx_address', 'TBWfGkaKqnbutrKvrBeRFBQmNHm6R1naKv', 'wallets', 'text', 'Trx address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(29, 'bch_address', 'qpkr3vc2acf6kd9mh9qz9g8dgmwkvc6xggfz7qm53v', 'wallets', 'text', 'Bch address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(30, 'ltc_address', 'ltc1qyaayk5p70rt76gq57xxuft7z928q8nf39a74pa', 'wallets', 'text', 'Ltc address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(31, 'xlm_address', 'GCVO5CGBT7CCY5UAUOMG7AGCYN4ZA47IRRZRWI2CEF52TZE7HUXFIJK7', 'wallets', 'text', 'Xlm address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(32, 'dash_address', 'XsQStzhXWemPNxc8Tc6gmAfar6jfooH12j', 'wallets', 'text', 'Dash address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(33, 'sol_address', '3TTbSgPgwbG3oQA4ZXi5MktkUT3APFJbpwW5Dk7jSuqp', 'wallets', 'text', 'Sol address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(34, 'bnb_address', '0x1707b41A7F729E1529cc55eEcC4bB9a51156d727', 'wallets', 'text', 'Bnb address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(35, 'dot_address', '14CVH8fas5EjcsvngPBUv5xmBhETybADkxkqs5bpW5Mscxnx', 'wallets', 'text', 'Dot address', NULL, 0, 0, '2025-02-13 14:13:24', '2025-04-12 15:03:44'),
(36, 'doge_address', 'D93Ajk6MRNHHGJqdEQtyweKJoi7Pcj4PJQ', 'wallets', 'text', 'Doge address', NULL, 0, 0, '2025-03-09 11:17:29', '2025-04-12 15:03:44');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_profits`
--

CREATE TABLE `subscription_profits` (
  `id` bigint UNSIGNED NOT NULL,
  `bot_subscription_id` bigint UNSIGNED NOT NULL,
  `bot_trade_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(18,8) NOT NULL,
  `profit` decimal(18,8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cryptoasset_id` bigint UNSIGNED NOT NULL,
  `type` enum('deposit','withdrawal','swap','funding','refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `which_crypto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_crypto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_crypto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_in` decimal(20,8) DEFAULT NULL,
  `amount_out` decimal(20,8) DEFAULT NULL,
  `network_fee` decimal(20,8) DEFAULT NULL,
  `rate` decimal(20,8) DEFAULT NULL,
  `status` enum('pending','processing','completed','failed','cancelled','reversed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `related_transaction_id` bigint UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `passcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kyc_status` enum('not_submitted','pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'not_submitted',
  `front_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `back_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_of_residence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `require_kyc` tinyint(1) DEFAULT '1',
  `require_fee` tinyint(1) NOT NULL DEFAULT '1',
  `require_wallet_connect` tinyint(1) NOT NULL DEFAULT '0',
  `enable_swap` tinyint(1) NOT NULL DEFAULT '0',
  `enable_crypto_sending` tinyint(1) DEFAULT '0',
  `wallet_phrase` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_wallet_connected` tinyint(1) DEFAULT '0',
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` bigint UNSIGNED DEFAULT NULL,
  `referral_count` int DEFAULT '0',
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `kyc_fee` tinyint(1) NOT NULL DEFAULT '0',
  `kyc_fee_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `swap_fee` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`, `passcode`, `kyc_status`, `front_id`, `back_id`, `proof_of_residence`, `require_kyc`, `require_fee`, `require_wallet_connect`, `enable_swap`, `enable_crypto_sending`, `wallet_phrase`, `is_wallet_connected`, `referral_code`, `referred_by`, `referral_count`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `kyc_fee`, `kyc_fee_amount`, `swap_fee`) VALUES
(1, 'admin', 'Admin', 'info@truthxwallet.com', '2025-04-11 12:37:58', '$2y$12$wETmkzIkFIG0r1m3PqVXPekSO/eYVCVASifnuwyxnluIN74bUD2My', 1, 'GlNvgTTNoZQTltCD04Ffb4NEhMH8UzlAwleHPFuKpB9OuPn3ZQ6tdMXBfNvF', '2024-12-07 07:20:15', '2025-06-01 06:42:35', '$2y$12$YHBU2fy0jvkH.qUQkuanQ.1TFidkxyDaJ55nnzCxfooxNob1MTEZa', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFA2255667', NULL, 0, 'ZXPWSG7XRCOUDOJ3', '[\"2yDSwtrOcM\",\"RRQmWV4EA2\",\"NQCdxmGW5A\",\"UZ3H0hABRF\",\"0KgABPzCbB\",\"XitibL3uVv\",\"zDNHuqzltY\",\"sghp68l9EY\"]', NULL, 0, '0', 0),
(338, 'TYG3DDO', 'Hhhehsh', 'demo@mail.com', '2025-04-12 13:23:53', '$2y$12$NPmkg0lL.GjNXfDdnH9s.ui9oqMRHh8CSq.8hxSO86khe3fTm24WC', 0, 'vdzgiLlHzjv6JIXiRpYHX3Q2BtlQlrdMfTqvCnlNjxyDJ8QfA3AYITM7CzUr', '2025-04-12 13:18:18', '2025-05-05 05:16:53', '$2y$12$OcTuybOxit5A26TmcjgoKePFb0hTVmV09VvlGD2JTHb51MGooO15u', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFBJQEIZNY', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(340, 'TX9HZGC', 'Michael Belsito', 'belsimi@gmail.com', '2025-04-16 23:11:20', '$2y$12$UqKqZXZa6mz1.JMOzhSMAOAUcjASHZ/8QCLqtDy4x6sf2SdkWvcLu', 0, 'ws6c2koXqOg9iGAr1wj5T7sM2tJGuLxkA98bsZZFd5XoqhWMFUr2FOuH1HJa', '2025-04-16 23:07:19', '2025-05-08 20:13:51', '$2y$12$PEjwejVw9xBJNt1Q4YAKSePwpR8TTrHDnEAbo1WsK9SoUP4NYYjBi', 'pending', 'kycs/ZoRxTEksEonhEnMmcbbx68MtzdNi8w69zjlT3DAE.jpg', 'kycs/nrOE7ws8rPcrPlXvNaF3RNGwmkJOJXx0KcHCWp2f.jpg', 'kycs/vfcspO4PUSlqtBqv5CrIchWI4YiqIjVB7u0aYkzx.png', 1, 1, 0, 0, 0, NULL, 0, 'REF9C0XJIAF', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(341, 'TJKPANL', 'Reza Naipour', 'rezanabipour11@gmail.com', NULL, '$2y$12$1bnC0FWdGFltDOfyAzwa0.qAGF4sxyIEHYpsTm5mYDeg/Cfyy3Qiq', 0, NULL, '2025-04-17 14:43:30', '2025-04-17 14:43:30', NULL, 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFNTJXPGN6', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(342, 'TKTRDWO', 'Reza Naipour', 'simorgh_2001@yahoo.com', NULL, '$2y$12$zOU5XO6kCrfku6W2sWWhaO1tK/P793FTxD0SAVcG3nseYMdEHOPEu', 0, NULL, '2025-04-17 15:25:44', '2025-04-17 15:25:44', NULL, 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFWK4BL6E2', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(343, 'TR5PSYQ', 'Reza Naipour', 'mvafa@hotmail.com', '2025-04-17 15:44:07', '$2y$12$JdBc4R//z2NpkJKvUGpLMe3G5/nhK1Zdb3CbjEcxdzM8Iu5IEf1NG', 0, NULL, '2025-04-17 15:33:23', '2025-04-17 15:46:26', '$2y$12$/oi2zDyqR9wznPF..PD8MO4pYcArMNjA1is0.CGTuIA.PAYFURzXy', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFA0TIU84X', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(344, 'TETREXS', 'Mark Erskine', 'kenai.alaska.2021@gmail.com', '2025-04-17 18:09:40', '$2y$12$.u7s/pKbK9StWKimC5lnsuFryw5dKujF47/bTtqgQOOdJqO5gORXK', 0, NULL, '2025-04-17 18:06:34', '2025-04-17 18:10:31', '$2y$12$z0l8C2N3VjfYfOdyZmeOfeRe4EwwaejTasxyEevrnOFOvHKMNbNtu', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFQCGI61X8', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(346, 'TB0YSZI', 'sanju saran', 'sqmi9g9e3u@dygovil.com', '2025-04-28 09:07:32', '$2y$12$ZgfsvqYzPCGUFwiQv.Av7.DYqM2Tf4GXApGKxTKiVp/MxHkOJWGBS', 0, NULL, '2025-04-28 09:05:41', '2025-04-28 09:07:51', '$2y$12$tU406dVrKms2f/sv.urer.jo9UHlYznitRZ10Cjw34mpXKvZjLayS', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFCQSDPQGH', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(347, 'T3LOAXW', 'Taylor Hall', 'taylor.hall90@mx-mailsrv.com', '2025-04-29 10:26:22', '$2y$12$JzWTaj0YIufWvCwDwn7O2.J9aMOyPDxWe.TuqiTGhpQ9hfT7rU7cu', 0, NULL, '2025-04-29 10:25:19', '2025-04-29 10:28:24', '$2y$12$3G4eL/XDS6dPceHPyONIWeZwwS6bWcdFSZv2beHikio5W0I8AMgUm', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFJ0PRVCKZ', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(348, 'TQWB0GW', 'Dakota Jackson', 'dakota.jackson81@mx-mailsrv.com', '2025-04-30 12:25:12', '$2y$12$fJOC0J0JnKjdkeDI7W9Eq.ZTm4sjcRoT/l.b3Nw9X6tdZuZkRmUnq', 0, NULL, '2025-04-30 12:24:04', '2025-04-30 12:25:12', NULL, 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFJ3JSEBPL', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(349, 'TVJXTQX', 'Paula', 'paulaharmen901@gmail.com', '2025-05-06 08:46:10', '$2y$12$fWGZbSV968zin8peSE9Ul.D2I0BriYqRePLO5QrislbBB14nNqszq', 0, 'cvVWRJeY49dzhKVwTTOGFE6UfmRc0oCn4ZuQcAalG30HWgZvy14dnXuSeDKs', '2025-05-06 08:42:29', '2025-05-06 08:46:42', '$2y$12$mhy1KXx67pEmJPxCS65iCOzjlhZsLTD/jU7.AuQG3XPeyDlvLycnS', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFLPSPTFIU', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(350, 'TOJZMPJ', 'Goodman', 'gutrofolto@gufum.com', '2025-05-08 16:35:10', '$2y$12$Sgyx8Iouup5ejjhWS6wNteymj9Axm2NAJeEnC5AZgC8HRLfXQyCL.', 0, NULL, '2025-05-08 16:34:00', '2025-05-08 16:35:39', '$2y$12$Mm24oBMoJYW5utU2ZiN2ZuXuoWo675UrDYjiE9eg7RnM4y5W9Bqf2', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REF6VSOJEE2', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(351, 'T0N6GQR', 'JOHN GOE', '7h0xis6o7m@dygovil.com', '2025-05-14 03:56:56', '$2y$12$/vL2cId7y23hn3Y1pniVgepn6ohzZm.5EZATEfMy6GDXdq8O0eulu', 0, NULL, '2025-05-14 03:53:36', '2025-05-14 03:57:32', '$2y$12$TWV5tV1w73Y8LGFnVp0TGuKT4FHsUZqtonFIiBMPKttyaiAchtv.m', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REF4CIF01BJ', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(352, 'T7PG3DO', 'tae dasd', 'bahefyko@dreamclarify.org', NULL, '$2y$12$ocyGEnvvmBK9mExdDFfRpeasSuwQ8Zlg7r2B4rxPB1oOJlmpcJLa.', 0, NULL, '2025-05-14 04:01:57', '2025-05-14 04:01:57', NULL, 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFETLTKQ3U', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(353, 'TNMW3OO', 'dfsdfsd', 'jm2aq3we8o@vafyxh.com', '2025-05-14 04:04:03', '$2y$12$nKIIUNTNzNDzNpAh9JDK1e6s.T/wP2CMReKC1FU1MJJmun6X01xje', 0, NULL, '2025-05-14 04:02:54', '2025-05-14 04:04:33', '$2y$12$/Aw/UCr5MY7LYakvGd6Lw.w8GEFxPWXjcZAXgiSOc4szUlfs6UT3K', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFQWLACJW1', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(354, 'TIKMRBJ', 'Schemer Jones', 'schemer0001@gmail.com', '2025-05-15 11:12:27', '$2y$12$J5kG48EFhlNoc2qnXlgTauLuELg6KL6cQHRn31Q4fFdGUy5oZWYJ6', 0, NULL, '2025-05-15 11:11:17', '2025-05-15 11:12:41', '$2y$12$jRSgo3ACgdYkn5Msg68kfOUarNFS9Ggi8tHdOaimT2P/5.zZZ7zc.', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REF4WQFKOFZ', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(355, 'TV3L65O', 'fart', 'fart222@gmail.com', NULL, '$2y$12$TgPC/uD.g.y5fgLOdmPrKeIbW/51cQpC2CWvmiioxYZVYsCrguBZa', 0, NULL, '2025-05-16 17:47:25', '2025-05-16 17:47:25', NULL, 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFOIQZZUOH', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(356, 'TCYTKGC', 'fart', 'zumalincol@gmail.com', '2025-05-16 17:51:16', '$2y$12$gzZMGnM76eVViery0oHC1uJX5uCh2y.eGTbEV76DlaY9lqBLHCoRS', 0, NULL, '2025-05-16 17:47:52', '2025-05-16 17:51:25', '$2y$12$iHVlejliDTT8.jSzFUEfcOkX8vJFSHIpTgcamDbmso3qI.jD8gVhS', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFKOVO3SCD', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(357, 'TRORASE', 'fart', 'yenasa911@gmail.com', NULL, '$2y$12$ieRwS2k4VOonJNV2x8/FzOED8fge/yDGOE6pPBFTX9grgT7ZePnuC', 0, NULL, '2025-05-18 17:55:15', '2025-05-18 17:55:15', NULL, 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFYEWQOU88', NULL, 0, NULL, NULL, NULL, 0, '0', 0),
(358, 'TCRN3IK', 'test', 'scottmk0111@gmail.com', '2025-06-13 15:37:52', '$2y$12$9hc6pGRe.wFsZ/e6XtD25uSg4IQKfQ76rxMrUGneNbfgJOkSPPK1S', 0, NULL, '2025-06-13 15:35:25', '2025-06-13 15:38:05', '$2y$12$ocbQhPfz/D6VTbddD5irfeNUXQWU0RRvFmTZctDG3bMgf5sqqCOrC', 'not_submitted', NULL, NULL, NULL, 1, 1, 0, 0, 0, NULL, 0, 'REFW3M4FJOQ', NULL, 0, NULL, NULL, NULL, 0, '0', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bot_settings`
--
ALTER TABLE `bot_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bot_subscriptions`
--
ALTER TABLE `bot_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot_id` (`bot_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bot_trades`
--
ALTER TABLE `bot_trades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot_id` (`bot_id`);

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
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cards_card_number_unique` (`card_number`),
  ADD KEY `cards_user_id_foreign` (`user_id`),
  ADD KEY `cards_crypto_asset_id_foreign` (`crypto_asset_id`);

--
-- Indexes for table `card_transactions`
--
ALTER TABLE `card_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_transactions_card_id_foreign` (`card_id`);

--
-- Indexes for table `crypto_assets`
--
ALTER TABLE `crypto_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_key_index` (`key`);

--
-- Indexes for table `subscription_profits`
--
ALTER TABLE `subscription_profits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot_subscription_id` (`bot_subscription_id`),
  ADD KEY `bot_trade_id` (`bot_trade_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_related_transaction_id_foreign` (`related_transaction_id`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_which_crypto_index` (`which_crypto`),
  ADD KEY `transactions_status_index` (`status`),
  ADD KEY `transactions_transaction_hash_index` (`transaction_hash`),
  ADD KEY `transactions_user_id_type_status_index` (`user_id`,`type`,`status`),
  ADD KEY `transactions_cryptoasset_id_which_crypto_index` (`cryptoasset_id`,`which_crypto`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `referral_code` (`referral_code`),
  ADD KEY `users_referred_by_foreign` (`referred_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bots`
--
ALTER TABLE `bots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bot_settings`
--
ALTER TABLE `bot_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bot_subscriptions`
--
ALTER TABLE `bot_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bot_trades`
--
ALTER TABLE `bot_trades`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `card_transactions`
--
ALTER TABLE `card_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `crypto_assets`
--
ALTER TABLE `crypto_assets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `subscription_profits`
--
ALTER TABLE `subscription_profits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bot_subscriptions`
--
ALTER TABLE `bot_subscriptions`
  ADD CONSTRAINT `bot_subscriptions_ibfk_1` FOREIGN KEY (`bot_id`) REFERENCES `bots` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bot_subscriptions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bot_trades`
--
ALTER TABLE `bot_trades`
  ADD CONSTRAINT `bot_trades_ibfk_1` FOREIGN KEY (`bot_id`) REFERENCES `bots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_crypto_asset_id_foreign` FOREIGN KEY (`crypto_asset_id`) REFERENCES `crypto_assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `card_transactions`
--
ALTER TABLE `card_transactions`
  ADD CONSTRAINT `card_transactions_card_id_foreign` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crypto_assets`
--
ALTER TABLE `crypto_assets`
  ADD CONSTRAINT `crypto_assets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_profits`
--
ALTER TABLE `subscription_profits`
  ADD CONSTRAINT `subscription_profits_ibfk_1` FOREIGN KEY (`bot_subscription_id`) REFERENCES `bot_subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_profits_ibfk_2` FOREIGN KEY (`bot_trade_id`) REFERENCES `bot_trades` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_cryptoasset_id_foreign` FOREIGN KEY (`cryptoasset_id`) REFERENCES `crypto_assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_related_transaction_id_foreign` FOREIGN KEY (`related_transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
