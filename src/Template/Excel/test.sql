-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2015 at 10:11 PM
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
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commodity` varchar(255) NOT NULL COMMENT 'test comment',
  `Percent_of_Revenue` varchar(255) NOT NULL COMMENT '[format=percent,align=right]',
  `Average_Load_Value` varchar(255) NOT NULL COMMENT '[align=right,format=currency]',
  `Maximum_Load_Value` varchar(255) NOT NULL COMMENT '[format=currency,align=right]',
  `Times_per_Month_Value_Exceeds_Average` varchar(255) NOT NULL COMMENT '[format=number,align=right]',
  `Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `commodity`, `Percent_of_Revenue`, `Average_Load_Value`, `Maximum_Load_Value`, `Times_per_Month_Value_Exceeds_Average`, `Comment`) VALUES
(3, '[colspan=all,format=uppercase,bgcolor=#99ccff,bold]Category 1', '0', '0', '0', '0', ''),
(4, 'Beer', '0', '0', '0', '0', ''),
(5, 'Liquor or wine', '0', '0', '0', '0', ''),
(6, 'Metals - high value', '0', '0', '0', '0', ''),
(7, 'Electronics', '0', '0', '0', '0', ''),
(8, 'Pharmaceuticals', '0', '0', '0', '0', ''),
(9, 'Tobacco', '0', '0', '0', '0', ''),
(10, 'Tools (hand or power)', '0', '0', '0', '0', ''),
(11, 'Explosives, munitions', '0', '0', '0', '0', ''),
(12, '[align=right,bold,bgcolor=#ccffff]Total:', '[bgcolor=#ccffff]=sum(C3:ME) ', '[bgcolor=#ccffff]=sum(D3:ME)', '[bgcolor=#ccffff]=sum(E3:ME)', '[bgcolor=#ccffff]=sum(F3:ME)', '[bgcolor=#ccffff]'),
(13, '[colspan=all,format=uppercase,bgcolor=#99ccff,bold]Category 2', '', '', '', '', ''),
(14, 'Clothing, textiles', '0.00', '', '', '', ''),
(15, 'Food - Seafood or shellfish', '0.00', '', '', '', ''),
(16, 'Food - Produce', '0.0', '', '', '', ''),
(17, 'Food - Other temperature sensative', '0.0', '', '', '', ''),
(18, 'Flowers, bulbs, nursery stock', '0.0', '', '', '', ''),
(19, 'Liquids in Bulk - Hazardous/regulated', '0.0', '', '', '', ''),
(20, 'Liquids in Bulk - Non-hazardous', '0.0', '', '', '', ''),
(21, 'Tires', '0.0', '', '', '', ''),
(22, '[align=right,bold,bgcolor=#ccffff]Total:', '[bgcolor=#ccffff]=sum(C14:ME) ', '[bgcolor=#ccffff]=sum(D14:ME)', '[bgcolor=#ccffff]=sum(E14:ME)', '[bgcolor=#ccffff]=sum(F14:ME)', '[bgcolor=#ccffff]'),
(23, '[colspan=all,format=uppercase,bgcolor=#99ccff,bold]Category 3', '', '', '', '', ''),
(24, 'Machinery & Equipment', '', '', '', '', ''),
(25, 'Auto parts – heavy', '', '', '', '', ''),
(26, 'Specialized equipment (describe in Comment)', '', '', '', '', ''),
(27, 'Live animals, poultry', '', '', '', '', ''),
(28, 'Over-dimensional Loads (describe in Comment)', '', '', '', '', ''),
(29, 'Furniture', '', '', '', '', ''),
(30, '[align=right,bold,bgcolor=#ccffff]Total:', '[bgcolor=#ccffff]=sum(C24:ME) ', '[bgcolor=#ccffff]=sum(D24:ME) ', '[bgcolor=#ccffff]=sum(E24:ME) ', '[bgcolor=#ccffff]=sum(F24:ME) ', '[bgcolor=#ccffff]'),
(31, '[colspan=all,format=uppercase,bgcolor=#99ccff,bold]Category 4', '', '', '', '', ''),
(32, 'Sealed shipping containers', '', '', '', '', ''),
(33, 'Food – Dry, packaged, or canned', '', '', '', '', ''),
(34, 'Auto parts – light', '', '', '', '', ''),
(35, 'Logs, pulpwood, pulp, chips, shavings', '', '', '', '', ''),
(36, 'Sand, gravel, aggregate', '', '', '', '', ''),
(37, 'Dry bulk – Hazardous/regulated', '', '', '', '', ''),
(38, 'Dry bulk – Non-hazardous', '', '', '', '', ''),
(39, 'Lumber, building materials', '', '', '', '', ''),
(40, 'Steel, pipes, concrete', '', '', '', '', ''),
(41, 'Paper', '', '', '', '', ''),
(42, 'Cement', '', '', '', '', ''),
(43, '[align=right,bold,bgcolor=#ccffff]Total:', '[bgcolor=#ccffff]=sum(C32:ME) ', '[bgcolor=#ccffff]=sum(D32:ME) ', '[bgcolor=#ccffff]=sum(E32:ME) ', '[bgcolor=#ccffff]=sum(F32:ME) ', '[bgcolor=#ccffff]'),
(44, '[colspan=all,format=uppercase,bgcolor=#99ccff,bold]Other', '', '', '', '', ''),
(45, 'Other: (describe in Comment)', '', '', '', '', ''),
(46, 'Other: (describe in Comment)', '', '', '', '', ''),
(47, 'Other: (describe in Comment)', '', '', '', '', ''),
(48, 'Other: (describe in Comment)', '', '', '', '', ''),
(49, 'Other: (describe in Comment)', '', '', '', '', ''),
(50, 'Other: (describe in Comment)', '', '', '', '', ''),
(51, 'Other: (describe in Comment)', '', '', '', '', ''),
(52, 'Other: (describe in Comment)', '', '', '', '', ''),
(53, '[align=right,bold,bgcolor=#ccffff]Total:', '[bgcolor=#ccffff]=sum(C45:ME) ', '[bgcolor=#ccffff]=sum(D45:ME) ', '[bgcolor=#ccffff]=sum(E45:ME) ', '[bgcolor=#ccffff]=sum(F45:ME) ', '[bgcolor=#ccffff]'),
(54, '[align=right,bold,bgcolor=#ccffff]Grand-Total:', '[bgcolor=#ccffff]=C12+C22+C30+C43+C53', '[bgcolor=#ccffff]=D12+D22+D30+D43+D53', '[bgcolor=#ccffff]=E12+E22+E30+E43+E53', '[bgcolor=#ccffff]=F12+F22+F30+F43+F53', '[bgcolor=#ccffff]'),
(55, 'General Comments', '[format,colspan=all]', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
