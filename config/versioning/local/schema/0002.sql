-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 05, 2012 at 07:52 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fotomatter`
--

-- --------------------------------------------------------

--
-- Table structure for table `db_local_updates`
--

CREATE TABLE IF NOT EXISTS `db_local_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` char(40) CHARACTER SET utf8 NOT NULL,
  `dev` char(20) CHARACTER SET utf8 NOT NULL,
  `status` enum('pending','started','success','failed') NOT NULL DEFAULT 'pending',
  `type` enum('sql','php') NOT NULL,
  `schema` char(10) NOT NULL,
  `full_file_path` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`,`dev`,`schema`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `db_local_updates`
--

INSERT INTO `db_local_updates` (`id`, `file_name`, `dev`, `status`, `type`, `schema`, `full_file_path`, `created`, `modified`) VALUES
(1, '0001.sql', 'drew', 'success', 'sql', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0001.sql', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(2, '0002.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0002.php', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(3, '0003.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0003.php', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(4, '0004.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0004.php', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(5, '0005.sql', 'adam', 'success', 'sql', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0005.sql', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(6, '0006.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0006.php', '2012-12-05 19:51:56', '2012-12-05 19:51:57'),
(7, '0007.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0007.php', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(8, '0008.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0008.php', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(9, '0009.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0009.php', '2012-12-05 19:51:57', '2012-12-05 19:51:58'),
(10, '0010.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0010.php', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(11, '0011.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0011.php', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(12, '0012.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0012.php', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(13, '0013.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0013.php', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(14, '0014.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0014.php', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(15, '0015.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0015.php', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(16, '0016.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0016.php', '2012-12-05 19:51:58', '2012-12-05 19:51:59'),
(17, '0017.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0017.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(18, '0018.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0018.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(19, '0019.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0019.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(20, '0020.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0020.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(21, '0021.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0021.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(22, '0022.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0022.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(23, '0023.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0023.php', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(24, '0024.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0024.php', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(25, '0025.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0025.php', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(26, '0026.php', 'adam', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/adam/0026.php', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(27, '0027.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0027.php', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(28, '0028.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0028.php', '2012-12-05 19:52:00', '2012-12-05 19:52:01'),
(29, '0029.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0029.php', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(30, '0030.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0030.php', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(31, '0031.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0031.php', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(32, '0032.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0032.php', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(33, '0033.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0033.php', '2012-12-05 19:52:01', '2012-12-05 19:52:02'),
(34, '0034.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0034.php', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(35, '0035.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0035.php', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(36, '0036.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0036.php', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(37, '0037.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0037.php', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(38, '0038.php', 'drew', 'success', 'php', '0001', '/home/amorrill/projects/fotomatter.dev/app/config/versioning/local/dev/0001/drew/0038.php', '2012-12-05 19:52:02', '2012-12-05 19:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `db_local_update_items`
--

CREATE TABLE IF NOT EXISTS `db_local_update_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_local_update_id` int(11) NOT NULL,
  `index` int(11) NOT NULL,
  `type` enum('sql','func') NOT NULL,
  `status` enum('started','success','failed') NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_local_update_id` (`db_local_update_id`,`index`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `db_local_update_items`
--

INSERT INTO `db_local_update_items` (`id`, `db_local_update_id`, `index`, `type`, `status`, `created`, `modified`) VALUES
(1, 2, 0, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(2, 2, 0, 'func', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(3, 3, 0, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(4, 3, 1, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(5, 3, 2, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(6, 3, 3, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(7, 3, 4, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(8, 3, 5, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(9, 3, 6, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(10, 3, 7, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(11, 3, 8, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(12, 4, 0, 'sql', 'success', '2012-12-05 19:51:56', '2012-12-05 19:51:56'),
(13, 6, 0, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(14, 6, 0, 'func', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(15, 7, 0, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(16, 7, 1, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(17, 8, 0, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(18, 8, 1, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(19, 8, 2, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(20, 8, 3, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(21, 8, 4, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(22, 8, 5, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(23, 8, 6, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(24, 8, 7, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(25, 8, 8, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(26, 8, 9, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(27, 8, 10, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(28, 8, 11, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(29, 9, 0, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(30, 9, 1, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(31, 9, 2, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(32, 9, 3, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:57'),
(33, 9, 4, 'sql', 'success', '2012-12-05 19:51:57', '2012-12-05 19:51:58'),
(34, 9, 5, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(35, 9, 6, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(36, 10, 0, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(37, 10, 1, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(38, 10, 2, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(39, 10, 3, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(40, 10, 4, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(41, 10, 5, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(42, 11, 0, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(43, 11, 1, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(44, 12, 0, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(45, 12, 1, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(46, 13, 0, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(47, 14, 0, 'func', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(48, 15, 0, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(49, 16, 0, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(50, 16, 1, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(51, 16, 2, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:58'),
(52, 16, 3, 'sql', 'success', '2012-12-05 19:51:58', '2012-12-05 19:51:59'),
(53, 17, 0, 'func', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(54, 18, 0, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(55, 18, 1, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(56, 18, 2, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(57, 18, 3, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(58, 18, 0, 'func', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(59, 19, 0, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(60, 19, 1, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(61, 19, 2, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(62, 19, 3, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(63, 20, 0, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(64, 21, 0, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(65, 22, 0, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(66, 22, 1, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(67, 22, 2, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(68, 22, 3, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(69, 22, 4, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(70, 22, 5, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(71, 22, 6, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(72, 22, 7, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(73, 22, 8, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(74, 22, 9, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(75, 22, 10, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(76, 22, 11, 'sql', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(77, 23, 0, 'func', 'success', '2012-12-05 19:51:59', '2012-12-05 19:51:59'),
(78, 24, 0, 'func', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(79, 25, 0, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(80, 25, 1, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(81, 25, 2, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(82, 26, 0, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(83, 26, 1, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(84, 26, 2, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(85, 27, 0, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:00'),
(86, 28, 0, 'sql', 'success', '2012-12-05 19:52:00', '2012-12-05 19:52:01'),
(87, 29, 0, 'sql', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(88, 29, 1, 'sql', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(89, 29, 0, 'func', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(90, 30, 0, 'func', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(91, 31, 0, 'sql', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(92, 32, 0, 'func', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(93, 33, 0, 'sql', 'success', '2012-12-05 19:52:01', '2012-12-05 19:52:02'),
(94, 34, 0, 'sql', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(95, 35, 0, 'func', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(96, 36, 0, 'sql', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(97, 37, 0, 'sql', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(98, 37, 1, 'sql', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(99, 37, 2, 'sql', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(100, 38, 0, 'func', 'success', '2012-12-05 19:52:02', '2012-12-05 19:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` char(36) NOT NULL,
  `name` varchar(40) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created`, `modified`) VALUES
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', 'System Developers', '2011-05-06 05:07:58', '2011-05-06 05:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `groups_permissions`
--

CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `group_id` char(36) NOT NULL,
  `permission_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups_permissions`
--

INSERT INTO `groups_permissions` (`group_id`, `permission_id`) VALUES
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', '4dc38200-2f70-46ff-b532-13d4044492e4');

-- --------------------------------------------------------

--
-- Table structure for table `groups_users`
--

CREATE TABLE IF NOT EXISTS `groups_users` (
  `group_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups_users`
--

INSERT INTO `groups_users` (`group_id`, `user_id`) VALUES
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', '4dc38294-d3cc-4db8-a3ef-13d4044492e4'),
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', '4f49e1c5-5980-43cd-9b3e-1f81044492e4');

-- --------------------------------------------------------

--
-- Table structure for table `hashes`
--

CREATE TABLE IF NOT EXISTS `hashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL,
  `name_space` char(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `image_wizard_quotes`
--

CREATE TABLE IF NOT EXISTS `image_wizard_quotes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `quote` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

--
-- Dumping data for table `image_wizard_quotes`
--

INSERT INTO `image_wizard_quotes` (`id`, `width`, `height`, `quote`) VALUES
(1, 16, 11, 189.53),
(2, 24, 16, 238.85),
(3, 30, 20, 312.51),
(4, 36, 24, 369.06),
(5, 45, 30, 441.34),
(6, 60, 40, 636.69),
(7, 71, 48, 814.23),
(8, 23, 16, 235.05),
(9, 28, 20, 302.97),
(10, 34, 24, 358.41),
(11, 42, 30, 422.88),
(12, 56, 40, 606.54),
(13, 68, 48, 788.3),
(14, 62, 44, 694.1),
(15, 24, 10, 210.11),
(16, 38, 16, 296.62),
(17, 53, 22, 415.86),
(18, 69, 29, 577.46),
(19, 7, 5, 144.96),
(20, 12, 8, 166.96),
(21, 66, 44, 726.46),
(22, 11, 8, 164.17),
(23, 23, 10, 207.06),
(24, 37, 16, 292.4),
(25, 51, 22, 405.77),
(26, 67, 29, 565.43),
(27, 6, 5, 142.54),
(28, 10, 8, 161.37),
(29, 14, 11, 183.19),
(30, 20, 16, 223.67),
(31, 25, 20, 288.67),
(32, 30, 24, 337.12),
(33, 38, 30, 398.26),
(34, 51, 40, 568.85),
(35, 56, 44, 645.55),
(36, 61, 48, 727.78),
(37, 19, 10, 194.88),
(38, 30, 16, 287.9),
(39, 41, 22, 355.32),
(40, 54, 29, 487.24),
(41, 15, 11, 186.36),
(42, 22, 16, 231.26),
(43, 27, 20, 298.2),
(44, 33, 24, 353.09),
(45, 41, 30, 416.72),
(46, 55, 40, 599),
(47, 60, 44, 677.91),
(48, 65, 48, 762.36),
(49, 5, 6, 142.54),
(50, 8, 10, 161.37),
(51, 11, 14, 183.19),
(52, 16, 21, 227.47),
(53, 20, 26, 293.43),
(54, 24, 31, 342.44),
(55, 30, 39, 404.42),
(56, 8, 8, 155.78),
(57, 11, 11, 173.68),
(58, 16, 16, 208.5),
(59, 20, 20, 240.84),
(60, 24, 24, 277.17),
(61, 30, 30, 349.04),
(62, 35, 35, 414.03),
(63, 40, 40, 485.94),
(64, 8, 5, 147.39),
(65, 17, 11, 192.7),
(66, 31, 20, 317.28),
(67, 37, 24, 349.38),
(68, 46, 30, 447.49),
(69, 61, 40, 644.23),
(70, 67, 44, 734.55),
(71, 73, 48, 831.52),
(72, 29, 20, 307.74),
(73, 35, 24, 363.73),
(74, 44, 30, 435.18),
(75, 59, 40, 629.15),
(76, 64, 44, 710.28),
(77, 70, 48, 805.59),
(78, 5, 5, 140.12),
(79, 10, 10, 167.46),
(80, 43, 30, 429.03),
(81, 57, 40, 614.08),
(82, 27, 10, 240.83),
(83, 43, 16, 317.69),
(84, 59, 22, 446.14),
(85, 78, 29, 631.59),
(86, 13, 11, 180.02),
(87, 19, 16, 219.88),
(88, 24, 20, 258.01),
(89, 29, 24, 331.8),
(90, 36, 30, 385.96),
(91, 65, 44, 718.37),
(92, 61, 44, 686.01),
(93, 67, 48, 779.65),
(94, 60, 26, 492.41),
(95, 32, 24, 347.77),
(96, 40, 30, 410.57),
(97, 29, 10, 247.59),
(98, 47, 16, 334.55),
(99, 65, 22, 476.41),
(100, 76, 26, 582),
(101, 85, 29, 673.69),
(102, 69, 48, 796.94),
(103, 35, 45, 482.48),
(104, 40, 52, 576.39),
(105, 44, 57, 653.64),
(106, 48, 62, 736.43),
(107, 18, 11, 195.87),
(108, 26, 16, 271.04),
(109, 32, 20, 322.05),
(110, 39, 24, 360.02),
(111, 49, 30, 465.95),
(112, 65, 40, 674.38),
(113, 71, 44, 766.92),
(114, 78, 48, 874.74),
(115, 70, 26, 548.4),
(116, 72, 48, 822.88),
(117, 39, 16, 300.83),
(118, 70, 29, 583.47);

-- --------------------------------------------------------

--
-- Table structure for table `major_errors`
--

CREATE TABLE IF NOT EXISTS `major_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` char(100) NOT NULL,
  `line_num` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `extra_data` longtext CHARACTER SET utf8,
  `type` char(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'normal',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` char(36) NOT NULL,
  `name` varchar(40) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created`, `modified`) VALUES
('4dc38200-2f70-46ff-b532-13d4044492e4', '*', '2011-05-06 05:07:12', '2011-05-06 05:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cdn-filename` char(100) DEFAULT NULL,
  `cdn-filename-forcache` char(100) DEFAULT NULL,
  `cdn-filename-smaller-forcache` char(100) DEFAULT NULL,
  `display_title` char(64) NOT NULL,
  `display_subtitle` char(128) NOT NULL,
  `description` text NOT NULL,
  `alt_text` char(128) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `photo_format_id` int(11) NOT NULL DEFAULT '1',
  `pixel_width` int(11) DEFAULT NULL,
  `pixel_height` int(11) DEFAULT NULL,
  `forcache_pixel_width` int(11) DEFAULT NULL,
  `forcache_pixel_height` int(11) DEFAULT NULL,
  `smaller_forcache_pixel_width` int(11) DEFAULT NULL,
  `smaller_forcache_pixel_height` int(11) DEFAULT NULL,
  `tag_attributes` char(100) CHARACTER SET latin1 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cdn-filename` (`cdn-filename`),
  UNIQUE KEY `cdn-filename-forcache` (`cdn-filename-forcache`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos_tags`
--

CREATE TABLE IF NOT EXISTS `photos_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_caches`
--

CREATE TABLE IF NOT EXISTS `photo_caches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `cdn-filename` char(100) DEFAULT NULL,
  `max_width` int(11) DEFAULT NULL,
  `max_height` int(11) DEFAULT NULL,
  `pixel_height` int(11) DEFAULT NULL,
  `pixel_width` int(11) DEFAULT NULL,
  `tag_attributes` char(100) DEFAULT NULL,
  `unsharp_amount` float DEFAULT NULL,
  `status` char(10) CHARACTER SET latin1 NOT NULL DEFAULT 'queued',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cdn-filename` (`cdn-filename`),
  UNIQUE KEY `photo_id` (`photo_id`,`max_width`,`max_height`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_dimensions`
--

CREATE TABLE IF NOT EXISTS `photo_dimensions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `allimage_id` int(11) NOT NULL,
  `relWidth` float NOT NULL,
  `relHeight` float NOT NULL,
  `inWidth` float NOT NULL,
  `inHeight` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1069 ;

--
-- Dumping data for table `photo_dimensions`
--

INSERT INTO `photo_dimensions` (`id`, `allimage_id`, `relWidth`, `relHeight`, `inWidth`, `inHeight`) VALUES
(1068, 22, 700, 496, 67.7419, 48),
(1067, 22, 700, 496, 62.0968, 44),
(1066, 22, 700, 496, 56.4516, 40),
(1065, 22, 700, 496, 42.3387, 30),
(1064, 22, 700, 496, 33.871, 24),
(1063, 22, 700, 496, 28.2258, 20),
(1062, 22, 700, 496, 22.5806, 16),
(1061, 22, 700, 496, 15.5242, 11),
(1060, 22, 700, 496, 11.2903, 8),
(1059, 22, 700, 496, 7.05645, 5);

-- --------------------------------------------------------

--
-- Table structure for table `photo_formats`
--

CREATE TABLE IF NOT EXISTS `photo_formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` char(64) NOT NULL,
  `ref_name` char(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `photo_formats`
--

INSERT INTO `photo_formats` (`id`, `display_name`, `ref_name`) VALUES
(1, 'Landscape', 'landscape'),
(2, 'Portrait', 'portrait'),
(3, 'Square', 'square'),
(4, 'Panoramic', 'panoramic'),
(5, 'Vertical Panoramic', 'vertical_panoramic');

-- --------------------------------------------------------

--
-- Table structure for table `photo_galleries`
--

CREATE TABLE IF NOT EXISTS `photo_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weight` int(11) NOT NULL,
  `type` char(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'standard',
  `display_name` char(100) NOT NULL,
  `description` longtext NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_galleries_photos`
--

CREATE TABLE IF NOT EXISTS `photo_galleries_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `photo_gallery_id` int(11) NOT NULL,
  `photo_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `photo_id` (`photo_id`,`photo_gallery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_prebuild_cache_sizes`
--

CREATE TABLE IF NOT EXISTS `photo_prebuild_cache_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `max_height` int(11) NOT NULL,
  `max_width` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `photo_prebuild_cache_sizes`
--

INSERT INTO `photo_prebuild_cache_sizes` (`id`, `max_height`, `max_width`, `created`, `modified`) VALUES
(1, 60, 60, '2012-04-28 00:13:12', '2012-04-28 00:13:15'),
(2, 110, 110, '2012-04-28 00:13:12', '2012-04-28 00:13:15'),
(3, 155, 155, '2012-04-28 00:13:12', '2012-04-28 00:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `site_one_level_menus`
--

CREATE TABLE IF NOT EXISTS `site_one_level_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_system` tinyint(4) NOT NULL DEFAULT '0',
  `ref_name` char(30) NOT NULL DEFAULT 'custom',
  `external_id` int(11) NOT NULL,
  `external_model` char(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `site_one_level_menus`
--

INSERT INTO `site_one_level_menus` (`id`, `is_system`, `ref_name`, `external_id`, `external_model`, `weight`, `created`, `modified`) VALUES
(21, 1, 'home', 0, 'SitePage', 1, '2012-12-05 19:52:01', '2012-12-05 19:52:01'),
(22, 1, 'image_galleries', 0, 'SitePage', 2, '2012-12-05 19:52:01', '2012-12-05 19:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `site_pages`
--

CREATE TABLE IF NOT EXISTS `site_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(128) NOT NULL,
  `external_link` varchar(1024) NOT NULL,
  `weight` int(11) NOT NULL,
  `type` char(20) NOT NULL DEFAULT 'custom',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='possible types are ''custom'',''smart''' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_pages_site_page_elements`
--

CREATE TABLE IF NOT EXISTS `site_pages_site_page_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_page_id` int(11) NOT NULL,
  `site_page_element_id` int(11) NOT NULL,
  `config` longtext NOT NULL,
  `page_element_order` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_page_elements`
--

CREATE TABLE IF NOT EXISTS `site_page_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_name` char(50) NOT NULL,
  `order` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `site_page_elements`
--

INSERT INTO `site_page_elements` (`id`, `ref_name`, `order`, `version`) VALUES
(1, 'para_header_image', 1, 1),
(2, 'image', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL,
  `value` char(128) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `name`, `value`, `created`, `modified`) VALUES
(1, 'current_schema', '0001', '2012-02-26 00:39:44', '2012-12-05 19:51:56'),
(2, 'our_product', 'worth it!', NULL, '2012-12-05 19:51:56'),
(3, 'our_ethic', 'perfect code!', NULL, NULL),
(4, 'our_skills', 'the coolest', NULL, '2012-12-05 19:51:57'),
(5, 'our_goal', '100 users', NULL, NULL),
(6, 'current_theme', 'default', '2012-12-05 19:51:59', '2012-12-05 19:51:59');

-- --------------------------------------------------------

--
-- Table structure for table `site_two_level_menus`
--

CREATE TABLE IF NOT EXISTS `site_two_level_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_system` tinyint(4) NOT NULL DEFAULT '0',
  `ref_name` char(30) NOT NULL DEFAULT 'custom',
  `external_id` int(11) NOT NULL,
  `external_model` char(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_two_level_menu_containers`
--

CREATE TABLE IF NOT EXISTS `site_two_level_menu_containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` char(30) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_two_level_menu_container_items`
--

CREATE TABLE IF NOT EXISTS `site_two_level_menu_container_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_two_level_menu_container_id` int(11) NOT NULL,
  `ref_name` char(30) NOT NULL,
  `external_id` int(11) NOT NULL,
  `external_model` char(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL DEFAULT '0',
  `ref_name` char(40) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref_name` (`ref_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `theme_id`, `ref_name`, `created`, `modified`) VALUES
(1, 0, 'simple_lightgrey_textured', '2012-12-05 19:52:02', '2012-12-05 19:52:02'),
(2, 0, 'white_slider', '2012-12-05 19:52:02', '2012-12-05 19:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `theme_global_settings`
--

CREATE TABLE IF NOT EXISTS `theme_global_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL,
  `value` char(128) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `theme_hidden_settings`
--

CREATE TABLE IF NOT EXISTS `theme_hidden_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `name` char(100) CHARACTER SET utf8 NOT NULL,
  `value` char(128) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `theme_id` (`theme_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) NOT NULL,
  `email_address` varchar(127) NOT NULL,
  `password` varchar(40) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `password`, `active`, `created`, `modified`) VALUES
('4dc38294-d3cc-4db8-a3ef-13d4044492e4', 'acmorrill@gmail.com', '9d3c620291d8c235446ea52876ebbacaee49bca7', 1, '2011-05-06 05:09:40', '2011-05-06 05:09:40'),
('4f49e1c5-5980-43cd-9b3e-1f81044492e4', 'adamdude828@gmail.com', '01ec50cd73fcbe40a3204d54783d490af696e749', 1, '2012-02-26 00:39:49', '2012-02-26 00:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `xhprof_profiles`
--

CREATE TABLE IF NOT EXISTS `xhprof_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xhprof_id` varchar(15) NOT NULL,
  `http_accept` varchar(255) NOT NULL,
  `request_uri` varchar(1080) NOT NULL,
  `name_space` text NOT NULL,
  `nano_seconds` float NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
