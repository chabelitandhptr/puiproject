-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 08:12 PM
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
-- Database: `collabnest2`
--

-- --------------------------------------------------------

--
-- Table structure for table `endorsements`
--

CREATE TABLE `endorsements` (
  `id` int(11) NOT NULL,
  `influencer_id` int(11) DEFAULT NULL,
  `promoted_item` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `tasks` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `free_product` text DEFAULT NULL,
  `contact_permission` int(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `endorsements`
--

INSERT INTO `endorsements` (`id`, `influencer_id`, `promoted_item`, `details`, `tasks`, `requirements`, `service`, `free_product`, `contact_permission`, `image`, `created_at`) VALUES
(22, 15, 'coba', 'coba', 'coba', 'coba', '1x Post di Story', 'coba', 1, '1749401524_Screenshot 2025-03-17 223604.png', '2025-06-08 16:52:04'),
(23, 15, 'coba', 'coba', 'coba', 'coba', '1x Post di Story', 'coba', 1, '1749402702_Screenshot 2025-03-17 223604.png', '2025-06-08 17:11:42'),
(24, 15, 'coba', 'coba', 'coba', 'coba', '1x Post di Story', 'coba', 1, '1749403936_Screenshot 2025-03-17 223604.png', '2025-06-08 17:32:16'),
(25, 15, 'COBA', 'COBA', 'COBA', 'COABA', '1x Post di Story', 'COBA', 1, '1749404397_Screenshot 2025-03-17 235719.png', '2025-06-08 17:39:57');

-- --------------------------------------------------------

--
-- Table structure for table `influencers`
--

CREATE TABLE `influencers` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `followers_instagram` int(11) DEFAULT 0,
  `tiktok` varchar(255) DEFAULT NULL,
  `followers_tiktok` int(11) DEFAULT 0,
  `category` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `payment_method` enum('bank_transfer','e_wallet') DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `influencers`
--

INSERT INTO `influencers` (`id`, `username`, `full_name`, `phone`, `email`, `profile_image`, `instagram`, `followers_instagram`, `tiktok`, `followers_tiktok`, `category`, `bio`, `created_at`, `updated_at`, `address`, `province`, `city`, `payment_method`, `account_number`) VALUES
(10, 'percobaan', 'chabelita', '081390529013', 'percobaan@gmail.com', 'fotopp1.jpg', '@coba', 1900, '@coba', 10000, 'food blogger', '', '2025-05-01 16:04:18', '2025-05-27 13:27:35', 'Trihanggo', '11', '1113', 'bank_transfer', '998799973636'),
(15, 'influencer', 'dinda laura basuki', '81390529012', 'influencer@gmail.com', 'sensroky037.jpg', 'andikaranda', 9900, 'randaandika', 20000, 'gaming', 'ready', '2025-06-02 08:03:26', '2025-06-02 08:05:30', 'Genengsari,Kemusu', '33', '3309', 'bank_transfer', '998766864444');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`user_id`, `username`, `password`) VALUES
(10, 'percobaan', '$2y$10$ybUOhEcP7XTDwabz2KhhS.oaTxGcGVUX8BNK8Rx8A2aAqfRb9plCq'),
(14, 'chabelita', '$2y$10$0wrWhU2bsLJnfjmuTzpJnurM7qoiSjG2nzXUbj82w5f.iWFp.eq0u'),
(15, 'influencer', '$2y$10$N059rdr8yIOe6wvwIFc04uJrcpvvU9v7e4IUe0JPmi..dkWRGY2Xq');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `umkm_id` int(11) NOT NULL,
  `influencer_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'makanan ringan', 'enakk dan mengenyangkan', 12000.00, '1746542508_Screenshot 2025-03-17 223604.png', '2025-05-06 14:41:48');

-- --------------------------------------------------------

--
-- Table structure for table `rate_cards`
--

CREATE TABLE `rate_cards` (
  `id` int(11) NOT NULL,
  `jasa` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rate_cards`
--

INSERT INTO `rate_cards` (`id`, `jasa`, `harga`, `username`, `created_at`) VALUES
(1, '1x Post di Story', 20000, 'percobaan', '2025-05-05 09:32:44'),
(2, '1x Post di Feed', 100000, 'percobaan', '2025-05-05 09:50:17'),
(3, '1x Post di Story', 60000, 'percobaan', '2025-05-27 13:38:06'),
(5, '1x Post di Story', 100000, 'chabelita', '2025-06-02 08:34:30'),
(6, '1x Post di Story', 100000, 'influencer', '2025-06-02 08:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `endorsement_id` int(11) NOT NULL,
  `influencer_id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `payment_image` varchar(255) DEFAULT NULL,
  `payment_method` enum('bank_transfer','e_wallet') NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Completed','Failed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `endorsement_id`, `influencer_id`, `service`, `price`, `payment_image`, `payment_method`, `account_number`, `transaction_date`, `status`, `created_at`, `updated_at`) VALUES
(7, 25, 15, '1x Post di Story', 100000.00, 'Screenshot 2025-03-13 163413.png', 'bank_transfer', '998766864444', '2025-06-09 00:40:06', 'Pending', '2025-06-08 17:40:06', '2025-06-08 17:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('umkm','influencer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `role`) VALUES
(10, 'percobaan', 'percobaan@gmail.com', 'influencer'),
(14, 'chabelita', 'chabel@gmail.com', 'umkm'),
(15, 'influencer', 'influencer@gmail.com', 'influencer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `endorsements`
--
ALTER TABLE `endorsements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `influencers`
--
ALTER TABLE `influencers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id` (`umkm_id`),
  ADD KEY `influencer_id` (`influencer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rate_cards`
--
ALTER TABLE `rate_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `endorsements`
--
ALTER TABLE `endorsements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `influencers`
--
ALTER TABLE `influencers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rate_cards`
--
ALTER TABLE `rate_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`umkm_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`influencer_id`) REFERENCES `influencers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
