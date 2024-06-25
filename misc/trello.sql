-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 25, 2024 at 12:10 PM
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
-- Database: `trello`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(255) NOT NULL,
  `email` varchar(33) NOT NULL,
  `simplepushKey` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`firstName`, `lastName`, `username`, `password`, `email`, `simplepushKey`) VALUES
('Aubrey', 'Graham', 'Dropper', '$2y$10$GFNl1Mvmy97F4ZnFoYypH.ow0KbCkYpTEF9yGPVh3gNDNp9E0CXlq', 'Aubrey43434@gmail.com', NULL),
('Jay', 'Walker', 'Jaywalker', '$2y$10$Nze1b/yM/ehKelrWxTekYuF2xcg7X.C/EagpPCHBXajBeKCzYc76S', 'JayWalker@gmail.com', NULL),
('Leon', 'Jones', 'LeeJ', '$2y$10$xtNrahQ2bpGsVd8YSxZJ6O301No50AZmCxk6bFg4njlgyZ8Yf3Gf.', 'LeeJones@gmail.com', 'G8f3jF'),
('Method', 'Man', 'Method Man', '$2y$10$YAX4ujxoWyzOfKnyrAVmKecYOZ5MbUEEMsU0ScBcuVyKcSBSWirey', 'Methodman35@ionio.gr', 'Uekieu'),
('Stephan', 'Borelli', 'Mr. Borelli', '$2y$10$pGNgdSD2wECX3V9kEuI12uPpPg0I1yhAm9mY2bSa9rJf2YqJgnb2O', 'stevenbonnell@gmail.com', NULL);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `before_insert_simplepushKey` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.simplepushKey = '' THEN
        SET NEW.simplepushKey = NULL;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_simplepushKey` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.simplepushKey = '' THEN
        SET NEW.simplepushKey = NULL;
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `simplepushKey` (`simplepushKey`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
