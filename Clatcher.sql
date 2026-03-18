-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 16. Mrz 2026 um 18:25
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `Clatcher`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Anfrage`
--

CREATE TABLE `Anfrage` (
  `anfrage_userid` int(11) NOT NULL,
  `anfrage_freundid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Freunde`
--

CREATE TABLE `Freunde` (
  `freunde_userid` int(11) NOT NULL,
  `freunde_freundid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Posts`
--

CREATE TABLE `Posts` (
  `posts_id` int(11) NOT NULL,
  `posts_user` varchar(100) NOT NULL,
  `posts_text` text DEFAULT NULL,
  `posts_bild` mediumblob DEFAULT NULL,
  `posts_public` tinyint(1) NOT NULL,
  `posts_aim` varchar(100) DEFAULT NULL,
  `posts_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Report`
--

CREATE TABLE `Report` (
  `report_id` int(11) NOT NULL,
  `report_postid` int(11) NOT NULL,
  `report_userid` int(11) NOT NULL,
  `report_grund` varchar(255) NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Storage`
--

CREATE TABLE `Storage` (
  `storage_id` int(11) NOT NULL,
  `storage_filename` text NOT NULL,
  `storage_type` varchar(50) NOT NULL,
  `storage_file` longblob NOT NULL,
  `storage_owner` int(11) NOT NULL,
  `storage_key` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Useranswers`
--

CREATE TABLE `Useranswers` (
  `useranswers_id` int(11) NOT NULL,
  `useranswers_postid` int(11) NOT NULL,
  `useranswers_name` varchar(100) DEFAULT NULL,
  `useranswers_text` text NOT NULL,
  `useranswers_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Userposts`
--

CREATE TABLE `Userposts` (
  `userposts_id` int(11) NOT NULL,
  `userposts_name` varchar(100) NOT NULL,
  `userposts_sharedid` int(11) DEFAULT NULL,
  `userposts_sharedname` varchar(100) DEFAULT NULL,
  `userposts_image` longblob DEFAULT NULL,
  `userposts_mime` varchar(100) DEFAULT NULL,
  `userposts_text` text NOT NULL,
  `userposts_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Users`
--

CREATE TABLE `Users` (
  `users_id` int(11) NOT NULL,
  `users_name` varchar(100) NOT NULL,
  `users_pass` varchar(100) NOT NULL,
  `users_header` mediumblob DEFAULT NULL,
  `users_logo` mediumblob DEFAULT NULL,
  `users_background` mediumblob DEFAULT NULL,
  `users_mail` varchar(255) NOT NULL,
  `users_activated` tinyint(1) NOT NULL,
  `users_activatecode` varchar(30) DEFAULT NULL,
  `users_op` tinyint(1) NOT NULL,
  `users_admin` tinyint(1) NOT NULL,
  `users_banned` tinyint(1) NOT NULL,
  `users_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Usersite`
--

CREATE TABLE `Usersite` (
  `usersite_id` int(11) NOT NULL,
  `usersite_name` varchar(100) NOT NULL,
  `usersite_birthday` date DEFAULT NULL,
  `usersite_location` varchar(255) DEFAULT NULL,
  `usersite_job` varchar(255) DEFAULT NULL,
  `usersite_interests` varchar(255) DEFAULT NULL,
  `usersite_website` varchar(100) DEFAULT NULL,
  `usersite_eventimage` mediumblob DEFAULT NULL,
  `usersite_eventtitle` varchar(255) DEFAULT NULL,
  `usersite_eventtext` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Anfrage`
--
ALTER TABLE `Anfrage`
  ADD KEY `anfrage_userid` (`anfrage_userid`),
  ADD KEY `anfrage_freundid` (`anfrage_freundid`);

--
-- Indizes für die Tabelle `Freunde`
--
ALTER TABLE `Freunde`
  ADD KEY `freunde_userid` (`freunde_userid`),
  ADD KEY `freunde_freundid` (`freunde_freundid`);

--
-- Indizes für die Tabelle `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`posts_id`),
  ADD KEY `posts_user` (`posts_user`);

--
-- Indizes für die Tabelle `Report`
--
ALTER TABLE `Report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `report_postid` (`report_postid`),
  ADD KEY `report_userid` (`report_userid`);

--
-- Indizes für die Tabelle `Storage`
--
ALTER TABLE `Storage`
  ADD PRIMARY KEY (`storage_id`),
  ADD KEY `storage_owner` (`storage_owner`);

--
-- Indizes für die Tabelle `Useranswers`
--
ALTER TABLE `Useranswers`
  ADD PRIMARY KEY (`useranswers_id`),
  ADD KEY `useranswers_postid` (`useranswers_postid`),
  ADD KEY `useranswers_name` (`useranswers_name`);

--
-- Indizes für die Tabelle `Userposts`
--
ALTER TABLE `Userposts`
  ADD PRIMARY KEY (`userposts_id`),
  ADD KEY `userposts_name` (`userposts_name`);

--
-- Indizes für die Tabelle `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `users_name` (`users_name`),
  ADD UNIQUE KEY `users_mail` (`users_mail`);

--
-- Indizes für die Tabelle `Usersite`
--
ALTER TABLE `Usersite`
  ADD PRIMARY KEY (`usersite_id`),
  ADD KEY `usersite_name` (`usersite_name`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Posts`
--
ALTER TABLE `Posts`
  MODIFY `posts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `Report`
--
ALTER TABLE `Report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `Storage`
--
ALTER TABLE `Storage`
  MODIFY `storage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `Useranswers`
--
ALTER TABLE `Useranswers`
  MODIFY `useranswers_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `Userposts`
--
ALTER TABLE `Userposts`
  MODIFY `userposts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `Users`
--
ALTER TABLE `Users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `Usersite`
--
ALTER TABLE `Usersite`
  MODIFY `usersite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Anfrage`
--
ALTER TABLE `Anfrage`
  ADD CONSTRAINT `Anfrage_ibfk_1` FOREIGN KEY (`anfrage_userid`) REFERENCES `Users` (`users_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Anfrage_ibfk_2` FOREIGN KEY (`anfrage_freundid`) REFERENCES `Users` (`users_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Freunde`
--
ALTER TABLE `Freunde`
  ADD CONSTRAINT `Freunde_ibfk_1` FOREIGN KEY (`freunde_userid`) REFERENCES `Users` (`users_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Freunde_ibfk_2` FOREIGN KEY (`freunde_freundid`) REFERENCES `Users` (`users_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Posts`
--
ALTER TABLE `Posts`
  ADD CONSTRAINT `Posts_ibfk_1` FOREIGN KEY (`posts_user`) REFERENCES `Users` (`users_name`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Report`
--
ALTER TABLE `Report`
  ADD CONSTRAINT `Report_ibfk_1` FOREIGN KEY (`report_postid`) REFERENCES `Userposts` (`userposts_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Report_ibfk_2` FOREIGN KEY (`report_userid`) REFERENCES `Users` (`users_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Storage`
--
ALTER TABLE `Storage`
  ADD CONSTRAINT `Storage_ibfk_1` FOREIGN KEY (`storage_owner`) REFERENCES `Users` (`users_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Useranswers`
--
ALTER TABLE `Useranswers`
  ADD CONSTRAINT `Useranswers_ibfk_1` FOREIGN KEY (`useranswers_postid`) REFERENCES `Userposts` (`userposts_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Useranswers_ibfk_2` FOREIGN KEY (`useranswers_name`) REFERENCES `Users` (`users_name`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Userposts`
--
ALTER TABLE `Userposts`
  ADD CONSTRAINT `Userposts_ibfk_1` FOREIGN KEY (`userposts_name`) REFERENCES `Users` (`users_name`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `Usersite`
--
ALTER TABLE `Usersite`
  ADD CONSTRAINT `Usersite_ibfk_1` FOREIGN KEY (`usersite_name`) REFERENCES `Users` (`users_name`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
