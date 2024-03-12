-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 12, 2024 at 10:25 PM
-- Server version: 8.0.36
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ucattu-db`
--
CREATE DATABASE IF NOT EXISTS `ucattu-db` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `ucattu-db`;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts` (
  `code` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name_en` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `district_center_code` int UNSIGNED NOT NULL,
  `district_center_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

DROP TABLE IF EXISTS `municipalities`;
CREATE TABLE `municipalities` (
  `code` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name_en` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `municipality_center_code` int UNSIGNED NOT NULL,
  `municipality_center_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `populated_places`
--

DROP TABLE IF EXISTS `populated_places`;
CREATE TABLE `populated_places` (
  `ecattu_id` int UNSIGNED NOT NULL,
  `type` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name_en` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `district_code` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `municipality_code` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `populated_places_types`
--

DROP TABLE IF EXISTS `populated_places_types`;
CREATE TABLE `populated_places_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`code`),
  ADD KEY `name` (`name`),
  ADD KEY `name_en` (`name_en`),
  ADD KEY `district_center_code` (`district_center_code`) USING BTREE;

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`code`),
  ADD KEY `name` (`name`),
  ADD KEY `name_en` (`name_en`),
  ADD KEY `municipality_center_code` (`municipality_center_code`) USING BTREE;

--
-- Indexes for table `populated_places`
--
ALTER TABLE `populated_places`
  ADD PRIMARY KEY (`ecattu_id`),
  ADD KEY `name` (`name`),
  ADD KEY `name_en` (`name_en`),
  ADD KEY `municipality_code` (`municipality_code`),
  ADD KEY `type` (`type`),
  ADD KEY `district_code` (`district_code`,`municipality_code`) USING BTREE;

--
-- Indexes for table `populated_places_types`
--
ALTER TABLE `populated_places_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `populated_places_types`
--
ALTER TABLE `populated_places_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `populated_places`
--
ALTER TABLE `populated_places`
  ADD CONSTRAINT `populated_places_ibfk_1` FOREIGN KEY (`district_code`) REFERENCES `districts` (`code`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `populated_places_ibfk_2` FOREIGN KEY (`municipality_code`) REFERENCES `municipalities` (`code`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `populated_places_ibfk_3` FOREIGN KEY (`type`) REFERENCES `populated_places_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
