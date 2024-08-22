-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2024 at 11:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omsoftware`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gst_number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_id`, `client_name`, `email`, `phone`, `gst_number`, `address`, `created_at`, `user_id`) VALUES
(5, 'Wah Taj', 'digilumes@gmail.com', '08882269510', '1123e2423lkj', 'mirzapur uttarpradesh\r\nMirzapur', '2024-08-06 04:15:44', NULL),
(6, 'Tata Company', 'tata@gmail.com', '8882169542', 'tata234567', 'adresss', '2024-08-06 09:19:10', NULL),
(7, 'Pune Digital', 'contact@pune.com', '882169541', 'pune745896', 'pune Maharastra', '2024-08-12 03:34:56', 7),
(9, 'Jitech', 'jitech@gmail.com', '4998788779', 'jit8723492', 'pune maharastra', '2024-08-21 04:03:12', 8),
(10, 'Pune Digital', 's@gmail.com', '4998788778', 'Pune8542', 'dfbjkd;flkjb;dlfkj;ldljwhp lsdfvhosdfhnvlkshv', '2024-08-21 04:04:12', 8);

-- --------------------------------------------------------

--
-- Table structure for table `company_profile`
--

CREATE TABLE `company_profile` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `gst_number` varchar(50) NOT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `terms` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `terms_accepted` tinyint(1) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_profile`
--

INSERT INTO `company_profile` (`id`, `company_name`, `email`, `phone`, `address`, `gst_number`, `company_logo`, `terms`, `created_at`, `terms_accepted`, `user_id`) VALUES
(20, 'OM Kitchen', 'kitchen@gmail.com', '8882169520', 'Shop No. 2 NIBM Road', 'OMKT8648', 'uploads/boss.png', '', '2024-08-21 03:58:14', 1, 8),
(22, 'SHIV Tech', 'shiv@gmail.com', '8882169808', 'sdjhgcfkjsadcgfkdc', 'shiv23485758', 'uploads/df.png', '', '2024-08-21 04:02:49', 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `head` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_mode` enum('credit_card','debit_card','paypal','bank_transfer','cash','check','other') NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `head`, `amount`, `payment_mode`, `payment_id`, `message`, `created_at`, `user_id`) VALUES
(5, 'shivendra', '5', 10000.00, 'debit_card', '654165465465', 'travell', '2024-08-12 03:36:43', 8),
(7, 'Raju Bhai', '6', 100.00, 'debit_card', '455656', 'testing', '2024-08-12 04:14:30', 8);

-- --------------------------------------------------------

--
-- Table structure for table `ex_head`
--

CREATE TABLE `ex_head` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ex_head`
--

INSERT INTO `ex_head` (`id`, `name`, `created_at`) VALUES
(5, 'Travell', '2024-08-12 03:36:16'),
(6, 'Party', '2024-08-12 04:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `hsn_sac_no` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `p_unit` varchar(255) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_image`, `hsn_sac_no`, `price`, `p_unit`, `discount`, `created_at`, `user_id`) VALUES
(24, 'Camera', 'products/pune-digital-agency-about-us.png', 'c9878', 1000.00, 'piece', 5.00, '2024-08-21 04:04:47', 8);

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`product_ids`)),
  `quantities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`quantities`)),
  `adv_payment` decimal(10,2) NOT NULL,
  `taxable` enum('yes','no') NOT NULL,
  `tax_rate` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '\'0.00\'',
  `total_amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `company_profile_id` int(11) DEFAULT NULL,
  `prices` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `quotation_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_ids` text NOT NULL,
  `quantities` text NOT NULL,
  `taxable` enum('yes','no') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`quotation_id`, `client_id`, `product_ids`, `quantities`, `taxable`, `total_amount`, `tax_amount`, `grand_total`, `created_at`, `updated_at`) VALUES
(4, 5, '[\"2\"]', '[\"1\"]', 'no', 50000.00, 0.00, 50000.00, '2024-08-06 14:07:42', NULL),
(5, 5, '[\"2\"]', '[\"3\"]', 'yes', 150000.00, 27000.00, 177000.00, '2024-08-06 14:16:17', NULL),
(6, 5, '[\"2\"]', '[\"2\"]', 'no', 100000.00, 0.00, 100000.00, '2024-08-06 14:19:43', NULL),
(7, 5, '[\"2\",\"3\"]', '[\"1\",\"2\"]', 'yes', 90000.00, 16200.00, 106200.00, '2024-08-06 14:33:27', NULL),
(8, 5, '[\"2\",\"3\"]', '[\"10\",\"20\"]', 'no', 900000.00, 0.00, 900000.00, '2024-08-06 14:35:11', NULL),
(9, 5, '[\"4\",\"2\",\"3\"]', '[\"10\",\"1\",\"2\"]', 'yes', 90120.00, 16221.60, 106341.60, '2024-08-06 14:37:13', NULL),
(10, 5, '[\"2\",\"4\"]', '[\"1\",\"2\"]', 'yes', 50024.00, 9004.32, 59028.32, '2024-08-06 14:41:57', NULL),
(11, 5, '[\"2\"]', '[\"1\"]', 'yes', 50000.00, 9000.00, 59000.00, '2024-08-06 14:54:25', NULL),
(12, 5, '[\"2\",\"3\",\"4\"]', '[\"1\",\"2\",\"10\"]', 'yes', 90120.00, 16221.60, 106341.60, '2024-08-07 07:13:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `reg_date`) VALUES
(8, 'admin', 'digilume@gmail.com', '$2y$10$RCXKTrZDWaPV8p3R2jk7je0k0HS465y5yKraVo4Xg1jGtenAjoTzq', '2024-08-12 07:10:39'),
(9, 'user02', 'user02@gmail.com', '$2y$10$Cj2PFIgY.kCB1HzOdfgnSe4iCp9KwKQxwJRUbc1zLk5uOZlTZC6Hq', '2024-08-12 12:23:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `company_profile`
--
ALTER TABLE `company_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ex_head`
--
ALTER TABLE `ex_head`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `company_profile_id` (`company_profile_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `company_profile`
--
ALTER TABLE `company_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ex_head`
--
ALTER TABLE `ex_head`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `quotation`
--
ALTER TABLE `quotation`
  ADD CONSTRAINT `quotation_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`),
  ADD CONSTRAINT `quotation_ibfk_2` FOREIGN KEY (`company_profile_id`) REFERENCES `company_profile` (`id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
