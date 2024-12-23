-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 02:33 PM
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
-- Database: `atlet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `atlet`
--

CREATE TABLE `atlet` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `speed` int(11) NOT NULL CHECK (`speed` between 0 and 100),
  `technical` int(11) NOT NULL CHECK (`technical` between 0 and 100),
  `intelligence` int(11) NOT NULL CHECK (`intelligence` between 0 and 100),
  `shooting` int(11) NOT NULL CHECK (`shooting` between 0 and 100),
  `passing` int(11) NOT NULL CHECK (`passing` between 0 and 100),
  `defending` int(11) NOT NULL CHECK (`defending` between 0 and 100),
  `browser` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atlet`
--

INSERT INTO `atlet` (`id`, `name`, `speed`, `technical`, `intelligence`, `shooting`, `passing`, `defending`, `browser`, `ip_address`, `created_at`) VALUES
(1, 'andrean', 90, 85, 90, 90, 90, 90, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '::1', '2024-12-23 12:08:20'),
(3, 'John Doe', 85, 78, 90, 88, 85, 80, NULL, NULL, '2024-12-23 12:10:03'),
(4, 'Jane Smith', 78, 82, 85, 80, 75, 70, NULL, NULL, '2024-12-23 12:10:03'),
(5, 'aoi', 78, 72, 85, 72, 75, 78, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '::1', '2024-12-23 12:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `country` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `gender`, `country`, `created_at`) VALUES
(1, 'syahrezi10@gmail.com', 'syahrezi10@gmail.com', '$2y$10$GOcdebE6hOQMA6xpK9zvWeGbdRRN2PgDQEUhnM8WVdZgfPExL72m6', 'male', 'indonesia', '2024-12-23 08:23:51'),
(2, 'eji', 'eji@gmail.com', '$2y$10$.JLPSytDwcQCA4I.zLOow.g/MReWWmx21mpHis9/4wjR97BJCOYwu', 'male', 'indonesia', '2024-12-23 12:48:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atlet`
--
ALTER TABLE `atlet`
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
-- AUTO_INCREMENT for table `atlet`
--
ALTER TABLE `atlet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
