-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 24, 2024 at 01:37 PM
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
('antonio', 'lyric', 'anth', '$2y$10$ZDJ8lB7Qgp2sI607048NOenZpUSkBsZhBoQu/0LPngTruzHlk8l.y', 'lyric1245@gmail.com', ''),
('Steven', 'Bonnell', 'Destiny', '$2y$10$ro2aMCesuokn//XDGATUp.7D9iYZ1/S1a/GwhkiVcG5TnOVZq.xEi', 'stevenbonnell@gmail.com', ''),
('ready', 'bee', 'layin', '$2y$10$YLMmZpg3YG7BdKJvYisutuuK/TYwMFvjlY4rZ8KTezMqJAi0N3w1K', 'lays@gmail.com', ''),
('Leon', 'Jones', 'LeeJ', '$2y$10$xtNrahQ2bpGsVd8YSxZJ6O301No50AZmCxk6bFg4njlgyZ8Yf3Gf.', 'LeeJones@gmail.com', 'G8f3jF'),
('Mal', 'CIBIS', 'MALCB', '$2y$10$52/cO8qgR5r.VJcy0hlXf.VDKk3Z04MMU5i0aCQW8q7no5.vHaY.G', 'malcb@ionio.gr', ''),
('mallard', 'CB', 'mallCB', '$2y$10$qiYxE0PzyezGl9JkOi5WYOLFbrTHMpNhfei5wWd9JS2fEY.oGy5Z.', 'mallory@yahoomail.com', ''),
('MC', 'Ryde', 'MC Ryde', '$2y$10$sV7QX43jyFBX/rt4VT8wZ.sE9Gc.COlXTFaJTLh0qPjDrypc5VHNi', 'mcryde@gmail.com', ''),
('Yassin', 'Beau', 'Mos', '$2y$10$37f24NaQDjlZGRH7/gSCMORyZ/X7l5tY36TRr8o/IPvMkz6lUBBYW', 'YassinB@gmail.com', 'Uekieu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `simplepushKey` (`simplepushKey`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
