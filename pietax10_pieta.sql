-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 22, 2013 at 12:26 PM
-- Server version: 5.1.65
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pietax10_pieta`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `datestamp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `industries`
--

CREATE TABLE IF NOT EXISTS `industries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `industry` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `industries`
--

INSERT INTO `industries` (`id`, `industry`) VALUES
(1, 'Apparel/Fashion/Retail'),
(2, 'Groceries');

-- --------------------------------------------------------

--
-- Table structure for table `lisstings`
--

CREATE TABLE IF NOT EXISTS `lisstings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `website` text NOT NULL,
  `originalprice` varchar(50) NOT NULL,
  `reducedprice` varchar(50) NOT NULL,
  `discountrate` varchar(30) NOT NULL,
  `expiredate` varchar(255) NOT NULL,
  `contributor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lisstings`
--

INSERT INTO `lisstings` (`id`, `name`, `pid`, `product`, `details`, `website`, `originalprice`, `reducedprice`, `discountrate`, `expiredate`, `contributor`) VALUES
(1, 'You are loved', '1', '', '', 'http://www.parisbaguetteusa.com/ParisBaguette/Gallery.asp', '', '', '', '2013-02-14', ''),
(2, 'Morning Manhattan Coffee', '1', 'Organic Coffee', 'Purchase any pastry and get $1 Organic Coffee until 10AM', 'http://www.parisbaguetteusa.com/ParisBaguette/Gallery.asp', '1.75', '1.00', '', '', ''),
(3, 'Free Bread', '1', '', 'Purchase 9 Loafs of Bread and get one free!', 'http://www.parisbaguetteusa.com/ParisBaguette/Gallery.asp', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `industry` varchar(255) NOT NULL,
  `site` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `industry`, `site`) VALUES
(1, 'Paris Baguette', ' - Groceries', 'http://www.parisbaguetteusa.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastactivity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `signedup` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verified` int(1) NOT NULL DEFAULT '0',
  `sectoken` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `secstamp` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `ip`, `lastactivity`, `signedup`, `verified`, `sectoken`, `secstamp`) VALUES
(1, 'Kevin', 'Kong', 'kevinkong91@gmail.com', '$2a$08$nxWlv1q4rhBWlqZizuRz6etJrM8N.EkZSKAd0fxTKwy7CnKbQRyt2', '173.77.243.127', '2013-01-20 20:40:58', '2013-01-08 01:06:16', 0, 'EsyLTq9be1utlDUpR5fo', 0);

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE IF NOT EXISTS `visitors` (
  `id` varchar(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `lastactivity` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `uid`, `ip`, `count`, `status`, `lastactivity`) VALUES
('931ace6b3bf1d548235e94a06979cfe6', '0', '173.77.243.127', 11, 'ON', '2013-01-08 00:53:47'),
('95cd6125598081d57653eb8b76cab64d', '0', '173.77.243.127', 14, 'ON', '2013-01-08 01:01:50'),
('bef0241cdd43605bde2a33205685e93a', '0', '173.77.243.127', 20, 'ON', '2013-01-08 01:09:44'),
('8d129a050d9fece64f1cbc48681b6e27', '1', '173.77.243.127', 325, 'ON', '2013-01-14 23:07:31'),
('3f3d7e9ec2005a6ebcd75dce54004745', '1', '173.77.243.127', 160, 'ON', '2013-01-14 14:57:20'),
('f6a3162c2e470ea7e9d73a7e801adb71', '', '173.77.243.127', 148, 'ON', '2013-01-19 01:52:07'),
('3a2664e0eab70a6701ff12a7023699d3', '1', '173.77.243.127', 22, 'ON', '2013-01-20 20:51:53'),
('a3cffe80664300afed17a3595027fb53', '', '173.77.243.127', 15, 'ON', '2013-01-20 20:54:25');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
