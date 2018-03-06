-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 06, 2018 alle 12:37
-- Versione del server: 10.1.25-MariaDB
-- Versione PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `are2`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `path` varchar(2056) NOT NULL,
  `class` enum('anger','contempt','disgust','fear','happiness','neutral','sadness','surprise') NOT NULL,
  `cs_vision` text,
  `cs_emotion` text,
  `our_class` enum('anger','contempt','disgust','fear','happiness','neutral','sadness','surprise') DEFAULT NULL,
  `id_dataset` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `images_facebook`
--

CREATE TABLE `images_facebook` (
  `id` int(11) NOT NULL,
  `facebook_id` bigint(20) NOT NULL,
  `url` varchar(8192) NOT NULL,
  `cs_vision` text,
  `cs_emotion` text,
  `our_class` enum('anger','contempt','disgust','fear','happiness','neutral','sadness','surprise') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `images_upload`
--

CREATE TABLE `images_upload` (
  `id` int(11) NOT NULL,
  `filename` varchar(256) CHARACTER SET utf8 NOT NULL,
  `path` varchar(2056) CHARACTER SET utf8 NOT NULL,
  `cs_vision` text CHARACTER SET utf8,
  `cs_emotion` text CHARACTER SET utf8,
  `our_class` enum('anger','contempt','disgust','fear','happiness','neutral','sadness','surprise') CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `images_facebook`
--
ALTER TABLE `images_facebook`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_id` (`facebook_id`);

--
-- Indici per le tabelle `images_upload`
--
ALTER TABLE `images_upload`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `images_facebook`
--
ALTER TABLE `images_facebook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `images_upload`
--
ALTER TABLE `images_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
