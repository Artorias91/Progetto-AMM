-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Ago 09, 2015 alle 18:44
-- Versione del server: 5.5.35
-- Versione PHP: 5.4.6-1ubuntu1.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `continiMattia`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `nome` varchar(128) DEFAULT NULL,
  `cognome` varchar(128) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `nome`, `cognome`) VALUES
(1, 'pluto', '123456', 'pluto@tiscali.it', 'Maria', 'Mario');

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE IF NOT EXISTS `articoli` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(7) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `prezzo` float DEFAULT NULL,
  `pizza_id` bigint(20) unsigned DEFAULT NULL,
  `ordine_id` bigint(20) unsigned DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `pizze_fk` (`pizza_id`),
  KEY `ordini_fk` (`ordine_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id`, `size`, `qty`, `prezzo`, `pizza_id`, `ordine_id`) VALUES
(1, 'Normale', 3, 7.5, 2, 1),
(2, 'Gigante', 2, 8, 3, 1),
(3, 'Ridotta', 1, 4, 5, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE IF NOT EXISTS `clienti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `nome` varchar(128) DEFAULT NULL,
  `cognome` varchar(128) DEFAULT NULL,
  `indirizzo_id` bigint(20) unsigned DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `indirizzi_clienti` (`indirizzo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `username`, `password`, `email`, `nome`, `cognome`, `indirizzo_id`) VALUES
(1, 'pippo', '123456', 'pippo@tiscali.it', 'Mattia', 'Contini', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti_pagamenti`
--

CREATE TABLE IF NOT EXISTS `clienti_pagamenti` (
  `clienti_id` bigint(20) unsigned DEFAULT NULL,
  `pagamenti_id` bigint(20) unsigned DEFAULT NULL,
  KEY `clienti_fk` (`clienti_id`),
  KEY `pagamenti_fk` (`pagamenti_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `clienti_pagamenti`
--

INSERT INTO `clienti_pagamenti` (`clienti_id`, `pagamenti_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi`
--

CREATE TABLE IF NOT EXISTS `indirizzi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `destinatario` varchar(128) DEFAULT NULL,
  `via_num` varchar(128) DEFAULT NULL,
  `citta` varchar(128) DEFAULT NULL,
  `provincia` varchar(128) DEFAULT NULL,
  `cap` varchar(5) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `indirizzi`
--

INSERT INTO `indirizzi` (`id`, `destinatario`, `via_num`, `citta`, `provincia`, `cap`, `telefono`) VALUES
(1, 'Mattia Contini', 'via Monte Claro 27', 'Sinnai', 'Cagliari', '09048', '0987654321');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_conclusione` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data_creazione` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `prezzo` float DEFAULT NULL,
  `spedito` tinyint(1) DEFAULT NULL,
  `cliente_id` bigint(20) unsigned DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `clienti_fk` (`cliente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`id`, `data_conclusione`, `data_creazione`, `prezzo`, `spedito`, `cliente_id`) VALUES
(1, '2015-07-13 10:42:41', '2015-07-13 10:35:28', 19.5, NULL, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `pagamenti`
--

CREATE TABLE IF NOT EXISTS `pagamenti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `saldo` float DEFAULT NULL,
  `num_carta` varchar(16) DEFAULT NULL,
  `cod_carta` varchar(3) DEFAULT NULL,
  `scadenza_carta` date DEFAULT NULL,
  `titolare_carta` varchar(128) DEFAULT NULL,
  `tipo_carta` varchar(128) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `pagamenti`
--

INSERT INTO `pagamenti` (`id`, `saldo`, `num_carta`, `cod_carta`, `scadenza_carta`, `titolare_carta`, `tipo_carta`) VALUES
(1, 50, '1451541414541545', '123', '2019-09-01', 'Mattia Contini', 'Postepay'),
(2, 20.6, '1751541494544541', '321', '2025-04-01', 'Mateo Contin', 'Visa');

-- --------------------------------------------------------

--
-- Struttura della tabella `pizze`
--

CREATE TABLE IF NOT EXISTS `pizze` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(128) DEFAULT NULL,
  `ingredienti_extra` varchar(128) DEFAULT NULL,
  `prezzo` float DEFAULT NULL,
  `image_url` varchar(128) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `pizze`
--

INSERT INTO `pizze` (`id`, `nome`, `ingredienti_extra`, `prezzo`, `image_url`) VALUES
(1, 'Margerita', NULL, 2.5, NULL),
(2, 'Napoli', 'Capperi e acciughe', 2.5, NULL),
(3, 'Canadese', 'Prosciutto cotto, funghi e wurstel', 3.5, NULL),
(4, 'Tonno', 'Tonno, pepperoni, funghi e olive nere', 4.5, 'img/pizza1.jpg'),
(5, 'Broccoletti', 'Broccoletti, pepperoni, funghi e olive nere', 4.5, 'img/pizza2.jpg'),
(6, 'Salame', 'Salame, pepperoni, funghi e olive nere', 4.5, 'img/pizza3.jpg');

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `articoli`
--
ALTER TABLE `articoli`
  ADD CONSTRAINT `articoli_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizze` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articoli_ibfk_2` FOREIGN KEY (`ordine_id`) REFERENCES `ordini` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `clienti`
--
ALTER TABLE `clienti`
  ADD CONSTRAINT `clienti_ibfk_1` FOREIGN KEY (`indirizzo_id`) REFERENCES `indirizzi` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `clienti_pagamenti`
--
ALTER TABLE `clienti_pagamenti`
  ADD CONSTRAINT `clienti_pagamenti_ibfk_1` FOREIGN KEY (`clienti_id`) REFERENCES `clienti` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `clienti_pagamenti_ibfk_2` FOREIGN KEY (`pagamenti_id`) REFERENCES `pagamenti` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
