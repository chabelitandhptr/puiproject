-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 08:07 PM
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
-- Database: `collabnest`
--

-- --------------------------------------------------------

--
-- Table structure for table `influencers`
--

CREATE TABLE `influencers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `followers` int(11) NOT NULL DEFAULT 0,
  `youtube` int(11) DEFAULT 0,
  `instagram` int(11) DEFAULT 0,
  `twitter` int(11) DEFAULT 0,
  `engagement_rate` decimal(5,2) DEFAULT NULL,
  `rate_card` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `influencers`
--

INSERT INTO `influencers` (`id`, `username`, `full_name`, `email`, `phone`, `country`, `followers`, `youtube`, `instagram`, `twitter`, `engagement_rate`, `rate_card`, `category`, `profile_image`, `created_at`) VALUES
(3, '5220411009', 'Chabelita ananda', 'coba@gmail.com', '081390529012', 'Indonesia', 1000, 5000, 18000, 5000, 100.00, '100', 'foodblogger', NULL, '2025-04-22 18:36:45');

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
(1, 'admin', '$2y$10$xkPf4zsrAZE1c/k/PmtWFOGpvSRAVxCHnZKIy0kg1EbCsH4IMKY9C'),
(2, 'chabelita', '$2y$10$n0cS7CiN/EUKhtNPDoJMF.8NtEHa7oQGcl0D3r0AnNaCmaLKOw5Dy'),
(3, '5220411009', '$2y$10$fev/1Ghyvn7U3xwJhNa/uuAn3l8iFD4EUG/IBneyTNmeaQNU4KkPy');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('brand','influencer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `role`) VALUES
(1, 'admin', 'chabelitaanandaputri@gmail.com', ''),
(2, 'chabelita', 'coba@gmail.com', 'influencer'),
(3, '5220411009', 'cou@gmail.com', 'influencer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `influencers`
--
ALTER TABLE `influencers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `influencers`
--
ALTER TABLE `influencers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
