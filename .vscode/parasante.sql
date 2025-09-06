-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 05:10 PM
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
-- Database: `parasante`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `nom`, `email`, `mot_de_passe`, `created_at`) VALUES
(1, 'hannachiii', 'hannachizina338@gmail.com', '$2y$10$LqsgWBjuSfTOY3mZqCa/KOfXq8OiJjb7NgxsQxpb1m0Jj6z6Cgoa.', '2025-07-31 20:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `produit` text DEFAULT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` varchar(50) NOT NULL,
  `paiement` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commandes`
--

INSERT INTO `commandes` (`id`, `nom`, `adresse`, `telephone`, `produit`, `date_commande`, `statut`, `paiement`) VALUES
(62, 'kk', 'tunis', '97183233', '[{\"nom\":\"445\",\"prix\":\"54\"}]', '2025-09-03 15:37:24', 'validée', '[]'),
(63, ',', ';;lm', 'llpm', '[{\"nom\":\"445\",\"prix\":\"54\"}]', '2025-09-03 16:37:10', 'validée', '[]'),
(64, 'creme', 'tunis', '97183233', '[{\"nom\":\"1555\",\"prix\":\"455\"}]', '2025-09-03 16:43:41', 'validée', '[]'),
(65, 'll', 'll', 'mm', '[{\"nom\":\"1555\",\"prix\":\"455\"}]', '2025-09-03 16:46:56', 'validée', '[]'),
(66, 'Admin Principal', 'tunis', '97183233', '[{\"nom\":\"hfd14\",\"prix\":\"20\"},{\"nom\":\"jzjdai\",\"prix\":\"55\"}]', '2025-09-04 07:19:02', '', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` float NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `image`) VALUES
(31, 'creme', 'xkqks', 14, 'Face Sunscreen Avène Anti-imperfections for oily….jpeg'),
(32, 'hfd14', 'djijfeazi', 20, 'Face Sunscreen Avène Anti-imperfections for oily….jpeg'),
(33, '1555', 'mml', 455, 'Face Sunscreen Avène Anti-imperfections for oily….jpeg'),
(34, '445', 'en promo %50', 54, 'Face Sunscreen Avène Anti-imperfections for oily….jpeg'),
(35, '44455', 'kkok', 455, 'Face Sunscreen Avène Anti-imperfections for oily….jpeg'),
(36, 'jzjdai', 'oejfv', 55, 'Face Sunscreen Avène Anti-imperfections for oily….jpeg'),
(37, 'rouge a lebre', 'jiogreior', 15.15, 'images (2).jpeg'),
(38, 'hh', 'kalakm la,al nl,l', 45, '3d9d200e77520aff5d4ca2a5d7f84097.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
