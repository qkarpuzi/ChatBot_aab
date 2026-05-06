-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 06, 2026 at 08:28 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aab_chatbot_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminet`
--

CREATE TABLE `adminet` (
  `id` int NOT NULL,
  `perdoruesi` varchar(50) NOT NULL,
  `fjalekalimi` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `krijuar_me` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bisedat`
--

CREATE TABLE `bisedat` (
  `id` int NOT NULL,
  `pyetje_user` text NOT NULL,
  `pergjigje_bot` text NOT NULL,
  `vendi_id` int DEFAULT NULL,
  `faq_id` int DEFAULT NULL,
  `default_id` int DEFAULT NULL,
  `saktesia` float DEFAULT NULL,
  `koha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int NOT NULL,
  `pyetja` text NOT NULL,
  `pergjigjja` text NOT NULL,
  `kategori_id` int DEFAULT NULL,
  `aktiv` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fjalet_kyce`
--

CREATE TABLE `fjalet_kyce` (
  `id` int NOT NULL,
  `fjala` varchar(100) NOT NULL,
  `lloji` varchar(50) NOT NULL,
  `vendi_id` int DEFAULT NULL,
  `faq_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategorite`
--

CREATE TABLE `kategorite` (
  `id` int NOT NULL,
  `emri` varchar(100) NOT NULL,
  `pershkrimi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pergjigjet_default`
--

CREATE TABLE `pergjigjet_default` (
  `id` int NOT NULL,
  `teksti` text NOT NULL,
  `aktiv` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `udhezimet`
--

CREATE TABLE `udhezimet` (
  `id` int NOT NULL,
  `nga_id` int NOT NULL,
  `tek_id` int NOT NULL,
  `instruksioni` text NOT NULL,
  `distanca` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendndodhjet`
--

CREATE TABLE `vendndodhjet` (
  `id` int NOT NULL,
  `emri` varchar(100) NOT NULL,
  `pershkrimi` text,
  `ndertesa` varchar(50) DEFAULT NULL,
  `kati` int DEFAULT NULL,
  `dhoma` varchar(20) DEFAULT NULL,
  `x` float DEFAULT NULL,
  `y` float DEFAULT NULL,
  `link_harta` varchar(255) DEFAULT NULL,
  `aktiv` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminet`
--
ALTER TABLE `adminet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perdoruesi` (`perdoruesi`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bisedat`
--
ALTER TABLE `bisedat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_vendi` (`vendi_id`),
  ADD KEY `fk_log_faq` (`faq_id`),
  ADD KEY `fk_log_default` (`default_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_faq_kategori` (`kategori_id`);

--
-- Indexes for table `fjalet_kyce`
--
ALTER TABLE `fjalet_kyce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kyce_vendi` (`vendi_id`),
  ADD KEY `fk_kyce_faq` (`faq_id`);

--
-- Indexes for table `kategorite`
--
ALTER TABLE `kategorite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pergjigjet_default`
--
ALTER TABLE `pergjigjet_default`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `udhezimet`
--
ALTER TABLE `udhezimet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nga` (`nga_id`),
  ADD KEY `fk_tek` (`tek_id`);

--
-- Indexes for table `vendndodhjet`
--
ALTER TABLE `vendndodhjet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminet`
--
ALTER TABLE `adminet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bisedat`
--
ALTER TABLE `bisedat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fjalet_kyce`
--
ALTER TABLE `fjalet_kyce`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategorite`
--
ALTER TABLE `kategorite`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pergjigjet_default`
--
ALTER TABLE `pergjigjet_default`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `udhezimet`
--
ALTER TABLE `udhezimet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendndodhjet`
--
ALTER TABLE `vendndodhjet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bisedat`
--
ALTER TABLE `bisedat`
  ADD CONSTRAINT `fk_log_default` FOREIGN KEY (`default_id`) REFERENCES `pergjigjet_default` (`id`),
  ADD CONSTRAINT `fk_log_faq` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`id`),
  ADD CONSTRAINT `fk_log_vendi` FOREIGN KEY (`vendi_id`) REFERENCES `vendndodhjet` (`id`);

--
-- Constraints for table `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `fk_faq_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategorite` (`id`);

--
-- Constraints for table `fjalet_kyce`
--
ALTER TABLE `fjalet_kyce`
  ADD CONSTRAINT `fk_kyce_faq` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`id`),
  ADD CONSTRAINT `fk_kyce_vendi` FOREIGN KEY (`vendi_id`) REFERENCES `vendndodhjet` (`id`);

--
-- Constraints for table `udhezimet`
--
ALTER TABLE `udhezimet`
  ADD CONSTRAINT `fk_nga` FOREIGN KEY (`nga_id`) REFERENCES `vendndodhjet` (`id`),
  ADD CONSTRAINT `fk_tek` FOREIGN KEY (`tek_id`) REFERENCES `vendndodhjet` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
