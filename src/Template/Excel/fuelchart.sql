-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2015 at 04:31 PM
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
-- Table structure for table `fuelchart`
--

CREATE TABLE IF NOT EXISTS `fuelchart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProvState` varchar(255) NOT NULL COMMENT '[readonly]',
  `1st_QTR` varchar(255) NOT NULL COMMENT '[format=number]',
  `2nd_QTR` varchar(255) NOT NULL COMMENT '[format=number]',
  `3rd_QTR` varchar(255) NOT NULL COMMENT '[format=number]',
  `4th_QTR` varchar(255) NOT NULL COMMENT '[format=number]',
  `Total` varchar(255) NOT NULL COMMENT '[format=number,readonly]',
  `Percent` varchar(255) NOT NULL COMMENT '[format=number,readonly]',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `fuelchart`
--

INSERT INTO `fuelchart` (`id`, `ProvState`, `1st_QTR`, `2nd_QTR`, `3rd_QTR`, `4th_QTR`, `Total`, `Percent`) VALUES
(1, '[colspan=6,align=right,format=uppercase,bold]Total kilometers', '', '', '', '', '=sum(C1:ME)', ''),
(2, '[colspan=All] ', '', '', '', '', '', ''),
(3, '[format=uppercase,bold]Canada', '', '', '', '', '=sum(C3:ME)', ''),
(4, 'Alberta', '', '', '', '', '=sum(C4:ME)', ''),
(5, 'British Columbia', '', '', '', '', '=sum(C5:ME)', ''),
(6, 'Manitoba', '', '', '', '', '=sum(C6:ME)', ''),
(7, 'New Brunswick', '', '', '', '', '=sum(C7:ME)', ''),
(8, 'Newfoundland', '', '', '', '', '=sum(C8:ME)', ''),
(9, 'Nova Scotia', '', '', '', '', '=sum(C9:ME)', ''),
(10, 'North-Western Territories', '', '', '', '', '=sum(C10:ME)', ''),
(11, 'Ontario', '', '', '', '', '=sum(C11:ME)', ''),
(12, 'Prince Edward Island', '', '', '', '', '=sum(C12:ME)', ''),
(13, 'Quebec', '', '', '', '', '=sum(C13:ME)', ''),
(14, 'Saskatchewan', '', '', '', '', '=sum(C14:ME)', ''),
(15, '[colspan=5,bold]Total Canada %', '', '', '', '', '=sum(G3:ME)', ''),
(16, '[colspan=All]', '', '', '', '', '', ''),
(17, '[format=uppercase,bold]United States', '', '', '', '', '', ''),
(18, 'Alabama', '', '', '', '', '=sum(C18:ME)', ''),
(19, 'Alaska', '', '', '', '', '=sum(C19:ME)', ''),
(20, 'Arizona', '', '', '', '', '=sum(C20:ME)', ''),
(21, 'Arkansas', '', '', '', '', '=sum(C21:ME)', ''),
(22, 'California', '', '', '', '', '=sum(C22:ME)', ''),
(23, 'Colorado', '', '', '', '', '=sum(C23:ME)', ''),
(24, 'Connecticut', '', '', '', '', '=sum(C24:ME)', ''),
(25, 'Delaware', '', '', '', '', '=sum(C25:ME)', ''),
(26, 'Florida', '', '', '', '', '=sum(C26:ME)', ''),
(27, 'Georgia', '', '', '', '', '=sum(C27:ME)', ''),
(28, 'Hawaii', '', '', '', '', '=sum(C28:ME)', ''),
(29, 'Idaho', '', '', '', '', '=sum(C29:ME)', ''),
(30, 'Illinois', '', '', '', '', '=sum(C30:ME)', ''),
(31, 'Indiana', '', '', '', '', '=sum(C31:ME)', ''),
(32, 'Iowa', '', '', '', '', '=sum(C32:ME)', ''),
(33, 'Kansas', '', '', '', '', '=sum(C33:ME)', ''),
(34, 'Kentucky', '', '', '', '', '=sum(C34:ME)', ''),
(35, 'Louisiana', '', '', '', '', '=sum(C35:ME)', ''),
(36, 'Maine', '', '', '', '', '=sum(C36:ME)', ''),
(37, 'Maryland', '', '', '', '', '=sum(C37:ME)', ''),
(38, 'Massachusetts', '', '', '', '', '=sum(C38:ME)', ''),
(39, 'Michigan', '', '', '', '', '=sum(C39:ME)', ''),
(40, 'Minnesota', '', '', '', '', '=sum(C40:ME)', ''),
(41, 'Mississippi', '', '', '', '', '=sum(C41:ME)', ''),
(42, 'Missouri', '', '', '', '', '=sum(C42:ME)', ''),
(43, 'Montana', '', '', '', '', '=sum(C43:ME)', ''),
(44, 'Nebraska', '', '', '', '', '=sum(C44:ME)', ''),
(45, 'Nevada', '', '', '', '', '=sum(C45:ME)', ''),
(46, 'New Hampshire', '', '', '', '', '=sum(C46:ME)', ''),
(47, 'New Jersey', '', '', '', '', '=sum(C47:ME)', ''),
(48, 'New Mexico', '', '', '', '', '=sum(C48:ME)', ''),
(49, 'New York', '', '', '', '', '=sum(C49:ME)', ''),
(50, 'North Carolina', '', '', '', '', '=sum(C50:ME)', ''),
(51, 'North Dakota', '', '', '', '', '=sum(C51:ME)', ''),
(52, 'Ohio', '', '', '', '', '=sum(C52:ME)', ''),
(53, 'Oklahoma', '', '', '', '', '=sum(C53:ME)', ''),
(54, 'Oregon', '', '', '', '', '=sum(C54:ME)', ''),
(55, 'Pennsylvania', '', '', '', '', '=sum(C55:ME)', ''),
(56, 'Rhode Island', '', '', '', '', '=sum(C56:ME)', ''),
(57, 'South Carolina', '', '', '', '', '=sum(C57:ME)', ''),
(58, 'South Dakota', '', '', '', '', '=sum(C58:ME)', ''),
(59, 'Tennessee', '', '', '', '', '=sum(C59:ME)', ''),
(60, 'Texas', '', '', '', '', '=sum(C60:ME)', ''),
(61, 'Utah', '', '', '', '', '=sum(C61:ME)', ''),
(62, 'Vermont', '', '', '', '', '=sum(C62:ME)', ''),
(63, 'Virginia', '', '', '', '', '=sum(C63:ME)', ''),
(64, 'Washington', '', '', '', '', '=sum(C64:ME)', ''),
(65, 'West Virginia', '', '', '', '', '=sum(C65:ME)', ''),
(66, 'Wisconsin', '', '', '', '', '=sum(C66:ME)', ''),
(67, 'Wyoming', '', '', '', '', '=sum(C67:ME)', ''),
(68, '[colspan=5,bold]Total USA %', '', '', '', '', '=sum(G18:ME)', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
