-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 23, 2024 at 01:28 PM
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
(14, 'Gaddafi', 'Weapons Development', '2024-08-25 17:28:04'),
(16, 'The Landlord', 'Finance', '2024-08-25 19:26:27'),
(17, 'TC', 'Sleep', '2024-08-25 19:38:38'),
(18, 'MetroGroomin', 'Music', '2024-08-25 19:46:25'),
(19, 'Espien123', 'Math', '2024-08-28 11:10:14'),
(22, 'PolishNa', 'Polish', '2024-09-14 16:16:12'),
(23, 'Espien123', 'Workout', '2024-09-16 16:28:42'),
(26, 'Yamak', 'Sports', '2024-09-20 16:41:54'),
(29, 'The Jack', 'Reading', '2024-09-22 13:59:01'),
(30, 'overdramatize', 'amking', '2024-09-22 14:11:11'),
(31, 'Ally1', 'Math', '2024-09-22 16:56:55'),
(32, 'Ally1', 'Geography', '2024-09-23 10:41:28'),
(33, 'Crypto12', 'Gambling', '2024-09-23 11:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('in progress','stand by','completed') NOT NULL DEFAULT 'in progress',
  `task_list_id` int(11) NOT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `assigned` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `timestamp`, `status`, `task_list_id`, `owner`, `assigned`) VALUES
(10, 'Portugese', '2024-08-25 16:04:54', 'in progress', 8, 'Espien123', NULL),
(12, 'Mandarin', '2024-08-25 16:06:37', 'in progress', 8, 'Espien123', 'Jaywalk'),
(14, 'Snow', '2024-08-25 17:37:41', 'in progress', 14, 'Gaddafi', NULL),
(15, 'Drone R&D', '2024-08-25 17:37:51', 'in progress', 14, 'Gaddafi', NULL),
(20, 'Stocks', '2024-08-25 19:26:29', 'in progress', 16, 'The Landlord', NULL),
(21, 'Dream', '2024-08-25 19:38:41', 'in progress', 17, 'TC', NULL),
(23, 'rap', '2024-08-25 19:46:40', 'in progress', 18, 'MetroGroomin', 'NickM'),
(32, 'Crypto', '2024-08-28 15:27:11', 'in progress', 1, 'Jaywalk', 'Espien123'),
(33, 'Alphabet', '2024-09-14 16:16:14', 'in progress', 22, 'PolishNa', NULL),
(35, 'll', '2024-09-16 16:31:33', 'in progress', 23, 'Espien123', NULL),
(41, 'Go golfing at 5 PM', '2024-09-20 16:42:00', 'in progress', 26, 'Yamak', NULL),
(42, 'Jog near the church', '2024-09-20 16:42:09', 'stand by', 26, 'Yamak', NULL),
(44, 'Fiction', '2024-09-22 13:59:07', 'in progress', 29, 'The Jack', NULL),
(45, 'English', '2024-09-22 14:00:30', 'in progress', 29, 'The Jack', NULL),
(48, 'church', '2024-09-22 14:11:17', 'in progress', 30, 'overdramatize', NULL),
(49, 'Geometry', '2024-09-22 16:56:59', 'completed', 31, 'Ally1', NULL),
(51, 'Linear', '2024-09-23 10:40:48', 'in progress', 31, 'Ally1', NULL),
(53, 'Calculus', '2024-09-23 10:41:03', 'in progress', 31, 'Ally1', NULL),
(54, 'Earthquakes', '2024-09-23 10:41:36', 'stand by', 32, 'Ally1', 'Espien123'),
(56, 'Ocean', '2024-09-23 10:42:05', 'in progress', 32, 'Ally1', NULL),
(57, 'Sports', '2024-09-23 11:23:47', 'in progress', 33, 'Crypto12', 'Ally1'),
(58, 'Horse race', '2024-09-23 11:24:11', 'completed', 33, 'Crypto12', NULL);

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
('1234', '1234', '1234', '$2y$10$87AUB/77ihnR9mv8BXEE4.6PnXkLK4BmD2HZX9YpuAHPb6ScMm9u.', '1234@135', NULL),
('Alex', 'Hamilton', 'Ally1', '$2y$10$eHGOU0Ks9IdqgsSFnzxaGeCPhZhPbedhqynuCY9cbmoJDKrwTGufK', 'Hamilton@gmail.com', NULL),
('brunizems', 'entamoeba', 'anaglyphs', '$2y$10$rNu2u6h4Lo1w1nr9JP1ipOMIbDdedY9so.WoGHneFhaDStoWBbygm', 'wimbles@exhibited', NULL),
('Anita', 'Max Wynn', 'Anita Maxwin', '$2y$10$aE441V0O1lPOJ6JtSad1s.RD8MjPKt19gUp8X9XqSspS/9v/4OdDa', 'AMax@gmail.com', 'wsDTzJ'),
('culprit', 'fascistically', 'cory', '$2y$10$KxRAvNY3l0CoOle7KAkXaejXfBONwcrpICaGxqZUe7CCCYd9ydME2', 'sclerosis', NULL),
('Mark', 'Shkreli', 'Crypto12', '$2y$10$7XnRkW4e87UDYDv.cqU8fe7gPUt05XLM3u36hAcH.JTdhB6jkby0C', 'Martin@gmail.com', NULL),
('equivocate', 'ramtil', 'discloses', '$2y$10$L6tOwyw1tGznJdeCABUie.wBD3YTRiL6CBKm6Sf1SBDKI3Pux3rM.', 'wastrels@astragali', NULL),
('Darius', 'Jackson', 'DJ ignite', '$2y$10$yMDZ7lOOzwXKrjtFyfJkDu/CxJwVNhPtMY9JMYHlesHuWI8O4TPIS', 'perseverance@gmail.com', NULL),
('Aubrey', 'Graham', 'Dropper', '$2y$10$GFNl1Mvmy97F4ZnFoYypH.ow0KbCkYpTEF9yGPVh3gNDNp9E0CXlq', 'Aubrey43434@gmail.com', NULL),
('prevue', 'lurkingly', 'enlarges', '$2y$10$5Q7/ZbY2dJP95VQnbTSOpuqszsCAPoVbKgHCxUYqtLQ8nsbrCpMry', 'disestablished', NULL),
('Sarah', 'Jones', 'Espien123', '$2y$10$7tiDPpkEIT5XoqVV1cAmEOvdTuJONn0.oexGB7/yKl2AKblvP/3Jm', 'SarahJJ57@gmail.com', NULL),
('didynamies', 'paseo', 'fatwood', '$2y$10$3PYKa/mcfAmageRFTMlVr.7bp4EpTdLmZ2Vq5EEpIgalivFBA.cgy', 'mistitles@nutwood', NULL),
('Ø§Ù„Ù‚Ø°Ø§ÙÙŠ', 'Ù…Ø¹Ù…Ø±', 'Gaddafi', '$2y$10$FpT5XesiIsIdlkZ9ZDQijur/9kJjkGbeTF3kUqDrojaKSo58yFsCa', 'gad@gmail.com', NULL),
('Jay', 'Walker', 'Jaywalk', '$2y$10$cO.NKEoLekD8no2e/RnoKOQDEyir.PM9hj6MdJsyg.R0VR9XaPFdW', 'JayWalker@gmail.com', NULL),
('Jacques', 'Rousseau', 'Jean', '$2y$10$LEfcWau9.clFzTnjdWUS9.mdRUIIM0rfEj9Ht/niYOFU4C1ddzjYi', 'Philosophist@gmail.com', NULL),
('Ken', 'Kaine', 'Kenke', '$2y$10$IrfM0RxV1.qcUOxDkM2LgOhTG73pQmvylwlkCbccikjv5UDzFtZgu', 'ken123@gmail.com', NULL),
('Leon', 'Jones', 'LeeJ', '$2y$10$xtNrahQ2bpGsVd8YSxZJ6O301No50AZmCxk6bFg4njlgyZ8Yf3Gf.', 'LeeJones@gmail.com', 'G8f3jF'),
('Mallards', 'CB', 'MallardCB', '$2y$10$fpcsDOgJ61LN48gJxEv9buh.5FUDn8HI9DHZfA/Y3pLDqrbAXQgNy', 'mallory@yahoomail.com', NULL),
('Metro', 'Boomin', 'MetroGroomin', '$2y$10$VZYvBC7eOXNpT3YjJ7I3seL8IFpVn9d1p3zreM9MUpnP6pSGaHvr.', 'Metroooo@gmail.com', NULL),
('Stephan', 'Borelli', 'Mr. Borelli', '$2y$10$pGNgdSD2wECX3V9kEuI12uPpPg0I1yhAm9mY2bSa9rJf2YqJgnb2O', 'stevenbonnell@gmail.com', NULL),
('James', 'Brown', 'Mr. Brown', '$2y$10$iWlSF2C9kaVmbIOLkSFAqOoatKXXalKoz31wte3wVBC3oUTwIqxmi', 'JB@gmail.com', NULL),
('sunny', 'ironmasters', 'nation', '$2y$10$CreGvF7ZtuwzD1DxIxjlLORy5pW14l8iy1U7iISKtidhfl4K0808m', 'light', NULL),
('Nick', 'm', 'NickM', '$2y$10$sggzLgN72/nKJeDHlujRuepUG/5.6elRutRd7yKkV0eU7b1vW4aoq', 'n@gmail.com', '8JbxPv'),
('Steinersss', 'Gatezzz', 'Okabe sans', '$2y$10$10kIN.ALKUs6Ltz.CMoaZ.eeOqnjrQ4YJ/ebOc1HJSXeK8OW81Viu', 'cern@gmail', NULL),
('hemophilics', 'ingressiveness', 'overdramatize', '$2y$10$6rryj9bwFx9opfljMCKWVePV.o45sAmeiUAmJxH4CuNwi8/EvZTGq', 'imbruted@downtown', NULL),
('Naku', 'Poll', 'PolishNa', '$2y$10$5KQFPIUay3C8tSGZ1nPS/Ov7e4HphnbTX0F3rA9RbS.sVxq19j1S6', 'PP@gmail.com', NULL),
('heterotrophs', 'stutterer', 'supercar', '$2y$10$mNZosPc7LGMb4RU6SQpHMOcQRr9ekIoZRiIb75kEZcG5MNMpdCb3.', 'mestino', NULL),
('provider', 'wovens', 'supernatants', '$2y$10$3OGKh1UoKXLig14mXu1dZe9FfVyWMwEGANjKkOXYYbWZxSLXOkgji', 'veneration', NULL),
('Tom', 'Clancy', 'TC', '$2y$10$h1d9tww5RvMX13nbF7Ui1OWoWF9rkAJ3zxkqlmnPdv7W0N1YoKgne', 'Tom@gmail.com', NULL),
('New', 'Acc', 'The Jack', '$2y$10$rpzqNUmUf8xqQCBCkV36M.Oh3/lXNVBR1/wlIOPU/ba5WYOQe9.R.', 'f@fllz', NULL),
('Daniel', 'Saltman', 'The Landlord', '$2y$10$JW7AkJXjYM0AeCbCO3EC2OQiZ657Wlk3p1.MLcvSkUn8KgE4yhBVq', 'DanSalt@gmail.com', 'Uekieu'),
('cruzados', 'dodecaphony', 'traitoress', '$2y$10$xEEZot0VRu1LfR0tcLQ.ZeMxhFNRypE3LhEj5rROwDGWw7v6UChbm', 'emplaced', NULL),
('Akira', 'Yamaoka', 'Yamak', '$2y$10$tv8GQBPECn1l5Ko2zFH40ewpTA.G1seBDXGyYMa9H2KE.zVm446Ma', 'Akira456@gmail.com', NULL),
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
  ADD KEY `task_list_id` (`task_list_id`),
  ADD KEY `fk_owner` (`owner`),
  ADD KEY `fk_assigned` (`assigned`);

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
  MODIFY `task_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
  ADD CONSTRAINT `fk_assigned` FOREIGN KEY (`assigned`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`owner`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`task_list_id`) REFERENCES `taskLists` (`task_list_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
