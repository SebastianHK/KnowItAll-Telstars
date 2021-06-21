-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 jun 2021 om 14:13
-- Serverversie: 10.4.17-MariaDB
-- PHP-versie: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `knowitall`
--
CREATE DATABASE IF NOT EXISTS `knowitall` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `knowitall`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruikers`
--

CREATE TABLE `gebruikers` (
  `id` int(11) NOT NULL,
  `gebruiker` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `wachtwoord` varchar(100) NOT NULL,
  `rank` varchar(50) NOT NULL DEFAULT 'gebruiker'
) ENGINE=InnoDB DEFAULT CHARSET=armscii8;

--
-- Gegevens worden geëxporteerd voor tabel `gebruikers`
--

INSERT INTO `gebruikers` (`id`, `gebruiker`, `email`, `wachtwoord`, `rank`) VALUES
(1, 'admin', 'admin@admin.nl', '$2y$10$pDeIvcwHrbpF8KOBQ88kouQ1x4/LK38gg9Wb9hYq2nRTg5XeG9xgy', 'admin'),
(2, 'jan', 'jan@jan.jan', '$2y$10$xFn22c2q6UrSxy1NhkiYvOJOWFlRIa.146oQfxEyUIlZdN7vJdoEq', 'gebruiker'),
(8, 'piet', 'piet@piet.piet', '$2y$10$Ep7Dr1mO5sJ6qjlmBYV/4O7Qcnq/gM36q4gM2uRvL7KiKJL4YRGda', 'gebruiker'),
(9, 'henk', 'henk@henk.henk', '$2y$10$C0IhvBBHuLyV1ZvwgVq3yejPDZ3Oa1k2nSgyibAss5sOC1EJ.KegS', 'gebruiker'),
(10, 'Lex', 'lex@lex.lex', '$2y$10$v8U6gnZV4Py.nYUrbI/GlOHsyw5rut0KcHdj.5PDSq9pT3hQEYufW', 'gebruiker');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `weetjesdb`
--

CREATE TABLE `weetjesdb` (
  `id` int(11) NOT NULL,
  `titel` varchar(50) NOT NULL,
  `weetjes` varchar(500) NOT NULL,
  `gebruiker` varchar(30) NOT NULL,
  `plaats_datum` date NOT NULL DEFAULT current_timestamp(),
  `geb_datum` date DEFAULT NULL,
  `plaatje` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'niet_reviewed',
  `comment` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `weetjesdb`
--

INSERT INTO `weetjesdb` (`id`, `titel`, `weetjes`, `gebruiker`, `plaats_datum`, `geb_datum`, `plaatje`, `status`, `comment`) VALUES
(6, '', 'dfsdfds', 'Henk', '2021-05-17', '0000-00-00', '', 'goedgekeurd', ''),
(7, '', 'dsfsdf', 'Henk', '2021-05-17', '0000-00-00', '', 'goedgekeurd', ''),
(8, '', 'kekekekolo', 'Henk', '2021-05-17', '0000-00-00', '', 'goedgekeurd', ''),
(10, '', 'ikmotnaarschool\r\n', 'Henk', '2021-05-17', '0000-00-00', '', 'goedgekeurd', ''),
(38, '', 'wweetjfeeeeee', 'henk', '2021-06-02', '2021-06-03', '', 'goedgekeurd', ''),
(40, '', 'wpegtrhehrt', 'piet', '2021-06-03', '0000-00-00', '', 'goedgekeurd', ''),
(42, '', 'dit is een weetje, weetje?', 'admin', '2021-06-10', '2021-06-10', '', 'goedgekeurd', ''),
(50, '', 'dit weetje is afgekeurd', 'admin', '2021-06-15', '0000-00-00', '', 'afgekeurd', 'afgekeurd');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `weetjesdb`
--
ALTER TABLE `weetjesdb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `weetjesdb`
--
ALTER TABLE `weetjesdb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
