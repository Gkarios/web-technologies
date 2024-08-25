-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 25, 2024 at 04:38 PM
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
-- Table structure for table `taskLists`
--

CREATE TABLE `taskLists` (
  `task_list_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `list_title` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `taskLists`
--

INSERT INTO `taskLists` (`task_list_id`, `username`, `list_title`, `timestamp`) VALUES
(1, 'Jaywalk', '??', '2024-08-09 13:16:09'),
(2, 'NickM', 'dfs', '2024-08-09 13:27:15'),
(3, 'Jaywalk', 'ds', '2024-08-09 13:22:53'),
(8, 'Espien123', 'language', '2024-08-25 13:31:41'),
(10, 'Espien123', 'music', '2024-08-25 14:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('in progress','stand by','completed') NOT NULL DEFAULT 'in progress',
  `task_list_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `timestamp`, `status`, `task_list_id`) VALUES
(1, 'English', '2024-08-25 14:10:13', 'in progress', 8),
(2, 'Guitar', '2024-08-25 14:10:55', 'in progress', 10),
(4, 'French', '2024-08-25 14:30:50', 'in progress', 8);

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
('culprit', 'fascistically', 'cory', '$2y$10$KxRAvNY3l0CoOle7KAkXaejXfBONwcrpICaGxqZUe7CCCYd9ydME2', 'sclerosis', NULL),
('Darius', 'Jackson', 'DJ ignite', '$2y$10$yMDZ7lOOzwXKrjtFyfJkDu/CxJwVNhPtMY9JMYHlesHuWI8O4TPIS', 'perseverance@gmail.com', NULL),
('Aubrey', 'Graham', 'Dropper', '$2y$10$GFNl1Mvmy97F4ZnFoYypH.ow0KbCkYpTEF9yGPVh3gNDNp9E0CXlq', 'Aubrey43434@gmail.com', NULL),
('prevue', 'lurkingly', 'enlarges', '$2y$10$5Q7/ZbY2dJP95VQnbTSOpuqszsCAPoVbKgHCxUYqtLQ8nsbrCpMry', 'disestablished', NULL),
('Sarah', 'Jones', 'Espien123', '$2y$10$7tiDPpkEIT5XoqVV1cAmEOvdTuJONn0.oexGB7/yKl2AKblvP/3Jm', 'SarahJJ56@gmail.com', NULL),
('Jay', 'Walker', 'Jaywalk', '$2y$10$cO.NKEoLekD8no2e/RnoKOQDEyir.PM9hj6MdJsyg.R0VR9XaPFdW', 'JayWalker@gmail.com', NULL),
('Leon', 'Jones', 'LeeJ', '$2y$10$xtNrahQ2bpGsVd8YSxZJ6O301No50AZmCxk6bFg4njlgyZ8Yf3Gf.', 'LeeJones@gmail.com', 'G8f3jF'),
('Mallards', 'CB', 'MallardCB', '$2y$10$fpcsDOgJ61LN48gJxEv9buh.5FUDn8HI9DHZfA/Y3pLDqrbAXQgNy', 'mallory@yahoomail.com', NULL),
('Stephan', 'Borelli', 'Mr. Borelli', '$2y$10$pGNgdSD2wECX3V9kEuI12uPpPg0I1yhAm9mY2bSa9rJf2YqJgnb2O', 'stevenbonnell@gmail.com', NULL),
('James', 'Brown', 'Mr. Brown', '$2y$10$iWlSF2C9kaVmbIOLkSFAqOoatKXXalKoz31wte3wVBC3oUTwIqxmi', 'JB@gmail.com', NULL),
('Nick', 'm', 'NickM', '$2y$10$sggzLgN72/nKJeDHlujRuepUG/5.6elRutRd7yKkV0eU7b1vW4aoq', 'n@gmail.com', '8JbxPv'),
('heterotrophs', 'stutterer', 'supercar', '$2y$10$mNZosPc7LGMb4RU6SQpHMOcQRr9ekIoZRiIb75kEZcG5MNMpdCb3.', 'mestino', NULL),
('provider', 'wovens', 'supernatants', '$2y$10$3OGKh1UoKXLig14mXu1dZe9FfVyWMwEGANjKkOXYYbWZxSLXOkgji', 'veneration', NULL),
('cruzados', 'dodecaphony', 'traitoress', '$2y$10$xEEZot0VRu1LfR0tcLQ.ZeMxhFNRypE3LhEj5rROwDGWw7v6UChbm', 'emplaced', NULL),
('Yassin', 'Brown', 'YBRR', '$2y$10$Gd/VLZY8lGjb1/DSW6yzQ.OsaKFO8gLdBk.P/fgIGcUCup0xmBER6', 'YassinB@gmail.com', NULL);

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
-- Indexes for table `taskLists`
--
ALTER TABLE `taskLists`
  ADD PRIMARY KEY (`task_list_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_list_id` (`task_list_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `simplepushKey` (`simplepushKey`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `taskLists`
--
ALTER TABLE `taskLists`
  MODIFY `task_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `taskLists`
--
ALTER TABLE `taskLists`
  ADD CONSTRAINT `taskLists_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`task_list_id`) REFERENCES `taskLists` (`task_list_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
