-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2016 at 05:57 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `veritas3`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE IF NOT EXISTS `order_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `enable` int(11) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '0',
  `titleFrench` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `title`, `enable`, `number`, `titleFrench`) VALUES
(1, 'Premium National Criminal Record Check', 1, 1603, 'Programme national de vérification approfondie de casiers judiciaires'),
(2, 'Driver''s Record Abstract', 1, 1, 'Dossiers du conducteur (MVR)'),
(3, 'CVOR', 1, 14, 'IUVU (CVOR)'),
(4, 'Pre-employment Screening Program Report', 1, 77, 'Rapport du programme de vérification avant l’emploi'),
(5, 'TransClick', 1, 78, 'TransClick'),
(6, 'Education Verification', 1, 1650, 'Vérification de l’éducation'),
(7, 'Letter Of Experience', 1, 1627, 'Lettre d’expérience'),
(8, 'Check DL', 1, 72, 'Vérifier DL'),
(9, 'Social Media Search', 0, 32, 'Recherche sur les médias sociaux'),
(10, 'Credit Check', 0, 31, 'Vérification de crédit'),
(12, 'Social Media Footprint', 0, 99, 'Social Media Footprint'),
(13, 'Social Media Surveillance', 0, 500, 'Social Media Surveillance'),
(14, 'Physical Surveillance', 0, 501, 'Physical Surveillance'),
(24, 'Social Media Investigation', 1, 601, 'Social Media Investigation'),
(25, 'Drug Test', 1, 602, 'Drug Test'),
(26, 'Occupational Medical', 1, 603, 'Occupational Medical');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE IF NOT EXISTS `product_types` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Acronym` varchar(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Alias` varchar(255) NOT NULL,
  `Color` varchar(255) NOT NULL,
  `Checked` tinyint(4) NOT NULL DEFAULT '0',
  `Sidebar_Alias` varchar(255) NOT NULL,
  `ButtonColor` varchar(255) NOT NULL,
  `Blocked` varchar(255) NOT NULL,
  `doc_ids` text,
  `Blocks_Alias` varchar(255) NOT NULL,
  `Block_Color` varchar(255) DEFAULT 'bg-grey-cascade',
  `NameFrench` varchar(255) NOT NULL,
  `DescriptionFrench` varchar(255) NOT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  `Bypass` tinyint(4) NOT NULL DEFAULT '0',
  `Icon` varchar(255) NOT NULL DEFAULT 'icon-docs',
  `Price` decimal(10,0) NOT NULL DEFAULT '0',
  `profile_types` varchar(512) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`ID`, `Acronym`, `Name`, `Description`, `Alias`, `Color`, `Checked`, `Sidebar_Alias`, `ButtonColor`, `Blocked`, `doc_ids`, `Blocks_Alias`, `Block_Color`, `NameFrench`, `DescriptionFrench`, `Visible`, `Bypass`, `Icon`, `Price`, `profile_types`) VALUES
(1, 'MEE', 'Driver Order', 'The all in one package', '0', 'red', 1, 'orders_mee', 'blue', '1603,1,14,77,78,1627', '3,9,15,4', 'ordersmee', 'blue', 'Commande pour chauffeur', 'Driver Order', 1, 0, 'icon-basket', '0', '5,7,8'),
(2, 'CAR', 'Order A La Carte', ' ', '0', '', 0, 'orders_products', 'blue', '1603,1,14,77,78,1650,1627,32,72,601,602,603', '', 'ordersproducts', 'blue', 'Commander des produits', 'Produits Tri', 1, 0, 'icon-basket', '0', '5,7,8,9,12'),
(3, 'BUL', 'Bulk Order', 'Requalify multiple drivers', '0', 'red', 0, 'bulk', 'blue', '72,1,3,77,78,14', '', 'ordersbulk', 'blue', 'Commande en vrac', 'commande en vrac', 1, 0, 'icon-basket', '0', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
