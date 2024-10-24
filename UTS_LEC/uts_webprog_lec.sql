-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 24, 2024 at 08:08 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uts_webprog_lec`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `filepath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `date`, `password`, `created_at`, `filepath`) VALUES
(1, 'Admin', 'Admin@gmail.com', '2005-01-26', '$2y$10$3nxhupBjHET8E6YWUDwPpuxz7.mbE5SzGDs1ky/PfQoajfyHeg94C', '2024-10-24 04:17:49', '../uploads/uvent-logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `detail_event`
--

CREATE TABLE `detail_event` (
  `id` int(11) NOT NULL,
  `nama_event` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `jam` time NOT NULL,
  `tanggal` date NOT NULL,
  `participant` int(255) NOT NULL,
  `description` varchar(300) NOT NULL,
  `is_main_event` tinyint(1) NOT NULL,
  `registration_status` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_event`
--

INSERT INTO `detail_event` (`id`, `nama_event`, `lokasi`, `jam`, `tanggal`, `participant`, `description`, `is_main_event`, `registration_status`) VALUES
(10, 'KEPANITIAAN UTF 2025', 'Lounge D', '09:30:00', '2025-11-11', 200, 'UMN TECH FESTIVAL', 1, 'yes'),
(11, 'OMB UMN 2025', 'Lapangan UMN', '05:30:00', '2025-08-29', 2100, 'Orientasi Mahasiswa Baru UMN', 0, 'yes'),
(12, 'PPIF 2025', 'Lecture Theatre', '09:00:00', '2025-08-31', 300, 'Perkenalan Prodi Informatika', 0, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `event_participant`
--

CREATE TABLE `event_participant` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_participant`
--

INSERT INTO `event_participant` (`id`, `user_id`, `username`, `email`, `event_id`, `created_at`) VALUES
(9, 18, 'Audrey Christabelle Hakim', 'audrey@gmail.com', 10, '2024-10-24 05:39:30');

-- --------------------------------------------------------

--
-- Table structure for table `event_photos`
--

CREATE TABLE `event_photos` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `filepath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_photos`
--

INSERT INTO `event_photos` (`id`, `event_id`, `filepath`) VALUES
(29, 11, '../uploads/Screenshot 2024-10-24 at 11.45.05.jpg'),
(30, 12, '../uploads/Screenshot 2024-10-24 at 11.46.03.jpg'),
(31, 10, '../uploads/UTF1.jpeg'),
(32, 10, '../uploads/UTF2.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `filepath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `date`, `password`, `created_at`, `filepath`) VALUES
(8, 'Frendhy', 'frendhyzhuang@gmail.com', '2005-01-26', '$2y$10$rVGeP1COvll9Ln52M81PmuvMARaSj6omQ9POaASRQW2HMJYzkRWi6', '2024-10-09 06:51:47', '../uploads/Dokumentasi.png'),
(10, 'Frendhyy', 'frendhyzhuangg@gmail.com', '2005-01-26', '$2y$10$DH.YTePtbFUFny1V/Bo/eO2I85ozHMjDwlgtYHHYfnlpp2jvm/xci', '2024-10-09 06:52:31', '../uploads/SNAPSHOT.png'),
(11, 'Budi', 'budi123@gmail.com', '2001-01-01', '$2y$10$5BJjvtETFvaevQ6G.xatfeB431Q2ygHUmYw97UeZ8UGbsZ1ulSM3G', '2024-10-09 11:32:19', '../uploads/SNAPSHOT.png'),
(15, 'Andi', 'andi@gmail.com', '2001-01-01', '$2y$10$QYvbdQu.9UBbu8bT9uKDmO.KCuIajq2f1v0X6o9imGnmCImcKefyK', '2024-10-13 08:09:41', '../uploads/connect.PNG'),
(16, 'Rivo', 'rivoganteng123@gmail.com', '1997-08-19', '$2y$10$gbqaawJ75N04MysCzZ75NOI1LlIllCUQJsIhEEIdrcENhbSVfSa7K', '2024-10-18 11:35:57', '../uploads/contoh skema.jpg'),
(18, 'Audrey', 'audrey@gmail.com', '2005-10-05', '$2y$10$EVh8Mc4CU9hq0Dw6oPbo8OznhMieD1SB5bTRmGpHI0izddgGk4U7.', '2024-10-24 04:21:52', '../uploads/audrey.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `detail_event`
--
ALTER TABLE `detail_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participant`
--
ALTER TABLE `event_participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_photos`
--
ALTER TABLE `event_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_event`
--
ALTER TABLE `detail_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `event_participant`
--
ALTER TABLE `event_participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `event_photos`
--
ALTER TABLE `event_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_participant`
--
ALTER TABLE `event_participant`
  ADD CONSTRAINT `event_participant_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `detail_event` (`id`);

--
-- Constraints for table `event_photos`
--
ALTER TABLE `event_photos`
  ADD CONSTRAINT `event_photos_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `detail_event` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
